<?php
// Inicia a sessão
session_start();
// Verifica se o token está presente na sessão
if (!isset($_SESSION['auth_token'])) {
    // Se não houver token, redireciona o usuário para a página de login
    header("Location: /login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel + Vue</title>
    @vite(['resources/js/app.js'])
</head>
<style>
    body {
        margin: 0;
    }
</style>
<body>
    <div id="app">
        <navbar-vue></navbar-vue>
        <user-vue></user-vue>
        <footer-vue></footer-vue>
    </div>
</body>
</html>
