// Database connection parameters.
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        button.delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ma To-Do List</h1>
        <form method="POST" action="">
            <input type="text" name="task" placeholder="Nouvelle tâche">
            <button type="submit" name="add_task">Ajouter</button>
        </form>
        <ul>
            <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                <li>
                    <?php echo htmlspecialchars($task); ?>
                    <form method="POST" action="" style="margin: 0;">
                        <input type="hidden" name="task_index" value="<?php echo $index; ?>">
                        <button type="submit" name="delete_task" class="delete">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

