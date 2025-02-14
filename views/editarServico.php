<?php
session_start(); // Inicia a sessão
require_once __DIR__ . '/../App/db/Database.php';

// Instancia a classe Database com o nome da tabela
$database = new Database('servicos');

// Verifica se o ID do serviço foi passado na URL
if (isset($_GET['id'])) {
    $id_servico = $_GET['id'];
    // Busca o serviço pelo ID
    $servico = $database->buscarServicoPorId($id_servico);

    // Verifica se o serviço foi encontrado
    if (!$servico) {
        echo "Serviço não encontrado.";
        exit();
    }
} else {
    echo "ID do serviço não fornecido.";
    exit();
}

// Atualiza o serviço
if (isset($_POST['editar'])) {
    $id_servico = $_POST['id_servico'];
    $nome = $_POST['nome'];          // Nome correto do campo
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    // Atualiza o serviço no banco de dados
    $database->editarServico($id_servico, $nome, $descricao, $preco);

    // Define a mensagem de sucesso na sessão
    $_SESSION['mensagem'] = "Alteração bem-sucedida!";

    // Redireciona para a lista de serviços
    header('Location: listarServico.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        .form-container h2 {
            margin-top: 0;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        .form-container button:hover {
            background-color: #218838;
        }
        .btn-voltar {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-voltar:hover {
            background-color: #0056b3;
        }
        .mensagem {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style> -->
</head>
<body>
    <div class="form-container">
        <h2>Editar Serviço</h2>
        <form method="POST">
            <input type="hidden" name="id_servico" value="<?= $servico['id_servico'] ?>">
            <input type="text" name="nome" value="<?= $servico['nome'] ?? '' ?>" placeholder="Nome do Serviço" required>
            <textarea name="descricao" placeholder="Descrição" required><?= $servico['descricao'] ?? '' ?></textarea>
            <input type="number" name="preco" step="0.01" value="<?= $servico['preco'] ?? '' ?>" placeholder="Preço" required>
            <button type="submit" name="editar">Salvar Alterações</button>
        </form>
    </div>
    <!-- Botão "Voltar" -->
    <a href="listarServico.php" class="btn-voltar">Voltar</a>
</body>
</html>