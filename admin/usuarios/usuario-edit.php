<?php
session_start();
include('../../conf/conexao.php');
include('../../includes/verifica-permissao.php');
verificaAdmin();

if (!isset($_GET['id'])) {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'ID não informado.'];
    header('Location: index-create.php');
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, data_nasci = :data_nasci, endereco = :endereco, tipo = :tipo WHERE id = :id");
    $stmt->execute([
        ':nome' => $_POST['nome'],
        ':cpf' => $_POST['cpf'],
        ':email' => $_POST['email'],
        ':telefone' => $_POST['telefone'],
        ':data_nasci' => $_POST['data_nasci'],
        ':endereco' => $_POST['endereco'],
        ':tipo' => $_POST['tipo'],
        ':id' => $id
    ]);

    $_SESSION['mensagem'] = ['tipo' => 'success', 'texto' => 'Usuário atualizado com sucesso!'];
    header('Location: index-create.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Usuário não encontrado.'];
    header('Location: index-create.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Usuário</h2>
    <form method="POST">
        <div class="mb-3"><label>Nome</label><input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>CPF</label><input type="text" name="cpf" value="<?= htmlspecialchars($usuario['cpf']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>Telefone</label><input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>Data de Nascimento</label><input type="date" name="data_nasci" value="<?= htmlspecialchars($usuario['data_nasci']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>Endereço</label><input type="text" name="endereco" value="<?= htmlspecialchars($usuario['endereco']) ?>" class="form-control" required></div>
        <div class="mb-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control">
                <option value="usuario" <?= $usuario['tipo'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
                <option value="admin" <?= $usuario['tipo'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-warning">Atualizar</button>
        <a href="index-create.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
