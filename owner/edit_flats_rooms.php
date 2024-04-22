<?php
include('config.php');
session_start();

// Check if user is not logged in or not an owner, redirect to login page
if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'owner') {
    header("Location: login.php");
    exit();
}

// Check if ID parameter is set in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_flats.php");
    exit();
}

$id = $_GET['id'];

// Fetch flat or room details from the database
$sql = "SELECT * FROM flats_rooms WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // No flat or room found with the specified ID
    header("Location: view_flats.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Initialize variables with current values
$flat_number = $row['flat_number'];
$room_number = $row['room_number'];
$flat_type = $row['flat_type'];
$room_type = $row['room_type'];
$size = $row['size'];
$rent = $row['rent'];
$status = $row['status'];
$tenant_id = $row['tenant_id'];

// Fetch tenants' data from the database
$sql_tenants = "SELECT id, username FROM users WHERE user_type = 'tenant'";
$result_tenants = mysqli_query($conn, $sql_tenants);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $flat_number = $_POST['flat_number'];
    $room_number = $_POST['room_number'];
    $flat_type = $_POST['flat_type'];
    $room_type = $_POST['room_type'];
    $size = $_POST['size'];
    $rent = $_POST['rent'];
    $status = $_POST['status'];
    $tenant_id = $_POST['tenant_id'];

    // Update flat or room details in the database
    $sql_update = "UPDATE flats_rooms SET flat_number = ?, room_number = ?, flat_type = ?, room_type = ?, size = ?, rent = ?, status = ?, tenant_id = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "iissdissi", $flat_number, $room_number, $flat_type, $room_type, $size, $rent, $status, $tenant_id, $id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Redirect to view_flats.php after updating
    header("Location: view_flats.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Flat or Room</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Edit Flat or Room</h2>
        <form method="post">
            <div class="form-group">
                <label>Flat Number</label>
                <input type="number" name="flat_number" class="form-control" value="<?php echo $flat_number; ?>">
            </div>
            <div class="form-group">
                <label>Room Number</label>
                <input type="number" name="room_number" class="form-control" value="<?php echo $room_number; ?>">
            </div>
            <div class="form-group">
                <label>Flat Type</label>
                <input type="text" name="flat_type" class="form-control" value="<?php echo $flat_type; ?>">
            </div>
            <div class="form-group">
                <label>Room Type</label>
                <input type="text" name="room_type" class="form-control" value="<?php echo $room_type; ?>">
            </div>
            <div class="form-group">
                <label>Size</label>
                <input type="number" name="size" class="form-control" value="<?php echo $size; ?>">
            </div>
            <div class="form-group">
                <label>Rent</label>
                <input type="number" name="rent" class="form-control" value="<?php echo $rent; ?>">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="booked" <?php if ($status == 'booked') echo 'selected'; ?>>Booked</option>
                    <option value="not booked" <?php if ($status == 'not booked') echo 'selected'; ?>>Not Booked</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tenant</label>
                <select name="tenant_id" class="form-control">
                    <option value="">Select Tenant</option>
                    <?php while ($row_tenant = mysqli_fetch_assoc($result_tenants)) : ?>
                        <option value="<?php echo $row_tenant['id']; ?>" <?php if ($row_tenant['id'] == $tenant_id) echo 'selected'; ?>><?php echo $row_tenant['username']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="view_flats.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
