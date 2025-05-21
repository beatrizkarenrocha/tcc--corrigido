<?php
function exibirMensagem() {
    if (isset($_SESSION['mensagem'])) {
        $mensagem = $_SESSION['mensagem'];
        echo "<div class='alert alert-{$mensagem['tipo']} alert-dismissible fade show' role='alert'>
                {$mensagem['texto']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        unset($_SESSION['mensagem']);
    }
}
?>