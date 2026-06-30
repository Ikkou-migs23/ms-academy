<?php
function h($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function redirecionar($url) {
    header('Location: ' . $url);
    exit;
}

function disciplinas_todas($conn) {
    $res = $conn->query("SELECT * FROM disciplina ORDER BY nome ASC");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function disciplina_por_id($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM disciplina WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function conteudos_por_disciplina($conn, $disciplina_id) {
    $stmt = $conn->prepare(
        "SELECT * FROM conteudo WHERE disciplina_id = ? ORDER BY ordem ASC, id ASC"
    );
    $stmt->bind_param("i", $disciplina_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function conteudo_por_id($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM conteudo WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function videos_por_conteudo($conn, $conteudo_id) {
    $stmt = $conn->prepare(
        "SELECT * FROM video WHERE conteudo_id = ? ORDER BY ordem ASC, id ASC"
    );
    $stmt->bind_param("i", $conteudo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function perguntas_por_conteudo($conn, $conteudo_id) {
    $stmt = $conn->prepare(
        "SELECT * FROM pergunta WHERE conteudo_id = ? ORDER BY ordem ASC, id ASC"
    );
    $stmt->bind_param("i", $conteudo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function alternativas_por_pergunta($conn, $pergunta_id) {
    $stmt = $conn->prepare(
        "SELECT * FROM alternativa WHERE pergunta_id = ? ORDER BY id ASC"
    );
    $stmt->bind_param("i", $pergunta_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function conteudo_anterior($conn, $disciplina_id, $ordem, $id) {
    $stmt = $conn->prepare(
        "SELECT id, titulo FROM conteudo
         WHERE disciplina_id = ? AND (ordem < ? OR (ordem = ? AND id < ?))
         ORDER BY ordem DESC, id DESC LIMIT 1"
    );
    $stmt->bind_param("iiii", $disciplina_id, $ordem, $ordem, $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function conteudo_proximo($conn, $disciplina_id, $ordem, $id) {
    $stmt = $conn->prepare(
        "SELECT id, titulo FROM conteudo
         WHERE disciplina_id = ? AND (ordem > ? OR (ordem = ? AND id > ?))
         ORDER BY ordem ASC, id ASC LIMIT 1"
    );
    $stmt->bind_param("iiii", $disciplina_id, $ordem, $ordem, $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function youtube_embed($url) {
    // Supports youtu.be/ID, ?v=ID, /embed/ID
    $id = '';
    if (preg_match('/youtu\.be\/([^?&]+)/', $url, $m)) $id = $m[1];
    elseif (preg_match('/[?&]v=([^&]+)/', $url, $m))   $id = $m[1];
    elseif (preg_match('/embed\/([^?&]+)/', $url, $m))  $id = $m[1];
    if ($id) return "https://www.youtube.com/embed/" . h($id);
    return h($url);
}

function buscar_conteudos($conn, $termo) {
    $like = '%' . $conn->real_escape_string($termo) . '%';
    $stmt = $conn->prepare(
        "SELECT c.*, d.nome AS disciplina_nome
         FROM conteudo c
         JOIN disciplina d ON d.id = c.disciplina_id
         WHERE c.titulo LIKE ? OR c.resumo LIKE ?
         ORDER BY c.titulo ASC"
    );
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function icone_disciplina($nome) {
    $mapa = [
        'matem'  => ['emoji' => '📐', 'cor' => '#1A56DB', 'bg' => '#DBEAFE'],
        'fis'    => ['emoji' => '⚡', 'cor' => '#7C3AED', 'bg' => '#EDE9FE'],
        'quim'   => ['emoji' => '🧪', 'cor' => '#059669', 'bg' => '#D1FAE5'],
        'bio'    => ['emoji' => '🧬', 'cor' => '#D97706', 'bg' => '#FEF3C7'],
        'hist'   => ['emoji' => '🏛️', 'cor' => '#B91C1C', 'bg' => '#FEE2E2'],
        'port'   => ['emoji' => '📖', 'cor' => '#4338CA', 'bg' => '#E0E7FF'],
        'geo'    => ['emoji' => '🌍', 'cor' => '#0891B2', 'bg' => '#CFFAFE'],
        'fil'    => ['emoji' => '🤔', 'cor' => '#6D28D9', 'bg' => '#EDE9FE'],
        'soc'    => ['emoji' => '👥', 'cor' => '#065F46', 'bg' => '#D1FAE5'],
        'ing'    => ['emoji' => '🌐', 'cor' => '#1E40AF', 'bg' => '#DBEAFE'],
        'ed fis' => ['emoji' => '🏃', 'cor' => '#B45309', 'bg' => '#FEF3C7'],
        'arte'   => ['emoji' => '🎨', 'cor' => '#BE185D', 'bg' => '#FCE7F3'],
    ];
    $lower = mb_strtolower($nome);
    foreach ($mapa as $key => $val) {
        if (str_contains($lower, $key)) return $val;
    }
    return ['emoji' => '📚', 'cor' => '#374151', 'bg' => '#F3F4F6'];
}
