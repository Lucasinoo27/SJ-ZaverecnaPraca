<nav>
    <ul class="main-menu">
        <li><a href="index.php">Domov</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php">Odhlásiť sa</a></li>
        <?php else: ?>
            <li><a href="login.php">Prihlásiť sa</a></li>
            <li><a href="register.php">Registrovať sa</a></li>
        <?php endif; ?>
    </ul>
</nav>
