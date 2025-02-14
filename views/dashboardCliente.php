<?php
session_start(); // Inicia a sessão

// Verifica se há uma mensagem na sessão (caso tenha alguma de sucesso ou erro)
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente</title>
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
        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .dashboard-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            display: block;
        }
        .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Dashboard Cliente</h1>

    <div class="dashboard-container">
        <!-- Botões para o cliente -->
        <a href="listarServicosCliente.php" class="dashboard-btn">Visualizar Serviços</a>
        <a href="verificarStatus.php" class="dashboard-btn">Verificar Status</a>
    </div>
</body>
</html>
