<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("DELETE FROM conteudo WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    flash('Conteúdo excluído.');
}
header('Location: listar.php'); exit;
