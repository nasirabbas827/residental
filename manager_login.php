<?php
include('config.php');

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no errors, check credentials and log in manager
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM managers WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $id, $username, $stored_password);
            if (mysqli_stmt_fetch($stmt)) {
                if ($password === $stored_password) {
                    // Password is correct, start session and log in manager
                    session_start();
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    mysqli_stmt_close($stmt);

                    // Redirect to manager dashboard
                    header("location: manager/manager_dashboard.php");
                    exit();
                } else {
                    // Password is incorrect
                    $password_err = "The password you entered is incorrect.";
                }
            }
        } else {
            // Username not found in managers table
            $username_err = "No account found with that username.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manager Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
<?php
include('navbar.php');
?>
<div class="container mt-5">
    <h2 class="text-center">Manager Login</h2>
    <p class="text-center">Please fill in your credentials to log in.</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span><?php echo $password_err; ?></span>
        </div>
        <div class="form-group text-center">
            <input type="submit" value="Log in" class="btn btn-primary">
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
