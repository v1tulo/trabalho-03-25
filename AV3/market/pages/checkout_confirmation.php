<?php
session_start();

// Inclui o header
include_once '../templates/header.php';

// Verifica se o carrinho está vazio
if (empty($_SESSION['cart'])) {
    echo "<div class='confirmation-container'>";
    echo "<h2>Confirmação de Compra</h2>";
    echo "<p>O seu carrinho está vazio.</p>";
    echo "</div>";
} else {
    // Inicializa variáveis para o total de itens e o total geral da compra
    $total_items = 0;
    $total_price = 0;

    // Exibe detalhes do pedido
    echo "<div class='confirmation-container'>";
	echo "<h2>Confirmação de Compra</h2>";  
    $celular = $_SESSION['celular']; //DESAFIO
    echo "<p> Obrigado pelo seu pedido, usuário com celular $celular!</p>"; // DESAFIO
    echo "<p>Sua compra foi concluída com sucesso!</p>";
    echo "<h3>Detalhes do Pedido:</h3>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product) {
        echo "<li>{$product['name']} - R$ {$product['price']} x {$product['quantity']}</li>";
        // Adiciona a quantidade de itens e o subtotal ao total geral da compra
        $total_items += $product['quantity'];
        $total_price += $product['price'] * $product['quantity'];
    }
    echo "</ul>";
    echo "<p>Total de Itens: $total_items</p>";
    echo "<p>Total: R$ $total_price</p>";
    echo "</div>";
	
	// Destroi a sessão após a confirmação da compra
    session_destroy();
}

// Inclui o footer
include_once '../templates/footer.php';
?>

<!-- Estilos CSS -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    
    .confirmation-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    p {
        color: #666;
        font-size: 16px;
        line-height: 1.5;
    }
    
    ul {
        list-style-type: none;
        padding: 0;
    }
    
    li {
        margin-bottom: 10px;
    }
    
    li::before {
        content: "• ";
        color: #666;
    }
</style>
