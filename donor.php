<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "db1"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = htmlspecialchars($_POST['name']);
    $blood_type = htmlspecialchars($_POST['blood_type']);
    $gender = htmlspecialchars($_POST['gender']);
    $age = htmlspecialchars($_POST['age']);
    $contact_info = htmlspecialchars($_POST['contact_info']);

   
    $stmt = $conn->prepare("INSERT INTO donors (name, blood_type, gender, age, contact_info) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $blood_type, $gender, $age, $contact_info);

    if ($stmt->execute()) {
        $message = "Donor information has been submitted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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




<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-danger text-white">
                    <h2>Donor Registration</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="donor.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Donor Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="blood_type" class="form-label">Blood Type</label>
                            <select class="form-select" id="blood_type" name="blood_type" required>
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

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" min="18" required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_info" class="form-label">Contact Information</label>
                            <input type="text" class="form-control" id="contact_info" name="contact_info" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">Submit Donor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close(); 
?>
