<?php
require_once("assets/util/Conexao.php");

$conexao = Conexao::getConexao();

//1- Identificar qual memoria o usuário quer excluir
$id = 0;

//2- Validar se o identificador da memoria foi recebido
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    //3- Buscar imagem do banco
    $sql = "SELECT imagem FROM memorias WHERE id = ?";
    $stm = $conexao->prepare($sql);
    $stm->execute([$id]);

    $caminho = $stm->fetch();

    //4- Excluir memória da pasta
    if ($caminho && file_exists($caminho["imagem"])) {
        unlink($caminho["imagem"]);
    }

    //5- Excluir a memória do banco de dados
    $sql = "DELETE FROM memorias WHERE id = ?";
    $stm = $conexao->prepare($sql);
    $stm->execute([$id]);
}

//6- Redirecionar para a listagem de memórias                    
header("location: memorias.php");

?>