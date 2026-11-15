<?php
// include/auth.php — sesión, CSRF y utilidades compartidas

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

function e(?string $v): string
{
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valido(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

function usuario_actual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function requiere_login(): array
{
    $u = usuario_actual();
    if (!$u) {
        header('Location: login.php');
        exit;
    }
    return $u;
}
