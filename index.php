<?php
include("database.php");

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
                    To-Do List
                </p>
                <h2>Add a task to the list:</h2>
                <input class="center-block" type="text" name="task"><br>
                <input class="center-submit" type="submit" name="submit" value="Add"><br>
                <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    //$_POST = array();
                    //unset($_SESSION['post_occurred']);

                    $task = filter_input(INPUT_POST, "task", FILTER_SANITIZE_SPECIAL_CHARS);

                    if (empty($task)) {
                        echo "<div class=\"redText\">";
                        echo "You tried to add an empty task!";
                        echo "</div>";
                    } else {

                        try {
                            $sql = "INSERT INTO List (task) VALUES ('$task')";
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
                    $query = "SELECT * FROM List";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $taskID = $row['id'];
                        $taskName = $row['task'];
                        $completed = $row['completed'];

                        echo "<div class=\"listItems\">";
                        echo "<br><br>";
                        echo "<input type='checkbox' onchange='updateTaskStatus(this)' name='taskCheckbox' value='$taskID' " . ($completed ? "checked" : "") . ">";
                        echo "<span>" . "   $taskName" . "</span>";
                        
                        echo "</div>";
                    }
                    mysqli_close($conn);
                }
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