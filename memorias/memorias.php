<?php

//Mostrar Erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("assets/util/Conexao.php");

//Conexão
$conexao = Conexao::getConexao();

//Declara aqui a mensagem de erro pra usar em todo código
$msgErro = "";

//Cria o array pras memórias
$cards = array();

//Salvar a memória
if (isset($_POST["nome"])) {

    //Receber os dados do formulário 
    $nome = trim($_POST["nome"]) ? ucfirst(trim($_POST["nome"])) : null;
    $descricao = trim($_POST["descricao"]) ? ucfirst(trim($_POST["descricao"])) : null;
    $imagem = $_FILES["imagem"];
    $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
    $frequencia = isset($_POST["frequencia"]) ? $_POST["frequencia"] : null;
    $dataMemoria = ($_POST["dataMemoria"]) ? ($_POST["dataMemoria"]) : null;

    $dataHoje = date("Y-m-d");

    //Validar os dados
    $msgs = array();

    //Processa o upload da imagem: gera um nome único, move o arquivo da pasta temporária para a pasta "uploads" e armazena o caminho final para salvar no banco de dados.
    $nomeImagem = $imagem["name"];
    $tmp = $imagem["tmp_name"];

    //Evita nomes repetidos
    $nomeFinal = uniqid() . "_" . $nomeImagem;

    $caminho = "assets/images/" . $nomeFinal;

    if (!$nome) {
        array_push($msgs, "Informe o nome da memória!");
    } else if (strlen($nome) < 3 || strlen($nome) > 50) {
        array_push($msgs, "O nome deve ter entre 3 e 50 caracteres!");
    }

    if (!$descricao) {
        array_push($msgs, "Informe a descrição!");
    } else if (strlen($descricao) < 5 || strlen($descricao) > 500) {
        array_push($msgs, "A descrição deve ter entre 5 e 500 caracteres!");
    }

    if (empty($imagem["name"])) {
        array_push($msgs, "Você deve colocar uma imagem obrigatoriamente.");
    }

    if (!$tipo) {
        array_push($msgs, "Informe o tipo da memória!");
    }

    if (!$frequencia) {
        array_push($msgs, "Informe a frequência com que isso ocorre!");
    }

    if (!$dataMemoria) {
        array_push($msgs, "Informe a data da memória");
    } else if ($dataMemoria > $dataHoje) {
        array_push($msgs, "A data da memória não pode ser maior que a data atual!");
    }

    if (empty($msgs)) {
        //Mover pra pasta
        move_uploaded_file($tmp, $caminho);

        //Inserir no banco
        $sql = "INSERT INTO memorias (nome, descricao, imagem, tipo, frequencia, dataMemoria) VALUES(?, ?, ?, ?, ?, ?)";
        $stm = $conexao->prepare($sql);
        $stm->execute(array($nome, $descricao, $caminho, $tipo, $frequencia, $dataMemoria));

        //Redirecionar para a página de listagem
        header("location:memorias.php");
    } else {
        //Mensagem de erro
        $msgErro = implode("<br>", $msgs);
    }
}

//Listagem
$sql = "SELECT * FROM memorias";
$stm = $conexao->prepare($sql);
$stm->execute();
$memorias = $stm->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Memórias</title>

    <link href="assets/styles/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/app.css">
</head>

<body class="bg-primary-subtle">

    <div class="container py-5">

        <h1 class="display-5 fw-bold text-center text-primary mb-5">Cadastro de memórias com a Gigi</h1>

        <!-- Mensagem de erro -->
        <?php if (!empty($msgErro)): ?>
            <div class="alert alert-danger shadow-sm mb-4">
                <?= $msgErro ?>
            </div>
        <?php endif; ?>

        <!-- Tabela -->
        <div class="card shadow border-0 mb-5">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Memórias Cadastradas</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Imagem</th>
                                <th>Tipo</th>
                                <th>Frequência</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($memorias as $m): ?>
                                <tr>
                                    <td><?= $m["id"] ?></td>
                                    <td class="fw-semibold"><?= $m["nome"] ?></td>
                                    <td> <?= strlen($m["descricao"]) > 30 ? substr($m["descricao"], 0, 30) . "..." : $m["descricao"] ?> </td>
                                    <td><img src="<?= $m["imagem"] ?>" class="rounded shadow-sm" width="90" height="60" style="object-fit:cover;"></td>
                                    <td>
                                        <?php
                                            if ($m['tipo'] == 'V') 
                                                print "Vida";
                                            else if ($m['tipo'] == 'F') 
                                                print "Filme";
                                            else if ($m['tipo'] == 'A') 
                                                print "Anime";
                                            else if ($m['tipo'] == 'J') 
                                                print "Jogo";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if ($m['frequencia'] == 'T') 
                                                print "Toda Hora";
                                            else if ($m['frequencia'] == 'M') 
                                                print "Muito";
                                            else if ($m['frequencia'] == 'F') 
                                                print "Frequentemente";
                                            else if ($m['frequencia'] == 'A') 
                                                print "Às Vezes";
                                            else if ($m['frequencia'] == 'D') 
                                                print "Dificilmente";
                                            else if ($m['frequencia'] == 'R') 
                                                print "Raramente";
                                            else if ($m['frequencia'] == 'N') 
                                                print "Nunca";
                                        ?>
                                    </td>
                                    <td><?= date("d/m/Y", strtotime($m["dataMemoria"])) ?></td>
                                    <td><a href="memoriasExcluir.php?id=<?= $m["id"] ?>" class="btn btn-danger btn-sm" onclick="if(!confirm('Confirme a exclusão da memória')) return false;">Excluir</a></td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                        
                    </table>

                </div>

            </div>
        </div>

        <!-- Formulário -->
        <div class="card shadow-lg border-0 mx-auto form-memoria">

            <div class="card-header bg-success text-white text-center">
                <h3 class="mb-0">Nova Memória</h3>
            </div>

            <div class="card-body p-4">

                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?= isset($nome) ? $nome : '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Imagem</label>
                        <input type="file" class="form-control" name="imagem">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descrição</label>
                        <textarea class="form-control" rows="3" name="descricao"><?= isset($descricao) ? $descricao : '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo</label>

                        <select class="form-select" name="tipo">
                            <option value="">Selecionar Tipo</option>
                            <option value="V" <?= (isset($tipo)) && $tipo == "V" ? "selected" : "" ?> >Vida</option>
                            <option value="F" <?= (isset($tipo)) && $tipo == "F" ? "selected" : "" ?> >Filme</option>
                            <option value="A" <?= (isset($tipo)) && $tipo == "A" ? "selected" : "" ?> >Anime</option>
                            <option value="J" <?= (isset($tipo)) && $tipo == "J" ? "selected" : "" ?> >Jogo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Frequência</label>

                        <select class="form-select" name="frequencia">
                            <option value="">Selecionar Frequência</option>
                            <option value="T" <?= (isset($frequencia)) && $frequencia == "T" ? "selected" : "" ?> >Toda Hora (100%)</option>
                            <option value="M" <?= (isset($frequencia)) && $frequencia == "M" ? "selected" : "" ?> >Muito (80%)</option>
                            <option value="F" <?= (isset($frequencia)) && $frequencia == "F" ? "selected" : "" ?> >Frequentemente (60%)</option>
                            <option value="A" <?= (isset($frequencia)) && $frequencia == "A" ? "selected" : "" ?> >Às Vezes (50%)</option>
                            <option value="D" <?= (isset($frequencia)) && $frequencia == "D" ? "selected" : "" ?> >Dificilmente (40%)</option>
                            <option value="R" <?= (isset($frequencia)) && $frequencia == "R" ? "selected" : "" ?> >Raramente (20%)</option>
                            <option value="N" <?= (isset($frequencia)) && $frequencia == "N" ? "selected" : "" ?> >Nunca (0%)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Quando começou</label>

                        <input type="date" class="form-control" name="dataMemoria" value="<?= isset($dataMemoria) ? $dataMemoria : '' ?>">
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">Salvar Memória</button>
                </form>
            </div>

        </div>

        <div class="text-center mt-4">
            <a href="cards.php" class="btn btn-primary btn-lg shadow">Ver Memórias em Cards</a>
        </div>

    </div>

    <script src="assets/scripts/bootstrap.bundle.min.js"></script>

</body>

</html>
