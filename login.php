<?php
session_start();
include_once 'db.php';
include_once 'user.class.php';

// Inicializujeme premennú pre chybové správy
$error_message = '';

// Ak je požiadavka metódy POST, vykoná sa prihlásenie užívateľa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    // Ak je prihlásenie úspešné, nastaví sa session a presmeruje na index.php
    if ($user->login()) {
        $_SESSION['user_id'] = $user->id;
        header("Location: index.php");
        exit;
    } else {
        // Nastavíme chybovú správu
        $error_message = "Prihlásenie zlyhalo. Nesprávne používateľské meno alebo heslo.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
        <?php include __DIR__ . '/casti/navbar.php'; ?>
    </header>
    <div class="container">
        <h1>Prihlásenie</h1>
        <?php
        // Zobrazíme chybovú správu, ak existuje
        if (!empty($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Používateľské meno" required>
            <input type="password" name="password" placeholder="Heslo" required>
            <button type="submit">Prihlásiť sa</button>
        </form>
    </div>
    <footer>
        <?php include __DIR__ . '/casti/footer.php'; ?>
    </footer>
</body>
</html>
