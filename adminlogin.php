<?php
// admin_login.php - Connects to adminsreg table

$conn = mysqli_connect("localhost", "root", "", "booking_and_transport_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;
$message = "Please enter your credentials.";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim(mysqli_real_escape_string($conn, $_POST['admin_username']));
    $password = $_POST['admin_password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "Username and Password are required.";
    } else {
        $sql = "SELECT password FROM adminsreg WHERE username = '$username' AND is_active = 1 LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (md5($password) === $row['password']) {
                $success = true;
                $message = "✅ Login Successful!";
            } else {
                $message = "❌ Incorrect Password.";
            }
        } else {
            $message = "❌ Username not found or account inactive.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result</title>
    <style>
        body { font-family: Arial, sans-serif; background:#1a3a2a; color:white; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
        .container { text-align:center; background:rgba(12,28,20,0.95); padding:40px; border-radius:12px; border:1px solid #c9a84c; max-width:500px; }
        .success { color:#52b788; }
        .error { color:#ff6b6b; }
        .btn { padding:12px 25px; background:#c9a84c; color:#1a3a2a; text-decoration:none; border-radius:5px; font-weight:bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <p class="<?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>
        <?php if ($success): ?>
            <p>Redirecting to Home...</p>
            <meta http-equiv="refresh" content="2;url=index.html">
        <?php else: ?>
            <a href="admin_login.html" class="btn">← Try Again</a>
        <?php endif; ?>
    </div>
</body>
</html>