<?php
session_start(); // Inicia a sessão
require_once '../App/db/Database.php';

// Instancia a classe Database com o nome da tabela
$database = new Database('cliente');

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
    <title>Listar Clientes</title>
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
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .btn-aprovar {
            background-color: #28a745;
        }
        .btn-rejeitar {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h1>Lista de Clientes e Solicitações</h1>

    <!-- Tabela de clientes e solicitações -->
    <table>
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Serviço Solicitado</th>
                <th>Data da Solicitação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Lista as solicitações com detalhes do cliente e do serviço
            $solicitacoes = $database->listarSolicitacoes();
            foreach ($solicitacoes as $solicitacao) :
            ?>
                <tr>
                    <td><?= $solicitacao['cliente_id'] ?></td>
                    <td><?= $solicitacao['cliente_nome'] ?></td>
                    <td><?= $solicitacao['cliente_email'] ?></td>
                    <td><?= $solicitacao['cliente_telefone'] ?></td>
                    <td><?= $solicitacao['servico_nome'] ?></td>
                    <td><?= $solicitacao['data_solicitacao'] ?></td>
                    <td>
                        <form action="processarSolicitacao.php" method="POST" style="display: inline;">
                            <input type="hidden" name="solicitacao_id" value="<?= $solicitacao['solicitacao_id'] ?>">
                            <button type="submit" name="acao" value="aprovar" class="btn btn-aprovar">Aprovar</button>
                            <button type="submit" name="acao" value="rejeitar" class="btn btn-rejeitar">Rejeitar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>