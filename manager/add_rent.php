<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $tenant_id = $_POST['tenant_id'];
    $payment_date = $_POST['payment_date'];
    $amount = $_POST['amount'];
    $month = $_POST['month'];

    // Insert payment details into Rent Payments Table
    $sql = "INSERT INTO rent_payments (tenant_id, payment_date, amount, month) 
            VALUES ('$tenant_id', '$payment_date', '$amount', '$month')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Rent collected successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
// Query to fetch all tenants
$sql = "SELECT id, username FROM tenants";
$result = mysqli_query($conn, $sql);

// Array to store fetched tenants
$tenants = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tenants[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tenants</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Collect Monthly Rent</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group">
            <label for="tenant_id">Tenant:</label>
            <select class="form-control" id="tenant_id" name="tenant_id" required>
                <?php foreach ($tenants as $tenant) { ?>
                    <option value="<?php echo $tenant['id']; ?>"><?php echo $tenant['username']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date:</label>
            <input type="date" class="form-control" id="payment_date" name="payment_date" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="month">For Month:</label>
            <input type="month" class="form-control" id="month" name="month" required>
        </div>
        <button type="submit" class="btn btn-primary">Collect Rent</button>
        <a class="btn btn-outline-dark" href="view_rents.php">View Rents</a>
    </form>
</div>

</body>
</html>
