<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if rent ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: view_rents.php");
    exit();
}

// Get rent ID from the URL
$rent_id = $_GET['id'];

// Fetch rent details from the database
$sql = "SELECT rp.id, rp.tenant_id, t.username AS tenant_name, rp.payment_date, rp.amount, rp.month 
        FROM rent_payments rp
        INNER JOIN tenants t ON rp.tenant_id = t.id
        WHERE rp.id = $rent_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $rent = mysqli_fetch_assoc($result);
} else {
    echo "Rent not found.";
    exit();
}

// Update rent details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_date = $_POST['payment_date'];
    $amount = $_POST['amount'];
    $month = $_POST['month'];

    $sql_update = "UPDATE rent_payments 
                   SET payment_date = '$payment_date', amount = '$amount', month = '$month'
                   WHERE id = $rent_id";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Rent details updated successfully');</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Rent</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Edit Rent</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $rent_id; ?>" method="post">
        <div class="form-group">
            <label for="tenant">Tenant:</label>
            <input type="text" class="form-control" id="tenant" value="<?php echo $rent['tenant_name']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date:</label>
            <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo $rent['payment_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $rent['amount']; ?>" required>
        </div>
        <div class="form-group">
            <label for="month">For Month:</label>
            <input type="month" class="form-control" id="month" name="month" value="<?php echo $rent['month']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Rent</button>
    </form>
</div>

</body>
</html>
