<?php
require_once 'includes/functions.php';

// Verifica se o formulário de login foi submetido
if(isset($_POST['username'], $_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(login($username, $password)){
        // Login bem sucedido, redireciona para a página protegida
        header("Location: reports.php");
        exit();
    } else {
        // Login falhou
        $error = "Usuário ou senha inválidos.";
    }
}
?>

<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p>$error</p>"; ?>
    <form method="post" action="">
        <label for="username">Usuário:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Senha:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
