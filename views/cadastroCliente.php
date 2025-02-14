<?php
// Conexão com o banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=servicos', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Insert
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

    try {
        // Verifica se o telefone já existe
        $sqlCheck = $pdo->prepare("SELECT COUNT(*) FROM cliente WHERE telefone = ?");
        $sqlCheck->execute([$telefone]);
        $count = $sqlCheck->fetchColumn();

        if ($count > 0) {
            echo "<div class='msg-erro'>Erro: O número de telefone já está cadastrado.</div>";
        } else {
            // Prepara a consulta SQL
            $sql = $pdo->prepare("INSERT INTO cliente (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
            $sql->execute([$nome, $email, $telefone, $senha]);

            // Redireciona para a tela de login
            header('Location: loginCliente.php');
            exit(); // Encerra a execução do script após o redirecionamento
        }
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
   
</head>
<body>
    <h1>Cadastro de Cliente</h1>
    <form method="POST" class="form-cadastro">
        <div>
            <label for="nome">Nome completo:</label>
            <input type="text" name="nome" required>
        </div>
        <div>
            <label for="email">Digite seu email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" required>
        </div>
        <div>
            <label for="senha">Crie uma senha:</label>
            <input type="password" name="senha" required>
        </div>
        <div>
            <input type="submit" name="submit" value="Cadastrar">
        </div>
    </form>
</body>
</html>