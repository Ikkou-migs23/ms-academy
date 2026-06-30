<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Perguntas';
$res = $conn->query("SELECT p.*, c.titulo AS cont_titulo, d.nome AS disc_nome
  FROM pergunta p JOIN conteudo c ON c.id=p.conteudo_id JOIN disciplina d ON d.id=c.disciplina_id
  ORDER BY d.nome, c.titulo, p.ordem");
$perguntas = $res->fetch_all(MYSQLI_ASSOC);
require_once __DIR__ . '/../includes/admin_header.php';
?>
<div class="page-header">
  <h2>Perguntas</h2>
  <a href="criar.php" class="btn btn-primary">+ Nova Pergunta</a>
</div>
<?php show_flash(); ?>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>#</th><th>Disciplina</th><th>Conteúdo</th><th>Enunciado</th><th>Ordem</th><th>Ações</th></tr></thead>
    <tbody>
    <?php if (empty($perguntas)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--gray-500);padding:24px;">Nenhuma pergunta cadastrada.</td></tr>
    <?php else: foreach ($perguntas as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= h($p['disc_nome']) ?></td>
        <td><?= h($p['cont_titulo']) ?></td>
        <td style="max-width:260px;"><?= h(mb_substr($p['enunciado'], 0, 90)) ?>…</td>
        <td><?= $p['ordem'] ?></td>
        <td class="td-actions">
          <a href="../alternativa/listar.php?pergunta_id=<?= $p['id'] ?>" class="btn btn-sm btn-ghost">Alternativas</a>
          <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
          <a href="excluir.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir esta pergunta e suas alternativas?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
