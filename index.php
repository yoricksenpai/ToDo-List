<?php
session_start();

// Database connection parameters.
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

// Connexion à la base de données MySQL
try {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
    $conn = new PDO($dsn, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la table si elle n'existe pas
    $conn->exec("CREATE TABLE IF NOT EXISTS todo (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        title VARCHAR(2048) NOT NULL,
        done TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )");
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Gérer les actions reçues par la page
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'new' && isset($_POST['title'])) {
            $task = htmlspecialchars($_POST['title']);
            $stmt = $conn->prepare("INSERT INTO todo (title, created_at) VALUES (:task, NOW())");
            $stmt->bindParam(':task', $task);
            $stmt->execute();
        } elseif ($action === 'delete' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM todo WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } elseif ($action === 'toggle' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE todo SET done = 1 - done WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }
}

// Lire la liste des tâches enregistrées
$stmt = $conn->prepare("SELECT * FROM todo ORDER BY created_at DESC");
$stmt->execute();
$taches = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">To-Do List</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Ma To-Do List</h1>
        <form method="POST" action="" class="form-inline my-4">
            <input type="text" name="title" class="form-control mr-2" placeholder="Nouvelle tâche">
            <button type="submit" name="action" value="new" class="btn btn-primary">Ajouter</button>
        </form>
        <ul class="list-group">
            <?php foreach ($taches as $tache): ?>
                <li class="list-group-item <?= $tache->done ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
                    <span><?= htmlspecialchars($tache->title); ?></span>
                    <div>
                        <form method="POST" action="" class="d-inline">
                            <input type="hidden" name="id" value="<?= $tache->id; ?>">
                            <button type="submit" name="action" value="toggle" class="btn btn-warning btn-sm">
                                <?= $tache->done ? 'Non fait' : 'Fait'; ?>
                            </button>
                        </form>
                        <form method="POST" action="" class="d-inline">
                            <input type="hidden" name="id" value="<?= $tache->id; ?>">
                            <button type="submit" name="action" value="delete"
                                class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>