<?php
require_once 'includes/conexao.php';
require_once 'includes/funcoes.php';

$conteudo_id = (int)($_GET['conteudo_id'] ?? 0);
if (!$conteudo_id) { header('Location: /'); exit; }

$conteudo   = conteudo_por_id($conn, $conteudo_id);
if (!$conteudo) { header('Location: /'); exit; }

$disciplina = disciplina_por_id($conn, $conteudo['disciplina_id']);
$perguntas  = perguntas_por_conteudo($conn, $conteudo_id);

if (empty($perguntas)) {
    header('Location: conteudo.php?id=' . $conteudo_id);
    exit;
}

// Load alternatives for each question
foreach ($perguntas as &$p) {
    $p['alternativas'] = alternativas_por_pergunta($conn, $p['id']);
}
unset($p);

$page_title = 'Quiz – ' . h($conteudo['titulo']) . ' – MS Academy';

// Pass data to JS (RN04 – no DB storage)
$quiz_json = json_encode(array_map(function($p) {
    return [
        'id'        => $p['id'],
        'enunciado' => $p['enunciado'],
        'alternativas' => array_map(fn($a) => [
            'id'        => $a['id'],
            'texto'     => $a['texto'],
            'correta'   => (bool)$a['correta'],
            'explicacao'=> $a['explicacao'],
        ], $p['alternativas']),
    ];
}, $perguntas));

require_once 'includes/header.php';
?>

<main>
<div class="container">

  <div class="breadcrumb">
    <a href="/">Home</a><span class="sep">›</span>
    <a href="disciplina.php?id=<?= $disciplina['id'] ?>"><?= h($disciplina['nome']) ?></a><span class="sep">›</span>
    <a href="conteudo.php?id=<?= $conteudo_id ?>"><?= h($conteudo['titulo']) ?></a><span class="sep">›</span>
    <span>Quiz</span>
  </div>

  <div class="quiz-layout">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <h2>Quiz – <?= h($conteudo['titulo']) ?></h2>
      <span class="badge badge-blue"><?= count($perguntas) ?> questão<?= count($perguntas) !== 1 ? 'ões' : '' ?></span>
    </div>

    <div class="quiz-progress-info" id="progressInfo">0 de <?= count($perguntas) ?> respondidas</div>
    <div class="progress-wrap">
      <div class="progress-bar" id="progressBar" style="width:0%"></div>
    </div>

    <!-- Questions rendered by JS -->
    <div id="questionsContainer"></div>

    <div class="quiz-submit-bar">
      <span class="warn-msg" id="warnMsg" style="display:none;">⚠️ Responda todas as questões.</span>
      <button class="btn btn-primary btn-lg" onclick="finalizarQuiz()">Finalizar Quiz</button>
    </div>
  </div>

</div>
</main>

<script>
const QUIZ_DATA = <?= $quiz_json ?>;
const CONTEUDO_ID = <?= $conteudo_id ?>;
const answers = {}; // pergunta_id → alternativa_id (RN04: never sent to server)

function renderQuestions() {
  const container = document.getElementById('questionsContainer');
  QUIZ_DATA.forEach((p, qi) => {
    const card = document.createElement('div');
    card.className = 'question-card';
    card.id = 'qcard-' + p.id;
    card.innerHTML = `
      <div class="question-num">Questão ${qi + 1}</div>
      <div class="question-text">${escHtml(p.enunciado)}</div>
      <div class="opts">
        ${p.alternativas.map((a, ai) => `
          <label class="quiz-option" id="opt-${p.id}-${ai}"
                 onclick="selectAnswer(${p.id}, ${a.id}, ${ai}, this)">
            <input type="radio" name="q${p.id}" value="${a.id}" style="display:none;"/>
            <span class="opt-radio"
                  style="width:17px;height:17px;border:2px solid var(--gray-300);
                         border-radius:50%;flex-shrink:0;display:inline-block;
                         transition:all .18s;margin-top:2px;"></span>
            <span>${escHtml(a.texto)}</span>
          </label>
        `).join('')}
      </div>`;
    container.appendChild(card);
  });
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function selectAnswer(perguntaId, altId, optIdx, label) {
  document.querySelectorAll(`[id^="opt-${perguntaId}-"]`).forEach(el => {
    el.classList.remove('sel');
    el.querySelector('.opt-radio').style.cssText = 'width:17px;height:17px;border:2px solid var(--gray-300);border-radius:50%;flex-shrink:0;display:inline-block;transition:all .18s;margin-top:2px;background:';
  });
  label.classList.add('sel');
  const r = label.querySelector('.opt-radio');
  r.style.borderColor = 'var(--blue)'; r.style.background = 'var(--blue)';
  answers[perguntaId] = altId;
  document.getElementById(`qcard-${perguntaId}`).classList.remove('unanswered');
  updateProgress();
  document.getElementById('warnMsg').style.display = 'none';
}

function updateProgress() {
  const done = Object.keys(answers).length;
  const total = QUIZ_DATA.length;
  document.getElementById('progressBar').style.width = (done / total * 100) + '%';
  document.getElementById('progressInfo').textContent = `${done} de ${total} respondidas`;
}

function finalizarQuiz() {
  if (Object.keys(answers).length < QUIZ_DATA.length) {
    document.getElementById('warnMsg').style.display = 'inline';
    QUIZ_DATA.forEach(p => {
      if (answers[p.id] === undefined) {
        const card = document.getElementById('qcard-' + p.id);
        card.classList.add('unanswered');
        if (QUIZ_DATA.indexOf(p) === 0 || Object.keys(answers).length === 0) {
          card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
    return;
  }
  // Build result payload and store in sessionStorage (RN04 – no server storage)
  const result = QUIZ_DATA.map(p => {
    const chosen_id = answers[p.id];
    const chosen = p.alternativas.find(a => a.id === chosen_id);
    const correct = p.alternativas.find(a => a.correta);
    return {
      enunciado: p.enunciado,
      chosen_texto: chosen?.texto,
      correct_texto: correct?.texto,
      acertou: chosen?.correta,
      explicacao: chosen?.explicacao || correct?.explicacao,
    };
  });
  sessionStorage.setItem('quiz_result', JSON.stringify(result));
  sessionStorage.setItem('quiz_conteudo_id', CONTEUDO_ID);
  window.location.href = 'resultado.php?conteudo_id=' + CONTEUDO_ID;
}

renderQuestions();
</script>

<?php require_once 'includes/footer.php'; ?>
