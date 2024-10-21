<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db1"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all donors
$donors = [];
$result = $conn->query("SELECT * FROM donors"); 
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $donor_id = $_GET['delete'];
    $conn->query("DELETE FROM donors WHERE id = $donor_id");
    header("location: admin.php");
    exit();
}

// Handle edit request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $donor_id = $_POST['id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $blood_type = htmlspecialchars(trim($_POST['blood_type']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $age = htmlspecialchars(trim($_POST['age']));
    $status = htmlspecialchars(trim($_POST['status'])); // Capture the updated status
    
    $conn->query("UPDATE donors SET name = '$name', blood_type = '$blood_type', gender = '$gender', age = '$age', status = '$status' WHERE id = $donor_id");
    header("location: admin.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
        <img src="./assets/red-caduceus-medical-symbol-srsywsh5dweqct8w.webp" width="30" height="30" class="d-inline-block align-top" alt="">
            Life Line - Admin Dashboard
        </a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="admin.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Manage Donors</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Blood Type</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Status</th> <!-- New Status Column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donors as $donor): ?>
                <tr>
                    <td><?php echo $donor['id']; ?></td>
                    <td><?php echo $donor['name']; ?></td>
                    <td><?php echo $donor['blood_type']; ?></td>
                    <td><?php echo $donor['gender']; ?></td>
                    <td><?php echo $donor['age']; ?></td>
                    <td><?php echo $donor['status']; ?></td> <!-- Show status here -->
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $donor['id']; ?>">Edit</button>
                        <a href="?delete=<?php echo $donor['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this donor?');">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $donor['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="admin.php">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Donor</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $donor['id']; ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo $donor['name']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="blood_type" class="form-label">Blood Type</label>
                                        <input type="text" class="form-control" name="blood_type" value="<?php echo $donor['blood_type']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <input type="text" class="form-control" name="gender" value="<?php echo $donor['gender']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="age" class="form-label">Age</label>
                                        <input type="number" class="form-control" name="age" value="<?php echo $donor['age']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" name="status" required>
                                            <option value="available" <?php if ($donor['status'] === 'available') echo 'selected'; ?>>Available</option>
                                            <option value="booked" <?php if ($donor['status'] === 'booked') echo 'selected'; ?>>Booked</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="edit">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
