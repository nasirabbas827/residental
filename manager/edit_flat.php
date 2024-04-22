<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if flat ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manager_dashboard.php");
    exit();
}

$flat_id = $_GET['id'];

// Fetch flat details from the database
$sql = "SELECT * FROM flats_rooms WHERE id = $flat_id";
$result = mysqli_query($conn, $sql);

// Check if flat exists
if (mysqli_num_rows($result) == 0) {
    echo "Flat not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

// Handling update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flat_number = $_POST['flat_number'];
    $floor_number = $_POST['floor_number'];
    $size = $_POST['size'];
    $rent = $_POST['rent'];
    $status = $_POST['status'];

    $update_sql = "UPDATE flats_rooms SET flat_number='$flat_number', floor_number='$floor_number', size='$size', rent='$rent', status='$status' WHERE id = $flat_id";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: view_flats.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Flat</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <!-- Edit Flat Form -->
    <div class="container mb-5">
        <h1>Edit Flat</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $flat_id;?>">
            <div class="form-group">
                <label for="flat_number">Flat/Room Number:</label>
                <input type="number" class="form-control" id="flat_number" name="flat_number" value="<?php echo $row['flat_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="floor_number">Floor Number:</label>
                <input type="numnber" class="form-control" id="floor_number" name="floor_number" value="<?php echo $row['floor_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="number" class="form-control" id="size" name="size" value="<?php echo $row['size']; ?>" required>
            </div>
            <div class="form-group">
                <label for="rent">Rent:</label>
                <input type="number" class="form-control" id="rent" name="rent" value="<?php echo $row['rent']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="booked" <?php if ($row['status'] == 'booked') echo 'selected'; ?>>Booked</option>
                    <option value="not booked" <?php if ($row['status'] == 'not booked') echo 'selected'; ?>>Not Booked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
