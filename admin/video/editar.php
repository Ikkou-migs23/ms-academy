<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Editar Vídeo';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM video WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
$video = $stmt->get_result()->fetch_assoc();
if (!$video) { flash('Vídeo não encontrado.','error'); header('Location: listar.php'); exit; }
$conteudos = $conn->query("SELECT c.id, c.titulo, d.nome AS disc FROM conteudo c JOIN disciplina d ON d.id=c.disciplina_id ORDER BY d.nome,c.titulo")->fetch_all(MYSQLI_ASSOC);
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cont_id = (int)($_POST['conteudo_id'] ?? 0);
    $titulo  = trim($_POST['titulo'] ?? '');
    $url     = trim($_POST['url'] ?? '');
    $ordem   = (int)($_POST['ordem'] ?? 1);
    if (!$cont_id || !$url) { $erro = 'Conteúdo e URL são obrigatórios.'; }
    else {
        $stmt2 = $conn->prepare("UPDATE video SET conteudo_id=?,titulo=?,url=?,ordem=? WHERE id=?");
        $stmt2->bind_param("issii", $cont_id, $titulo, $url, $ordem, $id);
        if ($stmt2->execute()) { flash('Vídeo atualizado!'); header('Location: listar.php'); exit; }
        else { $erro = 'Erro ao salvar.'; }
    }
}
require_once __DIR__ . '/../includes/admin_header.php';
?>
<div class="page-header"><h2>Editar Vídeo</h2><a href="listar.php" class="btn btn-ghost">← Voltar</a></div>
<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>
<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Conteúdo <span style="color:var(--red)">*</span></label>
      <select name="conteudo_id" required>
        <?php foreach ($conteudos as $c): ?>
          <option value="<?= $c['id'] ?>" <?= (($_POST['conteudo_id'] ?? $video['conteudo_id']) == $c['id']) ? 'selected' : '' ?>>
            <?= h($c['disc']) ?> › <?= h($c['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Título do vídeo</label>
      <input type="text" name="titulo" value="<?= h($_POST['titulo'] ?? $video['titulo']) ?>"/>
    </div>
    <div class="form-group">
      <label>URL do YouTube <span style="color:var(--red)">*</span></label>
      <input type="url" name="url" required value="<?= h($_POST['url'] ?? $video['url']) ?>"/>
    </div>
    <div class="form-group">
      <label>Ordem</label>
      <input type="number" name="ordem" min="1" value="<?= h($_POST['ordem'] ?? $video['ordem']) ?>"/>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="listar.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
