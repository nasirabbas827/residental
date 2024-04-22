<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if expenditure ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: view_expenditures.php");
    exit();
}

// Get expenditure ID from the URL
$expenditure_id = $_GET['id'];

// Fetch expenditure details from the database
$sql = "SELECT * FROM daily_expenditure WHERE id = $expenditure_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $expenditure = mysqli_fetch_assoc($result);
} else {
    echo "Expenditure not found.";
    exit();
}

// Define variables and initialize with fetched values
$date = $expenditure['date'];
$description = $expenditure['description'];
$amount = $expenditure['amount'];
$employee_id = $expenditure['employee_id'];

// Update expenditure details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $employee_id = $_POST['employee_id'];

    $sql_update = "UPDATE daily_expenditure 
                   SET date = '$date', description = '$description', amount = $amount, employee_id = $employee_id
                   WHERE id = $expenditure_id";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Expenditure details updated successfully');</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expenditure</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Edit Expenditure</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $expenditure_id; ?>" method="post">
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo $date; ?>">
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>">
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>">
        </div>
        <div class="form-group">
            <label for="employee_id">Employee:</label>
            <select class="form-control" id="employee_id" name="employee_id">
                <?php
                $sql_employees = "SELECT id, name FROM employees";
                $result_employees = mysqli_query($conn, $sql_employees);

                while ($row = mysqli_fetch_assoc($result_employees)) {
                    $selected = ($row['id'] == $employee_id) ? "selected" : "";
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Expenditure</button>
    </form>
</div>

</body>
</html>
