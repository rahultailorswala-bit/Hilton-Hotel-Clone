<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilton Hotels - Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .booking-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .booking-form h2 {
            color: #1a2a44;
            margin-bottom: 20px;
        }
        .booking-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .booking-form button {
            background: #ff6f61;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .booking-form button:hover {
            background: #e55a50;
        }
        .confirmation {
            display: none;
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="booking-form">
            <h2>Book Your Stay</h2>
            <?php
            include 'db.php';
            if (isset($_GET['hotel_id']) && isset($_GET['check_in']) && isset($_GET['check_out'])) {
                $hotel_id = $_GET['hotel_id'];
                $check_in = $_GET['check_in'];
                $check_out = $_GET['check_out'];
 
                $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
                $stmt->execute([$hotel_id]);
                $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
 
                if ($hotel) {
                    echo "<p>Booking: {$hotel['name']} - {$hotel['location']}</p>";
                    echo "<p>Price per night: \${$hotel['price']}</p>";
                }
            }
            ?>
            <form id="bookingForm">
                <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                <input type="hidden" name="check_in" value="<?php echo $check_in; ?>">
                <input type="hidden" name="check_out" value="<?php echo $check_out; ?>">
                <input type="text" name="user_id" placeholder="Your ID (e.g., email)" required>
                <button type="submit">Confirm Booking</button>
            </form>
            <div class="confirmation" id="confirmation">
                <p>Booking Confirmed! Thank you for choosing Hilton Hotels.</p>
            </div>
        </div>
    </div>
 
    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
 
            fetch('booking.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      document.getElementById('confirmation').style.display = 'block';
                      setTimeout(() => window.location.href = 'index.php', 2000);
                  }
              });
        });
    </script>
 
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hotel_id = $_POST['hotel_id'];
        $user_id = $_POST['user_id'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
 
        $stmt = $pdo->prepare("SELECT price FROM hotels WHERE id = ?");
        $stmt->execute([$hotel_id]);
        $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
 
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $days = $check_in_date->diff($check_out_date)->days;
        $total_price = $hotel['price'] * $days;
 
        $stmt = $pdo->prepare("INSERT INTO bookings (hotel_id, user_id, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$hotel_id, $user_id, $check_in, $check_out, $total_price]);
 
        echo json_encode(['success' => true]);
        exit;
    }
    ?>
</body>
</html>
