<?php
include('config.php');
session_start();

if (!isset($_SESSION['owner_id'])) {
    header("Location: login_owner.php");
    exit();
}

$owner_id = $_SESSION['owner_id'];

// Fetch flats owned by the owner
$sql_flats = "SELECT * FROM flats_rooms WHERE id IN (SELECT Flat_id FROM owners WHERE ID = $owner_id)";
$result_flats = mysqli_query($conn, $sql_flats);

$flats = array();
while ($row_flats = mysqli_fetch_assoc($result_flats)) {
    $flats[$row_flats['id']] = $row_flats;
}

// Fetch rent payments for each flat and calculate total rent amount
$rent_payments = array();
foreach ($flats as $flat_id => $flat) {
    $sql_payments = "SELECT rp.* FROM rent_payments rp
                     INNER JOIN tenants t ON rp.tenant_id = t.id
                     WHERE t.flat_id = $flat_id";
    $result_payments = mysqli_query($conn, $sql_payments);

    $total_rent = 0;
    $payments = array();
    while ($row_payments = mysqli_fetch_assoc($result_payments)) {
        $total_rent += $row_payments['amount'];
        $payments[] = $row_payments;
    }

    $rent_payments[$flat_id] = [
        'total_rent' => $total_rent,
        'payments' => $payments
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Rent Payments</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>View Rent Payments</h2>
    <?php foreach ($flats as $flat_id => $flat) { ?>
        <div class="card mb-4">
            <div class="card-header">
                Flat Number: <?php echo $flat['flat_number']; ?>
            </div>
            <div class="card-body">
                <h5 class="card-title">Rent Payments</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>For Month</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rent_payments[$flat_id]['payments'] as $payment) { ?>
                        <tr>
                            <td><?php echo $payment['payment_date']; ?></td>
                            <td><?php echo $payment['amount']; ?></td>
                            <td><?php echo $payment['month']; ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3" style="text-align: right;">Total Rent: <?php echo $rent_payments[$flat_id]['total_rent']; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
