<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin'])) {
    header("Location: ../login.php");
    exit;
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. RECEBER TODOS OS DADOS DO FORMULÁRIO ATUALIZADO
    $nome = $_POST['nome_hotel'];
    $cnpj = $_POST['cnpj_hotel'];
    $endereco = $_POST['endereco_hotel'];
    $numero = $_POST['numero_hotel'];
    $cidade = $_POST['cidade_hotel'];
    $estado = $_POST['estado_hotel'];
    $quantidade_quartos = $_POST['quartos_hotel'];
    $preco = $_POST['preco_hotel'];

    // --- Conexão com o banco de dados (código igual) ---
    $servidor = "localhost";
    $usuario = "root";
    $senha = "usbw";
    $banco_de_dados = "viagem";
    $conexao = new mysqli($servidor, $usuario, $senha, $banco_de_dados);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // 2. PREPARAR A NOVA CONSULTA SQL (COM MAIS COLUNAS)
    $sql = "INSERT INTO hoteis (nome, cnpj, endereco, numero, cidade, estado, quantidade_quartos, quartos_disponiveis, preco_diaria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexao->prepare($sql);

    // No cadastro, vamos assumir que o número de quartos disponíveis é igual ao total.
    $quartos_disponiveis = $quantidade_quartos;

    // 3. VINCULAR TODOS OS PARÂMETROS (A ORDEM É IMPORTANTE!)
    // A string de tipos precisa corresponder a cada campo: s = string, i = integer, d = double
    $stmt->bind_param("ssssssiid", $nome, $cnpj, $endereco, $numero, $cidade, $estado, $quantidade_quartos, $quartos_disponiveis, $preco);

    // --- Execução e redirecionamento (código igual) ---
    if ($stmt->execute()) {
        header("Location: listar_hoteis.php");
        exit();
    } else {
        echo "<h1>Erro ao cadastrar o hotel: " . $stmt->error . "</h1>";
    }

    $stmt->close();
    $conexao->close();
}
?>