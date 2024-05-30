<?php
session_start();
// Skontroluje, či je užívateľ prihlásený, ak nie, presmeruje na landing.php
if (!isset($_SESSION['user_id'])) {
    header("Location: landing.php");
    exit;
}

include_once 'db.php';
include_once 'task.class.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);
$task->user_id = $_SESSION['user_id'];

// Ak je požiadavka metódy POST, vykonajú sa príslušné akcie (vytvorenie, aktualizácia, zmazanie)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $task->title = $_POST['title'];
        $task->description = $_POST['description'];
        $task->status = $_POST['status'];
        $task->create();
    } elseif (isset($_POST['update'])) {
        $task->id = $_POST['id'];
        $task->title = $_POST['title'];
        $task->description = $_POST['description'];
        $task->status = $_POST['status'];
        $task->update();
    } elseif (isset($_POST['delete'])) {
        $task->id = $_POST['id'];
        $task->delete();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
        <?php include __DIR__ . '/casti/navbar.php'; ?>
    </header>
    <div class="container">
        <h1>To-Do List</h1>
        <form method="post" action="index.php">
            <input type="hidden" name="id" id="task-id">
            <input type="text" name="title" id="task-title" placeholder="Názov úlohy" required>
            <textarea name="description" id="task-description" placeholder="Popis úlohy" required></textarea>
            <select name="status" id="task-status">
                <option value="pending">Čakajúca</option>
                <option value="completed">Dokončená</option>
            </select>
            <button type="submit" name="create">Pridať úlohu</button>
            <button type="submit" name="update">Aktualizovať úlohu</button>
            <button type="submit" name="delete">Zmazať úlohu</button>
        </form>

        <h2>Zoznam úloh</h2>
        <ul>
            <?php
            // Načíta a zobrazí všetky úlohy pre prihláseného užívateľa
            $stmt = $task->read();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<li>
                    <h3>{$title}</h3>
                    <p>{$description}</p>
                    <p>Status: {$status}</p>
                    <button onclick=\"editTask({$id}, '{$title}', '{$description}', '{$status}')\">Edit</button>
                </li>";
            }
            ?>
        </ul>
    </div>
    <footer>
        <?php include __DIR__ . '/casti/footer.php'; ?>
    </footer>
    <script src="js/menu.js"></script>
    <script src="js/slider.js"></script>
    <script>
        // Funkcia na predvyplnenie formulára pre editáciu úlohy
        function editTask(id, title, description, status) {
            document.getElementById('task-id').value = id;
            document.getElementById('task-title').value = title;
            document.getElementById('task-description').value = description;
            document.getElementById('task-status').value = status;
        }
        // Funkcia na potvrdenie zmazania úlohy
        function deleteTask(id) {
            if (confirm('Naozaj chcete zmazať túto úlohu?')) {
                document.getElementById('task-id').value = id;
                document.forms[0].submit();
            }
        }
    </script>
</body>
</html>
