<?php
// Staff.php

$host = "localhost";
$user = "root";
$password = "";
$database = "booking_and_transport_system";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$message = "Please complete the staff registration form.";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $role         = isset($_POST['role']) ? trim($_POST['role']) : '';
    $firstName    = isset($_POST['fname']) ? trim($_POST['fname']) : '';
    $lastName     = isset($_POST['lname']) ? trim($_POST['lname']) : '';
    $nin          = isset($_POST['NIN']) ? trim($_POST['NIN']) : '';
    $telephone    = isset($_POST['telnumber']) ? trim($_POST['telnumber']) : '';
    $address      = isset($_POST['address']) ? trim($_POST['address']) : '';
    $username     = isset($_POST['username']) ? trim($_POST['username']) : '';

    if (empty($role) || empty($firstName) || empty($lastName) || empty($nin) || 
        empty($telephone) || empty($address)) {
        $message = "❌ Role, full name, NIN, telephone, and address are required.";
    } else {

        $photoPath = null;
        $uploadError = false;

        // Photo Upload
        if (isset($_FILES['passportphoto']) && $_FILES['passportphoto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['passportphoto'];
            $allowedTypes = array('image/jpeg', 'image/png', 'image/jpg');

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $message = "❌ Photo upload failed.";
                $uploadError = true;
            } elseif (!in_array($file['type'], $allowedTypes)) {
                $message = "❌ Only JPG and PNG files are allowed.";
                $uploadError = true;
            } else {
                $uploadDir = __DIR__ . '/uploads/staff_photos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                // Compatible random name for old PHP
                $randomPart = mt_rand(100000, 999999);
                $safeName = 'staff_' . time() . '_' . $randomPart . '.' . $extension;
                $destination = $uploadDir . $safeName;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $photoPath = 'uploads/staff_photos/' . $safeName;
                } else {
                    $message = "❌ Failed to save photo.";
                    $uploadError = true;
                }
            }
        }

        if (!$uploadError) {
            $stmt = $conn->prepare("INSERT INTO staff 
                (role, fname, lname, NIN, telnumber, address, username, passportphoto) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param('ssssssss', 
                    $role, $firstName, $lastName, $nin, $telephone, 
                    $address, $username, $photoPath
                );

                if ($stmt->execute()) {
                    $success = true;
                    $message = "✅ Staff member registered successfully!";
                } else {
                    $message = "❌ Database Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "❌ Prepare failed: " . $conn->error;
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
    <title>Staff Registration — AgriRoute</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --green:#1a3a2a; --gold:#c9a84c; --white:#fff; --border:rgba(255,255,255,0.12); }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:var(--green);color:var(--white);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
        .card{width:100%;max-width:720px;background:rgba(12,28,20,0.95);border:1px solid var(--border);border-radius:10px;padding:2.5rem;}
        .alert{padding:1rem 1.2rem;border-radius:6px;margin:1.5rem 0;font-size:1rem;}
        .alert.success{background:rgba(82,183,136,0.15);border:1px solid rgba(82,183,136,0.4);color:#b8f0ce;}
        .alert.error{background:rgba(220,53,69,0.15);border:1px solid rgba(220,53,69,0.4);color:#ff9b9b;}
        .btn{display:inline-block;padding:0.9rem 1.8rem;margin:0.4rem 0.3rem 0 0;border-radius:5px;text-decoration:none;font-weight:700;}
        .btn-primary{background:var(--gold);color:var(--green);}
        .btn-secondary{background:rgba(255,255,255,0.1);color:var(--white);border:1px solid var(--border);}
    </style>
</head>
<body>
    <div class="card">
        <h1>Staff Registration Result</h1>
        
        <div class="alert <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>

        <?php if ($success) { ?>
            <p><strong>Registration Completed Successfully!</strong></p>
        <?php } ?>

        <div style="margin-top:2rem;">
            <a href="Staff.html" class="btn btn-primary">← Register Another Staff</a>
            <a href="index.html" class="btn btn-secondary">← Home</a>
        </div>
    </div>
</body>
</html>