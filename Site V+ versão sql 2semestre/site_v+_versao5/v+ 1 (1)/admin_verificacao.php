<?php
session_start();

$senhaAdminSecreta = "admin123"; // precisa ser a mesma do login.php
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senhaAdmin = $_POST['senha_admin'];

    if ($senhaAdmin === $senhaAdminSecreta) {
        // Liberar acesso ao admin
        $_SESSION['is_admin'] = true;
        header("Location: admin/adicionar_hotel.html"); // redireciona para a área admin
        exit;
    } else {
        $mensagem = "❌ Senha de administrador incorreta!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verificação de Administrador</title>
</head>
<body>
    <h2>Confirmação de Administrador</h2>

    <?php if ($mensagem): ?>
        <p style="color:red;"><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="senha_admin">Senha de Administrador:</label>
        <input type="password" name="senha_admin" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
