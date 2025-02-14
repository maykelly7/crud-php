<?php
require_once '../App/db/Database.php';

$database = new Database('servicos');

// Adicionar serviço
if (isset($_POST['adicionar'])) {
    $nome_servico = $_POST['nome_servico'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $database->adicionarServico($nome_servico, $descricao, $preco);
    header('Location: dashboardAdm.php');
    exit();
}

// Excluir serviço
if (isset($_GET['excluir'])) {
    $id_servico = $_GET['excluir'];
    $database->excluirServico($id_servico);
    header('Location: dashboardAdm.php');
    exit();
}

// Editar serviço
if (isset($_POST['editar'])) {
    $id_servico = $_POST['id_servico'];
    $nome_servico = $_POST['nome_servico'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $database->editarServico($id_servico, $nome_servico, $descricao, $preco);
    header('Location: dashboardAdm.php');
    exit();
}

// Listar serviços
$servicos = $database->listarServicos();
?>