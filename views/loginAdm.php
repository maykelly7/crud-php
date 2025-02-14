<?php
require_once '../App/db/Database.php'; // Inclui a classe Database

// Instancia a classe Database para a tabela 'adm'
$database = new Database('adm');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ADM</title>
</head>
<body>
    <h1>Login ADM</h1>
    <form method="POST">
        <div class="form-login">
            <label for="email">Digite seu email:</label>
            <input type="text" name="email" required>

            <label for="senha">Digite sua senha:</label>
            <input type="password" name="senha" required>

            <input type="submit" value="Entrar"><br>
            <a class="inscreva" href="../views/cadastroAdm.php">Inscreva-se</a>
        </div>
    </form>

    <?php
    if (isset($_POST['email'])) {
        $email = addslashes($_POST['email']);
        $senha = addslashes($_POST['senha']);

        if (!empty($email) && !empty($senha)) {
            // Busca o usuário pelo email
            $usuario = $database->buscarUsuarioPorEmail($email);

            if ($usuario) {
                // Verifica se a senha está correta
                if (password_verify($senha, $usuario['senha'])) {
                    
                    // Redirecionar para a área restrita
                    header("location: dashboardAdm.php");
                } else {
                    echo "<div class='msg-erro'>Senha incorreta.</div>";
                }
            } else {
                echo "<div class='msg-erro'>Email não cadastrado.</div>";
            }
        } else {
            echo "<div class='msg-erro'>Preencha todos os campos.</div>";
        }
    }
    ?>
</body>
</html>