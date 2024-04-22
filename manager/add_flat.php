<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id']) ) {
    header("Location: ../login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Extract data from the form
    $flat_number = $_POST['flat_number'];
    $floor_number = $_POST['floor_number'];
    $size = $_POST['size'];
    $rent = $_POST['rent'];
    $status = $_POST['status'];

    // File upload handling
    $target_directory = "flat_pictures/"; // Directory where files will be uploaded
    $target_file = $target_directory . basename($_FILES["flat_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["flat_picture"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["flat_picture"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["flat_picture"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["flat_picture"]["name"]). " has been uploaded.";
            
            // Insert data into the database
            $flat_picture = $target_file;
            $sql = "INSERT INTO flats_rooms (flat_picture, flat_number, floor_number, size, rent, status) VALUES ('$flat_picture', '$flat_number', '$floor_number', '$size', '$rent', '$status')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                header("Location: view_flats.php");
                exit();
            } else {
                // Handle error if insertion fails
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
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
    <div class="container mb-5">
        <!-- Add your dashboard content here -->
        <h2>Add Flat</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="flat_picture">Flat Picture:</label>
                <input type="file" class="form-control-file" id="flat_picture" name="flat_picture" required>
            </div>
            <div class="form-group">
                <label for="flat_number">Flat/Room Number:</label>
                <input type="number" class="form-control" id="flat_number" name="flat_number" required>
            </div>
            <div class="form-group">
                <label for="floor_number">Floor Number:</label>
                <input type="number" class="form-control" id="floor_number" name="floor_number" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="number" class="form-control" id="size" name="size" required>
            </div>
            <div class="form-group">
                <label for="rent">Rent:</label>
                <input type="number" class="form-control" id="rent" name="rent" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="booked">Booked</option>
                    <option value="not booked">Not Booked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a class="btn btn-outline-dark" href="view_flats.php">View Flats</a>
        </form>
    </div>
</body>
</html>
