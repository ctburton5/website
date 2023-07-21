<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=4, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>

</head>

<body>
    <div class="main">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h1>Welcome to To-Do List</h1>
            <label>Username: </label>
            <input type="text" name="username"><br>
            <label>Password: </label>
            <input type="password" name="password"><br>
            <input type="submit" name="submit" value="Register"><br>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);


                if (empty($username)) {
                    echo "Please enter a username";
                } elseif (empty($password)) {
                    echo "Please enter a password";
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users (user, password) VALUES ('$username', '$hash')";

                    try {
                        mysqli_query($conn, $sql);
                        header("Location: login.php");
                    } catch (mysqli_sql_exception) {
                        echo "That username is taken";
                    }
                }
            }
            mysqli_close($conn);
            ?>

            <h3>Already have an account?</h3>
            <button class="button"><a href="login.php">Login</a></button>
        </form>

    </div>
</body>

</html>