<?php
include ('../conexao.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuário - Visualizar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
   
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Visualizar usuário
                <a href="../admin/index-painel.php?page=index-create" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
                <?php
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];

                    // Consulta com prepared statement para evitar SQL injection
                    $sql = "SELECT * FROM usuarios WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>

                <div class="mb-3">
                  <label>Nome</label>
                  <p class="form-control">
                    <?=$usuario['nome'];?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Cpf</label>
                  <p class="form-control">
                    <?=$usuario['cpf'];?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <p class="form-control">
                    <?=$usuario['email'];?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Telefone</label>
                  <p class="form-control">
                    <?=$usuario['telefone'];?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Data Nascimento</label>
                  <p class="form-control">
                    <?=date('d/m/Y', strtotime($usuario['data_nasci']));?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Endereço</label>
                  <p class="form-control">
                    <?=$usuario['endereco'];?>
                  </p>
                </div>
                <?php
                } else {
                  echo "<h5>Usuário não encontrado</h5>";
                }
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>