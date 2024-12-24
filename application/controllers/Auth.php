<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->library('email');     // Memuat library email
    }
	public function index()
	{
		$data['web'] = $this->db->get('web')->row();
		$this->load->view('login',$data);
	}
	public function aksi_login()
	{
		// Ambil data dari input form
		$data = [
			'nama_user' => $this->input->post('nama'),
			'password'  => md5($this->input->post('password')),
			'level'     => $this->input->post('level')
		];

		$level = $this->input->post('level');
		$cek = $this->db->get_where('user', $data);

		if ($cek->num_rows() > 0) {
			$usr = $cek->row_array();

			// Cek apakah user sudah divalidasi
			if ($usr['is_active'] != 1) {
				$this->session->set_flashdata('message', 'swal("Ops!", "Akun Anda belum divalidasi oleh admin.", "warning");');
				redirect('Auth');
				return; // Hentikan eksekusi lebih lanjut
			}

			// Set session data jika validasi berhasil
			$this->session->set_userdata($usr);

			// Redirect berdasarkan level user
			if ($usr['level'] == 2 || $usr['level'] == 3 || $usr['level'] == 0) {
				redirect('admin');
			} else {
				redirect('Frontend');
			}
		} else {
			// Jika login gagal
			$this->session->set_flashdata('message', 'swal("Ops!", "Username / Password yang anda masukkan salah", "error");');
			redirect('Auth');
		}
	}


	public function registrasi(){
		$data['web'] 			= $this->db->get('web')->row();
		$data['departement'] 	= $this->db->get('departement')->result();
		$this->load->view('registrasi',$data);
	}

	public function proses_registrasi(){
		$data = [
			'nip'		=> $this->input->post('nip'),
			'nama_user'	=> $this->input->post('nama'),
			'level'		=> $this->input->post('level'),
			'email'		=> $this->input->post('email'),
			'id_departement'		=> $this->input->post('id_departement'),
			'password'	=> md5($this->input->post('password')),
		];
		$cek = $this->db->get_where('user',$data);
		if ($cek->num_rows() > 0) {
			$this->session->set_flashdata('message', 'swal("Ops!", "User dengan data tersebut sudah terdaftar", "error");');
			redirect('auth/registrasi');
		}
		else
		{
			$this->db->insert('user', $data);
			$this->session->set_flashdata('message', 'swal("Berhasil!", "Berhasil Registrasi", "success");');
			redirect('auth');
		}
	}

	public function update_profile() {
		// Ambil data yang dikirimkan dari form
		$nama_user = $this->input->post('nama_user');
		$email = $this->input->post('email');
		$nip = $this->input->post('nip');
		$password = $this->input->post('password');
		$id_departement = $this->input->post('id_departement');
		$confirm_password = $this->input->post('confirm_password');
	
		// Validasi password jika ada perubahan
		if (!empty($password) && $password != $confirm_password) {
			$this->session->set_flashdata('message', 'swal("Ops!", "Password tidak cocok!", "error");');
			redirect('permintaan');  // Kembali ke halaman profil
		}
	
		// Siapkan data untuk update profil
		$data = [
			'nama_user' => $nama_user,
			'email' => $email,
			'nip' => $nip,
			'id_departement' => $id_departement,
		];
	
		// Jika password diubah, gunakan MD5 untuk mengenkripsi password
		if (!empty($password)) {
			// Enkripsi password menggunakan MD5
			$data['password'] = md5($password);
		}
	
		// Update profil di database
		$this->db->where('id_user', $this->session->userdata('id_user'));
		$this->db->update('user', $data);
	
		// Mengecek apakah ada baris yang terpengaruh oleh update
		if ($this->db->affected_rows() > 0) {
			// Ambil data terbaru setelah update
			$this->db->where('id_user', $this->session->userdata('id_user'));
			$cek = $this->db->get('user'); // Ambil data user setelah update
			$usr = $cek->row_array();
	
			// Perbarui session dengan data terbaru
			$this->session->set_userdata($usr);
	
			// Set flashdata sukses
			$this->session->set_flashdata('message', 'swal("Berhasil!", "Profil berhasil diperbarui!", "success");');
		} else {
			// Jika tidak ada yang terpengaruh, beri pesan error
			$this->session->set_flashdata('message', 'swal("Ops!", "Tidak ada perubahan yang dilakukan!", "error");');
		}
	
		redirect(base_url());
	}
	
	
	

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('Auth');
	}

	public function lupa_password() {
		// Ambil parameter username dari URL
		$username = $this->input->get('username');
		$email    = '';
		// Cek apakah parameter username tidak kosong
		if (!empty($username)) {
			// Query database untuk mendapatkan data pengguna
			$this->db->where('nama_user', $username);
			$user = $this->db->get('user')->row(); // 'users' adalah nama tabel, sesuaikan dengan kebutuhan Anda
			$email = $user->email;

		} 
		
		$data['email'] = $email;
		// Ambil data konfigurasi web
		$data['web'] = $this->db->get('web')->row();
	
		// Load view lupa_password dengan data
		$this->load->view('lupa_password', $data);
	}
	
	
	public function aksi_lupa_password() {
		$email = $this->input->post('email');
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// Cek apakah email terdaftar
			$query = $this->db->get_where('user', ['email' => $email]);
			$user = $query->row();
			
			if ($user) {
				// Generate token untuk reset password
				$token = bin2hex(random_bytes(16));  
				$token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));  
				
				// Simpan log reset password
				$data = [
					'email' => $email,
					'token' => $token,
					'token_expiry' => $token_expiry,
					'status' => 'sent'
				];
				$this->db->insert('email_reset_logs', $data);
	
				// Generate reset link
				$reset_link = site_url('auth/reset_password/' . urlencode($token));
				
				// Kirim email untuk reset password
				$this->_send_reset_email($email, $reset_link);
				
				// Feedback untuk pengguna
				$this->session->set_flashdata('message', 'swal("Berhasil!", "Link reset password telah dikirim ke email Anda.", "success");');
				redirect('auth');
			} else {
				// Jika email tidak ditemukan
				$this->session->set_flashdata('message', 'swal("Ops!", "Email tidak ditemukan.", "error");');
				redirect('auth/lupa_password');
			}
		} else {
			// Jika email tidak valid
			$this->session->set_flashdata('message', 'swal("Ops!", "Email tidak valid.", "error");');
			redirect('auth/lupa_password');
		}
	}
	
	private function _send_reset_email($email, $reset_link) {
		$this->load->library('email');
		
		// Konfigurasi email
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.swarehousesugin.my.id'; 
		$config['smtp_port'] = 465; 
		$config['smtp_user'] = 'admin@swarehousesugin.my.id'; 
		$config['smtp_pass'] = 'Arsyal2001';  
		$config['smtp_crypto'] = 'ssl';  
		$config['mailtype'] = 'text';  
		$config['charset'] = 'UTF-8';
		$config['wordwrap'] = TRUE;
		
		$this->email->initialize($config);
		
		$this->email->from('admin@swarehousesugin.my.id', 'Reset Password');
		$this->email->to($email);  
		$this->email->subject('Permintaan Reset Password');
		
		// Membuat pesan email
		$message = "
		==================================================================
								  Permintaan Reset Password
		==================================================================
		
		Halo,
		
		Kami menerima permintaan untuk mereset password Anda. Jika Anda yang
		melakukan permintaan ini, silakan klik tautan berikut untuk melanjutkan
		proses reset password:
		
			Reset Password: $reset_link
		
		Jika Anda tidak merasa melakukan permintaan ini, Anda tidak perlu
		khawatir. Anda bisa mengabaikan email ini dan tidak ada yang akan
		berubah pada akun Anda.
		
		==================================================================
		Jika Anda membutuhkan bantuan lebih lanjut, silakan hubungi tim kami:
		
			Email: support@swarehousesugin.my.id
			Telepon: 0800-123-456
		
		Terima kasih atas kepercayaan Anda,
		Tim Swarehouse Sugin
		==================================================================
		";
		
		// Mengirim email
		$this->email->message($message);
		
		if ($this->email->send()) {
			log_message('debug', 'Email reset password berhasil dikirim ke: ' . $email);
		} else {
			log_message('error', 'Gagal mengirim email reset password: ' . $this->email->print_debugger());
			echo "Gagal mengirim email.";
		}
	}
	
	public function reset_password($token) {
		// Decode token
		$token = urldecode($token);
		
		// Cek apakah token valid dan belum kedaluwarsa
		$query = $this->db->get_where('email_reset_logs', ['token' => $token, 'status' => 'sent']);
		$log = $query->row();
		
		if ($log) {
			// Cek masa berlaku token
			if (strtotime($log->token_expiry) > time()) {
				$data['token'] = $token;
				$this->load->view('reset_password', $data);
			} else {
				// Token kedaluwarsa
				$this->db->update('email_reset_logs', ['status' => 'expired'], ['token' => $token]);
				show_404();  
			}
		} else {
			show_404();
		}
	}
	
	public function aksi_reset_password() {
		$token = $this->input->post('token');
		$new_password = $this->input->post('new_password');
		
		// Validasi panjang password baru
		if (strlen($new_password) < 6) {
			$this->session->set_flashdata('message', 'swal("Ops!", "Password baru harus lebih dari 6 karakter.", "error");');
			redirect('auth/reset_password/' . $token);
		}
	
		// Cek apakah token valid dan masih berlaku
		$query = $this->db->get_where('email_reset_logs', ['token' => $token, 'status' => 'sent']);
		$log = $query->row();
		
		if ($log) {
			// Cek apakah token belum kedaluwarsa
			if (strtotime($log->token_expiry) > time()) {
				$email = $log->email;
				// Update password baru
				$this->db->update('user', ['password' => md5($new_password)], ['email' => $email]);
				
				// Update status token menjadi "used"
				$this->db->update('email_reset_logs', ['status' => 'used'], ['token' => $token]);
				
				$this->session->set_flashdata('message', 'swal("Berhasil!", "Password berhasil direset.", "success");');
				redirect('auth');
			} else {
				// Token sudah kedaluwarsa
				$this->db->update('email_reset_logs', ['status' => 'expired'], ['token' => $token]);
				$this->session->set_flashdata('message', 'swal("Ops!", "Token reset password sudah kedaluwarsa.", "error");');
				redirect('auth/lupa_password');
			}
		} else {
			// Token tidak ditemukan
			$this->session->set_flashdata('message', 'swal("Ops!", "Token tidak valid.", "error");');
			redirect('auth/lupa_password');
		}
	}
	


}
