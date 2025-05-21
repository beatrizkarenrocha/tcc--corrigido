<?php
session_start();
require_once '../conf/conexao.php'; // conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Email ou senha incorretos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | POWER PC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .logo {
            font-weight: 700;
            color: #224abe;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-voltar {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #2e2c2c;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="../public/index.php" class="btn-voltar">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

    <div class="login-card card">
        <div class="card-body p-4">
            <div class="logo text-center mb-4">
                <i class="bi bi-pc-display-horizontal" style="font-size: 2.5rem;"></i>
                <h2 class="mt-2">POWER PC</h2>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($erro) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                </button>
            </form>

            <div class="mt-3 text-center">
                <p class="mb-0">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
                <p class="mb-0"><a href="recuperar-senha.php">Esqueceu a senha?</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('email').focus();
    </script>
</body>
</html>