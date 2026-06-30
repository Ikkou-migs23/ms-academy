<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';

$id          = (int)($_GET['id'] ?? 0);
$pergunta_id = (int)($_GET['pergunta_id'] ?? 0);

if ($id) {
    $stmt = $conn->prepare("DELETE FROM alternativa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    flash('Alternativa excluída.');
}

$redirect = $pergunta_id
    ? 'listar.php?pergunta_id=' . $pergunta_id
    : 'listar.php';

header('Location: ' . $redirect);
exit;
