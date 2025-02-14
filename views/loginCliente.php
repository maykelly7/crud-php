<?php
// Conexão com o banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=servicos', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($email) && !empty($senha)) {
        try {
            // Busca o cliente pelo email
            $sql = $pdo->prepare("SELECT * FROM cliente WHERE email = ?");
            $sql->execute([$email]);
            $cliente = $sql->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                // Verifica se a senha está correta
                if (password_verify($senha, $cliente['senha'])) {
                  
                    // Redireciona para a área do cliente
                    header("location: dashboardCliente.php");
                } else {
                    echo "<div class='msg-erro'>Senha incorreta.</div>";
                }
            } else {
                echo "<div class='msg-erro'>Email não cadastrado.</div>";
            }
        } catch (PDOException $e) {
            echo "Erro ao realizar login: " . $e->getMessage();
        }
    } else {
        echo "<div class='msg-erro'>Preencha todos os campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Cliente</title>
    
</head>
<body>
    <h1>Login Cliente</h1>
    <form method="POST" class="form-login">
        <div>
            <label for="email">Digite seu email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="senha">Digite sua senha:</label>
            <input type="password" name="senha" required>
        </div>
        <div>
            <input type="submit" name="submit" value="Entrar">
        </div>
        <div>
            <a href="cadastroCliente.php">Não tem uma conta? Cadastre-se</a>
        </div>
    </form>
</body>
</html>