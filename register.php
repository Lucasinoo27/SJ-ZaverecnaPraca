<?php
include_once 'db.php';
include_once 'user.class.php';

// Inicializujeme premennú pre chybové správy
$error_message = '';

// Ak je požiadavka metódy POST, vykoná sa registrácia užívateľa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    // Ak je registrácia úspešná, presmeruje na login.php
    if ($user->register()) {
        header("Location: login.php");
        exit;
    } else {
        // Nastavíme chybovú správu
        $error_message = "Registrácia zlyhala. Používateľské meno už existuje alebo došlo k chybe.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
        <?php include __DIR__ . '/casti/navbar.php'; ?>
    </header>
    <div class="container">
        <h1>Registrácia</h1>
        <?php
        // Zobrazíme chybovú správu, ak existuje
        if (!empty($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
        <form method="post" action="register.php">
            <input type="text" name="username" placeholder="Používateľské meno" required>
            <input type="password" name="password" placeholder="Heslo" required>
            <button type="submit">Registrovať</button>
        </form>
    </div>
    <footer>
        <?php include __DIR__ . '/casti/footer.php'; ?>
    </footer>
</body>
</html>
