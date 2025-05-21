<?php
session_start();
include('../conf/conexao.php');
include('verifica-permissao.php');
verificaAdmin();

if (isset($_POST['delete_usuario'])) {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['mensagem'] = ['tipo' => 'success', 'texto' => 'Usuário excluído com sucesso!'];
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Erro ao excluir usuário: ' . $e->getMessage()];
    }

    header('Location: ../usuarios/index-create.php');
    exit;
}
