<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if employee ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: view_employees.php");
    exit();
}

// Get employee ID from the URL
$employee_id = $_GET['id'];

// Fetch employee details from the database
$sql = "SELECT * FROM employees WHERE id = $employee_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $employee = mysqli_fetch_assoc($result);
} else {
    echo "Employee not found.";
    exit();
}

// Define variables and initialize with fetched values
$name = $employee['name'];
$role = $employee['role'];
$contact_information = $employee['contact_information'];
$salary = $employee['salary'];

// Update employee details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $contact_information = $_POST['contact_information'];
    $salary = $_POST['salary'];

    $sql_update = "UPDATE employees 
                   SET name = '$name', role = '$role', contact_information = '$contact_information', salary = '$salary'
                   WHERE id = $employee_id";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Employee details updated successfully');</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Edit Employee</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $employee_id; ?>" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" class="form-control" id="role" name="role" value="<?php echo $role; ?>">
        </div>
        <div class="form-group">
            <label for="contact_information">Contact Information:</label>
            <input type="text" class="form-control" id="contact_information" name="contact_information" value="<?php echo $contact_information; ?>">
        </div>
        <div class="form-group">
            <label for="salary">Salary:</label>
            <input type="number" class="form-control" id="salary" name="salary" value="<?php echo $salary; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Employee</button>
    </form>
</div>

</body>
</html>
