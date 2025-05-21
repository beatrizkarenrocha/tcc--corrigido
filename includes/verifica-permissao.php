<?php
function verificaAdmin() {
    if (!isset($_SESSION['usuario_tipo'])) {
        header("Location: ../../public/login.php");
        exit();
    }
    
    if ($_SESSION['usuario_tipo'] !== 'admin') {
        $_SESSION['mensagem'] = [
            'tipo' => 'danger',
            'texto' => 'Acesso restrito a administradores!'
        ];
        header("Location: ../../public/index.php");
        exit();
    }
}

function verificaUsuario() {
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['mensagem'] = [
            'tipo' => 'warning',
            'texto' => 'Faça login para acessar!'
        ];
        header("Location: ../public/login.php");
        exit();
    }
}
?>