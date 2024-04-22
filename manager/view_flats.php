<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Query to retrieve all flats
$sql = "SELECT * FROM flats_rooms";
$result = mysqli_query($conn, $sql);

// Handling delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_sql = "DELETE FROM flats_rooms WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: view_flats.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <!-- Manager Dashboard Content -->
    <div class="container">
        <h1>Welcome to Manager Dashboard</h1>
        <!-- Display all flats -->
        <h2>Manage Flats</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Flat Picture</th>
                    <th>Flat/Room Number</th>
                    <th>Floor Number</th>
                    <th>Size</th>
                    <th>Rent</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="<?php echo $row['flat_picture']; ?>" alt="Flat Picture" style="width: 100px;"></td>
                        <td><?php echo $row['flat_number']; ?></td>
                        <td><?php echo $row['floor_number']; ?></td>
                        <td><?php echo $row['size']; ?></td>
                        <td><?php echo $row['rent']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a href="edit_flat.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this flat?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
