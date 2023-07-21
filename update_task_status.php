<?php
    // Retrieve the task ID and completed status from the AJAX request
    $taskID = $_POST['taskID'];
    $completed = $_POST['completed'];

    // Perform the database update
    $conn = mysqli_connect("localhost", "root", "", "tododb");

    $query = "UPDATE tasks SET completed = '$completed' WHERE ID = '$taskID'";
    $result = mysqli_query($conn, $query);

    // Close the database connection
    mysqli_close($conn);
?>