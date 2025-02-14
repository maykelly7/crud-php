<?php
session_start();
require_once '../App/db/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitacao_id = $_POST['solicitacao_id'];
    $acao = $_POST['acao'];

    $database = new Database('solicitacoes');

    try {
        if ($acao === 'aprovar') {
            // Atualiza o status da solicitação para "aprovado"
            $sql = "UPDATE solicitacoes SET status = 'aprovado' WHERE id = ?";
            $mensagem = "Solicitação aprovada com sucesso!";
        } elseif ($acao === 'rejeitar') {
            // Atualiza o status da solicitação para "rejeitado"
            $sql = "UPDATE solicitacoes SET status = 'rejeitado' WHERE id = ?";
            $mensagem = "Solicitação rejeitada com sucesso!";
        } else {
            throw new Exception("Ação inválida.");
        }

        $stmt = $database->getConnection()->prepare($sql);
        $stmt->execute([$solicitacao_id]);

        $_SESSION['mensagem'] = $mensagem;
        $_SESSION['tipo_mensagem'] = 'sucesso';
    } catch (Exception $e) {
        $_SESSION['mensagem'] = "Erro ao processar solicitação: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = 'erro';
    }

    header('Location: listarClientes.php');
    exit();
}
?>