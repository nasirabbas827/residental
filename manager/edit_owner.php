<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Initialize variables
$owner_id = $name = $contact_information = $email = "";
$name_err = $contact_information_err = $email_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter owner's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate contact information
    if (empty(trim($_POST["contact_information"]))) {
        $contact_information_err = "Please enter contact information.";
    } else {
        $contact_information = trim($_POST["contact_information"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check input errors before updating the database
    if (empty($name_err) && empty($contact_information_err) && empty($email_err)) {
        // Prepare an update statement
        $sql = "UPDATE owners SET Name=?, Contact_Info=?, Email=? WHERE ID=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_contact_information, $param_email, $param_owner_id);

            // Set parameters
            $param_name = $name;
            $param_contact_information = $contact_information;
            $param_email = $email;
            $param_owner_id = $_GET['id'];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to manager dashboard after successful update
                header("Location: view_owners.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Retrieve owner details from database
    $sql = "SELECT Name, Contact_Info, Email FROM owners WHERE ID = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_owner_id);

        // Set parameters
        $param_owner_id = $_GET["id"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            // Check if owner exists
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $name, $contact_information, $email);
                mysqli_stmt_fetch($stmt);
            } else {
                // Redirect to error page if owner ID is not found
                header("Location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Owner</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <!-- Edit Owner Form -->
    <div class="container mt-5">
        <h1>Edit Owner</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $_GET['id']; ?>" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="contact_information">Contact Information:</label>
                <input type="text" class="form-control" id="contact_information" name="contact_information" value="<?php echo $contact_information; ?>">
                <span class="text-danger"><?php echo $contact_information_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="view_owners.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>
