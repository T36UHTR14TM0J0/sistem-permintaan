<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_data extends CI_Model
{

	 public function simpan_log_permintaan($data) {
        $this->db->insert('log_permintaan', $data); 
        return $this->db->insert_id();
    }

	public function get_all_user($limit, $offset)
	{
		$this->db->select('user.*, departement.nama_departement'); // Pilih kolom dari tabel user dan departement
		$this->db->from('user');
		$this->db->join('departement', 'user.id_departement = departement.id_departement', 'left'); // Join tabel departement
		$this->db->limit($limit, $offset); 
		return $this->db->get()->result();
	}


	public function count_all_user()
	{
		// Menghitung total jumlah user
		return $this->db->count_all('user');
	}

	public function search_user($filter, $limit, $offset)
	{
		$this->db->select('user.*, departement.nama_departement'); // Pilih kolom dari user dan departement
		$this->db->from('user');
		$this->db->join('departement', 'user.id_departement = departement.id_departement', 'left'); // Join tabel departement
		
		// Menggunakan LIKE untuk pencarian berdasarkan nama_user atau departement_name
		$this->db->like('user.nama_user', $filter);
		$this->db->or_like('departement.nama_departement', $filter); // Pencarian juga pada nama departement
		
		// Menambahkan limit dan offset untuk pagination
		$this->db->limit($limit, $offset);
		
		// Menjalankan query
		return $this->db->get()->result();
	}


	public function count_search_user($filter)
	{
		$this->db->from('user');
		$this->db->join('departement', 'user.id_departement = departement.id_departement', 'left'); // Join tabel departement
		
		// Menggunakan LIKE untuk pencarian berdasarkan nama_user atau departement_name
		$this->db->like('user.nama_user', $filter);
		$this->db->or_like('departement.nama_departement', $filter); // Pencarian juga pada nama departement
		
		// Menghitung jumlah hasil pencarian
		return $this->db->count_all_results(); 
	}


	


	

	



    

	public function get_all_departemen()
{
    return $this->db->get('departemen')->result();
}



    // Mengambil Semua Kategori Barang
    public function get_all_kategori_barang()
    {
        return $this->db->get('kategori_barang')->result();
    }
	public function pegawai()
	{
		return $this->db->get('pegawai')->result(); // Ganti 'pegawai' dengan nama tabel yang sesuai
	}
	


	function hari($hari)
	{

		switch ($hari) {
			case 'Sun':
				$hari_ini = "Minggu";
				break;

			case 'Mon':
				$hari_ini = "Senin";
				break;

			case 'Tue':
				$hari_ini = "Selasa";
				break;

			case 'Wed':
				$hari_ini = "Rabu";
				break;

			case 'Thu':
				$hari_ini = "Kamis";
				break;

			case 'Fri':
				$hari_ini = "Jumat";
				break;

			case 'Sat':
				$hari_ini = "Sabtu";
				break;

			default:
				$hari_ini = "Tidak di ketahui";
				break;
		}

		return $hari_ini;
	}
	function tgl_indo($tanggal)
	{
		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);

		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun

		return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
	}
	function hadirtoday($tahun, $bulan, $hari)
	{
		$this->db->select('*');
		$this->db->from('absen');
		$this->db->where('keterangan', 'masuk');
		$this->db->where('year(waktu)', $tahun);
		$this->db->where('month(waktu)', $bulan);
		$this->db->where('day(waktu)', $hari);
		return $this->db->get();
	}


	// class Laporan_model extends CI_Model {

		// Fungsi untuk mendapatkan semua data lap penjadwalan
		

		

		


		
		public function get_data_barang_per_tahun($tahun) {
			// Query untuk mengambil data barang masuk dan keluar berdasarkan bulan
			$this->db->select('MONTH(tanggal) AS bulan');
			$this->db->select('SUM(CASE WHEN status = "masuk" THEN jumlah ELSE 0 END) AS barang_masuk');
			$this->db->select('SUM(CASE WHEN status = "keluar" THEN jumlah ELSE 0 END) AS barang_keluar');
			$this->db->from('inventori_barang');
			$this->db->where('YEAR(tanggal)', $tahun);
			$this->db->group_by('MONTH(tanggal)');
			$this->db->order_by('bulan');
			$query = $this->db->get();
			
			$data = $query->result_array();
		
			// Inisialisasi array dengan nilai 0 untuk 12 bulan
			$result = array(
				'barang_masuk' => array_fill(0, 12, 0),
				'barang_keluar' => array_fill(0, 12, 0)
			);
		
			// Menyusun hasil query ke dalam array sesuai bulan (index 0 untuk Januari, 11 untuk Desember)
			foreach ($data as $row) {
				$bulan = $row['bulan'] - 1; // Index bulan dimulai dari 0 (Jan) hingga 11 (Des)
				$result['barang_masuk'][$bulan] = $row['barang_masuk'];
				$result['barang_keluar'][$bulan] = $row['barang_keluar'];
			}
		
			return $result; // Mengembalikan array data dengan format yang sudah disesuaikan
		}

		public function get_data_permintaan_per_tahun($tahun) {
			$this->db->select('MONTH(tanggal_permintaan) AS bulan');
			$this->db->select('COUNT(id_permintaan) AS jumlah_permintaan');
			$this->db->from('permintaan');
			$this->db->where('YEAR(tanggal_permintaan)', $tahun);
			$this->db->group_by('MONTH(tanggal_permintaan)');
			$this->db->order_by('bulan');
			$query = $this->db->get();
			
			$data = $query->result_array();
		
			// Inisialisasi array dengan nilai 0 untuk 12 bulan
			$result = array_fill(0, 12, 0);
		
			// Menyusun hasil query ke dalam array sesuai bulan (index 0 untuk Januari, 11 untuk Desember)
			foreach ($data as $row) {
				$bulan = $row['bulan'] - 1; // Index bulan dimulai dari 0 (Jan) hingga 11 (Des)
				$result[$bulan] = $row['jumlah_permintaan'];
			}
		
			return $result; // Mengembalikan array data permintaan
		}

		// Fungsi untuk memperbarui data lebih dari 30 hari
		public function updateOldRecords() {
			// Hitung tanggal 30 hari yang lalu
			$thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
	
			// Data yang ingin diperbarui
			$data = [
				'status' => 'batas' // Contoh: Mengubah status menjadi 'expired'
			];
	
			// Update data yang lebih lama dari 30 hari
			$this->db->where('status', 'Menunggu Diterima');
			$this->db->where('tanggal_permintaan <', $thirtyDaysAgo);
			$this->db->update('permintaan', $data);
	
			// Mengembalikan jumlah baris yang diperbarui
			return $this->db->affected_rows();
		}
		
		
		

		

	// }
	
}

/* End of file M_data.php */
/* Location: ./application/models/M_data.php */