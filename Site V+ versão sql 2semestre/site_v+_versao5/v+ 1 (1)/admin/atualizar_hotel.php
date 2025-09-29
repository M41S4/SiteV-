<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin'])) {
    header("Location: ../login.php");
    exit;
}
?>

<?php
// --- PARTE 1: RECEBER OS DADOS DO FORMULÁRIO ---

// Primeiro, verificamos se os dados foram enviados via método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pega todos os dados do formulário e o ID escondido.
    $id_hotel = $_POST['id_hotel'];
    $nome = $_POST['nome_hotel'];
    $cidade = $_POST['cidade_hotel'];
    $preco = $_POST['preco_hotel'];

    // --- PARTE 2: ATUALIZAR OS DADOS NO BANCO ---

    // Conecta ao banco de dados.
    $servidor = "localhost";
    $usuario = "root";
    $senha = "usbw";
    $banco_de_dados = "viagem";
    $conexao = new mysqli($servidor, $usuario, $senha, $banco_de_dados);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Prepara a consulta SQL para ATUALIZAR os dados.
    // "UPDATE hoteis SET..." é o comando para alterar um registro.
    // "WHERE id = ?" é a cláusula crucial que garante que vamos alterar APENAS o hotel certo.
    $sql = "UPDATE hoteis SET nome = ?, cidade = ?, preco_diaria = ? WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    // Vincula os parâmetros. A ordem é importante!
    // "ssdi" significa: String, String, Double, Integer.
    $stmt->bind_param("ssdi", $nome, $cidade, $preco, $id_hotel);

    // Executa a consulta e verifica se deu certo.
    if ($stmt->execute()) {
        // Se deu certo, redireciona de volta para a lista.
        header("Location: listar_hoteis.php");
        exit();
    } else {
        echo "Erro ao atualizar o hotel: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();

} else {
    // Se alguém tentar acessar este arquivo diretamente sem enviar dados, redireciona para a lista.
    header("Location: listar_hoteis.php");
    exit();
}
?>