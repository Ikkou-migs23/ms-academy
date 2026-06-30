<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Disciplinas';
$disciplinas = disciplinas_todas($conn);
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Disciplinas</h2>
  <a href="criar.php" class="btn btn-primary">+ Nova Disciplina</a>
</div>

<?php show_flash(); ?>

<div class="panel">
  <table class="data-table">
    <thead>
      <tr>
        <th>#</th><th>Nome</th><th>Descrição</th><th>Conteúdos</th><th>Ações</th>
      </tr>
    </thead>
    <tbody>
    <?php if (empty($disciplinas)): ?>
      <tr><td colspan="5" style="text-align:center;color:var(--gray-500);padding:24px;">Nenhuma disciplina cadastrada.</td></tr>
    <?php else:
      foreach ($disciplinas as $d):
        $stmt = $conn->prepare("SELECT COUNT(*) FROM conteudo WHERE disciplina_id = ?");
        $stmt->bind_param("i", $d['id']);
        $stmt->execute();
        $qtd = $stmt->get_result()->fetch_row()[0];
    ?>
      <tr>
        <td><?= $d['id'] ?></td>
        <td><strong><?= h($d['nome']) ?></strong></td>
        <td><?= h(mb_substr($d['descricao'] ?? '', 0, 80)) ?></td>
        <td><span class="badge badge-blue"><?= $qtd ?></span></td>
        <td class="td-actions">
          <a href="editar.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
          <a href="excluir.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir esta disciplina e todos seus conteúdos?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
