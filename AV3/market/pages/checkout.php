<?php
session_start();
include_once '../templates/header.php';

// Verifica se o carrinho está vazio
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Seu carrinho está vazio.'); window.location.href = 'cart.php';</script>";
    exit(); // Encerra o script caso o carrinho esteja vazio
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['celular'])) {
    // Conecta ao banco de dados
    $servername = "localhost";
    $port = 7306;
    $username = "root";
    $password = "";
    $dbname = "banco_de_dados";
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obtém o número de celular do formulário
    $celular = $_POST['celular'];

    //DESAFIO DA SESSÃO COM  CELULAR
    $_SESSION['celular'] = $_POST['celular'];   //DESAFIO
	
    // Insere os dados na tabela orders
    $sql = "INSERT INTO orders (celular) VALUES ('$celular')";
    if ($conn->query($sql) !== TRUE) {
        echo "Erro ao inserir na tabela orders: " . $conn->error;
    }

    // Obtém o ID do pedido inserido
    $id_order = $conn->insert_id;

    // Insere os itens do carrinho na tabela orders_items
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $quantity = $product['quantity'];
        $subtotal = $product['price'] * $quantity;
        $sql = "INSERT INTO orders_items (id_order, product_id, quantity, subtotal) VALUES ($id_order, $product_id, $quantity, $subtotal)";
        if ($conn->query($sql) !== TRUE) {
            echo "Erro ao inserir na tabela orders_items: " . $conn->error;
        }
    }

    // Fecha a conexão com o banco de dados
    $conn->close();

    // Redireciona para uma página de confirmação ou qualquer outra página após o checkout
    header('Location: checkout_confirmation.php');
    exit();
}
include_once '../templates/footer.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    <form action="" method="post">
        <label for="celular">Número de Celular:</label>
        <input type="text" id="celular" name="celular" required>
        <button type="submit">Finalizar Compra</button>
    </form>
</body>
</html>
