<?php
require '../src/php/db.php';

$produtos = []; // Inicializar a variável para exibir mesmo que a pesquisa não retorne dados

// Verifique se foi feito um GET com o botão de pesquisa
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT * FROM produtos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // Filtrar e sanitizar o input
  if (isset($_GET['pesquisa_simples'])) {
    $pesquisa_simples = filter_input(INPUT_GET, 'pesquisa_simples', FILTER_SANITIZE_STRING);
    // Consultar produtos com o termo pesquisado
    $sql = "SELECT * FROM produtos WHERE nome LIKE '%$pesquisa_simples%'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  elseif(isset($_GET['ordem']) AND isset($_GET['categoria'])) {
    $ordem = filter_input(INPUT_GET, 'ordem', FILTER_SANITIZE_STRING);
    $categoria = filter_input(INPUT_GET, 'categoria', FILTER_SANITIZE_STRING);
    $sql = "SELECT * FROM produtos WHERE categoria_id = $categoria ORDER BY preço $ordem";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  elseif(isset($_GET['categoria'])){
    $categoria = filter_input(INPUT_GET, 'categoria', FILTER_SANITIZE_STRING);
    $sql = "SELECT * FROM produtos WHERE categoria_id = $categoria";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciar Estoque</title>
  <link rel="stylesheet" href="main.css">
</head>

<body class="d-flex flex-column align-items-center">

  <!-- Modal Pesquisa Avançada -->
  <div class="modal" id="modalPesquisa" tabindex="-1" role="dialog" aria-labelledby="modalPesquisa" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form class="modal-content" method="get">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPesquisa">Filtros</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="ordem" value="DESC">
            <label class="form-check-label">
              Maior preço
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="ordem" value="ASC" checked>
            <label class="form-check-label">
              Menor preço
            </label>
          </div>
          <select class="form-control mt-3" name="categoria">
            <option value="1">Ferramentas Manuais</option>
            <option value="2">Ferramentas Elétricas</option>
            <option value="3">Ferramentas Medição</option>
            <option value="4">EPIs</option>
            <option value="5">Acessórios</option>
            <option value="6">Material de construção</option>
            <option value="7">Armazenamento e Organização</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button> 
          <button type="submit" class="btn btn-primary" data-target="#modalPesquisa" data-toggle="modal">Aplicar filtro</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Modificar produtos -->
  <div class="modal" id="modalProdutos" tabindex="-1" role="dialog" aria-labelledby="modalProdutos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form class="modal-content" method="get">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modificar Produtos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h1>OLA MUNDO</h1>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button> 
          <button type="submit" class="btn btn-primary" data-target="#modalProdutos" data-toggle="modal">Aplicar filtro</button>
        </div>
      </form>
    </div>
  </div>
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <header class="d-flex justify-content-center">
    <div class="w-75 d-flex align-items-center">
      <h1>Estoque</h1>
      <img src="" alt="" srcset="">
    </div>
  </header>
  <main class="w-75 mt-5">
    <div class="pesquisa_container w-75 d-flex flex-column">
      <!-- <label for="pesquisa_simples w-25">Pesquisa Simples</label> -->
      <div class="pesquisa_simples_container d-flex w-75">
        <form class="w-100" method="GET">
          <input type="text" name="pesquisa_simples" class="form-control w-100 h-50" placeholder="Pesquisa simples">
          <button class="btn btn-dark h-50 w-100 mt-3">Pesquisar</button>
        </form>
        <div class="ml-5">
          <button class="btn btn-dark h-50 w-100" data-target="#modalPesquisa" data-toggle="modal">Pesquisa Avançada</button>
          <button class="btn btn-dark w-100 mt-3 h-50" data-target="#modalProdutos" data-toggle="modal">Modificar Produtos</button>
        </div>
        <div class="ml-5">
          <button class="btn btn-dark h-50 w-100" data-target="#modalPesquisa" data-toggle="modal">Novo Produto</button>
        </div>
      </div>
    </div>
    <table class="table table-striped mt-5">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Cód</th>
          <th scope="col">Nome</th>
          <th scope="col">Preço</th>
          <th scope="col">Quantidade</th>
          <th scope="col">Adicionado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $produto) { ?>
          <tr scope="row">
            <th><?= $produto['produto'] ?> </th>
            <td><?= $produto['nome'] ?></td>
            <td>R$<?= $produto['preço'] ?></td>
            <td><?= $produto['quantidade_estoque'] ?></td>
            <td><?= $produto['data_adicionado'] ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </main>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
</body>

</html>