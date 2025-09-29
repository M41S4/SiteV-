<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin'])) {
    header("Location: ../login.php");
    exit;
}
?>

<?php
// 1. VERIFICAR SE UM ID FOI ENVIADO PELA URL
// Usamos 'isset' para checar se a variável $_GET['id'] existe.
// Isso evita erros caso alguém acesse o arquivo diretamente sem um ID.
if (isset($_GET['id'])) {
    
    // Pega o ID da URL e armazena em uma variável.
    $id_hotel_para_excluir = $_GET['id'];

    // --- 2. CONEXÃO COM O BANCO DE DADOS ---
    // Os mesmos dados de conexão que já usamos antes.
    $servidor = "localhost";
    $usuario = "root";
    $senha = "usbw";
    $banco_de_dados = "viagem";
    
    $conexao = new mysqli($servidor, $usuario, $senha, $banco_de_dados);

    // Checa se a conexão foi bem-sucedida.
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // --- 3. PREPARAR E EXECUTAR A EXCLUSÃO ---
    // Comando SQL para deletar um registro da tabela 'hoteis'
    // Onde a coluna 'id' for igual ao valor que vamos passar.
    $sql = "DELETE FROM hoteis WHERE id = ?";

    // Prepara a consulta para evitar injeção de SQL (mais seguro).
    $stmt = $conexao->prepare($sql);
    
    // Vincula o parâmetro: o 'i' significa que a variável é um Inteiro (Integer).
    $stmt->bind_param("i", $id_hotel_para_excluir);

    // Executa a consulta.
    if ($stmt->execute()) {
        // Se a exclusão deu certo, a mágica acontece aqui:
        // Redireciona o navegador do usuário de volta para a página da lista.
        header("Location: listar_hoteis.php");
        exit(); // Garante que o script pare de ser executado após o redirecionamento.
    } else {
        // Se deu algum erro na execução, mostra a mensagem.
        echo "Erro ao excluir o hotel: " . $stmt->error;
    }

    // Fecha a declaração e a conexão.
    $stmt->close();
    $conexao->close();

} else {
    // Se ninguém passou um ID pela URL, mostra uma mensagem de erro.
    echo "ID do hotel não foi fornecido. Operação cancelada.";
    exit();
}
?>