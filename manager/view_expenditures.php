<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Delete expenditure if delete button is clicked
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM daily_expenditure WHERE id = $delete_id";

    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('Expenditure deleted successfully');</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Query to fetch all expenditures with employee details
$sql = "SELECT de.*, e.name AS employee_name FROM daily_expenditure de
        INNER JOIN employees e ON de.employee_id = e.id";
$result = mysqli_query($conn, $sql);

// Array to store fetched expenditures
$expenditures = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $expenditures[] = $row;
    }
}

// Calculate total expenditure
$total_expenditure = 0;
foreach ($expenditures as $expenditure) {
    $total_expenditure += $expenditure['amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Expenditures</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>View Expenditures</h2>
    <div class="mb-3">
        <strong>Total Expenditure: </strong><?php echo $total_expenditure; ?>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Employee</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($expenditures as $expenditure) { ?>
            <tr>
                <td><?php echo $expenditure['id']; ?></td>
                <td><?php echo $expenditure['date']; ?></td>
                <td><?php echo $expenditure['description']; ?></td>
                <td><?php echo $expenditure['amount']; ?></td>
                <td><?php echo $expenditure['employee_name']; ?></td>
                <td>
                    <a href="edit_expenditure.php?id=<?php echo $expenditure['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="?delete_id=<?php echo $expenditure['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
