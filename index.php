<?php
/**
 * Index — Entry point redirect ke Dashboard
 * 
 * PHP built-in server membutuhkan index.php sebagai default file.
 * File ini mengarahkan (redirect) ke halaman dashboard.php.
 */
header('Location: dashboard.php');
exit;
