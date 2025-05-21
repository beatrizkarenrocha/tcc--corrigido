<?php
session_start();
include('../conf/conexao.php');
include('../includes/verifica-permissao.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o ID é do próprio usuário
    if($_POST['id'] != $_SESSION['usuario_id']) {
        die("Acesso não autorizado");
    }
    
    // Atualiza apenas campos permitidos (não permite alterar tipo)
    $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id";
    // ... executa a atualização ...
}