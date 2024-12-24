<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']    = 'smtp';             // Menggunakan SMTP untuk pengiriman
$config['smtp_host']    = 'mail.swarehousesugin.my.id';   // Host SMTP Domainesia (ganti dengan host SMTP yang sesuai)
$config['smtp_port']    = 465;               // Port SMTP (587 untuk TLS atau 465 untuk SSL)
$config['smtp_user']    = 'admin@swarehousesugin.my.id';  // Alamat email yang digunakan untuk mengirim (ganti dengan email yang valid)
$config['smtp_pass']    = 'Arsyal2001';        // Password email Anda (ganti dengan password yang sesuai)
$config['smtp_crypto']  = 'ssl';              // Keamanan TLS (gunakan 'ssl' jika port 465)
$config['mailtype']     = 'html';             // Mengirim email dengan format HTML
$config['charset']      = 'utf-8';            // Charset yang digunakan
$config['wordwrap']     = TRUE;               // Pembungkusan kata pada email
$config['newline']      = "\r\n";             // Menambahkan newline
$config['validation']   = FALSE;               // Validasi email
