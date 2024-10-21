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


$username = $password = $login_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    if (empty($username) || empty($password)) {
        $login_err = "Please enter both email and password.";
    } else {
  
        if ($username === "admin" && $password === "admin") {
          
            $_SESSION["loggedin"] = true;
            $_SESSION["role"] = "admin";

            header("location: admin.php");
            exit();
        } else {
         
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $password);

           
            $stmt->execute();
            $stmt->store_result();

        
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $username, $password);
                $stmt->fetch();

                
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;

              
                header("location: landing.html");
                exit();
            } else {
                $login_err = "Invalid email or password.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body id="login">

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
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
            <h1 class="display-5 fw-bold lh-1 text-body-emphasis mb-3">Welcome to Life Line!!!</h1>
            <center><img src="./assets/Blood donation.jpg" class="loginimg" height="300"></center>
            <p class="fs-4">Donate blood and be the reason for a smile to many faces!!!</p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="login.php" method="POST">
    
                <?php if (!empty($login_err)): ?>
                    <div class="alert alert-danger">
                        <?php echo $login_err; ?>
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
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="w-100 btn btn-lg btn-danger" type="submit">Login</button>
                <hr class="my-4">
                <small class="text-body-secondary">By clicking Login, you agree to the terms of use.</small>

          
                <p class="mt-3">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
