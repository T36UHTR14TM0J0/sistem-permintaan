<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memastikan autoload Dompdf di-load
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Dompdf_gen
{
    public $dompdf;

    public function __construct()
    {
        // Inisialisasi objek Dompdf
        $this->dompdf = new Dompdf();

        // Buat properti $CI agar bisa digunakan dalam CodeIgniter
        $CI = &get_instance();
        $CI->dompdf = $this->dompdf;
    }
}
