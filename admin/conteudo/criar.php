<?php
session_start();
require_once __DIR__ . '/../includes/admin_init.php';
$page_title = 'Novo Conteúdo';
$disciplinas = disciplinas_todas($conn);
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $disc_id = (int)($_POST['disciplina_id'] ?? 0);
    $titulo  = trim($_POST['titulo'] ?? '');
    $resumo  = trim($_POST['resumo'] ?? '');
    $html    = $_POST['html_content'] ?? '';   // WYSIWYG output
    $ordem   = (int)($_POST['ordem'] ?? 1);

    if (!$disc_id || !$titulo) {
        $erro = 'Disciplina e título são obrigatórios.';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO conteudo (disciplina_id, titulo, resumo, html, ordem) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssi", $disc_id, $titulo, $resumo, $html, $ordem);
        if ($stmt->execute()) {
            flash('Conteúdo criado com sucesso!');
            header('Location: listar.php');
            exit;
        } else {
            die('Erro MySQL: ' . $stmt->error);
      }
    }
}

$extra_head = '<link rel="stylesheet" href="' . BASE_URL . 'editor/editor.css"/>';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="page-header">
  <h2>Novo Conteúdo</h2>
  <a href="listar.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro): ?><div class="alert alert-error"><?= h($erro) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="form-card" style="max-width:860px;">

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="form-group">
      <label>Disciplina <span style="color:var(--red)">*</span></label>
      <select name="disciplina_id" required>
        <option value="">Selecione...</option>
        <?php foreach ($disciplinas as $d): ?>
          <option value="<?= $d['id'] ?>" <?= (($_POST['disciplina_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
            <?= h($d['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Ordem</label>
      <input type="number" name="ordem" min="1" value="<?= h($_POST['ordem'] ?? 1) ?>"/>
    </div>
  </div>

  <div class="form-group">
    <label>Título <span style="color:var(--red)">*</span></label>
    <input type="text" name="titulo" required value="<?= h($_POST['titulo'] ?? '') ?>" placeholder="Título do conteúdo"/>
  </div>

  <div class="form-group">
    <label>Resumo</label>
    <textarea name="resumo" placeholder="Breve descrição exibida na listagem..."><?= h($_POST['resumo'] ?? '') ?></textarea>
  </div>

  <div class="form-group">
    <label>Conteúdo (HTML)</label>
    <!-- WYSIWYG Toolbar -->
    <div id="editorToolbar">
      <button type="button" onclick="fmt('bold')"><b>B</b></button>
      <button type="button" onclick="fmt('italic')"><i>I</i></button>
      <button type="button" onclick="fmt('underline')"><u>U</u></button>
      <button type="button" onclick="fmt('strikeThrough')"><s>S</s></button>
      <button type="button" onclick="fmt('insertUnorderedList')">• Lista</button>
      <button type="button" onclick="fmt('insertOrderedList')">1. Lista</button>
      <button type="button" onclick="insertHeading('h2')">H2</button>
      <button type="button" onclick="insertHeading('h3')">H3</button>
      <button type="button" onclick="fmt('formatBlock','blockquote')">❝ Citação</button>
      <button type="button" onclick="insertLink()">🔗 Link</button>
      <button type="button" onclick="insertImg()">🖼 Imagem URL</button>
      <button type="button" onclick="insertTable()">📊 Tabela</button>
      <button type="button" onclick="fmt('removeFormat')">✕ Limpar</button>
    </div>
    <div id="editorArea" contenteditable="true"><?= $_POST['html_content'] ?? '' ?></div>
    <input type="hidden" name="html_content" id="html_content"/>
    <small>O conteúdo será salvo como HTML. Você pode incluir textos, imagens, tabelas e listas.</small>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Salvar Conteúdo</button>
    <a href="listar.php" class="btn btn-ghost">Cancelar</a>
  </div>

</div>
</form>

<script src="<?= BASE_URL ?>editor/editor.js"></script>
<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>