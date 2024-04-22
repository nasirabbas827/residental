<?php
include('config.php');
session_start();

// Check if user is not logged in or not an owner, redirect to login page
if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'owner') {
    header("Location: login.php");
    exit();
}

// Define variables and initialize with empty values
$flat_number = $room_number = $flat_type = $room_type = $size = $rent = $status = $tenant_id = "";
$flat_number_err = $room_number_err = $flat_type_err = $room_type_err = $size_err = $rent_err = $status_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate flat number
    if (empty(trim($_POST["flat_number"]))) {
        $flat_number_err = "Please enter the flat number.";
    } else {
        $flat_number = trim($_POST["flat_number"]);
    }

    // Validate room number
    if (empty(trim($_POST["room_number"]))) {
        $room_number_err = "Please enter the room number.";
    } else {
        $room_number = trim($_POST["room_number"]);
    }

    // Validate flat type
    if (empty(trim($_POST["flat_type"]))) {
        $flat_type_err = "Please enter the flat type.";
    } else {
        $flat_type = trim($_POST["flat_type"]);
    }

    // Validate room type
    if (empty(trim($_POST["room_type"]))) {
        $room_type_err = "Please enter the room type.";
    } else {
        $room_type = trim($_POST["room_type"]);
    }

    // Validate size
    if (empty(trim($_POST["size"]))) {
        $size_err = "Please enter the size.";
    } else {
        $size = trim($_POST["size"]);
    }

    // Validate rent
    if (empty(trim($_POST["rent"]))) {
        $rent_err = "Please enter the rent.";
    } else {
        $rent = trim($_POST["rent"]);
    }

    // Validate status
    if (empty($_POST["status"])) {
        $status_err = "Please select the status.";
    } else {
        $status = $_POST["status"];
    }

    // Validate tenant ID if status is booked
    if ($status == 'booked') {
        if (empty($_POST["tenant_id"])) {
            $status_err = "Please select a tenant.";
        } else {
            $tenant_id = $_POST["tenant_id"];
        }
    }

    // Check input errors before inserting into database
    if (empty($flat_number_err) && empty($room_number_err) && empty($flat_type_err) && empty($room_type_err) && empty($size_err) && empty($rent_err) && empty($status_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO flats_rooms (flat_number, room_number, flat_type, room_type, size, rent, status, tenant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iisssdss", $param_flat_number, $param_room_number, $param_flat_type, $param_room_type, $param_size, $param_rent, $param_status, $param_tenant_id);

            // Set parameters
            $param_flat_number = $flat_number;
            $param_room_number = $room_number;
            $param_flat_type = $flat_type;
            $param_room_type = $room_type;
            $param_size = $size;
            $param_rent = $rent;
            $param_status = $status;
            $param_tenant_id = ($status == 'booked') ? $tenant_id : null;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to owner dashboard after successful creation
                header("location: view_flats.php");
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

// Fetch all tenants
$sql_tenants = "SELECT id, username, email FROM users WHERE user_type = 'tenant'";
$result_tenants = mysqli_query($conn, $sql_tenants);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flats and Rooms</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Add Flats and Rooms</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Flat Number</label>
                <input type="number" name="flat_number" class="form-control <?php echo (!empty($flat_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $flat_number; ?>">
                <span class="invalid-feedback"><?php echo $flat_number_err; ?></span>
            </div>
            <div class="form-group">
                <label>Room Number</label>
                <input type="number" name="room_number" class="form-control <?php echo (!empty($room_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $room_number; ?>">
                <span class="invalid-feedback"><?php echo $room_number_err; ?></span>
            </div>
            <div class="form-group">
                <label>Flat Type</label>
                <input type="text" name="flat_type" class="form-control <?php echo (!empty($flat_type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $flat_type; ?>">
                <span class="invalid-feedback"><?php echo $flat_type_err; ?></span>
            </div>
            <div class="form-group">
                <label>Room Type</label>
                <input type="text" name="room_type" class="form-control <?php echo (!empty($room_type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $room_type; ?>">
                <span class="invalid-feedback"><?php echo $room_type_err; ?></span>
            </div>
            <div class="form-group">
                <label>Size</label>
                <input type="number" name="size" class="form-control <?php echo (!empty($size_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $size; ?>">
                <span class="invalid-feedback"><?php echo $size_err; ?></span>
            </div>
            <div class="form-group">
                <label>Rent</label>
                <input type="number" name="rent" class="form-control <?php echo (!empty($rent_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rent; ?>">
                <span class="invalid-feedback"><?php echo $rent_err; ?></span>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>">
                    <option value="" disabled selected>Select Status</option>
                    <option value="booked">Booked</option>
                    <option value="not_booked">Not Booked</option>
                </select>
                <span class="invalid-feedback"><?php echo $status_err; ?></span>
            </div>
            <div class="form-group" id="tenant_select">
                <label>Select Tenant</label>
                <select name="tenant_id" class="form-control">
                    <option value="">Select Tenant</option>
                    <?php while ($row = mysqli_fetch_assoc($result_tenants)) : ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-dark" href="view_flats.php">View Flats</a>
            </div>
        </form>
    </div>
</body>
</html>
