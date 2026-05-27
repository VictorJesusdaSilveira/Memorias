<?php

//Mostrar Erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("assets/util/Conexao.php");
require_once("assets/model/Memoria.php");


//Conexão
$conexao = Conexao::getConexao();

//Declara aqui a mensagem de erro pra usar em todo código
$msgErro = "";

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

        //Inserir no objeto
        $memoria = new Memoria($nome, $descricao, $caminho, $tipo, $frequencia, $dataMemoria);

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
</head>

<body class="">

    <h1 class="text-center fw-bold fs-2 my-3 text-danger-emphasis">Cadastro de Memórias</h1>

    <!-- Listagem de Memorias -->
    <table class="" border="1">
        <!-- Cabeçalho -->
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descricao</th>
            <th>Imagem</th>
            <th>Tipo</th>
            <th>Frequência</th>
            <th>Data</th>
            <th></th>
        </tr>

        <!-- Dados -->
        <?php foreach ($memorias as $m): ?>
            <tr>
                <td><?= $m["id"] ?></td>
                <td><?= $m["nome"] ?></td>
                <td><?= strlen($m["descricao"]) > 30 ? substr($m["descricao"], 0, 30) . "..." : $m["descricao"] ?></td>
                <td>
                    <img src="<?= $m["imagem"] ?>" width="75" height="50">
                </td>
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
                        print "As Vezes";
                    else if ($m['frequencia'] == 'D')
                        print "Dificilmente";
                    else if ($m['frequencia'] == 'R')
                        print "Raramente";
                    else if ($m['frequencia'] == 'N')
                        print "Nunca";
                    ?>
                </td>
                <td><?= $m["dataMemoria"] ?></td>
                <td><a href="memoriasExcluir.php?id=<?= $m["id"] ?>" onclick="if(! confirm('Confirme a exclusão da memória')) return false;">Excluir</a></td>
            </tr>

        <?php endforeach; ?>
    </table>

    <br><br><br>

    <!--Formulário-->
    <form action="" method="POST" enctype="multipart/form-data"> <!--enctype para permitir o upload das imagems-->
        <label for="">Nome: </label>
        <input type="text" placeholder="Nome da memória:" name="nome" value="<?php if (isset($nome)) echo $nome; ?>">
        <br><br>

        <label for="">Imagem: </label>
        <input type="file" name="imagem">
        <br><br>

        <label for="">Descrição: </label>
        <input type="text" placeholder="Descrição da memória:" name="descricao" value="<?php if (isset($descricao)) echo $descricao; ?>">
        <br><br>

        <label for="">Tipo: </label>
        <select name="tipo">
            <option class="text-secondary" value="" disabled selected>Selecionar Tipo</option>
            <option value="V" <?= (isset($tipo)) && $tipo == "V" ? "selected" : "" ?>>Vida</option>
            <option value="F" <?= (isset($tipo)) && $tipo == "F" ? "selected" : "" ?>>Filme</option>
            <option value="A" <?= (isset($tipo)) && $tipo == "A" ? "selected" : "" ?>>Anime</option>
            <option value="J" <?= (isset($tipo)) && $tipo == "J" ? "selected" : "" ?>>Jogo</option>
        </select>
        <br><br>

        <label for="">Frequência: </label>
        <select name="frequencia">
            <option class="text-secondary" value="" disabled selected>Selecionar Frequência</option>
            <option value="T" <?= (isset($frequencia)) && $frequencia == "T" ? "selected" : "" ?>>Toda Hora (100%)</option>
            <option value="M" <?= (isset($frequencia)) && $frequencia == "M" ? "selected" : "" ?>>Muito (80%)</option>
            <option value="F" <?= (isset($frequencia)) && $frequencia == "F" ? "selected" : "" ?>>Frequentemente (60%)</option>
            <option value="A" <?= (isset($frequencia)) && $frequencia == "A" ? "selected" : "" ?>>As Vezes (50%)</option>
            <option value="D" <?= (isset($frequencia)) && $frequencia == "D" ? "selected" : "" ?>>Dificilmente (40%)</option>
            <option value="R" <?= (isset($frequencia)) && $frequencia == "R" ? "selected" : "" ?>>Raramente (20%)</option>
            <option value="N" <?= (isset($frequencia)) && $frequencia == "N" ? "selected" : "" ?>>Nunca (0%)</option>
        </select>
        <br><br>

        <label for="">Quando começou: </label>
        <input type="date" name="dataMemoria" value="<?= isset($dataMemoria) ? $dataMemoria : '' ?>">
        <br><br>

        <button>Salvar Memória</button>
    </form>

    <!-- Visualizar -> Leva Pros Cards -->

    <div style="color: red;">
        <br>
        <?= $msgErro ?>
    </div>

    <script src="assets/scripts/bootstrap.bundle.min.js"></script>

</body>

</html>