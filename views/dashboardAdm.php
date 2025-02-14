<?php
session_start(); // Inicia a sessão
require_once '../App/db/Database.php';

// Instancia a classe Database com o nome da tabela
$database = new Database('servicos');

// Verifica se o parâmetro "excluir" está presente na URL
if (isset($_GET['excluir'])) {
    $id_servico = $_GET['excluir'];

    try {
        // Exclui o serviço
        $database->excluirServico($id_servico);

        // Define a mensagem de sucesso na sessão
        $_SESSION['mensagem'] = "Excluído com sucesso!";
        $_SESSION['tipo_mensagem'] = "sucesso"; // Pode ser usado para estilizar a mensagem
    } catch (Exception $e) {
        // Define a mensagem de erro na sessão
        $_SESSION['mensagem'] = "Erro ao excluir serviço: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
    }

    // Redireciona de volta para o dashboard
    header('Location: dashboardAdm.php');
    exit();
}

// Verifica se o parâmetro "editar" está presente na URL
if (isset($_GET['editar'])) {
    $id_servico = $_GET['editar'];

    try {
        // Aqui você pode adicionar a lógica para editar o serviço, se necessário
        // Por exemplo, redirecionar para uma página de edição
        header('Location: editarServico.php?editar=' . $id_servico);
        exit();
    } catch (Exception $e) {
        // Define a mensagem de erro na sessão
        $_SESSION['mensagem'] = "Erro ao editar serviço: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";

        // Redireciona de volta para o dashboard
        header('Location: dashboardAdm.php');
        exit();
    }
}

// Verifica se há uma mensagem na sessão
if (isset($_SESSION['mensagem'])) {
    $tipo_mensagem = $_SESSION['tipo_mensagem'] ?? 'sucesso'; // Define o tipo de mensagem (sucesso ou erro)
    $mensagem = $_SESSION['mensagem'];

    // Exibe a mensagem
    echo "<div class='mensagem $tipo_mensagem'>$mensagem</div>";

    // Remove a mensagem da sessão após exibi-la
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .mensagem {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .mensagem.sucesso {
            background-color: #d4edda;
            color: #155724;
        }
        .mensagem.erro {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <div class="nav-buttons">
        <a href="cadastrarServico.php"><button>Cadastrar Serviço</button></a>
        <a href="listarServico.php"><button>Listar Serviços</button></a>
        <a href="listarClientes.php"><button>Listar clientes</button></a>
        <a href="listarSolicitacoes.php"><button>Listar solicitacoes</button></a>
    </div>
    <a href="../index.php">Sair</a>
</body>
</html>