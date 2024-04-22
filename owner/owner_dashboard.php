<?php
include('config.php');
session_start();

if (!isset($_SESSION['owner_id'])) {
    header("Location: login_owner.php");
    exit();
}

$owner_id = $_SESSION['owner_id'];

// Fetch flat details for the owner
$sql = "SELECT fr.* FROM flats_rooms fr
        INNER JOIN owners o ON fr.id = o.Flat_id
        WHERE o.ID = $owner_id";
$result = mysqli_query($conn, $sql);

$flats = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $flats[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Owner Dashboard</h2>
    <div class="row">
        <?php foreach ($flats as $flat) { ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="../manager/<?php echo $flat['flat_picture']; ?>" class="card-img-top" alt="Flat Picture">
                    <div class="card-body">
                        <h5 class="card-title">Flat Number: <?php echo $flat['flat_number']; ?></h5>
                        <p class="card-text">Floor Number: <?php echo $flat['floor_number']; ?></p>
                        <p class="card-text">Size: <?php echo $flat['size']; ?></p>
                        <p class="card-text">Rent: <?php echo $flat['rent']; ?></p>
                        <p class="card-text">Status: <?php echo $flat['status']; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
