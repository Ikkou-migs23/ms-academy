<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= h($page_title ?? 'MS Academy') ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <?php if (!empty($extra_head)) echo $extra_head; ?>
</head>
<body class="page-wrapper">
<?php require_once __DIR__ . '/menu.php'; ?>
