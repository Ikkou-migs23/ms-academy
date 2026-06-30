<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Alternativas';

// Filtro opcional por pergunta
$pergunta_id = (int)($_GET['pergunta_id'] ?? 0);
$pergunta    = null;

if ($pergunta_id) {
    $stmt = $conn->prepare(
        "SELECT p.*, c.titulo AS cont_titulo
         FROM pergunta p JOIN conteudo c ON c.id = p.conteudo_id
         WHERE p.id = ?"
    );
    $stmt->bind_param("i", $pergunta_id);
    $stmt->execute();
    $pergunta = $stmt->get_result()->fetch_assoc();
}

if ($pergunta_id && $pergunta) {
    $stmt2 = $conn->prepare("SELECT * FROM alternativa WHERE pergunta_id = ? ORDER BY id ASC");
    $stmt2->bind_param("i", $pergunta_id);
    $stmt2->execute();
    $alternativas = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $res = $conn->query(
        "SELECT a.*, p.enunciado AS perg_enunc, c.titulo AS cont_titulo
         FROM alternativa a
         JOIN pergunta p ON p.id = a.pergunta_id
         JOIN conteudo c ON c.id = p.conteudo_id
         ORDER BY a.pergunta_id ASC, a.id ASC"
    );
    $alternativas = $res->fetch_all(MYSQLI_ASSOC);
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Alternativas<?= $pergunta ? ' — ' . h(mb_substr($pergunta['perg_enunc'] ?? $pergunta['enunciado'], 0, 60)) . '…' : '' ?></h2>
  <div style="display:flex;gap:8px;">
    <?php if ($pergunta_id): ?>
      <a href="listar.php" class="btn btn-ghost btn-sm">Ver todas</a>
    <?php endif; ?>
    <a href="criar.php<?= $pergunta_id ? '?pergunta_id=' . $pergunta_id : '' ?>" class="btn btn-primary">+ Nova Alternativa</a>
  </div>
</div>

<?php show_flash(); ?>

<?php if ($pergunta): ?>
  <div class="alert alert-info" style="margin-bottom:16px;">
    <strong>Conteúdo:</strong> <?= h($pergunta['cont_titulo']) ?> &nbsp;|&nbsp;
    <strong>Pergunta:</strong> <?= h($pergunta['enunciado']) ?>
  </div>
<?php endif; ?>

<div class="panel">
  <table class="data-table">
    <thead>
      <tr>
        <th>#</th>
        <?php if (!$pergunta_id): ?><th>Pergunta</th><?php endif; ?>
        <th>Texto da Alternativa</th>
        <th>Correta?</th>
        <th>Explicação</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
    <?php if (empty($alternativas)): ?>
      <tr>
        <td colspan="6" style="text-align:center;color:var(--gray-500);padding:28px;">
          Nenhuma alternativa cadastrada.
          <?php if ($pergunta_id): ?>
            <br><a href="criar.php?pergunta_id=<?= $pergunta_id ?>" style="color:var(--blue);">Adicionar agora →</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php else: foreach ($alternativas as $a): ?>
      <tr>
        <td><?= $a['id'] ?></td>
        <?php if (!$pergunta_id): ?>
          <td style="max-width:180px;font-size:.8rem;">
            <?= h(mb_substr($a['perg_enunc'] ?? '', 0, 60)) ?>…
          </td>
        <?php endif; ?>
        <td style="max-width:220px;"><?= h(mb_substr($a['texto'], 0, 90)) ?></td>
        <td>
          <?php if ($a['correta']): ?>
            <span class="badge badge-green">✅ Correta</span>
          <?php else: ?>
            <span class="badge" style="background:var(--gray-100);color:var(--gray-500);">Incorreta</span>
          <?php endif; ?>
        </td>
        <td style="font-size:.8rem;color:var(--gray-500);max-width:180px;">
          <?= $a['explicacao'] ? h(mb_substr($a['explicacao'], 0, 70)) . '…' : '—' ?>
        </td>
        <td class="td-actions">
          <a href="editar.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline">Editar</a>
          <a href="excluir.php?id=<?= $a['id'] ?><?= $pergunta_id ? '&pergunta_id=' . $pergunta_id : '' ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir esta alternativa?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php if ($pergunta_id): ?>
  <div style="display:flex;gap:10px;">
    <a href="criar.php?pergunta_id=<?= $pergunta_id ?>" class="btn btn-primary btn-sm">+ Adicionar Alternativa</a>
    <a href="../pergunta/listar.php" class="btn btn-ghost btn-sm">← Voltar às Perguntas</a>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
