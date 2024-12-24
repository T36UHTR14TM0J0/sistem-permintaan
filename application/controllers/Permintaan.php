<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('id_user')) {
            redirect('auth');
        }
        $this->load->model('Permintaan_model');
        $this->load->model('m_data');
        
    }

    public function index() {    
        $this->load->view('index');
    }

    public function permintaan()
    {
        $id_departement             = $this->session->userdata('id_departement');
        $data['departement']        = $this->db->select('nama_departement')->where('id_departement',$id_departement)->get('departement')->result();
        $data['kategori_barang']    = $this->Permintaan_model->get_kategori_dan_barang();
        $data['title']              = 'Permintaan Barang';
        $data['body']               = 'permintaan';
        $this->load->view('index', $data);
    }
    

    public function simpan() {
        // var_dump($_POST);
        // exit;
        $nama_user         = $this->session->userdata('id_user');    
        $keterangan        = $this->input->post('keterangan');     
        $barang_ids        = $this->input->post('barang_ids');     
        $qtys              = $this->input->post('qtys');  
        $tanggal_permintaan = date('Y-m-d H:i:s'); 
    
        // Menangani input barang_ids yang mungkin berupa string yang dipisahkan koma
        if (is_array($barang_ids) && count($barang_ids) === 1) {
            $barang_ids = explode(',', $barang_ids[0]);
        }
    
        // Menangani input qtys yang mungkin berupa string yang dipisahkan koma
        if (is_array($qtys) && count($qtys) === 1) {
            $qtys = explode(',', $qtys[0]);
        }
    
        // Validasi jika barang_ids kosong atau tidak ada barang yang dipilih
        if (empty($barang_ids[0]) || count($barang_ids[0]) === 0) {
            $this->session->set_flashdata('message', 'swal("Ops!", "Pilih barang terlebih dahulu.", "error");');
            redirect('permintaan/permintaan');
        }
    
        // Validasi jika jumlah barang dan qty tidak cocok
        if (count($barang_ids[0]) !== count($qtys[0])) {
            $this->session->set_flashdata('message', 'swal("Ops!", "Jumlah barang dan kuantitas tidak cocok.", "error");');
            redirect('permintaan/permintaan');
        }
    
        // Data untuk menyimpan permintaan utama
        $permintaan_data = [
            'id_user'           => $nama_user,
            'deskripsi'         => $keterangan,
            'status'            => 'Menunggu Diterima',
            'tanggal_permintaan' => $tanggal_permintaan
        ];
    
        // Menyimpan data permintaan
        $permintaan_id = $this->Permintaan_model->simpan_permintaan($permintaan_data);
    
        // Data log permintaan
        $log_data = [
            'id_user'               => $nama_user,
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $keterangan,
            'status'                => 'Menunggu Diterima',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
    
        // Menyimpan log permintaan
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);
    
        // Validasi dan penyimpanan detail permintaan untuk setiap barang
        foreach ($barang_ids as $index => $barang_id) {
            $qty = (int) $qtys[$index];
    
            // Validasi jika jumlah barang tidak valid
            if ($qty <= 0) {
                $this->session->set_flashdata('message', 'swal("Ops!", "Jumlah barang tidak valid.", "error");');
                redirect('permintaan/permintaan');
            }
    
            // Menyimpan detail permintaan
            $detail_data = [
                'id_permintaan'    => $permintaan_id,
                'id_barang'        => $barang_id,
                'jumlah'           => $qty
            ];
    
            $this->Permintaan_model->simpan_detail_permintaan($detail_data);
        }
    
        // Mengirimkan pesan sukses jika permintaan berhasil
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Permintaan berhasil dikirim.", "success");');
        redirect('permintaan/daftar_permintaan');
    }
    

    public function daftar_permintaan(){
        $filter_type                = $this->input->get('filter_type');
        $filter_value               = $this->input->get('filter_value');
        $tanggal_awal               = $this->input->get('tanggal_awal');
        $tanggal_akhir              = $this->input->get('tanggal_akhir');
        $data['filter_type']        = $filter_type;
        $data['filter_value']       = $filter_value;
        $data['tanggal_awal']       = $tanggal_awal;
        $data['tanggal_akhir']      = $tanggal_akhir;
        $page                       = $this->input->get('page') ?: 1;
        $limit                      = 10;
        $offset                     = ($page - 1) * $limit;
    
        $data['daftar_permintaan'] = $this->Permintaan_model->get_all_daftar_permintaan(
            $filter_type, $filter_value, $tanggal_awal,$tanggal_akhir, $limit, $offset
        );

        $total_rows = $this->Permintaan_model->count_all_daftar_permintaan(
            $filter_type, $filter_value,  $tanggal_awal,$tanggal_akhir
        );
    
        $total_pages                = ceil($total_rows / $limit);
        $pagination                 = $this->generate_pagination($page, $total_pages, $filter_type, $filter_value,  $tanggal_awal,$tanggal_akhir);
        $data['pagination']         = $pagination;
        $data['title']              = 'Daftar Permintaan Saya';
        $data['body']               = 'daftar_permintaan';
        $this->load->view('index', $data);
    }

    private function generate_pagination($page, $total_pages, $filter_type, $filter_value, $tanggal_awal, $tanggal_akhir)
    {
        $pagination = '';

        // Generate base URL with filters
        $base_url = '?filter_type=' . urlencode($filter_type) . 
                    '&filter_value=' . urlencode($filter_value) . 
                    '&tanggal_awal=' . urlencode($tanggal_awal) . 
                    '&tanggal_akhir=' . urlencode($tanggal_akhir);

        // Link to first and previous page
        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=1">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($page - 1) . '">Sebelumnya</a></li>';
        }

        // Page number links
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // Link to next and last page
        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($page + 1) . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . $total_pages . '">Terakhir</a></li>';
        }

        return '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
    }


    public function detail_permintaan($id_permintaan){
        $data['title']              = 'Detail';
        $data['permintaan']         = $this->Permintaan_model->get_permintaan_by_id($id_permintaan);
        $data['barang']             = $this->Permintaan_model->get_daftar_barang_permintaan($id_permintaan);
        $data['body']               = 'detail_permintaan';
        $this->load->view('index', $data);
    }

    public function get_permintaan_data() {
        $id         = $this->input->get('id');
        $permintaan = $this->Permintaan_model->get_permintaan_by_id_user($id,$this->session->userdata('id_user'));
    
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
                    'satuan'      => $item->satuan
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
                'tanggal_jadwal'      => $permintaan->tanggal_jadwal,
                'nama_departement'    => $permintaan->nama_departement,
                'deskripsi'           => $permintaan->deskripsi,
                'detail_barang'       => $barang_details,
                'log_details'         => $log_details,  // Menambahkan data log dalam bentuk array
            ]);
        } else {
            echo json_encode(['error' => 'Data not found']);
        }
    }


    public function validasi($permintaan_id) {

        $upload_path = './uploads/validasi/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $foto = null;

        if (!empty($_FILES['upload_foto']['name'])) {
            $file_tmp = $_FILES['upload_foto']['tmp_name'];
            $file_name = $_FILES['upload_foto']['name'];
            $file_size = $_FILES['upload_foto']['size'];
            $file_type = $_FILES['upload_foto']['type'];
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_ext)) {
                $this->session->set_flashdata('message', 'swal("Error!", "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, dan GIF yang bisa diupload.", "error");');
                redirect('Permintaan/daftar_permintaan');
                return;
            }

            if ($file_size > 2 * 1024 * 1024) {
                $this->session->set_flashdata('message', 'swal("Error!", "Ukuran file terlalu besar. Maksimal 2MB.", "error");');
                redirect('Permintaan/daftar_permintaan');
                return;
            }

            $new_file_name = time() . '.' . $file_ext;
            $upload_file_path = $upload_path . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_file_path)) {
                $foto = $new_file_name;
            } else {
                $this->session->set_flashdata('message', 'swal("Error!", "Upload foto gagal. Pastikan folder uploads memiliki izin yang tepat.", "error");');
                redirect('Permintaan/daftar_permintaan');
                return;
            }
        }
        $tanggal_diterima = $this->input->post('tanggal_diterima');
        $data = array(
            'tanggal_diterima' => $tanggal_diterima,
            'bukti_terima' => $foto,
            'status'    => "Sudah Diterima Departement"
        );
        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => 'Sudah Diterima Departement',
            'status'                => 'Sudah Diterima Departement',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        $log_permintaan = $this->m_data->simpan_log_permintaan($log_data);
        if ($this->Permintaan_model->update_permintaan($permintaan_id,$data)) {
            $this->session->set_flashdata('message', 'swal("Berhasil!", "Berhasil menyimpan data.", "success");');
                redirect('Permintaan/daftar_permintaan');
        } else {
            $this->session->set_flashdata('message', 'swal("Error!", "Terjadi kesalahan saat menyimpan data validasi.", "error");');
                redirect('Permintaan/daftar_permintaan');
        }
        
    }

    public function get_image_data() {
        $id_permintaan = $this->input->get('id');
        
        // Query untuk mendapatkan data gambar (sesuaikan dengan struktur database Anda)
        $imageData = $this->db->select('bukti_terima')
                              ->from('permintaan')
                              ->where('id_permintaan', $id_permintaan)
                              ->get()
                              ->row();
    
        if ($imageData) {
            echo json_encode(['image_url' => $imageData->bukti_terima]);
        } else {
            echo json_encode(['image_url' => '']);
        }
    }
    
    
    
    
    
    
    
}
