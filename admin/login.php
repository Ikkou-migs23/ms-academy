<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . ADMIN_URL . 'index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $stmt = $conn->prepare("SELECT id, nome, senha FROM administrador WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin && password_verify($senha, $admin['senha'])) {
          session_regenerate_id(true);

          $_SESSION['admin_id']   = $admin['id'];
          $_SESSION['admin_nome'] = $admin['nome'];

          header('Location: ' . ADMIN_URL . 'index.php');
          exit;
        }
        else {
            $erro = 'E-mail ou senha inválidos.';
        }
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – MS Academy</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="logo" style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:24px;">
      <div class="logo-icon">
        <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
      </div>
      <span style="font-weight:800;font-size:1.2rem;color:var(--blue);">MS ACADEMY</span>
    </div>

    <h2 style="margin-bottom:4px;font-size:1.2rem;">Acesso Administrativo</h2>
    <p style="color:var(--gray-500);font-size:.85rem;margin-bottom:20px;">Entre com suas credenciais.</p>

    <?php if ($erro): ?>
      <div class="alert alert-error"><?= h($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="email" required autofocus
               value="<?= h($_POST['email'] ?? '') ?>" placeholder="admin@example.com"/>
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input type="password" name="senha" required placeholder="••••••••"/>
      </div>
      <button type="submit" class="btn btn-primary btn-full btn-lg">Entrar</button>
    </form>

    <p style="text-align:center;margin-top:18px;font-size:.8rem;">
      <a href="/ms-academy/">← Voltar ao site</a>
    </p>
  </div>
</div>
</body>
</html>
