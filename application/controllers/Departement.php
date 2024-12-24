<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->web = $this->db->get('web')->row();
        $this->load->library('upload');

        if ($this->session->userdata('level') == 1) {
            $this->session->set_flashdata('message', 'swal("Ops!", "Anda harus login sebagai admin", "error");');
            redirect('auth');
        }

        $this->load->model('Barang_model');
    }

    public function departemen()
    {
        $limit = 10;
        $page = $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $limit;

        $query = $this->input->get('query');

        $this->db->select('departement.*, dept_head.nama_dept_head');
        $this->db->from('departement');
        $this->db->join('dept_head', 'departement.id_dept_head = dept_head.id_dept_head', 'left'); // JOIN dengan tabel dept_head

        if (!empty($query)) {
            $this->db->group_start()
                    ->like('departement.id_departement', $query)
                    ->or_like('departement.nama_departement', $query)
                    ->or_like('dept_head.nama_dept_head', $query)
                    ->group_end();
        }

        $this->db->limit($limit, $offset);
        $data['data'] = $this->db->get()->result();

        $this->db->select('COUNT(*) as total');
        $this->db->from('departement');
        $this->db->join('dept_head', 'departement.id_dept_head = dept_head.id_dept_head', 'left');
        if (!empty($query)) {
            $this->db->group_start()
                    ->like('departement.id_departement', $query)
                    ->or_like('departement.nama_departement', $query)
                    ->or_like('dept_head.nama_dept_head', $query)
                    ->group_end();
        }
        $total_rows = $this->db->get()->row()->total;

        $total_pages = ceil($total_rows / $limit);
        $pagination = $this->generate_pagination($page, $total_pages, $query);

        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['query'] = $query;
        $data['title'] = 'Data Departemen';
        $data['body'] = 'admin/departemen/departemen';

        $this->load->view('template', $data);
    }

    private function generate_pagination($page, $total_pages, $query)
    {
        $prev_page = $page - 1;
        $next_page = $page + 1;
        $pagination = '';

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

        return $pagination;
    }


    public function add()
    {
        $data['web']    = $this->web;
        $data['title']  = 'Tambah Data Departemen';
        $data['body']   = 'admin/departemen/add';
        $data['dept_heads'] = $this->db->get('dept_head')->result(); // Ambil semua data dept_head
        $kode           = $this->generate_kode_departemen();
        $data['kode']   = $kode;
        $this->load->view('template', $data);
    }

    public function simpan()
    {
        $namaDepartement = $this->input->post('nama');
        $idDeptHead = $this->input->post('id_dept_head');
        $idDepartement = $this->input->post('kode');

        // Cek apakah nama departemen sudah ada
        $exists = $this->db->get_where('departement', ['nama_departement' => $namaDepartement])->row();

        if ($exists) {
            // Jika nama sudah ada, tampilkan pesan error dan kembali ke form tambah
            $this->session->set_flashdata('message', 'swal("Gagal!", "Nama Departemen sudah ada!", "error");');
            redirect('Departement/add');
        } else {
            // Jika nama belum ada, simpan data baru
            $this->db->insert('departement', [
                'id_departement' => $idDepartement,
                'nama_departement' => $namaDepartement,
                'id_dept_head' => $idDeptHead
            ]);

            $this->session->set_flashdata('message', 'swal("Berhasil!", "Tambah Departemen berhasil!", "success");');
            redirect('Departement/departemen');
        }
    }


    public function edit($id)
    {
        $data['web']        = $this->web;
        $data['data']       = $this->db->get_where('departement', ['id_departement' => $id])->row();
        $data['dept_heads'] = $this->db->get('dept_head')->result(); // Ambil semua data dept_head
        $data['title']      = 'Update Data Departemen';
        $data['body']       = 'admin/departemen/edit';
        $this->load->view('template', $data);
    }


    public function update($id)
    {
        $namaDepartement = $this->input->post('nama');
        $idDeptHead = $this->input->post('id_dept_head');

        // Cek apakah nama departemen sudah ada di database
        $exists = $this->db->where('nama_departement', $namaDepartement)
                        ->where('id_departement !=', $id) // Pastikan tidak mengecek nama milik ID yang sama
                        ->get('departement')
                        ->row();

        if ($exists) {
            // Jika nama sudah ada, beri pesan error dan kembalikan ke form edit
            $this->session->set_flashdata('message', 'swal("Gagal!", "Nama Departemen sudah ada!", "error");');
            redirect('Departement/edit/' . $id);
        } else {
            // Jika nama belum ada, lanjutkan update data
            $this->db->update('departement', [
                'id_dept_head' => $idDeptHead,
                'nama_departement' => $namaDepartement
            ], ['id_departement' => $id]);

            $this->session->set_flashdata('message', 'swal("Berhasil!", "Edit Departemen berhasil", "success");');
            redirect('Departement/departemen');
        }
    }


    public function delete($id)
    {
        $this->db->delete('departement', ['id_departement' => $id]);
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Hapus Departement berhasil", "success");');
        redirect('Departement/departemen');
    }

    private function generate_kode_departemen()
{
    // Ambil kode terakhir dari tabel departemen
    $this->db->select('id_departement');
    $this->db->order_by('id_departement', 'DESC');
    $this->db->limit(1);
    $last_kode = $this->db->get('departement')->row('id_departement');

    // Jika tidak ada kode, mulai dari awal
    if (!$last_kode) {
        return 'KDEP001';
    }

    // Ambil angka dari kode terakhir dan tambahkan 1
    $last_number = (int) substr($last_kode, 4);
    $new_number = $last_number + 1;

    // Format angka menjadi tiga digit dengan padding
    return 'KDEP' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

}
