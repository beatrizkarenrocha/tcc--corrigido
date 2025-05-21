<?php
session_start();
include('../../conf/conexao.php');
include('../../includes/verifica-permissao.php');
verificaAdmin();

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Usuários Cadastrados</h2>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-<?= $_SESSION['mensagem']['tipo'] ?>">
            <?= $_SESSION['mensagem']['texto'] ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <a href="usuario-create.php" class="btn btn-primary mb-3">Novo Usuário</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= ucfirst(htmlspecialchars($usuario['tipo'])) ?></td>
                <td>
                    <a href="usuario-view.php?id=<?= $usuario['id'] ?>" class="btn btn-info btn-sm">Visualizar</a>
                    <a href="usuario-edit.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <form action="../../includes/acoes.php" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <button type="submit" name="delete_usuario" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>
