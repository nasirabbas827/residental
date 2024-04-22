<?php
include('config.php');
session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Define variables and initialize with empty values
$date = $description = $amount = $employee_id = "";
$date_err = $description_err = $amount_err = $employee_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate date
    if (empty(trim($_POST["date"]))) {
        $date_err = "Please enter the date.";
    } else {
        $date = trim($_POST["date"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter the description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate amount
    if (empty(trim($_POST["amount"]))) {
        $amount_err = "Please enter the amount.";
    } else {
        $amount = trim($_POST["amount"]);
    }

    // Validate employee ID
    if (empty(trim($_POST["employee_id"]))) {
        $employee_id_err = "Please select the employee.";
    } else {
        $employee_id = trim($_POST["employee_id"]);
    }

    // Check input errors before inserting into database
    if (empty($date_err) && empty($description_err) && empty($amount_err) && empty($employee_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO daily_expenditure (date, description, amount, employee_id) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssdi", $param_date, $param_description, $param_amount, $param_employee_id);

            // Set parameters
            $param_date = $date;
            $param_description = $description;
            $param_amount = $amount;
            $param_employee_id = $employee_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to view_expenditures.php after successful addition
                header("location: view_expenditures.php");
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
    <title>Add Daily Expenditure</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Add Daily Expenditure</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo $date; ?>">
            <span class="text-danger"><?php echo $date_err; ?></span>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>">
            <span class="text-danger"><?php echo $description_err; ?></span>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>">
            <span class="text-danger"><?php echo $amount_err; ?></span>
        </div>
        <div class="form-group">
            <label for="employee_id">Employee:</label>
            <select class="form-control" id="employee_id" name="employee_id">
                <option value="">Select Employee</option>
                <?php
                $sql_employees = "SELECT id, name FROM employees";
                $result_employees = mysqli_query($conn, $sql_employees);

                while ($row = mysqli_fetch_assoc($result_employees)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>
            <span class="text-danger"><?php echo $employee_id_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Add Expenditure</button>
        <a class="btn btn-outline-dark" href="view_expenditures.php">View Expendeture</a>
    </form>
</div>

</body>
</html>
