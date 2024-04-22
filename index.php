<?php
include('config.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Residental Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .jumbotron {
            height: 550px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }

        .flat-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 20px;
        }

        .flat-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="jumbotron text-center">
    <h1 class="display-4">Residential Project</h1>
    <p class="lead">Explore a wide range of Flats and Rooms</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login Now</a>
</div>

<div class="container">
    <h2>All Flats</h2>
    <div class="row">
        <?php
        // Fetch all flats from the database
        $sql = "SELECT * FROM flats_rooms";
        $result = mysqli_query($conn, $sql);

        // Check if there are any flats
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-4">
                    <div class="flat-card">
                        <img src="./manager/<?php echo $row['flat_picture']; ?>" alt="Flat Image">
                        <h3>Flat Number: <?php echo $row['flat_number']; ?></h3>
                        <p>Floor Number: <?php echo $row['floor_number']; ?></p>
                        <p>Size: <?php echo $row['size']; ?> sqft</p>
                        <p>Rent: $<?php echo $row['rent']; ?></p>
                        <p>Status: <?php echo ucfirst($row['status']); ?></p>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No flats available.</p>";
        }
        ?>
    </div>
</div>

<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 Residential Project. All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
