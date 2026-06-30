<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Vídeos';

$res = $conn->query(
    "SELECT v.*, c.titulo AS cont_titulo
     FROM video v JOIN conteudo c ON c.id = v.conteudo_id
     ORDER BY c.titulo ASC, v.ordem ASC"
);
$videos = $res->fetch_all(MYSQLI_ASSOC);
require_once __DIR__ . '/../includes/admin_header.php';
?>
<div class="page-header">
  <h2>Vídeos</h2>
  <a href="criar.php" class="btn btn-primary">+ Novo Vídeo</a>
</div>
<?php show_flash(); ?>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>#</th><th>Conteúdo</th><th>Título</th><th>URL</th><th>Ordem</th><th>Ações</th></tr></thead>
    <tbody>
    <?php if (empty($videos)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--gray-500);padding:24px;">Nenhum vídeo cadastrado.</td></tr>
    <?php else: foreach ($videos as $v): ?>
      <tr>
        <td><?= $v['id'] ?></td>
        <td><?= h($v['cont_titulo']) ?></td>
        <td><?= h($v['titulo'] ?: '—') ?></td>
        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.78rem;">
          <a href="<?= h($v['url']) ?>" target="_blank" style="color:var(--blue);"><?= h($v['url']) ?></a>
        </td>
        <td><?= $v['ordem'] ?></td>
        <td class="td-actions">
          <a href="editar.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
          <a href="excluir.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este vídeo?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
