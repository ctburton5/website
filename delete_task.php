<?php
    if (isset($_POST['taskID'])) {
        $taskID = $_POST['taskID'];

        // Connect to your database (replace the placeholders with actual credentials)
        $conn = mysqli_connect("localhost", "root", "", "tododb");

        // Perform the database delete operation
        $query = "DELETE FROM list WHERE ID = '$taskID'";
        $result = mysqli_query($conn, $query);

        // Close the database connection
        mysqli_close($conn);

        // Return a response to the client (you can customize the response as needed)
        echo "Task deleted successfully!";
    }
?>