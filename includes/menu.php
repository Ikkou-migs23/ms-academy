<?php
$disciplinas_menu = disciplinas_todas($conn);
$busca_valor = h($_GET['q'] ?? '');
?>
<header>
  <nav class="nav-inner">
    <a href="<?= BASE_URL ?>" class="logo">
      <div class="logo-icon">
        <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
      </div>
      MS ACADEMY
    </a>

    <form class="search-box" action="<?= BASE_URL ?>index.php" method="GET">
      <button type="submit" aria-label="Buscar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
      <input type="text" name="q" placeholder="Pesquisar conteúdos..." value="<?= $busca_valor ?>" autocomplete="off"/>
    </form>

    <div class="nav-tabs">
      <?php foreach (array_slice($disciplinas_menu, 0, 4) as $dm):
        $ic = icone_disciplina($dm['nome']);
      ?>
        <a href="<?= BASE_URL ?>disciplina.php?id=<?= $dm['id'] ?>" class="nav-tab">
          <?= $ic['emoji'] ?> <?= h($dm['nome']) ?>
        </a>
      <?php endforeach; ?>
      <?php if (count($disciplinas_menu) > 4): ?>
        <span class="nav-more">••• Mais</span>
      <?php endif; ?>
    </div>
  </nav>
</header>
