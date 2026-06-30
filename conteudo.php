<?php
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: /'); exit; }

$conteudo = conteudo_por_id($conn, $id);
if (!$conteudo) { header('Location: /'); exit; }

$disciplina = disciplina_por_id($conn, $conteudo['disciplina_id']);
$videos     = videos_por_conteudo($conn, $id);
$perguntas  = perguntas_por_conteudo($conn, $id);
$todos_conteudos = conteudos_por_disciplina($conn, $conteudo['disciplina_id']);
$anterior   = conteudo_anterior($conn, $conteudo['disciplina_id'], $conteudo['ordem'], $id);
$proximo    = conteudo_proximo($conn,  $conteudo['disciplina_id'], $conteudo['ordem'], $id);

$page_title = h($conteudo['titulo']) . ' – MS Academy';
require_once 'includes/header.php';
?>

<main>
<div class="container">

  <div class="breadcrumb">
    <a href="/">Home</a>
    <span class="sep">›</span>
    <a href="disciplina.php?id=<?= $disciplina['id'] ?>"><?= h($disciplina['nome']) ?></a>
    <span class="sep">›</span>
    <span><?= h($conteudo['titulo']) ?></span>
  </div>

  <div class="content-page-layout">
    <!-- MAIN COLUMN -->
    <div>
      <!-- VIDEO(S) -->
      <?php if (!empty($videos)): ?>
        <?php if (count($videos) > 1): ?>
          <div class="video-tabs" id="videoTabs">
            <?php foreach ($videos as $vi => $v): ?>
              <button class="video-tab-btn <?= $vi === 0 ? 'active' : '' ?>"
                      onclick="trocarVideo(<?= $vi ?>, this)"
                      data-embed="<?= youtube_embed($v['url']) ?>">
                <?= h($v['titulo'] ?: 'Vídeo ' . ($vi + 1)) ?>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="video-player-wrap" style="margin-bottom:20px;">
          <iframe id="videoFrame"
                  src="<?= youtube_embed($videos[0]['url']) ?>"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  loading="lazy">
          </iframe>
        </div>
      <?php endif; ?>

      <!-- CONTENT HTML -->
      <h1 style="margin-bottom:16px;"><?= h($conteudo['titulo']) ?></h1>

      <?php if ($conteudo['resumo']): ?>
        <p style="color:var(--gray-500);margin-bottom:20px;font-size:.95rem;"><?= h($conteudo['resumo']) ?></p>
      <?php endif; ?>

      <div class="content-html">
        <?= $conteudo['html'] /* HTML sanitized on input by admin */ ?>
      </div>

      <!-- CONTENT NAVIGATION -->
      <div class="content-nav">
        <?php if ($anterior): ?>
          <a href="conteudo.php?id=<?= $anterior['id'] ?>">← <?= h($anterior['titulo']) ?></a>
        <?php else: ?>
          <span></span>
        <?php endif; ?>

        <?php if ($proximo): ?>
          <a href="conteudo.php?id=<?= $proximo['id'] ?>" style="text-align:right;">
            Próximo →<br><strong><?= h($proximo['titulo']) ?></strong>
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div>
      <!-- LESSON LIST -->
      <div class="sidebar-card">
        <h4>Aulas em <?= h($disciplina['nome']) ?></h4>
        <?php foreach ($todos_conteudos as $i => $tc): ?>
          <a href="conteudo.php?id=<?= $tc['id'] ?>" class="sidebar-lesson <?= $tc['id'] == $id ? 'active' : '' ?>">
            <div class="sl-num"><?= $i + 1 ?></div>
            <span><?= h($tc['titulo']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- QUIZ CTA -->
      <?php if (!empty($perguntas)): ?>
        <div class="quiz-cta">
          <h3>🎯 Testar conhecimento</h3>
          <p><?= count($perguntas) ?> questão<?= count($perguntas) !== 1 ? 'ões' : '' ?> sobre este conteúdo.</p>
          <a href="quiz.php?conteudo_id=<?= $id ?>" class="btn">Iniciar Quiz</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>
</main>

<script>
function trocarVideo(idx, btn) {
  document.getElementById('videoFrame').src = btn.dataset.embed;
  document.querySelectorAll('.video-tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>
