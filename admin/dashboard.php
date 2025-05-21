<?php
require_once '../conf/conexao.php';


// Total de Produtos
$stmtProdutos = $pdo->query("SELECT COUNT(*) as total FROM produtos");
$totalProdutos = $stmtProdutos->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Total de Usuários
$stmtUsuarios = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
$totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Total de Pedidos
$stmtPedidos = $pdo->query("SELECT COUNT(*) as total FROM pedidos");
$totalPedidos = $stmtPedidos->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Total de Vendas (exceto cancelados)
$stmtVendas = $pdo->query("SELECT SUM(total) as total FROM pedidos WHERE status != 'cancelado'");
$totalVendas = $stmtVendas->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Últimos pedidos
$stmtUltimos = $pdo->query("SELECT p.*, u.nome AS nome_usuario FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.data_pedido DESC LIMIT 5");
$ultimosPedidos = $stmtUltimos->fetchAll(PDO::FETCH_ASSOC);

// Pedidos por status (para gráfico)
$stmtStatus = $pdo->query("SELECT status, COUNT(*) as total FROM pedidos GROUP BY status");
$pedidosStatus = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);

// Vendas mensais (para gráfico)
$stmtMensal = $pdo->query("SELECT DATE_FORMAT(data_pedido, '%Y-%m') as mes, SUM(total) as total FROM pedidos WHERE status != 'cancelado' GROUP BY mes ORDER BY mes DESC LIMIT 6");
$vendasMensais = $stmtMensal->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
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
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
            font-weight: 600;
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
        
        .table-responsive {
            overflow-x: auto;
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
    <div class="container-fluid mt-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Painel Administrativo</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-download text-white-50"></i> Gerar Relatório
            </a>
        </div>
        <div class="mx-auto">
            <a href="../public/cadastro_new_V2.php" class="btn btn-primary " tabindex="-1" role="button" aria-disabled="true">Cadastro de Usuários link</a>
        </div>
        <div class="row">
            <!-- Cards de Estatísticas -->
            <?php
            $cards = [
                ['bg' => 'primary', 'icon' => 'bi-box-seam', 'title' => 'Produtos', 'total' => $totalProdutos],
                ['bg' => 'success', 'icon' => 'bi-people', 'title' => 'Usuários', 'total' => $totalUsuarios],
                ['bg' => 'warning', 'icon' => 'bi-cart3', 'title' => 'Pedidos', 'total' => $totalPedidos],
                ['bg' => 'info', 'icon' => 'bi-currency-dollar', 'title' => 'Vendas (R$)', 'total' => number_format($totalVendas, 2, ',', '.')]
            ];
            foreach ($cards as $card) {
                echo "
                <div class='col-xl-3 col-md-6 mb-4'>
                    <div class='card stat-card {$card['bg']} h-100 py-2'>
                        <div class='card-body'>
                            <div class='row no-gutters align-items-center'>
                                <div class='col mr-2'>
                                    <div class='card-title text-xs font-weight-bold text-uppercase mb-1'>{$card['title']}</div>
                                    <div class='card-value h5 mb-0 font-weight-bold text-gray-800'>{$card['total']}</div>
                                </div>
                                <div class='col-auto'>
                                    <i class='bi {$card['icon']} fa-2x text-gray-300'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            ?>
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
                                                <a href="#" class="btn btn-sm btn-primary" title="Ver detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-info" title="Editar">
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

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Seu Sistema <?= date('Y') ?></span>
            </div>
        </div>
    </footer>

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
                    document.querySelector('.card.primary .card-value').textContent = data.totalProdutos;
                    document.querySelector('.card.success .card-value').textContent = data.totalUsuarios;
                    document.querySelector('.card.warning .card-value').textContent = data.totalPedidos;
                    document.querySelector('.card.info .card-value').textContent = 'R$ ' + parseFloat(data.totalVendas).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    
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

<?php
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pendente':
            return '#f6c23e';
        case 'processando':
            return '#36b9cc';
        case 'enviado':
            return '#4e73df';
        case 'entregue':
            return '#1cc88a';
        case 'cancelado':
            return '#e74a3b';
        default:
            return '#858796';
    }
}
?>