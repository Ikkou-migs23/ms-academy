<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Editar Alternativa';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM alternativa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$alt = $stmt->get_result()->fetch_assoc();

if (!$alt) {
    flash('Alternativa não encontrada.', 'error');
    header('Location: listar.php');
    exit;
}

$perguntas = $conn->query(
    "SELECT p.id, p.enunciado, c.titulo AS cont_titulo, d.nome AS disc_nome
     FROM pergunta p
     JOIN conteudo c ON c.id = p.conteudo_id
     JOIN disciplina d ON d.id = c.disciplina_id
     ORDER BY d.nome, c.titulo, p.ordem"
)->fetch_all(MYSQLI_ASSOC);

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perg_id = (int)($_POST['pergunta_id'] ?? 0);
    $texto   = trim($_POST['texto'] ?? '');
    $correta = isset($_POST['correta']) ? 1 : 0;
    $explic  = trim($_POST['explicacao'] ?? '');

    if (!$perg_id || !$texto) {
        $erro = 'Pergunta e texto são obrigatórios.';
    } else {
        if ($correta) {
            $stmt0 = $conn->prepare("UPDATE alternativa SET correta = 0 WHERE pergunta_id = ? AND id != ?");
            $stmt0->bind_param("ii", $perg_id, $id);
            $stmt0->execute();
        }

        $stmt2 = $conn->prepare(
            "UPDATE alternativa SET pergunta_id=?, texto=?, correta=?, explicacao=? WHERE id=?"
        );
        $stmt2->bind_param("isisi", $perg_id, $texto, $correta, $explic, $id);

        if ($stmt2->execute()) {
            flash('Alternativa atualizada!');
            header("Location: listar.php?pergunta_id={$perg_id}");
            exit;
        } else {
            $erro = 'Erro ao salvar.';
        }
    }
}

// Preencher campos com POST ou valores do banco
$perg_id_v = $_POST['pergunta_id'] ?? $alt['pergunta_id'];
$texto_v   = $_POST['texto']       ?? $alt['texto'];
$explic_v  = $_POST['explicacao']  ?? $alt['explicacao'];
$correta_v = isset($_POST['correta']) ? true : (bool)$alt['correta'];

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Editar Alternativa</h2>
  <a href="listar.php?pergunta_id=<?= $alt['pergunta_id'] ?>" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>

<div class="form-card">
  <form method="POST">

    <div class="form-group">
      <label>Pergunta <span style="color:var(--red)">*</span></label>
      <select name="pergunta_id" required>
        <?php foreach ($perguntas as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $perg_id_v == $p['id'] ? 'selected' : '' ?>>
            <?= h($p['disc_nome']) ?> › <?= h($p['cont_titulo']) ?> › <?= h(mb_substr($p['enunciado'], 0, 70)) ?>…
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Texto da Alternativa <span style="color:var(--red)">*</span></label>
      <textarea name="texto" required><?= h($texto_v) ?></textarea>
    </div>

    <div class="form-group">
      <label>Explicação</label>
      <textarea name="explicacao" placeholder="Explique por que esta alternativa está certa ou errada..."><?= h($explic_v) ?></textarea>
      <small>Será exibida no resultado do quiz.</small>
    </div>

    <div class="form-group">
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
        <input type="checkbox" name="correta" value="1"
               style="width:18px;height:18px;accent-color:var(--green);"
               <?= $correta_v ? 'checked' : '' ?>/>
        <span>Esta é a alternativa <strong>correta</strong></span>
      </label>
      <small style="color:var(--orange);">⚠️ Marcar esta opção desmarcará as outras corretas da mesma pergunta.</small>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="listar.php?pergunta_id=<?= $alt['pergunta_id'] ?>" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
