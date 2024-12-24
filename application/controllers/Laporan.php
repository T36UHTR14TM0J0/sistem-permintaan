<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
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
        $this->load->model('Permintaan_model');
        $this->load->model('m_laporan');
        $this->load->model('Barang_model');
        $this->load->helper('common');
    }
 
    public function laporan_permintaan()
    {
        $limit          = 10;
        $page           = $this->input->get('page') ?: 1;
        $offset         = ($page - 1) * $limit;
        $tanggal_awal   = $this->input->get('tanggal_awal', TRUE);
        $tanggal_akhir  = $this->input->get('tanggal_akhir', TRUE);
        $departemen     = $this->input->get('departemen', TRUE);
        $data['data']   = $this->m_laporan->get_all_lap_permintaan($tanggal_awal, $tanggal_akhir, $departemen, $limit, $offset);
        $total_rows     = $this->m_laporan->count_all_lap_permintaan($tanggal_awal, $tanggal_akhir, $departemen);
        $total_pages    = ceil($total_rows / $limit);
        $prev_page      = max($page - 1, 1);
        $next_page      = min($page + 1, $total_pages);

        $pagination = '';
        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_permintaan') . '?page=1&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_permintaan') . '?page=' . $prev_page . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Sebelumnya</a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination .= '<li class="page-item' . ($i == $page ? ' active' : '') . '"><a class="page-link" href="' . site_url('admin/laporan_permintaan') . '?page=' . $i . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">' . $i . '</a></li>';
        }
        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_permintaan') . '?page=' . $next_page . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_permintaan') . '?page=' . $total_pages . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Terakhir</a></li>';
        }

        $data['pagination']     = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['offset']         = $offset;
        $data['tanggal_awal']   = $tanggal_awal;
        $data['tanggal_akhir']  = $tanggal_akhir;
        $data['departemen']     = $this->db->get('departement')->result();
        $data['title']          = 'Laporan Permintaan';
        $data['body']           = 'admin/laporan/laporan_permintaan';

        $this->load->view('template', $data);
    }

    public function cetak_laporan_permintaan_pdf()
    {
        $this->load->library('dompdf_gen');

        $tanggal_awal               = $this->input->get('tanggal_awal');
        $tanggal_akhir              = $this->input->get('tanggal_akhir');
        $departemen                 = $this->input->get('departemen');
        $limit                      = 10;
        $page                       = $this->input->get('page');
        $offset                     = ($page ? ($page - 1) * $limit : 0);
        $laporan_permintaan         = $this->m_laporan->get_all_lap_permintaan($tanggal_awal, $tanggal_akhir, $departemen, $limit, $offset);
        $total_rows                 = $this->m_laporan->count_all_lap_permintaan($tanggal_awal, $tanggal_akhir, $departemen);
        $data['laporan_permintaan'] = $laporan_permintaan;
        $data['nama_user']          = $this->session->userdata('nama_user');
        $data['tanggal_awal']       = $tanggal_awal;
        $data['tanggal_akhir']      = $tanggal_akhir;
        $data['total_rows']         = $total_rows;
        $html                       = $this->load->view('admin/laporan/cetak_laporan_permintaan', $data, true);
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        $file_name                  = 'laporan_permintaan_' . date('Y-m-d_H-i-s') . '.pdf';
        $this->dompdf->stream($file_name, array("Attachment" => false));
    }


    public function laporan_penjadwalan()
    {
        $limit                          = 10;
        $page                           = $this->input->get('page') ?: 1;
        $offset                         = ($page - 1) * $limit;
        $tanggal_awal                   = $this->input->get('tanggal_awal', TRUE);
        $tanggal_akhir                  = $this->input->get('tanggal_akhir', TRUE);
        $departemen                     = $this->input->get('departemen', TRUE);
        $data['laporan_penjadwalan']    = $this->m_laporan->get_all_lap_penjadwalan($tanggal_awal, $tanggal_akhir, $departemen, $limit, $offset);
        $total_rows                     = $this->m_laporan->count_all_lap_penjadwalan($tanggal_awal, $tanggal_akhir, $departemen);
        $total_pages                    = ceil($total_rows / $limit);
        $prev_page                      = max($page - 1, 1);
        $next_page                      = min($page + 1, $total_pages);

        $pagination = '';
        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_penjadwalan') . '?page=1&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_penjadwalan') . '?page=' . $prev_page . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Sebelumnya</a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination .= '<li class="page-item' . ($i == $page ? ' active' : '') . '"><a class="page-link" href="' . site_url('admin/laporan_penjadwalan') . '?page=' . $i . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">' . $i . '</a></li>';
        }
        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_penjadwalan') . '?page=' . $next_page . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="' . site_url('admin/laporan_penjadwalan') . '?page=' . $total_pages . '&tanggal_awal=' . urlencode($tanggal_awal) . '&tanggal_akhir=' . urlencode($tanggal_akhir) . '&departemen=' . urlencode($departemen) . '">Terakhir</a></li>';
        }

        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $next_page . '&bulan=' . $bulan . '&tahun=' . $tahun . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&bulan=' . $bulan . '&tahun=' . $tahun . '">Terakhir</a></li>';
        }

        $data['pagination']     = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['offset']         = $offset;
        $data['tanggal_awal']   = $tanggal_awal;
        $data['tanggal_akhir']  = $tanggal_akhir;
        $data['departemen']     = $this->db->get('departement')->result();
        $data['title']          = 'Laporan Penjadwalan';
        $data['body']           = 'admin/laporan/laporan_penjadwalan';
        $this->load->view('template', $data);
    }


    public function cetak_laporan_penjadwalan_pdf()
    {
        $this->load->library('dompdf_gen');

        $tanggal_awal               = $this->input->get('tanggal_awal');
        $tanggal_akhir              = $this->input->get('tanggal_akhir');
        $departemen                 = $this->input->get('departemen');
        $limit                      = 10;
        $page                       = $this->input->get('page');
        $offset                     = ($page ? ($page - 1) * $limit : 0);
        $laporan_penjadwalan         = $this->m_laporan->get_all_lap_penjadwalan($tanggal_awal, $tanggal_akhir, $departemen, $limit, $offset);
        $total_rows                 = $this->m_laporan->count_all_lap_penjadwalan($tanggal_awal, $tanggal_akhir, $departemen);
        $data['laporan_penjadwalan'] = $laporan_penjadwalan;
        $data['nama_user']          = $this->session->userdata('nama_user');
        $data['tanggal_awal']       = $tanggal_awal;
        $data['tanggal_akhir']      = $tanggal_akhir;
        $data['total_rows']         = $total_rows;
        $html                       = $this->load->view('admin/laporan/cetak_laporan_penjadwalan', $data, true);
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        $file_name                  = 'laporan_penjadwalan_' . date('Y-m-d_H-i-s') . '.pdf';
        $this->dompdf->stream($file_name, array("Attachment" => false));
    }

    public function laporan_barang()
    {
        // Set limit dan halaman
        $limit = 10;
        $page = $this->input->get('page');
        if (!$page) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        // Ambil tanggal dari input
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $jenis = $this->input->get('jenis');

        // Menyesuaikan pencarian berdasarkan rentang tanggal
        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $data['laporan_barang'] = $this->m_laporan->get_all_lap_barang($tanggal_awal, $tanggal_akhir,$jenis, $limit, $offset);
            $total_rows = $this->m_laporan->count_all_lap_barang($tanggal_awal, $tanggal_akhir);
        } else {
            // Jika tidak ada rentang tanggal, tampilkan semua data
            $data['laporan_barang'] = $this->m_laporan->get_all_lap_barang(null, null,null, $limit, $offset);
            $total_rows = $this->m_laporan->count_all_lap_barang(null, null,null);
        }


        // Menghitung total barang masuk dan keluar
        $total_barang_masuk = 0;
        $total_barang_keluar = 0;
        foreach ($data['laporan_barang'] as $b) {
            if ($b->status == 'masuk') {
                $total_barang_masuk += $b->jumlah;
            } elseif ($b->status == 'keluar') {
                $total_barang_keluar += $b->jumlah;
            }
        }

        // Pagination
        $total_pages = ceil($total_rows / $limit);
        $prev_page = $page - 1;
        $next_page = $page + 1;

        $pagination = '';

        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=1&tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis=' . $jenis . '">Pertama</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $prev_page . '&tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis=' . $jenis . '">Sebelumnya</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '&tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis=' . $jenis . '">' . $i . '</a></li>';
            }
        }

        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $next_page . '&tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis=' . $jenis . '">Selanjutnya</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis=' . $jenis . '">Terakhir</a></li>';
        }

        $data['pagination'] = '<nav><ul class="pagination justify-content-center">' . $pagination . '</ul></nav>';
        $data['total_barang_masuk'] = $total_barang_masuk;
        $data['total_barang_keluar'] = $total_barang_keluar;
        $data['offset'] = $offset;
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['title'] = 'Laporan Barang';
        $data['body'] = 'admin/laporan/laporan_barang';
        $this->load->view('template', $data);
    }

    public function cetak_laporan_barang_pdf()
    {
        $this->load->library('dompdf_gen');
        $tanggal_awal               = $this->input->get('tanggal_awal');
        $tanggal_akhir              = $this->input->get('tanggal_akhir');
        $jenis                      = $this->input->get('jenis');
        $limit                      = 10;
        $page                       = $this->input->get('page');
        $offset                     = ($page ? ($page - 1) * $limit : 0);
        $data['laporan_barang']     = $this->m_laporan->get_all_lap_barang($tanggal_awal, $tanggal_akhir,$jenis,$limit, $offset);
        $total_rows                 = $this->m_laporan->count_all_lap_barang($tanggal_awal, $tanggal_akhir,$jenis);
        $data['nama_user']          = $this->session->userdata('nama_user');
        $data['tanggal_awal']       = $tanggal_awal;
        $data['tanggal_akhir']      = $tanggal_akhir;
        $data['total_rows']         = $total_rows;
        $html                       = $this->load->view('admin/laporan/cetak_laporan_barang', $data, true);
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        $file_name                  = 'laporan_barang_' . date('Y-m-d_H-i-s') . '.pdf';
        $this->dompdf->stream($file_name, array("Attachment" => false));
    }
}
