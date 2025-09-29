<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin'])) {
    header("Location: ../login.php");
    exit;
}
?>


<?php
// --- Início do código PHP que busca os dados ---
$servidor = "localhost";
$usuario = "root";
$senha = "usbw";
$banco_de_dados = "viagem";
$conexao = new mysqli($servidor, $usuario, $senha, $banco_de_dados);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}
$sql = "SELECT id, nome, cidade, preco_diaria FROM hoteis";
$resultado = $conexao->query($sql);
$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Hotéis</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; }
        h1 { color: #333; text-align: center; }
        .hotel { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .hotel h2 { margin: 0 0 10px 0; }
        /* Estilo do botão de adicionar */
        .btn-adicionar {
            display: inline-block;
            padding: 10px 20px; /* Aumentei um pouco o padding */
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px; /* Aumentei a fonte */
        }
    </style>
</head>
<body>

    <h1>Hotéis Disponíveis</h1>

    <?php
    // --- Loop que exibe os hotéis ---
    if ($resultado->num_rows > 0) {
        while ($hotel = $resultado->fetch_assoc()) {
            echo '<div class="hotel">';
            echo '<h2>' . htmlspecialchars($hotel['nome']) . '</h2>';
            echo '<p><strong>Cidade:</strong> ' . htmlspecialchars($hotel['cidade']) . '</p>';
            echo '<p><strong>Preço da Diária:</strong> R$ ' . number_format($hotel['preco_diaria'], 2, ',', '.') . '</p>';
            
            // Links de Ação
            echo '<a href="editar_hotel.php?id=' . $hotel['id'] . '" style="color: blue; font-weight: bold; margin-right: 15px;">Editar</a>';
            echo '<a href="excluir_hotel.php?id=' . $hotel['id'] . '" style="color: red; font-weight: bold;">Excluir</a>';
            
            echo '</div>';
        }
    } else {
        echo "<p>Nenhum hotel encontrado.</p>";
    }
    ?>

    <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
        <a href="adicionar_hotel.html" class="btn-adicionar">Adicionar Novo Hotel</a>
    </div>

</body>
</html>