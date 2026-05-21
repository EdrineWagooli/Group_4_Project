<?php
/* =========================================
   BOOKING & TRANSPORT SYSTEM
   DASHBOARD CONNECTION FILE
========================================= */


/* =========================================
   DATABASE CONNECTION
========================================= */

$host = "localhost";
$username = "root";
$password = "";
$database = "booking_and_transport";

$conn = mysqli_connect($host, $username, $password, $database);


/* =========================================
   CONNECTION CHECK
========================================= */

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}


/* =========================================
   FETCH TOTAL CUSTOMERS
========================================= */

$totalCustomer = 0;

$customerQuery = "SELECT COUNT(*) AS total FROM customer";
$customerResult = mysqli_query($conn, $customerQuery);

if ($customerResult) {
    $customerData = mysqli_fetch_assoc($customerResult);
    $totalCustomer = $customerData['total'];
}


/* =========================================
   FETCH TOTAL BOOKINGS
========================================= */

$totalBooking = 0;

$bookingQuery = "SELECT COUNT(*) AS total FROM booking";
$bookingResult = mysqli_query($conn, $bookingQuery);

if ($bookingResult) {
    $bookingData = mysqli_fetch_assoc($bookingResult);
    $totalBooking = $bookingData['total'];
}


/* =========================================
   FETCH TOTAL PAYMENTS
========================================= */

$totalPayment = 0;

$paymentQuery = "SELECT COUNT(*) AS total FROM payment";
$paymentResult = mysqli_query($conn, $paymentQuery);

if ($paymentResult) {
    $paymentData = mysqli_fetch_assoc($paymentResult);
    $totalPayment = $paymentData['total'];
}


/* =========================================
   FETCH TOTAL STAFF
========================================= */

$totalStaff = 0;

$staffQuery = "SELECT COUNT(*) AS total FROM staff";
$staffResult = mysqli_query($conn, $staffQuery);

if ($staffResult) {
    $staffData = mysqli_fetch_assoc($staffResult);
    $totalStaff = $staffData['total'];
}


/* =========================================
   FETCH TOTAL VESSELS
========================================= */

$totalVessel = 0;

$vesselQuery = "SELECT COUNT(*) AS total FROM vessel";
$vesselResult = mysqli_query($conn, $vesselQuery);

if ($vesselResult) {
    $vesselData = mysqli_fetch_assoc($vesselResult);
    $totalVessel = $vesselData['total'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard — Booking & Transport System</title>

    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <!-- =========================
         NAVIGATION
    ========================== -->

    <header>

        <nav>

            <a href="index.html" class="nav-logo">
                🌿 AgriRoute
            </a>

            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="userlogin.html">Login</a></li>
                <li><a href="register.html">Register</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>

        </nav>

    </header>



    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main>

        <!-- HERO SECTION -->

        <section class="hero">

            <h1 class="hero-title">
                Booking & Transport Dashboard
            </h1>

            <p class="hero-sub">
                Centralized control system for agricultural transport,
                booking management, customer records, staff operations,
                and payment tracking.
            </p>

            <div class="btn-group">

                <a href="dashboard.php" class="btn-primary">
                    Open Dashboard
                </a>

                <a href="userlogin.html" class="btn-secondary">
                    Sign In
                </a>

            </div>

        </section>



        <!-- =========================
             STATISTICS SECTION
        ========================== -->

        <section class="section stats-grid">


            <article class="stat-card">

                <h2 class="stat-label">
                    Customers
                </h2>

                <p class="stat-value">
                    <?php echo $totalCustomers; ?>
                </p>

                <p class="card-text">
                    Registered customers in the system.
                </p>

            </article>



            <article class="stat-card">

                <h2 class="stat-label">
                    Bookings
                </h2>

                <p class="stat-value">
                    <?php echo $totalBookings; ?>
                </p>

                <p class="card-text">
                    Active cargo and transport bookings.
                </p>

            </article>



            <article class="stat-card">

                <h2 class="stat-label">
                    Payments
                </h2>

                <p class="stat-value">
                    <?php echo $totalPayments; ?>
                </p>

                <p class="card-text">
                    Payment transactions processed.
                </p>

            </article>



            <article class="stat-card">

                <h2 class="stat-label">
                    Staff
                </h2>

                <p class="stat-value">
                    <?php echo $totalStaff; ?>
                </p>

                <p class="card-text">
                    Staff members registered in the system.
                </p>

            </article>



            <article class="stat-card">

                <h2 class="stat-label">
                    Vessels
                </h2>

                <p class="stat-value">
                    <?php echo $totalVessels; ?>
                </p>

                <p class="card-text">
                    Available transport vessels and vehicles.
                </p>

            </article>

        </section>



        <!-- =========================
             MODULE SECTION
        ========================== -->

        <section class="section">

            <div class="card-grid">


                <article class="card">

                    <h2 class="card-title">
                        Customer Management
                    </h2>

                    <p class="card-text">
                        Manage customer records and information.
                    </p>

                    <a href="customer.php">
                        Open Module →
                    </a>

                </article>



                <article class="card">

                    <h2 class="card-title">
                        Booking Control
                    </h2>

                    <p class="card-text">
                        Handle cargo bookings and delivery routes.
                    </p>

                    <a href="booking.php">
                        Open Module →
                    </a>

                </article>



                <article class="card">

                    <h2 class="card-title">
                        Payment Records
                    </h2>

                    <p class="card-text">
                        Track all customer payments and transactions.
                    </p>

                    <a href="payment.php">
                        Open Module →
                    </a>

                </article>



                <article class="card">

                    <h2 class="card-title">
                        Staff Management
                    </h2>

                    <p class="card-text">
                        Manage staff registration and employee details.
                    </p>

                    <a href="staff.php">
                        Open Module →
                    </a>

                </article>



                <article class="card">

                    <h2 class="card-title">
                        Vessel Management
                    </h2>

                    <p class="card-text">
                        Manage transport vehicles and vessel records.
                    </p>

                    <a href="vessel.php">
                        Open Module →
                    </a>

                </article>



                <article class="card">

                    <h2 class="card-title">
                        Login System
                    </h2>

                    <p class="card-text">
                        Secure access for administrators and staff.
                    </p>

                    <a href="userlogin.html">
                        Login →
                    </a>

                </article>

            </div>

        </section>

    </main>



    <!-- =========================
         FOOTER
    ========================== -->

    <footer class="footer">

        Booking & Transport System — AgriRoute Uganda

    </footer>

</body>

</html>
