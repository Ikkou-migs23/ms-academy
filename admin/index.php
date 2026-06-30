<?php
session_start();
require_once __DIR__ . '/includes/admin_init.php';
$page_title = 'Dashboard';

$total_disc = $conn->query("SELECT COUNT(*) FROM disciplina")->fetch_row()[0];
$total_cont = $conn->query("SELECT COUNT(*) FROM conteudo")->fetch_row()[0];
$total_vid  = $conn->query("SELECT COUNT(*) FROM video")->fetch_row()[0];
$total_perg = $conn->query("SELECT COUNT(*) FROM pergunta")->fetch_row()[0];

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-header">
  <h2>Dashboard</h2>
  <span style="font-size:.85rem;color:var(--gray-500);">Bem-vindo, <?= h($_SESSION['admin_nome'] ?? 'Admin') ?>!</span>
</div>

<?php show_flash(); ?>

<!-- STAT CARDS -->
<div class="stat-cards">
  <div class="stat-card"><div class="s-icon">📚</div><div class="s-value"><?= $total_disc ?></div><div class="s-label">Disciplinas</div></div>
  <div class="stat-card"><div class="s-icon">📄</div><div class="s-value"><?= $total_cont ?></div><div class="s-label">Conteúdos</div></div>
  <div class="stat-card"><div class="s-icon">▶️</div><div class="s-value"><?= $total_vid ?></div><div class="s-label">Vídeos</div></div>
  <div class="stat-card"><div class="s-icon">❓</div><div class="s-value"><?= $total_perg ?></div><div class="s-label">Perguntas</div></div>
</div>

<!-- QUICK ACCESS -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

  <div class="panel">
    <div class="panel-header"><h3>Acesso Rápido</h3></div>
    <div class="panel-body" style="display:flex;flex-direction:column;gap:8px;">
      <a href="disciplina/criar.php" class="btn btn-outline btn-sm">+ Nova Disciplina</a>
      <a href="conteudo/criar.php"   class="btn btn-outline btn-sm">+ Novo Conteúdo</a>
      <a href="video/criar.php"      class="btn btn-outline btn-sm">+ Novo Vídeo</a>
      <a href="pergunta/criar.php"   class="btn btn-outline btn-sm">+ Nova Pergunta</a>
      <a href="alternativa/criar.php" class="btn btn-outline btn-sm">+ Nova Alternativa</a>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header"><h3>Últimos Conteúdos</h3></div>
    <table class="data-table">
      <thead><tr><th>Título</th><th>Disciplina</th></tr></thead>
      <tbody>
      <?php
      $res = $conn->query(
          "SELECT c.titulo, d.nome AS disc
           FROM conteudo c JOIN disciplina d ON d.id = c.disciplina_id
           ORDER BY c.criado_em DESC LIMIT 6"
      );
      while ($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= h($row['titulo']) ?></td>
          <td><?= h($row['disc']) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
