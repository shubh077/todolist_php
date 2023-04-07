<?php

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo_list";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS todos (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        description TEXT,
        completed BOOLEAN NOT NULL DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
mysqli_query($conn, $sql);

// Add new todo item
if (isset($_POST['description'])) {
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sql = "INSERT INTO todos (description) VALUES ('$description')";
    mysqli_query($conn, $sql);
}

// Update todo item
if (isset($_POST['id']) && isset($_POST['completed'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $completed = mysqli_real_escape_string($conn, $_POST['completed']);
    $sql = "UPDATE todos SET completed='$completed' WHERE id='$id'";
    mysqli_query($conn, $sql);
}

// Delete todo item
if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['delete']);
    $sql = "DELETE FROM todos WHERE id='$id'";
    mysqli_query($conn, $sql);
}

// Fetch all todo items
$sql = "SELECT * FROM todos ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Todo List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-4 mb-4">Todo List</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="description" placeholder="New Todo Item" class="form-control">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Add</button>
                        </div>
                    </div>
                </form>
                <ul class="list-group">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="form-check">
                                    <input type="checkbox" name="completed" value="1" class="form-check-input" <?php if ($row['completed'])
                                        echo 'checked'; ?>>
                                    <label class="form-check-label">
                                        <?php echo $row['description']; ?>
                                    </label>
                                </div>
                                <button type="submit" name="delete" value="<?php echo $row['id']; ?>"
                                    class="btn btn-danger">Delete</button>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>