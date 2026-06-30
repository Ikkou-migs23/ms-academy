<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Editar Pergunta';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM pergunta WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
$perg = $stmt->get_result()->fetch_assoc();
if (!$perg) { flash('Pergunta não encontrada.','error'); header('Location: listar.php'); exit; }
$conteudos = $conn->query("SELECT c.id, c.titulo, d.nome AS disc FROM conteudo c JOIN disciplina d ON d.id=c.disciplina_id ORDER BY d.nome,c.titulo")->fetch_all(MYSQLI_ASSOC);
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cont_id = (int)($_POST['conteudo_id'] ?? 0);
    $enunc   = trim($_POST['enunciado'] ?? '');
    $ordem   = (int)($_POST['ordem'] ?? 1);
    if (!$cont_id || !$enunc) { $erro = 'Preencha todos os campos.'; }
    else {
        $s = $conn->prepare("UPDATE pergunta SET conteudo_id=?,enunciado=?,ordem=? WHERE id=?");
        $s->bind_param("isii",$cont_id,$enunc,$ordem,$id);
        if ($s->execute()) { flash('Pergunta atualizada!'); header('Location: listar.php'); exit; }
        else { $erro = 'Erro ao salvar.'; }
    }
}
require_once __DIR__ . '/../includes/admin_header.php';
?>
<div class="page-header"><h2>Editar Pergunta</h2><a href="listar.php" class="btn btn-ghost">← Voltar</a></div>
<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>
<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Conteúdo</label>
      <select name="conteudo_id">
        <?php foreach ($conteudos as $c): ?>
          <option value="<?= $c['id'] ?>" <?= (($_POST['conteudo_id']??$perg['conteudo_id'])==$c['id'])?'selected':'' ?>>
            <?= h($c['disc']) ?> › <?= h($c['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Enunciado</label>
      <textarea name="enunciado" required><?= h($_POST['enunciado']??$perg['enunciado']) ?></textarea>
    </div>
    <div class="form-group">
      <label>Ordem</label>
      <input type="number" name="ordem" value="<?= h($_POST['ordem']??$perg['ordem']) ?>"/>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="../alternativa/listar.php?pergunta_id=<?= $id ?>" class="btn btn-outline">Ver Alternativas</a>
      <a href="listar.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
