<?php
session_start();
include('../../conf/conexao.php');
include('../../includes/verifica-permissao.php');
verificaAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $telefone = $_POST['telefone'];
    $data_nasci = $_POST['data_nasci'];
    $endereco = $_POST['endereco'];
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO usuarios (nome, cpf, email, senha, telefone, data_nasci, endereco, tipo)
            VALUES (:nome, :cpf, :email, :senha, :telefone, :data_nasci, :endereco, :tipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':cpf' => $cpf,
        ':email' => $email,
        ':senha' => $senha,
        ':telefone' => $telefone,
        ':data_nasci' => $data_nasci,
        ':endereco' => $endereco,
        ':tipo' => $tipo
    ]);

    $_SESSION['mensagem'] = ['tipo' => 'success', 'texto' => 'Usuário cadastrado com sucesso!'];
    header('Location: index-create.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Novo Usuário</h2>
    <form method="POST">
        <div class="mb-3"><label>Nome</label><input type="text" name="nome" class="form-control" required></div>
        <div class="mb-3"><label>CPF</label><input type="text" name="cpf" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Senha</label><input type="password" name="senha" class="form-control" required></div>
        <div class="mb-3"><label>Telefone</label><input type="text" name="telefone" class="form-control" required></div>
        <div class="mb-3"><label>Data de Nascimento</label><input type="date" name="data_nasci" class="form-control" required></div>
        <div class="mb-3"><label>Endereço</label><input type="text" name="endereco" class="form-control" required></div>
        <div class="mb-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control" required>
                <option value="usuario">Usuário</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="index-create.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
