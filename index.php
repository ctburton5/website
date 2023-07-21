<?php
    session_start();
    include("database.php");
    $userID = $_SESSION['id'];
    $username = $_SESSION['user'];
    
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do List</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="main">
        <div class="content">


            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <p class="heading">
                    <?php echo "$username's<br>"; ?>To-Do List
                </p>
                <h2>Add a task to the list:</h2>
                <input class="center-block" type="text" name="task"><br>
                <input class="center-submit" type="submit" name="submit" value="Add"><br>
                <?php
                
                

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $task = filter_input(INPUT_POST, "task", FILTER_SANITIZE_SPECIAL_CHARS);

                    if (empty($task)) {
                        echo "<div class=\"redText\">";
                        echo "You tried to add an empty task!";
                        echo "</div>";
                    } else {

                        try {
                            //$userID = $_SESSION["id"];
                            $sql = "INSERT INTO tasks (task, userid) VALUES ('$task', '$userID')";
                            mysqli_query($conn, $sql);
                            echo "<div class=\"added\">";
                            echo "Task Added!";
                            echo "</div>";
                        } catch (mysqli_sql_exception) {
                            echo "<div class=\"redText\">";
                            echo "That task already exists!";
                            echo "</div>";
                        }
                    }
                    
                    
                }
                    $query = "SELECT * FROM tasks WHERE userid = $userID";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $taskID = $row['id'];
                        $taskName = $row['task'];
                        $completed = $row['completed'];

                        echo "<div class=\"listItems\" data-task-id=\"$taskID\">";
                        echo "<button class='delete_button' onclick='deleteTask($taskID)''> DELETE </button>&nbsp&nbsp&nbsp";
                        echo "<input type='checkbox' onchange='updateTaskStatus(this)' name='taskCheckbox' value='$taskID' " . ($completed ? "checked" : "") . ">&nbsp&nbsp&nbsp";
                        echo "<span>" . "   $taskName" . "</span>";


                        echo "</div>";
                    }
                    mysqli_close($conn);
                ?>
            </form>

        </div>
    </div>
</body>

</html>



<script type="text/JavaScript">

    function updateTaskStatus(checkbox) {
        var taskID = checkbox.value;
        var completed = checkbox.checked ? 1 : 0;

        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_task_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        // Define the data to send
        var data = 'taskID=' + encodeURIComponent(taskID) + '&completed=' + encodeURIComponent(completed);
        
        // Send the request
        xhr.send(data);
    }
</script>



<script type="text/JavaScript">

    function deleteTask(taskID) {
        console.log("Task ID: ", taskID);
        if (confirm("Are you sure you want to delete this task?")) {
            
            // Create an AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_task.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // Define the data to send
            var data = 'taskID=' + encodeURIComponent(taskID);

            // Set up the callback function to handle the server's response
            xhr.onload = function() {
                console.log("Response from server: ", xhr.responseText);

                
                if (xhr.status === 200) {
                    // Successful response from the server, update the UI
                    
                    var deletedTask = document.querySelector('[data-task-id="' + taskID + '"]');
                    if (deletedTask) {
                        deletedTask.remove();
                    }
                } else {
                    console.log("error");
                    // Handle errors if needed
                }
            };

            // Send the request
            xhr.send(data);
        }
    }
</script>