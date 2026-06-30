<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Editar Disciplina';
$id = (int)($_GET['id'] ?? 0);
$disc = disciplina_por_id($conn, $id);
if (!$disc) { flash('Disciplina não encontrada.','error'); header('Location: listar.php'); exit; }
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $desc = trim($_POST['descricao'] ?? '');
    if (!$nome) {
        $erro = 'O nome é obrigatório.';
    } else {
        $stmt = $conn->prepare("UPDATE disciplina SET nome = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $desc, $id);
        if ($stmt->execute()) {
            flash('Disciplina atualizada!');
            header('Location: listar.php'); exit;
        } else {
            $erro = 'Erro ao salvar.';
        }
    }
}
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Editar Disciplina</h2>
  <a href="listar.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>

<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Nome <span style="color:var(--red)">*</span></label>
      <input type="text" name="nome" required value="<?= h($_POST['nome'] ?? $disc['nome']) ?>"/>
    </div>
    <div class="form-group">
      <label>Descrição</label>
      <textarea name="descricao"><?= h($_POST['descricao'] ?? $disc['descricao']) ?></textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="listar.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
