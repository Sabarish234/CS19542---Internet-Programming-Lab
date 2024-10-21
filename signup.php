<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db1"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$email = $password = $signup_err = "";
$signup_success = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["password"]));

  
    if (empty($email) || empty($password)) {
        $signup_err = "Please enter both email and password.";
    } else {
    
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);

       
        if ($stmt->execute()) {
           
            $signup_success = "Account created successfully! You can now log in.";
            
            
        } else {
            $signup_err = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body id="signup">

<nav class="navbar navbar-expand-md navbar-dark bg-dark" aria-label="Fourth navbar example">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
        <img src="./assets/red-caduceus-medical-symbol-srsywsh5dweqct8w.webp" width="30" height="30" class="d-inline-block align-top" alt="">
            Life Line
        </a>
    </div>
</nav>

<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center">
        <h1 class="display-5 fw-bold lh-1 text-body-emphasis mb-3">Create Your Account</h1>
        <center><img src="./assets/Blood donation.jpg" class="loginimg" height="300"></center>
        <p class="fs-4">Join us to make a difference in people's lives!</p>
      </div>
      <div class="col-md-10 mx-auto col-lg-5">
        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="signup.php" method="POST">
          <?php if (!empty($signup_err)): ?>
            <div class="alert alert-danger">
              <?php echo $signup_err; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($signup_success)): ?>
            <div class="alert alert-success">
              <?php echo $signup_success; ?>
            </div>
          <?php endif; ?>

          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
            <label for="floatingInput">Username</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
          </div>
          <button class="w-100 btn btn-lg btn-danger" type="submit">Sign Up</button>
          <hr class="my-4">
          <small class="text-body-secondary">By clicking Sign Up, you agree to the terms of use.</small>
          
          <p class="mt-3 text-center"><a href="login.php">Move to Login</a>.</p>
        </form>
      </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
