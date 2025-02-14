<?php
require_once __DIR__ . '/../App/entity/dashboardAdmLogic.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Serviços</title>
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
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
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .actions .editar {
            background-color: #ffc107;
        }
        .actions .excluir {
            background-color: #dc3545;
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
    </style> -->
</head>
<body>
   

    <h2>Lista de Serviços</h2>

    <table border="2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($servicos)): ?>
                <?php foreach ($servicos as $servico): ?>
                    <tr>
                        <td><?= $servico['id_servico'] ?? '' ?></td>
                        <td><?= $servico['nome'] ?? '' ?></td>
                        <td><?= $servico['descricao'] ?? '' ?></td>
                        <td>R$ <?= isset($servico['preco']) ? number_format($servico['preco'], 2, ',', '.') : '' ?></td>
                        <td>
                            <?php
                            // Formata a data para o padrão brasileiro
                            if (!empty($servico['data_criacao'])) {
                                $dataFormatada = date('d/m/Y H:i:s', strtotime($servico['data_criacao']));
                                echo $dataFormatada;
                            } else {
                                echo '';
                            }
                            ?>
                        </td>
                        <td class="actions">
                            <!-- Botão para editar -->
                            <a href="editarServico.php?id=<?= $servico['id_servico'] ?? '' ?>" class="editar">Editar</a>
                            <!-- Botão para excluir -->
                            <a href="dashboardAdm.php?excluir=<?= $servico['id_servico'] ?? '' ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir este serviço?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum serviço cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
     <!-- Botão "Voltar" -->
     <a href="dashboardAdm.php" class="btn-voltar">Voltar</a>
</body>
</html>