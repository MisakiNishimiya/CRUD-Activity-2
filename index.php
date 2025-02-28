<?php
require_once 'auth.php';

// Redirect to login page if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$username = getLoggedInUsername();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="container">
    <div class="header">
        <h1>My To-Do List</h1>
        <p>Logged in as <?php echo htmlspecialchars($username); ?> | <a href="logout.php">Logout</a></p>
    </div>

    <!-- Create Form -->
    <form method="post" action="" class="new-task-form">
        <input type="text" name="task" placeholder="Enter task" required>
        <button type="submit" name="create"><i class="fas fa-plus"></i> New task</button>
    </form>

    <!-- Display To-Do Items -->
    <div class="todo-list">
    <?php
    require_once 'functions.php';

    // Handle Create
    if (isset($_POST['create']) && !empty($_POST['task'])) {
        $task = $_POST['task'];
        createTodo($task);
        header("Location: index.php"); // Redirect to refresh the list
        exit();
    }

    // Handle Complete - Toggle the completion status
    if (isset($_GET['toggle'])) {
        $id = $_GET['toggle'];

        // Get current completion status
        $sql = "SELECT completed FROM todos WHERE id = " . (int)$id; // Ensure ID is an integer
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $currentStatus = $row['completed'];
            $newStatus = !$currentStatus; // Toggle the status

            updateTodo($id, $newStatus);
        }

        header("Location: index.php");
        exit();
    }


    // Handle Delete
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        deleteTodo($id);
        header("Location: index.php");
        exit();
    }

    // Read and Display To-Dos
    $todos = getAllTodos();

    if ($todos) {
        while ($row = $todos->fetch_assoc()) {
            $completedClass = $row['completed'] ? 'completed' : '';
            echo "<div class='todo-item'>";
             echo "<a href='?toggle=" . $row['id'] . "'>";
                echo "<i class='fas " . ($row['completed'] ? 'fa-check-circle completed-icon' : 'fa-circle incomplete-icon') . "'></i>";
                echo "</a>";
            echo "<span class='task " . $completedClass . "'>" . htmlspecialchars($row['task']) . "</span>";  // Output the task

            echo "<div class='todo-actions'>";
            echo "<a href='?delete=" . $row['id'] . "'><i class='fas fa-trash-alt'></i></a>";
            echo "</div>"; // .todo-actions

            echo "</div>"; // .todo-item
        }
    } else {
        echo "<p>No tasks found.</p>";
    }

    closeDB();
    ?>
    </div> <!-- .todo-list -->

</div> <!-- .container -->

</body>
</html>
