<?php
    session_start();
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Please Login</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label>Username: </label>
        <input type="text" name="username"><br>
        <label>Password: </label>
        <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Login"><br>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

                if (empty($username)) {
                    echo "Please enter your username";
                } elseif (empty($password)) {
                    echo "Please enter your password";
                } else {
                    //$hash = password_hash($password, PASSWORD_DEFAULT);


                    $query = "SELECT id, user, password FROM users WHERE user = '$username'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        $userID = $row["id"];
                        $hash = $row["password"];

                        // Verify the password
                        if (password_verify($password, $hash)) {

                            // Password is correct, start the session and redirect to dashboard
                            $_SESSION["user"] = $username;
                            $_SESSION["id"] = $userID;
                            echo "id for the user is $userID";
                            echo "<br>user is $username";
                           
                            
                            header("Location: index.php");
                            exit();
                        } else {
                            // Incorrect password
                            $error = "Invalid password.";
                        }
                    } else {
                        // User does not exist
                        $error = "User not found.";
                    }
                }
            }
            mysqli_close($conn);
        ?>

    </form>

</body>

</html>