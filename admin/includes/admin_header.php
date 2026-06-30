<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= h($page_title ?? 'Admin') ?> – MS Academy</title>
  <link rel="stylesheet" href="/ms-academy/assets/css/style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <?php if (!empty($extra_head)) echo $extra_head; ?>
</head>
<body>
<div class="admin-wrapper">
  <!-- SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
      </svg>
      <div>
        MS Academy
        <span class="sidebar-brand-sub">Administração</span>
      </div>
    </div>
    <nav class="admin-nav">
      <?php
      $current = basename($_SERVER['PHP_SELF']);
      $current_dir = basename(dirname($_SERVER['PHP_SELF']));
      function nav_link($href, $icon, $label, $dir = '') {
          global $current_dir;
          $active = ($dir && $current_dir === $dir) ? 'active' : '';
          echo "<a href=\"{$href}\" class=\"{$active}\"><span class=\"nav-icon\">{$icon}</span> <span>{$label}</span></a>";
      }
      ?>
      <?php nav_link(ADMIN_URL . 'index.php',                    '🏠', 'Dashboard',       'admin'); ?>
      <?php nav_link(ADMIN_URL . 'disciplina/listar.php',        '📚', 'Disciplinas',     'disciplina'); ?>
      <?php nav_link(ADMIN_URL . 'conteudo/listar.php',          '📄', 'Conteúdos',       'conteudo'); ?>
      <?php nav_link(ADMIN_URL . 'video/listar.php',             '▶️', 'Vídeos',          'video'); ?>
      <?php nav_link(ADMIN_URL . 'pergunta/listar.php',          '❓', 'Perguntas',       'pergunta'); ?>
      <?php nav_link(ADMIN_URL . 'alternativa/listar.php',       '✅', 'Alternativas',    'alternativa'); ?>
    </nav>
    <div class="sidebar-footer">
      <a href="<?= ADMIN_URL ?>logout.php"><span class="nav-icon">↩</span> <span>Sair</span></a>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="admin-content">
