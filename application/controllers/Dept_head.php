<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dept_head extends CI_Controller
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

    public function dept_head()
    {
        $limit = 10;
        $page = $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $limit;

        $query = $this->input->get('query');
        if (!empty($query)) {
            $this->db->group_start()
                    ->like('nama_dept_head', $query)
                    ->or_like('id_dept_head', $query)
                    ->group_end();
            $data['data'] = $this->db->get('dept_head', $limit, $offset)->result();
            $total_rows = $this->db->group_start()
                                ->like('nama_dept_head', $query)
                                ->or_like('id_dept_head', $query)
                                ->group_end()
                                ->count_all_results('dept_head');
        } else {
            $data['data'] = $this->db->get('dept_head', $limit, $offset)->result();
            $total_rows = $this->db->count_all('dept_head');
        }

        $total_pages = ceil($total_rows / $limit);
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

        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['query'] = $query;
        $data['title'] = 'Data Dept Head';
        $data['body'] = 'admin/dept_head/dept_head';

        $this->load->view('template', $data);
    }

    public function add()
    {
        $data['web'] = $this->web;
        $data['title'] = 'Tambah Data Departemen Head';
        $data['body'] = 'admin/dept_head/add';
        $data['kode'] = $this->generate_kode();
        $this->load->view('template', $data);
    }

    public function simpan()
    {
        $nama = $this->input->post('nama');
        $exists = $this->db->get_where('dept_head', ['nama_dept_head' => $nama])->row();

        if ($exists) {
            $this->session->set_flashdata('message', 'swal("Gagal!", "Nama Departemen Head sudah ada!", "error");');
            redirect('Dept_head/add');
        } else {
            $this->db->insert('dept_head', [
                'id_dept_head' => $this->input->post('kode'),
                'nama_dept_head' => $nama
            ]);

            $this->session->set_flashdata('message', 'swal("Berhasil!", "Tambah data berhasil", "success");');
            redirect('Dept_head/dept_head');
        }
    }

    public function edit($id)
    {
        $data['web'] = $this->web;
        $data['data'] = $this->db->get_where('dept_head', ['id_dept_head' => $id])->row();
        $data['title'] = 'Update Data Departement Head';
        $data['body'] = 'admin/dept_head/edit';
        $this->load->view('template', $data);
    }

    public function update($id)
    {
        $nama = $this->input->post('nama');
        $exists = $this->db->where('nama_dept_head', $nama)
            ->where('id_dept_head !=', $id)
            ->get('dept_head')
            ->row();

        if ($exists) {
            $this->session->set_flashdata('message', 'swal("Gagal!", "Nama Departemen Head sudah ada!", "error");');
            redirect('Dept_head/edit/' . $id);
        } else {
            $this->db->update('dept_head', [
                'nama_dept_head' => $nama
            ], ['id_dept_head' => $id]);

            $this->session->set_flashdata('message', 'swal("Berhasil!", "Edit data berhasil", "success");');
            redirect('Dept_head/dept_head');
        }
    }

    public function delete($id)
    {
        $this->db->delete('dept_head', ['id_dept_head' => $id]);
        $this->session->set_flashdata('message', 'swal("Berhasil!", "Hapus data berhasil", "success");');
        redirect('Dept_head/dept_head');
    }

    private function generate_kode()
    {
        $this->db->select('id_dept_head');
        $this->db->order_by('id_dept_head', 'DESC');
        $this->db->limit(1);
        $last_kode = $this->db->get('dept_head')->row('id_dept_head');

        if (!$last_kode) {
            return 'KDEPH001';
        }

        $last_number = (int) substr($last_kode, 5);
        $new_number = $last_number + 1;

        return 'KDEPH' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
    }
}
