<?php
session_start(); // Inicia a sessão
require_once '../App/db/Database.php';

// Instancia a classe Database com o nome da tabela
$database = new Database('servicos');

// Adicionar serviço
if (isset($_POST['adicionar'])) {
    $nome_servico = $_POST['nome_servico'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    try {
        // Adiciona o serviço ao banco de dados
        $database->adicionarServico($nome_servico, $descricao, $preco);

        // Define a mensagem de sucesso na sessão
        $_SESSION['mensagem'] = "Serviço cadastrado com sucesso!";
        $_SESSION['tipo_mensagem'] = "sucesso"; // Pode ser usado para estilizar a mensagem
    } catch (Exception $e) {
        // Define a mensagem de erro na sessão
        $_SESSION['mensagem'] = "Erro ao cadastrar serviço: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
    }

    // Redireciona para o dashboard
    header('Location: dashboardAdm.php?action=listar');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Serviço</title>
    <style>
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
    <div class="form-container">
        <h2>Cadastrar Serviço</h2>
        <form method="POST">
            <input type="text" name="nome_servico" placeholder="Nome do Serviço" required>
            <textarea name="descricao" placeholder="Descrição" required></textarea>
            <input type="number" name="preco" step="0.01" placeholder="Preço" required>
            <button type="submit" name="adicionar">Adicionar</button>
        </form>
        <br>
        <a href="dashboardAdm.php?action=listar">Voltar para o Dashboard</a>
    </div>
</body>
</html>