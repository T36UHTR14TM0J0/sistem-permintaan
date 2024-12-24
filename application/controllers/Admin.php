<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->web = $this->db->get('web')->row();
        $this->load->library('Pdf');
        $this->load->library('upload');
        if ($this->session->userdata('level') == 1 ) {
            $this->session->set_flashdata('message', 'swal("Ops!", "Anda harus login sebagai admin", "error");');
            redirect('auth');
        }
        $this->load->model('Permintaan_model');
        $this->load->model('Barang_model');
        $this->load->model('m_data');
        $this->load->library('pdf');
        $this->load->helper('common');
        $updatedRows = $this->m_data->updateOldRecords();
        
    }

    public function index()
    {
        $tahun = $this->input->get('tahun') ?: date("Y"); // Default ke tahun saat ini
        
        $data['data_barang'] = $this->m_data->get_data_barang_per_tahun($tahun); // Ambil data barang masuk dan keluar per tahun
        // Mengambil data permintaan barang per tahun
        $data['data_permintaan'] = $this->m_data->get_data_permintaan_per_tahun($tahun);
        $data['web']        = $this->web;
        $data['departemen'] = $this->db->get('departement')->num_rows();
        $data['title']      = 'Dashboard';
        $data['body']       = 'admin/home';
        
        $this->load->view('template', $data); // Load view dengan data
    }



    public function get_permintaan_data() {
        $id         = $this->input->get('id');
        $permintaan = $this->Permintaan_model->get_permintaan_by_id($id);
    
        if ($permintaan) {
            $barang         = $this->Permintaan_model->get_daftar_barang_permintaan($id);
            $data_log       = $this->Permintaan_model->get_log_permintaan($permintaan->id_permintaan);
            $barang_details = [];
            $log_details    = [];
    
            // Mengambil detail barang dari daftar barang
            foreach ($barang as $item) {
                $barang_details[] = [
                    'nama_barang' => $item->nama_barang,
                    'jumlah'      => $item->jumlah,
                    'satuan'      => $item->satuan,
                ];
            }
    
            // Mengubah data_log menjadi array jika memiliki banyak entri
            foreach ($data_log as $log) {
                $log_details[] = [
                    'tanggal_log'   => $log->tanggal_log,
                    'nama_user'     => $log->nama_user,
                    'status'        => $log->status,
                    'catatan'       => $log->keterangan
                ];
            }
    
            echo json_encode([
                'nama_peminta'        => $permintaan->nama_user,
                'tanggal_permintaan'  => $permintaan->tanggal_permintaan,
                'nama_departement'    => $permintaan->nama_departement,
                'deskripsi'           => $permintaan->deskripsi,
                'detail_barang'       => $barang_details,
                'log_details'         => $log_details,  // Menambahkan data log dalam bentuk array
            ]);
        } else {
            echo json_encode(['error' => 'Data not found']);
        }
    }
    

    // PERMINTAAN
    public function permintaan()
    {
        $data['web']    = $this->web;
        $query          = $this->input->get('query');
        $page           = $this->input->get('page');
        
        if (!$page) {
            $page = 1;
        }

        $limit          = 10;
        $offset         = ($page - 1) * $limit;
        $data['data']   = $this->Permintaan_model->get_all_permintaan($query, $limit, $offset);
        $total_rows     = $this->Permintaan_model->count_all_permintaan($query);
        $total_pages    = ceil($total_rows / $limit);
        $prev_page      = $page - 1;
        $next_page      = $page + 1;
        $pagination     = '';

        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=1&query=' . urlencode($query) . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $prev_page . '&query=' . urlencode($query) . '">Sebelumnya</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '&query=' . urlencode($query) . '">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $next_page . '&query=' . urlencode($query) . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&query=' . urlencode($query) . '">Terakhir</a></li>';
        }

        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['query']      = $query;
        $data['title']      = 'Data Permintaan';
        $data['body']       = 'admin/permintaan';
        $this->load->view('template', $data);
    }

    public function approve($permintaan_id)
    {
        $permintaan         = $this->Permintaan_model->get_permintaan_by_id($permintaan_id);
        $detail_permintaan  = $this->Permintaan_model->get_daftar_barang_permintaan($permintaan_id);
        $catatan            = $this->input->post('catatan');

        if (!$permintaan) {
            $this->session->set_flashdata('message', 'swal("Error!", "Permintaan tidak ditemukan!", "error");');
            redirect('admin/permintaan');
        }

        foreach ($detail_permintaan as $detail) {
            $barang = $this->Barang_model->get_barang_by_id($detail->id_barang);
            if ($barang->stok < $detail->jumlah) {
                $this->session->set_flashdata('message', 'swal("Error!", "Stok barang tidak mencukupi!", "error");');
                redirect('admin/permintaan');
            }
        }

        $this->db->trans_start();
        $this->Permintaan_model->update_permintaan($permintaan_id, [
            'status' => 'Diterima HRGA'
        ]);

        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $catatan,
            'status'                => 'Diterima HRGA',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);

        $this->db->trans_complete(); // Selesaikan transaksi

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('message', 'swal("Berhasil!", "Permintaan berhasil disetujui!", "success");');
        } else {
            $this->session->set_flashdata('message', 'swal("Error!", "Terjadi kesalahan saat menyetujui permintaan.", "error");');
        }

        redirect('admin/permintaan');
    }

    public function tolak_hrga($permintaan_id)
    {
        $permintaan = $this->Permintaan_model->get_permintaan_by_id($permintaan_id);
        $catatan        = $this->input->post('catatan');

        if (!$permintaan) {
            $this->session->set_flashdata('error', 'Permintaan tidak ditemukan!');
            redirect('admin/permintaan');
        }
        
        $this->Permintaan_model->update_status_permintaan($permintaan_id, 'Ditolak HRGA');
        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $catatan,
            'status'                => 'Ditolak HRGA',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);

        $this->session->set_flashdata('success', 'Permintaan berhasil ditolak.');
        redirect('admin/permintaan');
    }
    public function expired($permintaan_id)
    {
        $permintaan     = $this->Permintaan_model->get_permintaan_by_id($permintaan_id);
        $catatan        = $this->input->post('catatan');

        if (!$permintaan) {
            $this->session->set_flashdata('error', 'Permintaan tidak ditemukan!');
            redirect('admin/permintaan');
        }
        
        $this->Permintaan_model->update_status_permintaan($permintaan_id, 'Batas');
        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $catatan,
            'status'                => 'Permintaan sudah melebihi batas 30 hari',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);

        $this->session->set_flashdata('success', 'Permintaan berhasil diupdate.');
        redirect('admin/permintaan');
    }


    // PENGAJUAN
    public function pengajuan()
    {
        $data['web']    = $this->web;
        $query          = $this->input->get('query');
        $page           = $this->input->get('page');

        if (!$page) {
            $page = 1;
        }

        $limit          = 10;
        $offset         = ($page - 1) * $limit;
        $data['data']   = $this->Permintaan_model->get_all_pengajuan($query, $limit, $offset);
        $total_rows     = $this->Permintaan_model->count_all_pengajuan($query);
        $total_pages    = ceil($total_rows / $limit);
        $prev_page      = $page - 1;
        $next_page      = $page + 1;
        $pagination     = '';

        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=1&query=' . urlencode($query) . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $prev_page . '&query=' . urlencode($query) . '">Sebelumnya</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '&query=' . urlencode($query) . '">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $next_page . '&query=' . urlencode($query) . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&query=' . urlencode($query) . '">Terakhir</a></li>';
        }

        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';

        $data['query'] = $query;
        $data['title'] = 'Data Pengajuan';
        $data['body'] = 'admin/pengajuan/pengajuan';
        $this->load->view('template', $data);
    }

    public function ApprovePengajuan($permintaan_id)
    {
        $permintaan         = $this->Permintaan_model->get_permintaan_by_id($permintaan_id);
        $detail_permintaan  = $this->Permintaan_model->get_daftar_barang_permintaan($permintaan_id);
        $catatan        = $this->input->post('catatan');

        if (!$permintaan) {
            $this->session->set_flashdata('message', 'swal("Error!", "Permintaan tidak ditemukan!", "error");');
            redirect('admin/permintaan');
        }

        foreach ($detail_permintaan as $detail) {
            $barang = $this->Barang_model->get_barang_by_id($detail->id_barang);
            if ($barang->stok < $detail->jumlah) {
                $this->session->set_flashdata('message', 'swal("Error!", "Stok barang tidak mencukupi!", "error");');
                redirect('admin/permintaan');
            }
        }

        $this->db->trans_start();

        $this->Permintaan_model->update_permintaan($permintaan_id, [
            'status' => 'Diterima PUD/Purchasing'
        ]);

        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $catatan,
            'status'                => 'Diterima PUD/Purchasing',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);

        $pengajuan_data = [
            'id_user' => $this->session->userdata('id_user'),
            'id_permintaan' => $permintaan_id
        ];
        $this->Permintaan_model->simpan_pengajuan($pengajuan_data);

        foreach ($detail_permintaan as $detail) {
            $barang = $this->Barang_model->get_barang_by_id($detail->id_barang);
            $new_stok = $barang->stok - $detail->jumlah;
            $out_stok = [
                'id_barang' => $barang->id_barang,
                'id_user'   => $this->session->userdata('id_user'),
                'jumlah'    => $detail->jumlah,
                'tanggal'   => date('Y-m-d H:i:s'), 
                'status'    => 'keluar'
            ];
            $this->Barang_model->update_stok($detail->id_barang, $new_stok);
            $this->Barang_model->insert_inventori($out_stok);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('message', 'swal("Berhasil!", "Permintaan berhasil disetujui!", "success");');
        } else {
            $this->session->set_flashdata('message', 'swal("Error!", "Terjadi kesalahan saat menyetujui permintaan.", "error");');
        }

        redirect('admin/pengajuan');
    }

    public function tolak_pengajuan($permintaan_id)
    {
        $catatan        = $this->input->post('catatan');
        $permintaan = $this->Permintaan_model->get_permintaan_by_id($permintaan_id);

        if (!$permintaan) {
            $this->session->set_flashdata('error', 'Permintaan tidak ditemukan!');
            redirect('admin/pengajuan');
        }

        // Update status pengajuan
        $this->Permintaan_model->update_status_permintaan($permintaan_id, 'Ditolak PUD/Purchasing');
        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $catatan,
            'status'                => 'Ditolak PUD/Purchasing',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);

        $this->session->set_flashdata('success', 'Permintaan berhasil ditolak.');
        redirect('admin/pengajuan');
    }


    public function data_user() {
        $limit = 10;
        $page = $this->input->get('page');
        if (!$page) {
            $page = 1;
        }
    
        $offset = ($page - 1) * $limit;
    
        // Ambil parameter pencarian dari URL
        $filter = $this->input->get('query');
    
        // Cek jika ada filter pencarian dan panggil method yang sesuai
        if (!empty($filter)) {
            // Pencarian data user berdasarkan query
            $data['users'] = $this->m_data->search_user($filter, $limit, $offset);
            $total_rows = $this->m_data->count_search_user($filter);
        } else {
            // Ambil semua data user tanpa filter
            $data['users'] = $this->m_data->get_all_user($limit, $offset);
            $total_rows = $this->m_data->count_all_user();
        }
    
        // Hitung total halaman
        $total_pages = ceil($total_rows / $limit);
    
        $prev_page = $page - 1;
        $next_page = $page + 1;
    
        // Buat pagination
        $pagination = '';
    
        if ($page > 1) {
            // Tombol "Pertama" dan "Sebelumnya"
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=1&query=' . urlencode($filter)  . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $prev_page . '&query=' . urlencode($filter)  . '">Sebelumnya</a></li>';
        }
    
        // Loop untuk membuat tombol halaman
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '&query=' . urlencode($filter)  . '">' . $i . '</a></li>';
            }
        }
    
        if ($page < $total_pages) {
            // Tombol "Selanjutnya" dan "Terakhir"
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $next_page . '&query=' . urlencode($filter)  . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&query=' . urlencode($filter)  . '">Terakhir</a></li>';
        }
    
        // Menyimpan hasil pagination ke dalam variabel data
        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['offset'] = $offset;
        $data['filter'] = $filter;
        $data['departement'] = $this->db->get('departement')->result();
        $data['title'] = 'Data User';
        $data['body'] = 'admin/user/user'; // View yang akan di-load
        $this->load->view('template', $data); // Memuat template dengan data yang telah disiapkan
    }
    


   public function user_add(){
    $data = [
        'nip'		            => $this->input->post('nip'),
        'nama_user'	            => $this->input->post('nama_user'),
        'level'		            => $this->input->post('level'),
        'email'		            => $this->input->post('email'),
        'id_departement'		=> $this->input->post('id_departement'),
        'is_active'             => 1,
        'password'	            => md5($this->input->post('password')),
    ];
    $cek = $this->db->get_where('user',$data);
    if ($cek->num_rows() > 0) {
        $this->session->set_flashdata('message', 'swal("Ops!", "User dengan data tersebut sudah terdaftar", "error");');
        redirect('Admin/data_user');
    }
    else
    {
        $this->db->insert('user', $data);
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Berhasil Menyimpan Data", "success");');
        redirect('Admin/data_user');
    }
   }

   public function user_edit($id_user) {
        // Ambil data user berdasarkan ID
        $user = $this->db->get_where('user', ['id_user' => $id_user])->row();

        // Data yang akan diperbarui
        $update_data = [
            'nip'        => $this->input->post('nip'),
            'nama_user'  => $this->input->post('nama_user'),
            'email'      => $this->input->post('email'),
            'id_departement'=> $this->input->post('id_departement'),
            'level'      => $this->input->post('level')
        ];

        // Cek apakah password baru diubah
        $new_password = $this->input->post('password');
        if (!empty($new_password)) {
            // Cek apakah password baru berbeda dengan password lama
            if ($new_password !== $user->password) {
                // Jika password baru tidak sama dengan password lama, update password
                $update_data['password'] = md5($new_password); // Hash password baru
            }
        }
        // Jika password baru tidak ada (kosong), password lama tetap tidak diubah


            // Lakukan update data user
            $this->db->where('id_user', $id_user);
            if ($this->db->update('user', $update_data)) {
                $this->session->set_flashdata('message', 'swal("Berhasil!", "Data user berhasil diperbarui", "success");');
                redirect('Admin/data_user');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data user');
                redirect('Admin/data_user');
            }
        
    }

    public function user_delete($id_user) {
        // Pastikan user yang akan dihapus ada di database
        $user = $this->db->get_where('user', ['id_user' => $id_user])->row();
    
        if (!$user) {
            // Jika user tidak ditemukan, beri pesan error dan redirect
            $this->session->set_flashdata('error', 'User tidak ditemukan');
            redirect('Admin/data_user');
        }
    
        // Hapus data user berdasarkan ID
        $this->db->where('id_user', $id_user);
        if ($this->db->delete('user')) {
            // Jika berhasil dihapus, beri pesan sukses
            $this->session->set_flashdata('message', 'swal("Berhasil!", "User berhasil dihapus", "success");');
            redirect('Admin/data_user');
        } else {
            // Jika gagal dihapus, beri pesan error
            $this->session->set_flashdata('error', 'Gagal menghapus data user');
            redirect('Admin/data_user');
        }
    }

    public function validate_user()
    {
        // Ambil ID user dari input POST
        $id_user = $this->input->post('id_user');

         // Validasi ID user
        if (!$id_user) {
            echo json_encode(['success' => false, 'message' => 'ID user tidak ditemukan.']);
            return;
        }

        $user = $this->db->get_where('user', ['id_user' => $id_user])->row();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan.']);
            return;
        }


        // Data yang akan diperbarui
        $update_data = [
            'is_active'  => 1
        ];
        
        
        // Update data user
        $this->db->where('id_user', $id_user);
        if ($this->db->update('user', $update_data)) {
            echo json_encode(['success' => true, 'message' => 'Data user berhasil divalidasi.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal validasi data user.']);
        }
    }


    

}
