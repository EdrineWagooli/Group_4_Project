<?php
// admin_registration.php - Compatible with PHP 5.2.5

$conn = mysqli_connect("localhost", "root", "", "booking_and_transport_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_registration.html");
    exit();
}

// Get form data
$first_name     = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
$last_name      = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
$email          = trim(mysqli_real_escape_string($conn, $_POST['email']));
$username       = trim(mysqli_real_escape_string($conn, $_POST['username']));
$password       = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Validation
$errors = array();

if (empty($first_name))     $errors[] = "First name is required.";
if (empty($last_name))      $errors[] = "Last name is required.";
if (empty($email))          $errors[] = "Email is required.";
if (empty($username))       $errors[] = "Username is required.";
if (strlen($password) < 6)  $errors[] = "Password must be at least 6 characters.";
if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

if (!empty($errors)) {
    $msg = "<div style='color:red;'>" . implode("<br>", $errors) . "</div>";
    $msg .= "<br><a href='admin_registration.html' style='color:#c9a84c;'>← Go Back</a>";
    die($msg);
}

// Check if username or email already exists
$check = mysqli_query($conn, "SELECT id FROM admins WHERE username='$username' OR email='$email' LIMIT 1");

if (mysqli_num_rows($check) > 0) {
    die("Username or Email already exists.<br><a href='admin_registration.html'>← Go Back</a>");
}

// Hash password using MD5 (for old PHP compatibility)
$hashed_password = md5($password);

// Insert into database
$sql = "INSERT INTO admins (first_name, last_name, email, username, password) 
        VALUES ('$first_name', '$last_name', '$email', '$username', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "
    <div style='font-family:Arial; text-align:center; margin-top:50px; color:#52b788;'>
        <h2>✅ Registration Successful!</h2>
        <p>Your admin account has been created.</p>
        <a href='adminlogin.html' style='color:#c9a84c; font-size:18px;'>→ Go to Admin Login</a>
    </div>";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>