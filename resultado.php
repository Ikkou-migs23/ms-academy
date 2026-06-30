<?php
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

$conteudo_id = (int)($_GET['conteudo_id'] ?? 0);
$conteudo   = $conteudo_id ? conteudo_por_id($conn, $conteudo_id) : null;
$disciplina = $conteudo   ? disciplina_por_id($conn, $conteudo['disciplina_id']) : null;

$page_title = 'Resultado do Quiz – MS Academy';
require_once 'includes/header.php';
?>

<main>
<div class="container">

  <div class="breadcrumb">
    <a href="/">Home</a><span class="sep">›</span>
    <?php if ($disciplina): ?>
      <a href="disciplina.php?id=<?= $disciplina['id'] ?>"><?= h($disciplina['nome']) ?></a><span class="sep">›</span>
    <?php endif; ?>
    <?php if ($conteudo): ?>
      <a href="conteudo.php?id=<?= $conteudo_id ?>"><?= h($conteudo['titulo']) ?></a><span class="sep">›</span>
    <?php endif; ?>
    <span>Resultado</span>
  </div>

  <!-- Container rendered by JS from sessionStorage -->
  <div class="result-layout" id="resultLayout">
    <div class="empty-state">
      <span class="es-icon">⏳</span>
      <p>Carregando resultado...</p>
    </div>
  </div>

</div>
</main>

<script>
const CONTEUDO_ID = <?= $conteudo_id ?: 0 ?>;

function renderResult() {
  const raw = sessionStorage.getItem('quiz_result');
  const layout = document.getElementById('resultLayout');

  if (!raw) {
    layout.innerHTML = `
      <div class="empty-state">
        <span class="es-icon">❓</span>
        <p>Nenhum resultado encontrado. Por favor, realize o quiz primeiro.</p>
        ${CONTEUDO_ID ? `<a href="quiz.php?conteudo_id=${CONTEUDO_ID}" class="btn btn-primary" style="margin-top:16px;">Ir para o Quiz</a>` : ''}
      </div>`;
    return;
  }

  const result = JSON.parse(raw);
  const total   = result.length;
  const acertos = result.filter(r => r.acertou).length;
  const pct     = Math.round(acertos / total * 100);

  let medal, grade;
  if (pct >= 90)      { medal = '🥇'; grade = 'Excelente! Parabéns!'; }
  else if (pct >= 70) { medal = '🥈'; grade = 'Muito bem! Continue assim!'; }
  else if (pct >= 50) { medal = '🥉'; grade = 'Bom esforço! Revise o conteúdo.'; }
  else                { medal = '📚'; grade = 'Continue estudando! Você consegue!'; }

  const retryLink  = CONTEUDO_ID ? `<a href="quiz.php?conteudo_id=${CONTEUDO_ID}" class="btn btn-outline">🔄 Refazer Quiz</a>` : '';
  const contentLink = CONTEUDO_ID ? `<a href="conteudo.php?id=${CONTEUDO_ID}" class="btn btn-primary">Voltar ao Conteúdo</a>` : '';

  const reviewHTML = result.map((r, i) => `
    <div class="result-item ${r.acertou ? 'correct' : 'wrong'}">
      <div class="ri-header">
        <span class="ri-icon">${r.acertou ? '✅' : '❌'}</span>
        Questão ${i + 1}
      </div>
      <p style="font-size:.88rem;font-weight:600;margin-bottom:8px;">${escHtml(r.enunciado)}</p>
      <div class="ri-row">Sua resposta: <strong>${escHtml(r.chosen_texto || '—')}</strong></div>
      ${!r.acertou ? `<div class="ri-row wrong-ans">Resposta correta: <strong>${escHtml(r.correct_texto || '—')}</strong></div>` : ''}
      ${r.explicacao ? `<div class="ri-exp"><strong>Explicação:</strong> ${escHtml(r.explicacao)}</div>` : ''}
    </div>
  `).join('');

  layout.innerHTML = `
    <div class="result-score-card">
      <span class="medal">${medal}</span>
      <div class="score-circle">${acertos}/${total}</div>
      <div class="big-score">${pct}%</div>
      <div class="score-sub">Você acertou ${acertos} de ${total} questões.</div>
      <div class="grade-label">${grade}</div>
      <div class="result-actions">${retryLink}${contentLink}</div>
    </div>
    <h2 class="section-title">Revisão das questões</h2>
    ${reviewHTML}
  `;
}

function escHtml(str) {
  return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

renderResult();
</script>

<?php require_once 'includes/footer.php'; ?>
