<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexao_bdViajeMais.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Buscar usuário pelo email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica senha
    if ($senha === $usuario['senha']) {

        // Salva dados na sessão
        $_SESSION['usuario_id']    = $usuario['id'];
        $_SESSION['usuario_nome']  = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];

        // Verifica se é admin
        if (strpos($email, "@adm") !== false) {
            header("Location: admin_verificacao.php");
            exit;
        } else {
            header("Location: user/index.html");
            exit;
        }

    } else {
        $mensagem = "❌ Senha incorreta!";
    }

    } else {
        $mensagem = "❌ E-mail não encontrado!";
    }

    $stmt->close();
} 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_login.css">
    <title>Login</title>
</head>
<body>
    <div id="main">
        <form id="form" method="POST" action="login.php">
            <fieldset id="border_form">
                <legend>Login</legend>

                <?php if ($mensagem): ?>
                    <p class="mensagem" style="color:red;"><?= $mensagem ?></p>
                <?php endif; ?>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit" class="btn-link">Entrar</button>
                <a href="cadastro.php" class="btn-link">Cadastre-se</a>
            </fieldset>
        </form>
    </div>
</body>
</html>
