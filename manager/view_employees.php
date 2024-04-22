<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Delete employee if delete button is clicked
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM employees WHERE id = $delete_id";

    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('Employee deleted successfully');</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Query to fetch all employees
$sql = "SELECT * FROM employees";
$result = mysqli_query($conn, $sql);

// Array to store fetched employees
$employees = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }
}

// Calculate total salary
$total_salary = 0;
foreach ($employees as $employee) {
    $total_salary += $employee['salary'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Employees</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>View Employees</h2>
    <div class="mb-3">
        <strong>Total Salary: </strong><?php echo $total_salary; ?>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Contact Information</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($employees as $employee) { ?>
            <tr>
                <td><?php echo $employee['id']; ?></td>
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['role']; ?></td>
                <td><?php echo $employee['contact_information']; ?></td>
                <td><?php echo $employee['salary']; ?></td>
                <td>
                    <a href="edit_employee.php?id=<?php echo $employee['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="?delete_id=<?php echo $employee['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
