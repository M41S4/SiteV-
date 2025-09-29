<?php
// Usar a biblioteca de compatibilidade se estiver em PHP < 5.5
// Se você atualizou para o XAMPP, pode remover esta linha.
// require 'password.php'; 

// 1. CONEXÃO COM O BANCO DE DADOS
// Certifique-se que o caminho está correto
include("conexao_bdViajeMais.php");
$mensagem = "";

// 2. VERIFICA SE O FORMULÁRIO FOI ENVIADO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. COLETA E LIMPA OS DADOS DO FORMULÁRIO
    $nome       = trim($_POST['nome']);
    $sobrenome  = trim($_POST['sobrenome']);
    $data_nasc  = $_POST['data_nasc'];
    $cpf        = trim($_POST['cpf']);
    $genero     = $_POST['genero'];
    $telefone   = trim($_POST['telefone']);
    $endereco   = trim($_POST['endereco']);
    $email      = trim($_POST['email']);
    $senha      = $_POST['senha'];
    $confirma   = $_POST['confirma_senha'];

    // 4. VALIDAÇÃO: VERIFICA SE AS SENHAS COINCIDEM
if ($senha !== $confirma) {
    $mensagem = "❌ As senhas não coincidem!";
} else {
    // >>> SEM HASH (NÃO SEGURO, APENAS PARA TESTES) <<<
    $senhaHash = $senha;

    // 6. PREPARA E EXECUTA A INSERÇÃO NO BANCO DE DADOS
    $sql = "INSERT INTO usuarios 
                (nome, sobrenome, data_nasc, cpf, genero, telefone, endereco, email, senha) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("sssssssss", 
        $nome, $sobrenome, $data_nasc, $cpf, $genero, 
        $telefone, $endereco, $email, $senhaHash
    );

    if ($stmt->execute()) {
        $mensagem = "✅ Cadastro realizado com sucesso!";
    } else {
        $mensagem = "❌ Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}

}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #6dd5ed, #2193b0);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
  }

  .container {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    width: 350px;
    max-height: 90vh;
    overflow-y: auto;
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
  }

  label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: bold;
    color: #555;
  }

  input, select {
    box-sizing: border-box; /* Garante que padding não afete a largura final */
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
  }

  button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 30px;
    background: #e74c3c;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  button:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  }

  .mensagem {
    text-align: center;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 14px;
    color: #fff;
    background-color: <?php echo strpos($mensagem, '✅') !== false ? '#2ecc71' : '#e74c3c'; ?>;
  }
  </style>
</head>
<body>
  <div class="container">
    <h2>Cadastro de Usuário</h2>

    <?php if (!empty($mensagem)): ?>
      <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <label>Nome</label>
      <input type="text" name="nome" required>

      <label>Sobrenome</label>
      <input type="text" name="sobrenome" required>

      <label>Data de Nascimento</label>
      <input type="date" name="data_nasc" required>

      <label>CPF</label>
      <input type="text" name="cpf" required>

      <label>Gênero</label>
      <select name="genero" required>
        <option value="">Selecione</option>
        <option value="Masculino">Masculino</option>
        <option value="Feminino">Feminino</option>
        <option value="Outro">Outro</option>
      </select>

      <label>Telefone</label>
      <input type="tel" name="telefone">

      <label>Endereço</label>
      <input type="text" name="endereco">

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Senha</label>
      <input type="password" name="senha" required>

      <label>Confirme a Senha</label>
      <input type="password" name="confirma_senha" required>

      <button type="submit">Cadastrar</button>
    </form>
  </div>
</body>
</html>