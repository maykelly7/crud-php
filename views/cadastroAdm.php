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
        $sqlCheck = $pdo->prepare("SELECT COUNT(*) FROM adm WHERE telefone = ?");
        $sqlCheck->execute([$telefone]);
        $count = $sqlCheck->fetchColumn();

        if ($count > 0) {
            echo "<div class='msg-erro'>Erro: O número de telefone já está cadastrado.</div>";
        } else {
            // Prepara a consulta SQL
            $sql = $pdo->prepare("INSERT INTO adm (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
            $sql->execute([$nome, $email, $telefone, $senha]);

            // Redireciona para a tela de login
            header('Location: loginAdm.php');
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
    <title>Cadastro ADM</title>
</head>
<body>
    <h1>Cadastro ADM</h1>
    <form method="POST">
        <div class="form-cadastro">
            <label for="nome">Nome completo:</label>
            <input type="text" name="nome" required>

            <label for="email">Digite seu email:</label>
            <input type="text" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" required>

            <label for="senha">Crie uma senha:</label>
            <input type="password" name="senha" required>

            <input type="submit" name="submit" value="Cadastrar"><br>
        </div>
    </form>
</body>
</html>