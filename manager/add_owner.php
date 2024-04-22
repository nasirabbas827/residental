<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Define variables and initialize with empty values
$name = $contact_info = $email = $password = $flat_id = "";
$name_err = $contact_info_err = $email_err = $password_err = $flat_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter owner's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate contact information
    if (empty(trim($_POST["contact_info"]))) {
        $contact_info_err = "Please enter owner's contact information.";
    } else {
        $contact_info = trim($_POST["contact_info"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter owner's email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate flat ID
    if (empty(trim($_POST["flat_id"]))) {
        $flat_id_err = "Please enter flat ID.";
    } else {
        $flat_id = trim($_POST["flat_id"]);
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($contact_info_err) && empty($email_err) && empty($password_err) && empty($flat_id_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO owners (name, contact_info, email, password, flat_id) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_name, $param_contact_info, $param_email, $param_password, $param_flat_id);

            // Set parameters
            $param_name = $name;
            $param_contact_info = $contact_info;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
            $param_flat_id = $flat_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to manager dashboard after successful addition
                header("location: manager_dashboard.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Owner</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <!-- Add Owner Form -->
    <div class="container mb-5">
        <h1>Add Owner</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Information:</label>
                <input type="text" class="form-control" id="contact_info" name="contact_info" value="<?php echo $contact_info; ?>">
                <span class="text-danger"><?php echo $contact_info_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>">
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
    <label for="flat_id">Flat ID:</label>
    <select class="form-control" id="flat_id" name="flat_id">
        <?php
        // Fetch all flat numbers from the database
        $sql = "SELECT id, flat_number FROM flats_rooms";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($row['id'] == $flat_id) ? 'selected' : '';
                echo "<option value='" . $row['id'] . "' $selected>" . $row['flat_number'] . "</option>";
            }
        }
        ?>
    </select>
    <span class="text-danger"><?php echo $flat_id_err; ?></span>
</div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <a class="btn btn-outline-dark" href="view_owners.php">View Owners</a>

        </form>
    </div>
</body>
</html>
