<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Define variables and initialize with empty values
$name = $role = $contact_information = $salary = "";
$name_err = $role_err = $contact_information_err = $salary_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the employee's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please enter the employee's role.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Validate contact information
    if (empty(trim($_POST["contact_information"]))) {
        $contact_information_err = "Please enter the employee's contact information.";
    } else {
        $contact_information = trim($_POST["contact_information"]);
    }

    // Validate salary
    if (empty(trim($_POST["salary"]))) {
        $salary_err = "Please enter the employee's salary.";
    } else {
        $salary = trim($_POST["salary"]);
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($role_err) && empty($contact_information_err) && empty($salary_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, role, contact_information, salary) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssd", $param_name, $param_role, $param_contact_information, $param_salary);

            // Set parameters
            $param_name = $name;
            $param_role = $role;
            $param_contact_information = $contact_information;
            $param_salary = $salary;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to view_employees.php after successful addition
                header("location: view_employees.php");
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
    <title>Add Employee</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Add Employee</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
            <span class="text-danger"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" class="form-control" id="role" name="role" value="<?php echo $role; ?>">
            <span class="text-danger"><?php echo $role_err; ?></span>
        </div>
        <div class="form-group">
            <label for="contact_information">Contact Information:</label>
            <input type="text" class="form-control" id="contact_information" name="contact_information" value="<?php echo $contact_information; ?>">
            <span class="text-danger"><?php echo $contact_information_err; ?></span>
        </div>
        <div class="form-group">
            <label for="salary">Salary:</label>
            <input type="number" class="form-control" id="salary" name="salary" value="<?php echo $salary; ?>">
            <span class="text-danger"><?php echo $salary_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Add Employee</button>
        <a class="btn btn-outline-dark" href="view_employees.php">View Employees</a>
    </form>
</div>

</body>
</html>
