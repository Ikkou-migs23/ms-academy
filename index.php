<?php
ini_set('display_errors', 1); # pq é linux
ini_set('display_startup_errors', 1); # pq é linux
error_reporting(E_ALL);
include 'includes/conexao.php';
include 'includes/funcoes.php';

$page_title = 'MS Academy – Educação gratuita para o Ensino Médio';

$busca = trim($_GET['q'] ?? '');
$disciplinas = disciplinas_todas($conn);
$resultados_busca = [];

if ($busca !== '') {
    $resultados_busca = buscar_conteudos($conn, $busca);
}

require_once 'includes/header.php';
?>

<main>
<div class="container">

<?php if ($busca !== ''): ?>
  <!-- SEARCH RESULTS -->
  <div class="breadcrumb">
    <a href="/">Home</a><span class="sep">›</span>
    <span>Resultados para "<?= h($busca) ?>"</span>
  </div>
  <h2 style="margin-bottom:18px;">
    <?= count($resultados_busca) ?> resultado<?= count($resultados_busca) !== 1 ? 's' : '' ?>
    para "<em><?= h($busca) ?></em>"
  </h2>

  <?php if (empty($resultados_busca)): ?>
    <div class="empty-state">
      <span class="es-icon">🔍</span>
      <p>Nenhum conteúdo encontrado. Tente outra busca.</p>
    </div>
  <?php else: ?>
    <div class="search-results-list">
      <?php foreach ($resultados_busca as $r): ?>
        <a href="conteudo.php?id=<?= $r['id'] ?>" class="search-result-item">
          <div class="disc-tag"><?= h($r['disciplina_nome']) ?></div>
          <h4><?= h($r['titulo']) ?></h4>
          <?php if ($r['resumo']): ?>
            <p><?= h(mb_substr($r['resumo'], 0, 140)) ?>…</p>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

<?php else: ?>
  <!-- HOME -->
  <section class="home-hero">
    <h1>Bem-vindo ao MS Academy</h1>
    <p>Aprenda conteúdos do Ensino Médio gratuitamente.</p>
  </section>

  <?php if (empty($disciplinas)): ?>
    <div class="empty-state">
      <span class="es-icon">📚</span>
      <p>Nenhuma disciplina cadastrada ainda.</p>
    </div>
  <?php else: ?>
    <h2 class="section-title">Disciplinas Disponíveis</h2>
    <div class="disciplines-grid">
      <?php foreach ($disciplinas as $d):
        $ic = icone_disciplina($d['nome']);
      ?>
        <div class="disc-card">
          <div class="disc-icon-wrap" style="background:<?= $ic['bg'] ?>;">
            <span style="font-size:2rem;"><?= $ic['emoji'] ?></span>
          </div>
          <h3><?= h($d['nome']) ?></h3>
          <?php if ($d['descricao']): ?>
            <p><?= h(mb_substr($d['descricao'], 0, 60)) ?></p>
          <?php endif; ?>
          <a href="disciplina.php?id=<?= $d['id'] ?>" class="btn btn-primary btn-sm" style="margin-top:4px;">
            Acessar
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

</div>
</main>

<?php require_once 'includes/footer.php'; ?>
