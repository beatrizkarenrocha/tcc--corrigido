<?php
include('../includes/auth.php'); // Inclui as funções de autenticação
requireLogin(); // Garante que o usuário está logado
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Conta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Minha Conta</h3>
            </div>
            <div class="card-body">
                <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['usuario']['nome']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['usuario']['email']) ?></p>
                <a href="dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>
    </div>
</body>
</html>
