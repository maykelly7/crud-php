<?php
session_start();
require_once '../App/db/Database.php';

$database = new Database('solicitacoes');

try {
    // Recupera todas as solicitações de serviços
    $solicitacoes = $database->listarSolicitacoes();
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro ao listar solicitações: " . $e->getMessage();
    $_SESSION['tipo_mensagem'] = "erro";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Serviços</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Solicitações de Serviços</h1>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem <?= $_SESSION['tipo_mensagem'] ?>">
            <?= $_SESSION['mensagem'] ?>
            <?php unset($_SESSION['mensagem']); unset($_SESSION['tipo_mensagem']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabela de solicitações -->
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Serviço</th>
                <th>Data da Solicitação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitacoes as $solicitacao): ?>
                <tr>
                    <td>
                        <?php
                        // Obtém o nome do cliente (aqui estamos supondo que temos uma tabela de clientes)
                        $cliente = $database->getClienteById($solicitacao['cliente_id']);
                        echo $cliente['nome'];
                        ?>
                    </td>
                    <td>
                        <?php
                        // Obtém o nome do serviço
                        $servico = $database->getServicoById($solicitacao['servico_id']);
                        echo $servico['nome_servico'];
                        ?>
                    </td>
                    <td><?= $solicitacao['data_solicitacao'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
