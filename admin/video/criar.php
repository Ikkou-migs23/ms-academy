<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Novo Vídeo';
$conteudos = $conn->query("SELECT c.id, c.titulo, d.nome AS disc FROM conteudo c JOIN disciplina d ON d.id=c.disciplina_id ORDER BY d.nome,c.titulo")->fetch_all(MYSQLI_ASSOC);
$pre_cont = (int)($_GET['conteudo_id'] ?? 0);
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cont_id = (int)($_POST['conteudo_id'] ?? 0);
    $titulo  = trim($_POST['titulo'] ?? '');
    $url     = trim($_POST['url'] ?? '');
    $ordem   = (int)($_POST['ordem'] ?? 1);
    if (!$cont_id || !$url) { $erro = 'Conteúdo e URL são obrigatórios.'; }
    else {
        $stmt = $conn->prepare("INSERT INTO video (conteudo_id, titulo, url, ordem) VALUES (?,?,?,?)");
        $stmt->bind_param("issi", $cont_id, $titulo, $url, $ordem);
        if ($stmt->execute()) { flash('Vídeo adicionado!'); header('Location: listar.php'); exit; }
        else { $erro = 'Erro ao salvar.'; }
    }
}
require_once __DIR__ . '/../includes/admin_header.php';
?>
<div class="page-header"><h2>Novo Vídeo</h2><a href="listar.php" class="btn btn-ghost">← Voltar</a></div>
<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>
<div class="form-card">
  <form method="POST">
    <div class="form-group">
      <label>Conteúdo <span style="color:var(--red)">*</span></label>
      <select name="conteudo_id" required>
        <option value="">Selecione o conteúdo...</option>
        <?php foreach ($conteudos as $c): ?>
          <option value="<?= $c['id'] ?>" <?= ($pre_cont == $c['id'] || ($_POST['conteudo_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
            <?= h($c['disc']) ?> › <?= h($c['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Título do vídeo</label>
      <input type="text" name="titulo" value="<?= h($_POST['titulo'] ?? '') ?>" placeholder="ex: Introdução à Função Exponencial"/>
    </div>
    <div class="form-group">
      <label>URL do YouTube <span style="color:var(--red)">*</span></label>
      <input type="url" name="url" required value="<?= h($_POST['url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=..."/>
      <small>Aceita formatos: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID</small>
    </div>
    <div class="form-group">
      <label>Ordem</label>
      <input type="number" name="ordem" min="1" value="<?= h($_POST['ordem'] ?? 1) ?>"/>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Vídeo</button>
      <a href="listar.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
