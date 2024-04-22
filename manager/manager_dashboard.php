<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch all tenants with their assigned flats
$sql = "SELECT t.*, f.flat_number, f.floor_number, f.size, f.rent
        FROM tenants t
        LEFT JOIN flats_rooms f ON t.flat_id = f.id";
$result = mysqli_query($conn, $sql);

// Process flat assignment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenant_id = $_POST['tenant_id'];
    $flat_id = $_POST['flat_id'];

    // Fetch the currently assigned flat of the tenant
    $current_flat_sql = "SELECT flat_id FROM tenants WHERE id='$tenant_id'";
    $current_flat_result = mysqli_query($conn, $current_flat_sql);
    $current_flat_row = mysqli_fetch_assoc($current_flat_result);
    $current_flat_id = $current_flat_row['flat_id'];

    // Update the status of the previously assigned flat to "not booked"
    $update_previous_flat_sql = "UPDATE flats_rooms SET status='not booked' WHERE id='$current_flat_id'";
    mysqli_query($conn, $update_previous_flat_sql);

    // Update tenant's assigned flat in the database
    $update_tenant_sql = "UPDATE tenants SET flat_id='$flat_id' WHERE id='$tenant_id'";
    mysqli_query($conn, $update_tenant_sql);

    // Update the status of the newly assigned flat to "booked"
    $update_new_flat_sql = "UPDATE flats_rooms SET status='booked' WHERE id='$flat_id'";
    mysqli_query($conn, $update_new_flat_sql);

    header("Location: manager_dashboard.php");
    exit();
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
    <!-- Manage Tenants Section -->
    <div class="container mt-5">
        <h1>Manage Tenants</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact Information</th>
                    <th>Assigned Flat</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>Flat " . $row['flat_number'] . " (Floor " . $row['floor_number'] . ") - Size: " . $row['size'] . " sqft - Rent: $" . $row['rent'] . "</td>";
                        echo "<td>";
                        // Form for updating assigned flat
                        echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                        echo "<input type='hidden' name='tenant_id' value='" . $row['id'] . "'>";
                        echo "<select name='flat_id' class='form-control'>";
                        // Fetch all flats with status "not booked"
                        $flats_sql = "SELECT * FROM flats_rooms WHERE status='not booked'";
                        $flats_result = mysqli_query($conn, $flats_sql);
                        if (mysqli_num_rows($flats_result) > 0) {
                            while ($flat_row = mysqli_fetch_assoc($flats_result)) {
                                // Check if flat is assigned to this tenant
                                $selected = ($row['flat_id'] == $flat_row['id']) ? 'selected' : '';
                                echo "<option value='" . $flat_row['id'] . "' $selected>Flat " . $flat_row['flat_number'] . " (Floor " . $flat_row['floor_number'] . ") - Size: " . $flat_row['size'] . " sqft - Rent: $" . $flat_row['rent'] . "</option>";
                            }
                        }
                        echo "</select>";
                        echo "<button type='submit' class='btn btn-primary mt-2'>Update</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No tenants found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
