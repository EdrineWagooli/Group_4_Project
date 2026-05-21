<?php
// ==================== userlogin.php ====================

$host = "localhost";
$user = "root";
$password = "";
$database = "booking_and_transport_system";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$message = "Please enter your login credentials.";
$redirect = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $role          = trim(isset($_POST['role']) ? $_POST['role'] : 'customer');
    $username      = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $passwordInput = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($passwordInput)) {
        $message = "Username and Password are required.";
    } else {

        // ==================== ADMIN LOGIN (Hardcoded) ====================
        if ($role === 'admin') {
            if ($username === 'admin' && $passwordInput === 'Admin@123') {
                $success = true;
                $message = "✅ Admin Login Successful!";
                $redirect = "index.html";        // Change to your admin dashboard later
            } else {
                $message = "❌ Invalid Admin credentials.";
            }
        } 
        // ==================== CUSTOMER & STAFF LOGIN ====================
        else {
            $userType = ($role === 'staff') ? 'staff' : 'customer';

            $stmt = $conn->prepare("SELECT password_hash FROM accounts WHERE username = ? AND user_type = ? LIMIT 1");
            
            if ($stmt) {
                $stmt->bind_param("ss", $username, $userType);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($hash);
                    $stmt->fetch();

                    if (password_verify($passwordInput, $hash)) {
                        $success = true;
                        $message = "✅ Login Successful! Redirecting...";
                        $redirect = ($role === 'staff') ? "Staff.html" : "customer.html";
                    } else {
                        $message = "❌ Incorrect password.";
                    }
                } else {
                    $message = "❌ No account found with this username.";
                }
                $stmt->close();
            } else {
                $message = "❌ Database error. Please try again.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result — AgriRoute</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --green: #1a3a2a; --gold: #c9a84c; --white: #ffffff; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--green);
            color: var(--white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: rgba(12,28,20,0.95);
            border: 1px solid var(--gold);
            border-radius: 10px;
            padding: 40px 30px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h1 { font-family: 'Playfair Display', serif; margin-bottom: 10px; }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 1.05rem;
        }
        .success { background: rgba(82,183,136,0.2); border: 1px solid #52b788; color: #b8f0ce; }
        .error   { background: rgba(220,53,69,0.2); border: 1px solid #dc3545; color: #ff9b9b; }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: var(--gold);
            color: var(--green);
            text-decoration: none;
            font-weight: 700;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login Result</h1>
        
        <div class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>

        <?php if ($success && $redirect): ?>
            <p>Redirecting you shortly...</p>
            <meta http-equiv="refresh" content="2;url=<?php echo $redirect; ?>">
        <?php endif; ?>

        <a href="user-login.html" class="btn">← Back to Login</a>
    </div>
</body>
</html>