<?php
session_start();

// Verifica se o usuário está logado
function isLoggedIn(){
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Função para verificar login
function login($username, $password){
    // Aqui você pode adicionar lógica para verificar usuário e senha.
    // Por exemplo, comparar com dados em um banco de dados.
    // Neste exemplo, vamos apenas definir um usuário e senha fixos.
    $valid_username = "admin";
    $valid_password = "admin123";

    if($username == $valid_username && $password == $valid_password){
        $_SESSION['logged_in'] = true;
        return true;
    } else {
        return false;
    }
}
// Função para exibir o relatório de total de pedidos por dia
function showTotalPedidosPorDia($pdo) {
    try {
        // Consulta SQL para contar o número de pedidos feitos em cada dia
        $query = "SELECT DATE(data) as data_pedido, COUNT(*) as total_pedidos FROM orders GROUP BY DATE(data)";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Exibe os resultados
        echo "<h2>Total de Pedidos por Dia</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Data do Pedido</th><th>Total de Pedidos</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['data_pedido']}</td>";
            echo "<td>{$row['total_pedidos']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Erro ao executar a consulta: " . $e->getMessage();
    }
}

// Função para exibir o relatório de produtos mais vendidos
function showProdutosMaisVendidos($pdo) {
    try {
        // Consulta SQL para obter os produtos mais vendidos
        $query = "SELECT produtos.produto, SUM(orders_items.quantity) as total_vendas 
                  FROM produtos 
                  INNER JOIN orders_items ON produtos.id = orders_items.product_id 
                  GROUP BY produtos.produto 
                  ORDER BY total_vendas DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Exibe os resultados
        echo "<h2>Produtos Mais Vendidos</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Produto</th><th>Total de Vendas</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['produto']}</td>";
            echo "<td>{$row['total_vendas']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Erro ao executar a consulta: " . $e->getMessage();
    }
}

// Função para exibir o relatório de total de pedidos por celular
function showTotalPedidosPorCelular($pdo) {
    try {
        // Consulta SQL para calcular o total de pedidos por celular
        $query = "SELECT celular, COUNT(*) as total_pedidos FROM orders GROUP BY celular";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Exibe os resultados
        echo "<h2>Total de Pedidos por Celular</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Celular</th><th>Total de Pedidos</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['celular']}</td>";
            echo "<td>{$row['total_pedidos']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Erro ao executar a consulta: " . $e->getMessage();
    }
}

// Função para exibir o relatório de total da soma de subtotal por celular e ID do Pedido, juntamente com os detalhes de cada item
function showTotalComItensPorCelular($pdo) {
    try {
        // Consulta SQL para obter o total da soma de subtotal por celular e ID do Pedido, juntamente com os detalhes de cada item
        $query = "SELECT orders.celular, orders.id_order, produtos.produto, orders_items.quantity, orders_items.subtotal, 
                         SUM(orders_items.subtotal) OVER(PARTITION BY orders.celular, orders.id_order) as total_subtotal_pedido
                  FROM orders 
                  INNER JOIN orders_items ON orders.id_order = orders_items.id_order 
                  INNER JOIN produtos ON orders_items.product_id = produtos.id 
                  ORDER BY orders.celular, orders.id_order, produtos.produto";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Variáveis para controlar a mudança de celular e ID do Pedido
        $last_celular = null;
        $last_id_order = null;

        // Exibe os resultados
        echo "<h2>Total da Soma de Subtotal por Celular e ID do Pedido, Juntamente com os Detalhes de Cada Item</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Celular</th><th>ID do Pedido</th><th>Produto</th><th>Quantidade</th><th>Subtotal</th><th>Total Subtotal Pedido</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verifica se houve mudança de celular ou ID do Pedido
            if ($row['celular'] != $last_celular || $row['id_order'] != $last_id_order) {
                // Se sim, exibe uma nova linha com o total subtotal do pedido
                echo "<tr>";
                echo "<td>{$row['celular']}</td>";
                echo "<td>{$row['id_order']}</td>";
                echo "<td colspan='3'></td>"; // Colunas vazias para o produto, quantidade e subtotal
                echo "<td>{$row['total_subtotal_pedido']}</td>";
                echo "</tr>";
                // Atualiza as variáveis de controle
                $last_celular = $row['celular'];
                $last_id_order = $row['id_order'];
            }
            // Exibe os detalhes do item
            echo "<tr>";
            echo "<td colspan='2'></td>"; // Colunas vazias para o celular e ID do Pedido
            echo "<td>{$row['produto']}</td>";
            echo "<td>{$row['quantity']}</td>";
            echo "<td>{$row['subtotal']}</td>";
            echo "<td></td>"; // Coluna vazia para o total subtotal do pedido
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Erro ao executar a consulta: " . $e->getMessage();
    }
}



?>