<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$id = (int)($_GET['id'] ?? 0);
if ($id) { $s=$conn->prepare("DELETE FROM video WHERE id=?"); $s->bind_param("i",$id); $s->execute(); flash('Vídeo excluído.'); }
header('Location: listar.php'); exit;
