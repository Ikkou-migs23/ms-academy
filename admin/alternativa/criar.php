<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Nova Alternativa';

$pre_perg = (int)($_GET['pergunta_id'] ?? 0);
$erro = '';

// Load all perguntas for select
$perguntas = $conn->query(
    "SELECT p.id, p.enunciado, c.titulo AS cont_titulo, d.nome AS disc_nome
     FROM pergunta p
     JOIN conteudo c ON c.id = p.conteudo_id
     JOIN disciplina d ON d.id = c.disciplina_id
     ORDER BY d.nome, c.titulo, p.ordem"
)->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perg_id  = (int)($_POST['pergunta_id'] ?? 0);
    $texto    = trim($_POST['texto'] ?? '');
    $correta  = isset($_POST['correta']) ? 1 : 0;
    $explic   = trim($_POST['explicacao'] ?? '');

    if (!$perg_id || !$texto) {
        $erro = 'Pergunta e texto são obrigatórios.';
    } else {
        // Se marcada como correta, desmarcar as outras da mesma pergunta
        if ($correta) {
            $stmt0 = $conn->prepare("UPDATE alternativa SET correta = 0 WHERE pergunta_id = ?");
            $stmt0->bind_param("i", $perg_id);
            $stmt0->execute();
        }

        $stmt = $conn->prepare(
            "INSERT INTO alternativa (pergunta_id, texto, correta, explicacao) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("isis", $perg_id, $texto, $correta, $explic);

        if ($stmt->execute()) {
            flash('Alternativa adicionada com sucesso!');
            // Redireciona de volta para a lista da pergunta
            header("Location: listar.php?pergunta_id={$perg_id}");
            exit;
        } else {
            $erro = 'Erro ao salvar.';
        }
    }
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Nova Alternativa</h2>
  <a href="listar.php<?= $pre_perg ? '?pergunta_id=' . $pre_perg : '' ?>" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>

<div class="form-card">
  <form method="POST">

    <div class="form-group">
      <label>Pergunta <span style="color:var(--red)">*</span></label>
      <select name="pergunta_id" required>
        <option value="">Selecione a pergunta...</option>
        <?php foreach ($perguntas as $p): ?>
          <option value="<?= $p['id'] ?>"
            <?= (($pre_perg == $p['id']) || (($_POST['pergunta_id'] ?? '') == $p['id'])) ? 'selected' : '' ?>>
            <?= h($p['disc_nome']) ?> › <?= h($p['cont_titulo']) ?> › <?= h(mb_substr($p['enunciado'], 0, 70)) ?>…
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Texto da Alternativa <span style="color:var(--red)">*</span></label>
      <textarea name="texto" required placeholder="ex: O gráfico é sempre uma parábola."><?= h($_POST['texto'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label>Explicação</label>
      <textarea name="explicacao" placeholder="Explique por que esta alternativa está certa ou errada..."><?= h($_POST['explicacao'] ?? '') ?></textarea>
      <small>Será exibida no resultado do quiz para o aluno entender a resposta.</small>
    </div>

    <div class="form-group">
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
        <input type="checkbox" name="correta" value="1" style="width:18px;height:18px;accent-color:var(--green);"
               <?= isset($_POST['correta']) ? 'checked' : '' ?>/>
        <span>Esta é a alternativa <strong>correta</strong></span>
      </label>
      <small style="color:var(--orange);">⚠️ Marcar esta opção desmarcará automaticamente qualquer outra correta da mesma pergunta.</small>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Alternativa</button>
      <button type="submit" name="add_more" value="1" class="btn btn-outline">Salvar e Adicionar Outra</button>
      <a href="listar.php<?= $pre_perg ? '?pergunta_id=' . $pre_perg : '' ?>" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
