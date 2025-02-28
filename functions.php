<?php

require_once 'db.php'; // Include the database connection

// Function to create a new to-do item
function createTodo($task) {
    global $conn;
    $task = mysqli_real_escape_string($conn, htmlspecialchars($task)); //Sanitize input

    $sql = "INSERT INTO todos (task) VALUES ('$task')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

// Function to read all to-do items (including completed)
function getAllTodos() {
    global $conn;
    $sql = "SELECT * FROM todos ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result;
    } else {
        return false;
    }
}

// Function to update a to-do item (mark as complete/incomplete)
function updateTodo($id, $completed) {
    global $conn;
    $id = (int)$id; // Ensure ID is an integer
    $completed = (bool)$completed; //Ensure completed is a boolean
    $sql = "UPDATE todos SET completed = '$completed' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error updating record: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

// Function to delete a to-do item
function deleteTodo($id) {
    global $conn;
    $id = (int)$id; // Ensure ID is an integer
    $sql = "DELETE FROM todos WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error deleting record: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

//Close the connection
function closeDB() {
  global $conn;
  $conn->close();
}
?>
