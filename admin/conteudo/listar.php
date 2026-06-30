<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Conteúdos';

$res = $conn->query(
    "SELECT c.*, d.nome AS disc_nome
     FROM conteudo c JOIN disciplina d ON d.id = c.disciplina_id
     ORDER BY d.nome ASC, c.ordem ASC, c.id ASC"
);
$conteudos = $res->fetch_all(MYSQLI_ASSOC);
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Conteúdos</h2>
  <a href="criar.php" class="btn btn-primary">+ Novo Conteúdo</a>
</div>
<?php show_flash(); ?>

<div class="panel">
  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Disciplina</th><th>Título</th><th>Ordem</th><th>Atualizado</th><th>Ações</th></tr>
    </thead>
    <tbody>
    <?php if (empty($conteudos)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--gray-500);padding:24px;">Nenhum conteúdo cadastrado.</td></tr>
    <?php else: foreach ($conteudos as $c): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= h($c['disc_nome']) ?></td>
        <td><strong><?= h($c['titulo']) ?></strong></td>
        <td><?= $c['ordem'] ?></td>
        <td style="font-size:.8rem;color:var(--gray-500);"><?= date('d/m/Y', strtotime($c['atualizado_em'])) ?></td>
        <td class="td-actions">
          <a href="/conteudo.php?id=<?= $c['id'] ?>" target="_blank" class="btn btn-sm btn-ghost">Ver</a>
          <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
          <a href="excluir.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este conteúdo e todos os dados relacionados?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
