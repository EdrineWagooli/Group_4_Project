<?php
// customer.php
// Compatible with PHP and MySQL (mysqli)

$host = "localhost";
$user = "root";
$password = "";
$database = "booking and transport system";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the customers table exists.
$createTableSql = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone_number VARCHAR(50) NOT NULL,
    cargo_type VARCHAR(100) DEFAULT NULL,
    shipping_frequency VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createTableSql);

$success = false;
$message = "No customer data was submitted.";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST['First_name'] ?? '');
    $lastName = trim($_POST['Last_name'] ?? '');
    $address = trim($_POST['Address'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $telephone = trim($_POST['Telephone_number'] ?? '');
    $cargoType = trim($_POST['cargo_type'] ?? '');
    $shippingFrequency = trim($_POST['shipping_frequency'] ?? '');

    if ($firstName === '' || $lastName === '' || $address === '' || $email === '' || $telephone === '') {
        $message = "Please fill in all required fields before submitting the form.";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (first_name, last_name, address, email, telephone_number, cargo_type, shipping_frequency) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssss', $firstName, $lastName, $address, $email, $telephone, $cargoType, $shippingFrequency);

        if ($stmt->execute()) {
            $success = true;
            $message = "Customer registered successfully.";
        } else {
            $message = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Customer Registration — AgriRoute</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
<style>
:root {
    --green-deep: #1a3a2a;
    --green-light: #52b788;
    --gold: #c9a84c;
    --gold-light: #e6c97a;
    --white: #ffffff;
    --border: rgba(255,255,255,0.12);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DM Sans', sans-serif; min-height: 100vh; background: var(--green-deep); color: var(--white); display: flex; align-items: center; justify-content: center; padding: 2rem; }
.container { width: 100%; max-width: 720px; background: rgba(12,28,20,0.96); border: 1px solid var(--border); border-radius: 8px; padding: 2rem; }
.header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
.header .logo { font-family: 'Playfair Display', serif; font-size: 2rem; }
.header .logo span { color: var(--gold); }
h1 { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 0.5rem; }
p { color: rgba(255,255,255,0.72); line-height: 1.6; }
.card { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 6px; padding: 1.25rem; margin-top: 1.5rem; }
.status { padding: 1rem 1.1rem; border-radius: 5px; margin-bottom: 1rem; }
.status.success { background: rgba(82,183,136,0.14); border: 1px solid rgba(82,183,136,0.35); color: #b8f0ce; }
.status.error { background: rgba(224,112,112,0.12); border: 1px solid rgba(224,112,112,0.3); color: #ffb7b7; }
.details { display: grid; gap: 0.9rem; }
.details p { color: rgba(255,255,255,0.78); }
.details strong { color: var(--gold-light); }
.btn { display: inline-block; margin-top: 1.5rem; padding: 0.9rem 1.8rem; background: var(--gold); color: var(--green-deep); text-decoration: none; border-radius: 4px; font-weight: 700; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="logo"><span>🌿</span> AgriRoute</div>
  </div>
  <h1>Customer Registration</h1>
  <p>This page receives customer registration data from the form and stores it in MySQL.</p>

  <div class="card">
    <div class="status <?php echo $success ? 'success' : 'error'; ?>">
      <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    </div>

    <?php if ($success) { ?>
      <div class="details">
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Telephone:</strong> <?php echo htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php if ($cargoType !== '') { ?><p><strong>Cargo Type:</strong> <?php echo htmlspecialchars($cargoType, ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
        <?php if ($shippingFrequency !== '') { ?><p><strong>Shipping Frequency:</strong> <?php echo htmlspecialchars($shippingFrequency, ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
      </div>
    <?php } ?>

    <a class="btn" href="customer.html">Back to Customer Form</a>
  </div>
</div>
</body>
</html>
