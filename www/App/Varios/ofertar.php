<?php
namespace www\App\Varios;

// FIXME esto no va asi pero es un parche
require_once '/home/martinsa/public_html/www/App/Core/config.php';

use www\App\Controlers\LotesController;
$lotes = new LotesController();

echo $lotes->Ofertar();
