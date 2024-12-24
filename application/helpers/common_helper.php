<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_level_name($level_code)
{
    // Definisikan level berdasarkan kode
    $levels = [
        '1' => 'Admin Departement',
        '2' => 'Admin HRGA',
        '3' => 'Admin PUD/Purchasing',
        '0' => 'Admin IT / Helper',
    ];

    // Kembalikan nama level berdasarkan kode yang diberikan
    return isset($levels[$level_code]) ? $levels[$level_code] : 'Unknown Level';
}
