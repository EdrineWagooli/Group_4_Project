<?php
// Vessel.php

$host = "localhost";
$user = "root";
$password = "";
$database = "booking_and_transport_system";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$message = "Please complete the vessel registration form.";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $vesselType       = isset($_POST['vesseltype']) ? trim($_POST['vesseltype']) : '';
    $registrationNo   = isset($_POST['registrationnumber']) ? trim($_POST['registrationnumber']) : '';
    $capacity         = isset($_POST['capacity']) ? trim($_POST['capacity']) : '';
    $status           = isset($_POST['vessel_status']) ? trim($_POST['vessel_status']) : 'Active';
    $year             = isset($_POST['year']) ? trim($_POST['year']) : '';
    $owner            = isset($_POST['owner']) ? trim($_POST['owner']) : '';
    $notes            = isset($_POST['notes']) ? trim($_POST['notes']) : '';

    if (empty($vesselType) || empty($registrationNo) || empty($capacity)) {
        $message = "❌ Vessel Type, Registration Number, and Capacity are required.";
    } elseif (!is_numeric($capacity) || floatval($capacity) <= 0) {
        $message = "❌ Capacity must be a positive number.";
    } else {
        $capacityValue = (int) $capacity;
        $yearValue     = !empty($year) && is_numeric($year) ? (int)$year : null;

        // ✅ CHANGED TO 'vessel' (your actual table name)
        $stmt = $conn->prepare("INSERT INTO vessel 
            (vessel_type, registration_number, capacity, vessel_status, year_of_manufacture, owner, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param('ssisiss', 
                $vesselType, 
                $registrationNo, 
                $capacityValue, 
                $status, 
                $yearValue, 
                $owner, 
                $notes
            );

            if ($stmt->execute()) {
                $success = true;
                $message = "✅ Vessel registered successfully!";
            } else {
                $message = "❌ Database Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Prepare failed: " . $conn->error;
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
    <title>Vessel Registration Result — AgriRoute</title>
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
        <h1>Vessel Registration Result</h1>
        
        <div class="alert <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>

        <?php if ($success) { ?>
            <p><strong>Registration Completed Successfully!</strong></p>
        <?php } ?>

        <div style="margin-top:2rem;">
            <a href="Vessel.html" class="btn btn-primary">← Register Another Vessel</a>
            <a href="index.html" class="btn btn-secondary">← Home</a>
        </div>
    </div>
</body>
</html>