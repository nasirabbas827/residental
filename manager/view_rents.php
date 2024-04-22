<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Delete rent if delete button is clicked
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM rent_payments WHERE id = $delete_id";

    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('Rent deleted successfully');</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Query to fetch all rents
$sql = "SELECT rp.id, t.username AS tenant_name, rp.payment_date, rp.amount, rp.month 
        FROM rent_payments rp
        INNER JOIN tenants t ON rp.tenant_id = t.id";
$result = mysqli_query($conn, $sql);

// Array to store fetched rents
$rents = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rents[] = $row;
    }
}

// Calculate total rent amount
$total_rent_amount = 0;
foreach ($rents as $rent) {
    $total_rent_amount += $rent['amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Rents</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>View Rents</h2>
    <div class="mb-3">
        <strong>Total Rent Amount: </strong><?php echo $total_rent_amount; ?>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Tenant</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Month</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rents as $rent) { ?>
            <tr>
                <td><?php echo $rent['tenant_name']; ?></td>
                <td><?php echo $rent['payment_date']; ?></td>
                <td><?php echo $rent['amount']; ?></td>
                <td><?php echo $rent['month']; ?></td>
                <td>
                    <a href="edit_rent.php?id=<?php echo $rent['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="?delete_id=<?php echo $rent['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
