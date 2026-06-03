<?php

require_once("assets/util/Conexao.php");
require_once("assets/model/Card.php");

$conexao = Conexao::getConexao();

$sql = "SELECT * FROM memorias";
$stm = $conexao->prepare($sql);
$stm->execute();

$dados = $stm->fetchAll();

$cards = array();

foreach ($dados as $m) {
    $card = new Card($m["nome"], $m["descricao"], $m["imagem"], $m["tipo"], $m["frequencia"], $m["dataMemoria"]);
    array_push($cards, $card);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cards de Memórias</title>

    <link href="assets/styles/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/app.css">
</head>

<body class="bg-primary-subtle">

    <div class="container py-5">

        <h1 class="text-center mb-5 fw-bold text-primary">Minhas Memórias</h1>

        <div class="row g-4 justify-content-center">

            <?php if (count($cards) == 0): ?>
                <p class="text-center text-danger fs-3 fw-semibold">Você ainda não possui cards cadastrados</p>
            <?php endif ?>

            <?php foreach ($cards as $c): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 d-flex justify-content-center">

                    <div class="card card-memorias shadow-sm border-0">
                        <img src="<?= $c->getImagem() ?>" class="card-img-top" alt="<?= $c->getNome() ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-center"><?= $c->getNome() ?></h5>
                            <p class="card-text"><?= $c->getDescricao() ?></p>
                            <p><strong>Tipo: </strong><?= $c->getTipo() ?></p>
                            <p><strong>Frequência: </strong><?= $c->getFrequencia() ?></p>
                            <p><strong>Data: </strong><?= $c->getDataMemoria() ?></p>
                        </div>
                    </div>

                </div>
            <?php endforeach ?>

        </div>
        
        <div class="text-center mt-4">
            <a href="memorias.php" class="btn btn-primary btn-lg shadow">Voltar ao Formulário</a>
        </div>

    </div>

    <script src="assets/scripts/bootstrap.bundle.min.js"></script>

</body>

</html>
