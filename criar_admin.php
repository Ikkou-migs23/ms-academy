<?php
/**
 * MS Academy – Script de criação do primeiro administrador
 * Execute UMA VEZ pelo navegador: http://seusite.com/criar_admin.php
 * APAGUE este arquivo após criar o admin!
 */

// Altere estas credenciais antes de executar:
$nome  = 'Administrador';
$email = 'msacademy@gmail.com';
$senha = 'sapereaude';   // Mínimo 8 caracteres

// ─────────────────────────────────────────────
require_once __DIR__ . '/includes/conexao.php';

$hash = password_hash($senha, PASSWORD_BCRYPT);

$stmt = $conn->prepare(
    "INSERT INTO administrador (nome, email, senha) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $nome, $email, $hash);

if ($stmt->execute()) {
    echo "<h2 style='font-family:sans-serif;color:green;'>✅ Administrador criado com sucesso!</h2>";
    echo "<p style='font-family:sans-serif;'>E-mail: <strong>{$email}</strong></p>";
    echo "<p style='font-family:sans-serif;'>Senha: <strong>{$senha}</strong></p>";
    echo "<p style='font-family:sans-serif;color:red;'><strong>⚠️ APAGUE este arquivo imediatamente após o uso!</strong></p>";
    echo "<p><a href='/admin/' style='font-family:sans-serif;'>→ Ir para o painel</a></p>";
} else {
    echo "<h2 style='font-family:sans-serif;color:red;'>❌ Erro ao criar admin: " . htmlspecialchars($conn->error) . "</h2>";
    echo "<p style='font-family:sans-serif;'>Verifique se o e-mail já está cadastrado.</p>";
}
