<?php
function renderHeader($title = 'PowerPC Admin', $showSidebar = true) {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../css/style-painel.css" rel="stylesheet">
</head>
<body>
    <?php if ($showSidebar): ?>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-brand">
                <h3>PowerPC Admin</h3>
            </div>
            <div class="sidebar-menu">
                <a href="?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <?php if($_SESSION['usuario_tipo'] === 'admin'): ?>
                    <a href="?page=/usuarios/index-create"><i class="bi bi-people"></i> Usuários</a>
                    <a href="?page=/produtos/index-create"><i class="bi bi-pc-display"></i> Produtos</a>
                    <a href="?page=/pedidos/index-create"><i class="bi bi-cart-check"></i> Pedidos</a>
                <?php endif; ?>
                <a href="?page=minha-conta"><i class="bi bi-person"></i> Minha Conta</a>
            </div>
            <div class="sidebar-footer">
                <a href="../includes/logout.php" class="logout-btn">
                    <i class="bi bi-box-arrow-left"></i> Sair
                </a>
            </div>
        </div>
        <div class="main-content">
    <?php endif; ?>
<?php
}

function renderFooter($showSidebar = true) {
?>
    <?php if ($showSidebar): ?>
        </div> <!-- fecha main-content -->
    </div> <!-- fecha dashboard-container -->
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/jquery.inputmask.min.js"></script>
    </body>
</html>
<?php
}
?>