<?php
// booking.php

$host = "localhost";
$user = "root";
$password = "";
$database = "booking_and_transport_system";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $quantity     = $_POST['quantity'];
    $location     = $_POST['location'];
    $pickup       = $_POST['pickup'];
    $destination  = $_POST['destination'];
    $cost         = str_replace(',', '', $_POST['cost']);
    $bookingdate  = $_POST['bookingdate'];
    $deliverydate = $_POST['deliverydate'];

    // FIXED INSERT QUERY - Matching your actual database columns
    $sql = "INSERT INTO booking 
            (quantity, location, pickup, destination, cost, bookingdate, deliverydate) 
            VALUES 
            ('$quantity', '$location', '$pickup', '$destination', '$cost', '$bookingdate', '$deliverydate')";

    if ($conn->query($sql) === TRUE) {
        $message = "✅ Booking submitted successfully!";
        $success = true;
    } else {
        $message = "❌ Error: " . $conn->error;
        $success = false;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status — AgriRoute</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --gd:#1a3a2a; --gm:#2d6a4f; --gl:#52b788; --gold:#c9a84c; --gol:#e6c97a; --w:#fff; --bd:rgba(255,255,255,0.10);
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            background:var(--gd); color:var(--w); font-family:'DM Sans',sans-serif;
            min-height:100vh; display:flex; justify-content:center; align-items:center; padding:2rem;
        }
        .status-card{
            width:100%; max-width:650px; background:rgba(12,28,20,0.95); border:1px solid var(--bd);
            padding:2.5rem; border-radius:4px; text-align:center;
        }
        .logo{font-family:'Playfair Display',serif; font-size:2rem; margin-bottom:0.8rem;}
        .logo span{color:var(--gold);}
        h1{font-family:'Playfair Display',serif; font-size:1.8rem; margin-bottom:1rem;}
        .msg{padding:1rem; border-radius:3px; margin:1.5rem 0; font-size:0.95rem; line-height:1.6;}
        .success{background:rgba(82,183,136,0.12); border:1px solid rgba(82,183,136,0.3); color:#9ff0c8;}
        .error{background:rgba(255,0,0,0.08); border:1px solid rgba(255,0,0,0.2); color:#ff9b9b;}
        .btn{
            display:inline-block; margin-top:2rem; padding:0.9rem 2rem; background:var(--gold);
            color:var(--gd); text-decoration:none; font-weight:700; border-radius:2px;
        }
        .btn:hover{background:var(--gol);}
    </style>
</head>
<body>
    <div class="status-card">
        <div class="logo"><span>🌿</span> AgriRoute</div>
        <h1>Booking Status</h1>
        
        <div class="msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>

        <?php if($success){ ?>
        <div class="details" style="margin-top:1.5rem; text-align:left; border-top:1px solid var(--bd); padding-top:1.5rem;">
            <p><strong>Quantity:</strong> <?php echo $quantity; ?> KG</p>
            <p><strong>Location:</strong> <?php echo $location; ?></p>
            <p><strong>Pickup:</strong> <?php echo $pickup; ?> km</p>
            <p><strong>Destination:</strong> <?php echo $destination; ?> km</p>
            <p><strong>Total Cost:</strong> UGX <?php echo number_format($cost, 2); ?></p>
            <p><strong>Booking Date:</strong> <?php echo $bookingdate; ?></p>
            <p><strong>Delivery Date:</strong> <?php echo $deliverydate; ?></p>
        </div>
        <?php } ?>

        <a href="booking.html" class="btn">← Back to Booking</a>
    </div>
</body>
</html>