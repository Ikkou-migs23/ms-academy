<?php
define('BASE_URL', '/ms-academy/');
define('ADMIN_URL', BASE_URL . 'admin/');

require_once __DIR__ . '/../../includes/conexao.php';
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/auth.php';

requer_login();

function flash($msg, $tipo = 'success') {
    $_SESSION['flash'] = ['msg' => $msg, 'tipo' => $tipo];
}

function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function show_flash() {
    $f = get_flash();
    if ($f) {
        echo '<div class="alert alert-' . h($f['tipo']) . '">' . h($f['msg']) . '</div>';
    }
}
