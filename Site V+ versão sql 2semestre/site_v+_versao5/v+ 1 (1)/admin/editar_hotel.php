<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin'])) {
    header("Location: ../login.php");
    exit;
}
?>

<?php
// --- PARTE 1: BUSCAR OS DADOS DO HOTEL NO BANCO ---

// Inicializa a variável $hotel com valores vazios.
$hotel = [
    'id' => '',
    'nome' => '',
    'cidade' => '',
    'preco_diaria' => ''
];

// Verifica se um ID foi passado pela URL.
if (isset($_GET['id'])) {
    $id_hotel = $_GET['id'];

    // Conecta ao banco de dados.
    $servidor = "localhost";
    $usuario = "root";
    $senha = "usbw";
    $banco_de_dados = "viagem";
    $conexao = new mysqli($servidor, $usuario, $senha, $banco_de_dados);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Prepara a consulta SQL para buscar um hotel específico pelo seu ID.
    $sql = "SELECT id, nome, cidade, preco_diaria FROM hoteis WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_hotel);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Se encontrou o hotel, pega os dados dele.
    if ($resultado->num_rows > 0) {
        $hotel = $resultado->fetch_assoc();
    } else {
        echo "Hotel não encontrado.";
        exit; // Para a execução se o hotel não existe.
    }

    $stmt->close();
    $conexao->close();
} else {
    echo "ID do hotel não fornecido.";
    exit; // Para a execução se nenhum ID foi passado.
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Hotel</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 40px auto; }
        form { display: flex; flex-direction: column; gap: 10px; }
        label { font-weight: bold; }
        input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px;}
    </style>
</head>
<body>
    
    <h1>Editar Hotel: <?php echo htmlspecialchars($hotel['nome']); ?></h1>

    <form action="atualizar_hotel.php" method="post">

        <input type="hidden" name="id_hotel" value="<?php echo $hotel['id']; ?>">

        <label for="nome">Nome do Hotel:</label>
        <input type="text" id="nome" name="nome_hotel" value="<?php echo htmlspecialchars($hotel['nome']); ?>" required>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade_hotel" value="<?php echo htmlspecialchars($hotel['cidade']); ?>" required>

        <label for="preco">Preço da Diária:</label>
        <input type="number" step="0.01" id="preco" name="preco_hotel" value="<?php echo $hotel['preco_diaria']; ?>" required>

        <button type="submit">Salvar Alterações</button>
    </form>

</body>
</html>