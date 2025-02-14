<?php
session_start(); // Inicia a sessão
require_once '../App/db/Database.php';

// Instancia a classe Database com o nome da tabela
$database = new Database('cliente');

// Verifica se o parâmetro "excluir" está presente na URL
if (isset($_GET['excluir'])) {
    $id_cliente = $_GET['excluir'];

    try {
        // Exclui o cliente
        $database->excluirCliente($id_cliente);

        // Define a mensagem de sucesso na sessão
        $_SESSION['mensagem'] = "Cliente excluído com sucesso!";
        $_SESSION['tipo_mensagem'] = "sucesso"; // Pode ser usado para estilizar a mensagem
    } catch (Exception $e) {
        // Define a mensagem de erro na sessão
        $_SESSION['mensagem'] = "Erro ao excluir cliente: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
    }

    // Redireciona de volta para a lista de clientes
    header('Location: listarClientes.php');
    exit();
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
            text-decoration: none;
            border-radius: 4px;
        }
        .btn.editar {
            background-color: #28a745;
            color: white;
        }
        .btn.excluir {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Listar Clientes</h1>

    <!-- Link para adicionar novo cliente -->
    <a href="cadastrarCliente.php" class="btn editar">Adicionar Cliente</a>

    <!-- Tabela de clientes -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Lista os clientes
            $clientes = $database->listarClientes();
            foreach ($clientes as $cliente) :
            ?>
                <tr>
                    <td><?= $cliente['id_cliente'] ?></td>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= $cliente['email'] ?></td>
                    <td><?= $cliente['telefone'] ?></td>
                    <td>
                        <a href="editarCliente.php?editar=<?= $cliente['id_cliente'] ?>" class="btn editar">Editar</a>
                        <a href="listarClientes.php?excluir=<?= $cliente['id_cliente'] ?>" class="btn excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>