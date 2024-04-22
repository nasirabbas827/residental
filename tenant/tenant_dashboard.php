<?php
include('config.php');

session_start();

// Check if user is not logged in or not a tenant, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch details of the flat assigned to the tenant
$tenant_id = $_SESSION['id'];

$sql_flat_details = "SELECT fr.*, t.username 
                     FROM flats_rooms fr 
                     INNER JOIN tenants t ON fr.id = t.flat_id 
                     WHERE t.id = $tenant_id";
$result_flat_details = mysqli_query($conn, $sql_flat_details);

$flat_details = mysqli_fetch_assoc($result_flat_details);

// Fetch rent payments history for the tenant
$sql_rent_payments = "SELECT * FROM rent_payments WHERE tenant_id = $tenant_id";
$result_rent_payments = mysqli_query($conn, $sql_rent_payments);

$rent_payments = array();
while ($row_rent_payment = mysqli_fetch_assoc($result_rent_payments)) {
    $rent_payments[] = $row_rent_payment;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tenant Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <!-- Tenant Dashboard Content -->
    <div class="container">
        <h1>Welcome to Tenant Dashboard</h1>
        <h2>Assigned Flat Details</h2>
        <div>
            <p><strong>Flat Number:</strong> <?php echo $flat_details['flat_number']; ?></p>
            <p><strong>Floor Number:</strong> <?php echo $flat_details['floor_number']; ?></p>
            <p><strong>Size:</strong> <?php echo $flat_details['size']; ?></p>
            <p><strong>Rent:</strong> <?php echo $flat_details['rent']; ?></p>
            <img src="../manager/<?php echo $flat_details['flat_picture']; ?>" alt="Flat Picture">
        </div>

        <h2>Rent Payments History</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>For Month</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rent_payments as $payment) { ?>
                    <tr>
                        <td><?php echo $payment['payment_date']; ?></td>
                        <td><?php echo $payment['amount']; ?></td>
                        <td><?php echo $payment['month']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
