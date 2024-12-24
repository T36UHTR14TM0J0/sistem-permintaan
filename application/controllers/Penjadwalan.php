<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjadwalan extends CI_Controller
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

        $this->load->model('Penjadwalan_model');
        $this->load->model('Permintaan_model');
        $this->load->model('M_data');
    }

    public function penjadwalan()
{
    $data['web']        = $this->web;
    $filter_type        = $this->input->get('filter_type');
    $filter_value       = $this->input->get('filter_value');
    $bulan_penjadwalan  = $this->input->get('bulan_penjadwalan');
    $tahun_penjadwalan  = $this->input->get('tahun_penjadwalan');
    $page               = $this->input->get('page') ?: 1;
    $limit              = 10;
    $offset             = ($page - 1) * $limit;
    $data['data']       = $this->Penjadwalan_model->get_all_penjadwalan(
        $filter_type, $filter_value, $bulan_penjadwalan, $tahun_penjadwalan, $limit, $offset
    );

    $total_rows                 = $this->Penjadwalan_model->count_all_penjadwalan(
        $filter_type, $filter_value, $bulan_penjadwalan, $tahun_penjadwalan
    );
    $total_pages                = ceil($total_rows / $limit);
    $pagination                 = $this->generate_pagination($page, $total_pages, $filter_type, $filter_value, $bulan_penjadwalan, $tahun_penjadwalan);

    $data['pagination']         = $pagination;
    $data['filter_type']        = $filter_type;
    $data['filter_value']       = $filter_value;
    $data['bulan_penjadwalan']  = $bulan_penjadwalan;
    $data['tahun_penjadwalan']  = $tahun_penjadwalan;
    $data['title']              = 'Penjadwalan Permintaan';
    $data['body']               = 'admin/penjadwalan/penjadwalan';
    $this->load->view('template', $data);
}

private function generate_pagination($page, $total_pages, $filter_type, $filter_value, $bulan_penjadwalan, $tahun_penjadwalan)
{
    $pagination = '';

    if ($page > 1) {
        $pagination .= '<li class="page-item"><a class="page-link" href="?page=1&filter_type=' . urlencode($filter_type) . '&filter_value=' . urlencode($filter_value) . '&bulan_penjadwalan=' . $bulan_penjadwalan . '&tahun_penjadwalan=' . $tahun_penjadwalan . '">Pertama</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '&filter_type=' . urlencode($filter_type) . '&filter_value=' . urlencode($filter_value) . '&bulan_penjadwalan=' . $bulan_penjadwalan . '&tahun_penjadwalan=' . $tahun_penjadwalan . '">Sebelumnya</a></li>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '&filter_type=' . urlencode($filter_type) . '&filter_value=' . urlencode($filter_value) . '&bulan_penjadwalan=' . $bulan_penjadwalan . '&tahun_penjadwalan=' . $tahun_penjadwalan . '">' . $i . '</a></li>';
        }
    }

    if ($page < $total_pages) {
        $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '&filter_type=' . urlencode($filter_type) . '&filter_value=' . urlencode($filter_value) . '&bulan_penjadwalan=' . $bulan_penjadwalan . '&tahun_penjadwalan=' . $tahun_penjadwalan . '">Selanjutnya</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&filter_type=' . urlencode($filter_type) . '&filter_value=' . urlencode($filter_value) . '&bulan_penjadwalan=' . $bulan_penjadwalan . '&tahun_penjadwalan=' . $tahun_penjadwalan . '">Terakhir</a></li>';
    }

    return '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
}


    public function Proses_Penjadwalan($permintaan_id)
    {
        $permintaan = $this->Permintaan_model->get_permintaan_by_id($permintaan_id);

        if (!$permintaan) {
            $this->session->set_flashdata('message', 'swal("Error!", "Permintaan tidak ditemukan!", "error");');
            redirect('admin/permintaan');
        }

        $tanggal_jadwal = $this->input->post('tanggal_jadwal');
        $keterangan_jadwal = $this->input->post('keterangan_jadwal');

        if (empty($tanggal_jadwal) || empty($keterangan_jadwal)) {
            $this->session->set_flashdata('message', 'swal("Error!", "Tanggal dan Keterangan wajib diisi.", "error");');
            redirect('Penjadwalan/penjadwalan');
        }

        $id_departement = $permintaan->id_departement;

        $data = [
            'id_permintaan' => $permintaan_id,
            'tanggal' => $tanggal_jadwal,
            'keterangan' => $keterangan_jadwal,
            'id_user' => $this->session->userdata('id_user'),
        ];

        $this->db->trans_start();
        $this->Permintaan_model->update_permintaan($permintaan_id, [
            'status' => 'Dijadwalkan HRGA'
        ]);

        $log_data = [
            'id_user'               => $this->session->userdata('id_user'),
            'id_permintaan'         => $permintaan_id,
            'keterangan'            => $keterangan_jadwal,
            'status'                => 'Dijadwalkan HRGA',
            'tanggal_log'           => date('Y-m-d H:i:s')
        ];
        
        $log_permintaan = $this->M_data->simpan_log_permintaan($log_data);

        $this->Penjadwalan_model->insert_penjadwalan($data);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('message', 'swal("Berhasil!", "Permintaan berhasil dijadwalkan!", "success");');
        } else {
            $this->session->set_flashdata('message', 'swal("Error!", "Terjadi kesalahan saat menjadwalkan permintaan.", "error");');
        }

        redirect('Penjadwalan/penjadwalan');
    }
}
