<?php
// payment.php - PHP 5.2.5 Compatible

$host = "localhost";
$user = "root";
$password = "";
$database = "booking_and_transport_system";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$message = "Please submit the payment form.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $method        = isset($_POST['paymentmethod']) ? trim($_POST['paymentmethod']) : '';
    $amount        = isset($_POST['amount']) ? trim($_POST['amount']) : '';
    $paymentDate   = isset($_POST['paymentdate']) ? trim($_POST['paymentdate']) : '';
    $bookingRef    = isset($_POST['booking_ref']) ? trim($_POST['booking_ref']) : '';
    $customerRef   = isset($_POST['customer_ref']) ? trim($_POST['customer_ref']) : '';
    $transactionNo = isset($_POST['transaction_number']) ? trim($_POST['transaction_number']) : '';

    if (empty($method) || empty($amount) || empty($paymentDate)) {
        $message = "❌ Payment Method, Amount and Payment Date are required.";
    } elseif (!is_numeric($amount) || floatval($amount) <= 0) {
        $message = "❌ Amount must be a positive number.";
    } else {
        $amountValue = floatval($amount);
        $bookingID = !empty($bookingRef) ? $bookingRef : NULL;

        $stmt = $conn->prepare("INSERT INTO payment 
            (paymentmethod, amount, paymentdate, bookingID, customer_ref, transaction_number) 
            VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param('sdssss', $method, $amountValue, $paymentDate, $bookingID, $customerRef, $transactionNo);

            if ($stmt->execute()) {
                $success = true;
                $message = "✅ Payment recorded successfully!";
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
    <title>Payment Result — AgriRoute</title>
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
        <h1>Payment Result</h1>
        
        <div class="alert <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>

        <?php if ($success) { ?>
            <p><strong>Payment recorded successfully.</strong></p>
        <?php } ?>

        <div style="margin-top:2rem;">
            <a href="payment.html" class="btn btn-primary">← New Payment</a>
            <a href="index.html" class="btn btn-secondary">← Home</a>
        </div>
    </div>
</body>
</html>