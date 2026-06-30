<?php
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: /'); exit; }

$disciplina = disciplina_por_id($conn, $id);
if (!$disciplina) { header('Location: /'); exit; }

$conteudos = conteudos_por_disciplina($conn, $id);

$page_title = h($disciplina['nome']) . ' – MS Academy';
require_once 'includes/header.php';
$ic = icone_disciplina($disciplina['nome']);
?>

<main>
<div class="container">

  <div class="breadcrumb">
    <a href="/">Home</a>
    <span class="sep">›</span>
    <span><?= h($disciplina['nome']) ?></span>
  </div>

  <!-- Disciplina header -->
  <div style="display:flex;align-items:center;gap:18px;margin-bottom:28px;">
    <div class="disc-icon-wrap" style="background:<?= $ic['bg'] ?>;width:66px;height:66px;border-radius:18px;font-size:2rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <?= $ic['emoji'] ?>
    </div>
    <div>
      <h1><?= h($disciplina['nome']) ?></h1>
      <?php if ($disciplina['descricao']): ?>
        <p style="color:var(--gray-500);margin-top:4px;"><?= h($disciplina['descricao']) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <h2 class="section-title">Conteúdos:</h2>

  <?php if (empty($conteudos)): ?>
    <div class="empty-state">
      <span class="es-icon">📭</span>
      <p>Nenhum conteúdo disponível ainda para esta disciplina.</p>
    </div>
  <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:12px;max-width:720px;">
      <?php foreach ($conteudos as $i => $c): ?>
        <div class="content-item">
          <div class="content-item-icon"><?= $i + 1 ?></div>
          <div class="content-item-text">
            <h4><?= h($c['titulo']) ?></h4>
            <?php if ($c['resumo']): ?>
              <p><?= h(mb_substr($c['resumo'], 0, 110)) ?></p>
            <?php endif; ?>
          </div>
          <a href="conteudo.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-sm">Estudar</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
</main>

<?php require_once 'includes/footer.php'; ?>
