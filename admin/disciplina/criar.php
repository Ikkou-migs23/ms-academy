<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Nova Disciplina';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $desc = trim($_POST['descricao'] ?? '');

    if (!$nome) {
        $erro = 'O nome é obrigatório.';
    } else {
        $stmt = $conn->prepare("INSERT INTO disciplina (nome, descricao) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $desc);
        if ($stmt->execute()) {
            flash('Disciplina criada com sucesso!');
            header('Location: listar.php'); exit;
        } else {
            $erro = 'Erro ao salvar: ' . h($conn->error);
        }
    }
}
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Nova Disciplina</h2>
  <a href="listar.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>

<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Nome <span style="color:var(--red)">*</span></label>
      <input type="text" name="nome" required value="<?= h($_POST['nome'] ?? '') ?>" placeholder="ex: Matemática"/>
    </div>
    <div class="form-group">
      <label>Descrição</label>
      <textarea name="descricao" placeholder="Breve descrição da disciplina..."><?= h($_POST['descricao'] ?? '') ?></textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Disciplina</button>
      <a href="listar.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
