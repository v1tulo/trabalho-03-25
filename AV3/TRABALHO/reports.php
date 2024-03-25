<?php
require_once 'includes/functions.php';
require_once 'includes/db_connection.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if(!isLoggedIn()){
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Verifica se o botão de logoff foi clicado
if(isset($_POST['logout'])) {
    // Destruir todas as variáveis de sessão
    session_unset();
    // Finalizar a sessão
    session_destroy();
    // Redirecionar para a página de login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatórios</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <h2>Relatórios</h2>

    <!-- Botão de logoff -->
    <form method="post" action="">
        <input type="submit" name="logout" value="Logoff">
    </form>

    <!-- Formulário para selecionar o relatório -->
    <form method="post" action="">
        <label for="report">Selecione um Relatório:</label>
        <select id="report" name="report">
            <option value="total_pedidos_por_dia">Total de Pedidos por Dia</option>
            <option value="produtos_mais_vendidos">Produtos Mais Vendidos</option>
            <option value="total_pedidos_por_celular">Total de Pedidos por Celular</option>
			<option value="total_com_itens_por_celular">Total com Itens por Celular</option>
        </select>
        <input type="submit" value="Gerar Relatório">
    </form>

    <?php
    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se o parâmetro 'report' foi enviado
        if (isset($_POST['report'])) {
            $report = $_POST['report'];

            // Exibe o relatório correspondente com base no parâmetro recebido
            switch ($report) {
                case 'total_pedidos_por_dia':
                    showTotalPedidosPorDia($pdo);
                    break;
                case 'produtos_mais_vendidos':
                    showProdutosMaisVendidos($pdo);
                    break;
                case 'total_pedidos_por_celular':
                    showTotalPedidosPorCelular($pdo);
                    break;
				case 'total_com_itens_por_celular':
					showTotalComItensPorCelular($pdo);
					break;
                default:
                    echo "Relatório inválido.";
                    break;
            }
        }
    }
    ?>

</body>
</html>
