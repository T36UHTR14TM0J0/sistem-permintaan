<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->web = $this->db->get('web')->row();

        $this->load->library('upload');
        if ($this->session->userdata('level') == 1 ) {
            $this->session->set_flashdata('message', 'swal("Ops!", "Anda harus login sebagai admin", "error");');
            redirect('auth');
        }
        $this->load->model('Barang_model');

        
    }

    public function barang()
    {
        $limit = 10;
        $page = $this->input->get('page');
        if (!$page) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $filter = $this->input->get('filter');
        $query = $this->input->get('query');

        if (!empty($query) && !empty($filter)) {
            $data['barang'] = $this->Barang_model->search_barang($filter, $query, $limit, $offset);
            $total_rows = $this->Barang_model->count_search_barang($filter, $query);
        } else {
            $data['barang'] = $this->Barang_model->get_all_barang($limit, $offset);
            $total_rows = $this->Barang_model->count_all_barang();
        }

        $total_pages = ceil($total_rows / $limit);

        $prev_page = $page - 1;
        $next_page = $page + 1;

        $pagination = '';

        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=1&filter=' . urlencode($filter) . '&query=' . urlencode($query) . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $prev_page . '&filter=' . urlencode($filter) . '&query=' . urlencode($query) . '">Sebelumnya</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '&filter=' . urlencode($filter) . '&query=' . urlencode($query) . '">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $next_page . '&filter=' . urlencode($filter) . '&query=' . urlencode($query) . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&filter=' . urlencode($filter) . '&query=' . urlencode($query) . '">Terakhir</a></li>';
        }

        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['offset'] = $offset;
        $data['filter'] = $filter;
        $data['query'] = $query;
        $data['title'] = 'Data Barang';
        $data['body'] = 'admin/barang/barang';
        $this->load->view('template', $data);
    }

    public function add()
    {
        $data['title']  = 'Tambah Barang';
        $data['body']   = 'admin/barang/add';
        $this->load->view('template', $data);
    }

    public function simpan()
    {
        $upload_path = './uploads/barang/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $foto = null;

        if (!empty($_FILES['foto']['name'])) {
            $file_tmp       = $_FILES['foto']['tmp_name'];
            $file_name      = $_FILES['foto']['name'];
            $file_size      = $_FILES['foto']['size'];
            $file_type      = $_FILES['foto']['type'];
            $allowed_ext    = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_ext)) {
                $this->session->set_flashdata('message', 'swal("Error!", "Ekstensi file tidak diizinkan.", "error");');
                redirect('Barang/barang');
                return;
            }

            if ($file_size > 2 * 1024 * 1024) {
                $this->session->set_flashdata('message', 'swal("Error!", "Ukuran file terlalu besar.", "error");');
                redirect('Barang/barang');
                return;
            }

            $new_file_name = time() . '.' . $file_ext;
            $upload_file_path = $upload_path . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_file_path)) {
                $foto = $new_file_name;
            } else {
                $this->session->set_flashdata('message', 'swal("Error!", "Upload foto gagal.", "error");');
                redirect('Barang/barang');
                return;
            }
        }

        $data = [
            'kategori_barang'   => $this->input->post('kategori_barang'),
            'nama_barang'       => $this->input->post('nama_barang'),
            'harga'             => str_replace('.', '', $this->input->post('harga')), 
            'stok'              => $this->input->post('stok'),
            'satuan'            => $this->input->post('satuan'),
            'keterangan'        => $this->input->post('keterangan'),
            'foto'              => $foto
        ];

        $insert = $this->Barang_model->insert_barang($data);

        $out_stok = [
            'id_barang' => $insert,
            'id_user' => $this->session->userdata('id_user'),
            'jumlah' => $this->input->post('stok'),
            'tanggal' => date('Y-m-d H:i:s'),
            'status' => 'masuk'
        ];

        $this->Barang_model->insert_inventori($out_stok);

        $this->session->set_flashdata('message', 'swal("Success!", "Barang berhasil ditambahkan!", "success");');
        redirect('Barang/barang');
    }

    public function edit($id)
    {
        $data['barang'] = $this->Barang_model->get_barang_by_id($id);
        $data['title'] = 'Edit Barang';
        $data['body'] = 'admin/barang/edit';
        $this->load->view('template', $data);
    }

    public function update($id)
    {
        $upload_path = './uploads/barang/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $foto = $this->input->post('foto_lama');

        if (!empty($_FILES['foto']['name'])) {
            $file_tmp = $_FILES['foto']['tmp_name'];
            $file_name = $_FILES['foto']['name'];
            $file_size = $_FILES['foto']['size'];
            $file_type = $_FILES['foto']['type'];

            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_ext)) {
                $this->session->set_flashdata('message', 'swal("Error!", "Ekstensi file tidak diizinkan.", "error");');
                redirect('Barang/barang');
                return;
            }

            if ($file_size > 2 * 1024 * 1024) {
                $this->session->set_flashdata('message', 'swal("Error!", "Ukuran file terlalu besar.", "error");');
                redirect('Barang/barang');
                return;
            }

            $new_file_name = time() . '.' . $file_ext;
            $upload_path = './uploads/barang/' . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $foto = $new_file_name;
            } else {
                $this->session->set_flashdata('message', 'swal("Error!", "Upload foto gagal.", "error");');
                redirect('Barang/barang');
                return;
            }
        }

        $data = [
            'kategori_barang' => $this->input->post('kategori_barang'),
            'nama_barang' => $this->input->post('nama_barang'),
            'harga' => str_replace('.', '', $this->input->post('harga')),
            'stok' => $this->input->post('stok'),
            'keterangan' => $this->input->post('keterangan'),
            'satuan' => $this->input->post('satuan'),
            'foto' => $foto
        ];

        $this->Barang_model->update_barang($id, $data);
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Barang berhasil diupdate!", "success");');
        redirect('Barang/barang');
    }

    public function proses_input_stok($id)
{
    // Ambil data inputan dari form
    $stok = $this->input->post('stok');
    $tanggal = $this->input->post('tanggal');

    // Validasi jumlah stok
    if (empty($stok) || !is_numeric($stok) || $stok <= 0) {
        $this->session->set_flashdata('message', 'swal("Gagal!", "Jumlah stok harus berupa angka positif!", "error");');
        redirect('Barang/barang');
    }

    // Validasi tanggal
    if (empty($tanggal)) {
        $this->session->set_flashdata('message', 'swal("Gagal!", "Tanggal harus diisi!", "error");');
        redirect('Barang/barang');
    }

    // Konfigurasi folder upload
    $upload_path = './uploads/bukti/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);  // Membuat folder jika belum ada
    }

    $bukti = null;  // Variabel untuk menyimpan nama file bukti

    // Cek apakah ada file bukti yang diupload
    if (!empty($_FILES['bukti']['name'])) {
        $file_tmp = $_FILES['bukti']['tmp_name'];
        $file_name = $_FILES['bukti']['name'];
        $file_size = $_FILES['bukti']['size'];
        $file_type = $_FILES['bukti']['type'];

        // Ekstensi file yang diperbolehkan
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi ekstensi file
        if (!in_array($file_ext, $allowed_ext)) {
            $this->session->set_flashdata('message', 'swal("Error!", "Ekstensi file tidak diizinkan. (Hanya jpg, jpeg, png, pdf)", "error");');
            redirect('Barang/barang');
            return;
        }

        // Validasi ukuran file (maksimal 2MB)
        if ($file_size > 2 * 1024 * 1024) {
            $this->session->set_flashdata('message', 'swal("Error!", "Ukuran file terlalu besar. Maksimal 2MB", "error");');
            redirect('Barang/barang');
            return;
        }

        // Membuat nama file baru agar unik
        $new_file_name = time() . '.' . $file_ext;
        $upload_path = './uploads/bukti/' . $new_file_name;

        // Menyimpan file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            $bukti = $new_file_name;  // Simpan nama file bukti yang berhasil diupload
        } else {
            $this->session->set_flashdata('message', 'swal("Error!", "Upload bukti gagal.", "error");');
            redirect('Barang/barang');
            return;
        }
    }

    // Data untuk disimpan ke tabel inventori
    $out_stok = [
        'id_barang' => $id,
        'id_user' => $this->session->userdata('id_user'),
        'jumlah' => $stok,
        'tanggal' => $tanggal,
        'status' => 'masuk',
        'bukti' => $bukti  // Menyimpan nama file bukti
    ];

    // Update stok barang
    $update_result = $this->Barang_model->update_stok_barang($id, $stok);

    if ($update_result) {
        // Masukkan data inventori
        $this->Barang_model->insert_inventori($out_stok);
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Stok barang berhasil ditambahkan!", "success");');
    } else {
        $this->session->set_flashdata('message', 'swal("Gagal!", "Terjadi kesalahan saat memperbarui stok barang.", "error");');
    }

    redirect('Barang/barang');
}


    public function barang_delete($id)
    {
        $this->Barang_model->delete_barang($id);
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Barang berhasil dihapus!", "success");');
        redirect('Barang/barang');
    }

}