<?php
session_start();
require_once '../App/db/Database.php';

// Verifica se o ID do serviço foi passado
if (isset($_GET['solicitar'])) {
    $id_servico = $_GET['solicitar'];
    $database = new Database('servicos'); // Certifique-se de que está usando a tabela correta

    try {
        // Busca o serviço pelo ID
        $servico = $database->getServicoById($id_servico);
        
        // Verifique se o serviço foi encontrado
        if (!$servico) {
            $_SESSION['mensagem'] = "Serviço não encontrado.";
            $_SESSION['tipo_mensagem'] = "erro";
            header('Location: listarServicosCliente.php');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['mensagem'] = "Erro ao carregar os dados do serviço: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
        header('Location: listarServicosCliente.php');
        exit();
    }
} else {
    $_SESSION['mensagem'] = "Serviço não encontrado.";
    $_SESSION['tipo_mensagem'] = "erro";
    header('Location: listarServicosCliente.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Serviço</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
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
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h1>Solicitar Serviço</h1>

        <?php
        // Exibe mensagens de erro ou sucesso, se houver
        if (isset($_SESSION['mensagem'])) {
            echo '<div class="mensagem ' . $_SESSION['tipo_mensagem'] . '">' . $_SESSION['mensagem'] . '</div>';
            unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
        }
        ?>

        <form action="solicitarServicoCliente.php?solicitar=<?= $id_servico ?>" method="POST">
            <label for="nome_servico">Nome do Serviço</label>
            <input type="text" id="nome_servico" name="nome_servico" value="<?= htmlspecialchars($servico['nome_servico']) ?>" readonly>

            <label for="descricao_servico">Descrição</label>
            <textarea id="descricao_servico" name="descricao_servico" rows="4" readonly><?= htmlspecialchars($servico['descricao']) ?></textarea>

            <label for="preco_servico">Preço</label>
            <input type="text" id="preco_servico" name="preco_servico" value="<?= htmlspecialchars($servico['preco']) ?>" readonly>

            <button type="submit">Solicitar</button>
        </form>
    </div>

</body>
</html>
