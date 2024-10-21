<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db1"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$blood_type = "";
$message = "";

// Handle booking request
if (isset($_GET['book'])) {
    $donor_id = $_GET['book'];
    
    // Update donor status to 'booked'
    $stmt = $conn->prepare("UPDATE donors SET status = 'booked' WHERE id = ?");
    $stmt->bind_param("i", $donor_id);
    
    if ($stmt->execute()) {
        $message = "Donor has been booked successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// If the form is submitted, filter by blood type
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blood_type = htmlspecialchars($_POST['blood_type']);
    
    // Fetch donors based on blood type
    $stmt = $conn->prepare("SELECT * FROM donors WHERE blood_type = ?");
    $stmt->bind_param("s", $blood_type);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $donors = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "Error: " . $stmt->error;
    }
} else {
    $donors = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifeline - Donor Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
   
<nav class="navbar navbar-expand-md navbar-dark bg-dark" aria-label="Fourth navbar example">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
    	<img src="./assets/red-caduceus-medical-symbol-srsywsh5dweqct8w.webp" width="30" height="30" class="d-inline-block align-top" alt="">
    		Life Line
  		</a>
      <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbarmenu">
      <div class="navbar-collapse collapse" id="navbarsExample04" style="">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="landing.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="request.php">Request</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="donor.php">Donor</a>
          </li>
          <!--<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>-->
        </ul>
      </div>
      </div>
    </div>
  </nav>
  

<div class="container mt-4">
    <?php if ($message): ?>
        <div class="alert alert-success">
            <h2><?php echo $message; ?></h2>
        </div>
    <?php endif; ?>

    <h3>Search Donors by Blood Type</h3>
    <form action="request.php" method="POST" class="mb-4">
        <div class="form-group">
            <label for="blood-type">Blood Type:</label>
            <select id="blood-type" name="blood_type" class="form-control" required>
                <option value="">Select Blood Type</option>
                <option value="A+ve">A+</option>
                <option value="A-ve">A-</option>
                <option value="B+ve">B+</option>
                <option value="B-ve">B-</option>
                <option value="AB+ve">AB+</option>
                <option value="AB-ve">AB-</option>
                <option value="O+ve">O+</option>
                <option value="O-ve">O-</option>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Search Donors</button>
    </form>

    <h3>List of Donors with Blood Type: <?php echo $blood_type; ?></h3>
    <div class="row">
        <?php if (!empty($donors)): ?>
            <?php foreach($donors as $row): ?>
                <div class="col-md-4 mb-4">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['blood_type']; ?></h6>
                            <p class="card-text">Gender: <?php echo $row['gender']; ?></p>
                            <p class="card-text">Age: <?php echo $row['age']; ?></p>
                            <p class="card-text">Contact Info: <?php echo $row['contact_info']; ?></p>

                            <?php if ($row['status'] === 'available'): ?>
                                <a href="?book=<?php echo $row['id']; ?>" class="btn btn-success">Book</a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Booked</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    <strong>No donors found for blood type: <?php echo $blood_type; ?></strong>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $conn->close(); ?>

</body>
</html>
