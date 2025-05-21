<?php 
session_start();
include('../conf/conexao.php');
include'index-painel.php';
require_once('../includes/auth.php');

if (!isAdminLoggedIn()) {
    header("Location: ../public/login.php");
    exit();
}

// Consultas para o dashboard
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$totalVendas = $pdo->query("SELECT SUM(total) FROM pedidos WHERE status != 'cancelado'")->fetchColumn() ?? 0;
$ultimosPedidos = $pdo->query("SELECT p.*, u.nome AS nome_usuario FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.data_pedido DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$pedidosStatus = $pdo->query("SELECT status, COUNT(*) as total FROM pedidos GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
$vendasMensais = $pdo->query("SELECT DATE_FORMAT(data_pedido, '%Y-%m') as mes, SUM(total) as total FROM pedidos WHERE status != 'cancelado' GROUP BY mes ORDER BY mes DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

// Função auxiliar para cores de status
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pendente': return '#f6c23e';
        case 'processando': return '#36b9cc';
        case 'enviado': return '#4e73df';
        case 'entregue': return '#1cc88a';
        case 'cancelado': return '#e74a3b';
        default: return '#858796';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerPC Admin - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --secondary-color: #858796;
            --light-color: #f8f9fc;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            font-weight: 800;
            font-size: 1.2rem;
            text-align: center;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #d1d3e2;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: #4e73df;
            background: #f8f9fc;
            border-left: 3px solid #4e73df;
        }
        
        .sidebar-menu a i {
            margin-right: 0.5rem;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 250px;
            padding: 1rem;
            border-top: 1px solid #e3e6f0;
        }
        
        .logout-btn {
            color: #858796;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            color: var(--primary-color);
        }
        
        .main-content {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            border-left: 0.25rem solid;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary {
            border-left-color: var(--primary-color);
        }
        
        .stat-card.success {
            border-left-color: var(--success-color);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stat-card.info {
            border-left-color: var(--info-color);
        }
        
        .stat-card .card-title {
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .stat-card .card-value {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.25rem;
        }
        
        .badge-pendente {
            background-color: #f6c23e;
            color: #000;
        }
        
        .badge-processando {
            background-color: #36b9cc;
            color: #fff;
        }
        
        .badge-enviado {
            background-color: #4e73df;
            color: #fff;
        }
        
        .badge-entregue {
            background-color: #1cc88a;
            color: #fff;
        }
        
        .badge-cancelado {
            background-color: #e74a3b;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <h3>PowerPC Admin</h3>
            </div>
            <div class="sidebar-menu">
                <a href="dashboard.php" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="usuarios/index-create.php">
                    <i class="bi bi-people"></i> Usuários
                </a>
                <a href="?page=produtos">
                    <i class="bi bi-pc-display"></i> Produtos
                </a>
                <a href="?page=pedidos">
                    <i class="bi bi-cart-check"></i> Pedidos
                </a>
                <a href="?page=administradores">
                    <i class="bi bi-shield-lock"></i> Administradores
                </a>
                <a href="?page=minha-conta">
                    <i class="bi bi-person"></i> Minha Conta
                </a>
            </div>
            <div class="sidebar-footer">
                <a href="../includes/logout.php" class="logout-btn">
                    <i class="bi bi-box-arrow-left"></i> Sair
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="bi bi-download text-white-50"></i> Gerar Relatório
                </a>
            </div>
            
            <div class="row">
                <!-- Cards de Estatísticas -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card primary h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="card-title text-xs font-weight-bold text-uppercase mb-1">Produtos</div>
                                    <div class="card-value h5 mb-0 font-weight-bold text-gray-800"><?= $totalProdutos ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-box-seam fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card success h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="card-title text-xs font-weight-bold text-uppercase mb-1">Usuários</div>
                                    <div class="card-value h5 mb-0 font-weight-bold text-gray-800"><?= $totalUsuarios ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card warning h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="card-title text-xs font-weight-bold text-uppercase mb-1">Pedidos</div>
                                    <div class="card-value h5 mb-0 font-weight-bold text-gray-800"><?= $totalPedidos ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-cart3 fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card info h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="card-title text-xs font-weight-bold text-uppercase mb-1">Vendas (R$)</div>
                                    <div class="card-value h5 mb-0 font-weight-bold text-gray-800">R$ <?= number_format($totalVendas, 2, ',', '.') ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Gráfico de Vendas Mensais -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Vendas Mensais</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical text-gray-400"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Exportar Dados</a></li>
                                    <li><a class="dropdown-item" href="#">Filtrar</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="vendasChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfico de Status de Pedidos -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Status dos Pedidos</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <?php foreach ($pedidosStatus as $status): ?>
                                    <span class="mr-2">
                                        <i class="bi bi-circle-fill" style="color: <?= getStatusColor($status['status']) ?>"></i> <?= ucfirst($status['status']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabela de Últimos Pedidos -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Últimos Pedidos</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pedidosTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuário</th>
                                            <th>Data</th>
                                            <th>Total (R$)</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ultimosPedidos as $pedido): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($pedido['id']) ?></td>
                                                <td><?= htmlspecialchars($pedido['nome_usuario']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                                                <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                                <td>
                                                    <span class="status-badge badge-<?= strtolower($pedido['status']) ?>">
                                                        <?= ucfirst($pedido['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="pedidos/visualizar.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-primary" title="Ver detalhes">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="pedidos/editar.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-info" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Vendas Mensais
        const vendasCtx = document.getElementById('vendasChart').getContext('2d');
        const vendasChart = new Chart(vendasCtx, {
            type: 'bar',
            data: {
                labels: [<?php foreach(array_reverse($vendasMensais) as $venda): ?>'<?= date('M/Y', strtotime($venda['mes'].'-01')) ?>',<?php endforeach; ?>],
                datasets: [{
                    label: 'Vendas Mensais (R$)',
                    data: [<?php foreach(array_reverse($vendasMensais) as $venda): ?><?= $venda['total'] ?>,<?php endforeach; ?>],
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.raw.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Status de Pedidos
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php foreach($pedidosStatus as $status): ?>'<?= ucfirst($status['status']) ?>',<?php endforeach; ?>],
                datasets: [{
                    data: [<?php foreach($pedidosStatus as $status): ?><?= $status['total'] ?>,<?php endforeach; ?>],
                    backgroundColor: [<?php foreach($pedidosStatus as $status): ?>'<?= getStatusColor($status['status']) ?>',<?php endforeach; ?>],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%',
            },
        });

        // Função para buscar dados atualizados
        function atualizarDashboard() {
            fetch('api/dashboard.php')
                .then(response => response.json())
                .then(data => {
                    // Atualizar cards
                    document.querySelector('.stat-card.primary .card-value').textContent = data.totalProdutos;
                    document.querySelector('.stat-card.success .card-value').textContent = data.totalUsuarios;
                    document.querySelector('.stat-card.warning .card-value').textContent = data.totalPedidos;
                    document.querySelector('.stat-card.info .card-value').textContent = 'R$ ' + parseFloat(data.totalVendas).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    
                    // Atualizar gráficos
                    vendasChart.data.datasets[0].data = data.vendasMensais.map(v => v.total);
                    statusChart.data.datasets[0].data = data.pedidosStatus.map(s => s.total);
                    vendasChart.update();
                    statusChart.update();
                })
                .catch(error => console.error('Erro ao atualizar dashboard:', error));
        }

        // Atualizar a cada 5 minutos
        setInterval(atualizarDashboard, 300000);
    </script>
</body>
</html>