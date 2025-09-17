<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilton Hotels - Listings</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .filters form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .filters select, .filters input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .hotel-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .hotel-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .hotel-card:hover {
            transform: translateY(-5px);
        }
        .hotel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .hotel-card-content {
            padding: 15px;
        }
        .hotel-card-content h3 {
            color: #1a2a44;
        }
        .hotel-card-content p {
            color: #666;
        }
        .book-btn {
            background: #ff6f61;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .book-btn:hover {
            background: #e55a50;
        }
        @media (max-width: 768px) {
            .filters form {
                flex-direction: column;
            }
            .filters select, .filters input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Available Hotels</h2>
        <div class="filters">
            <form id="filterForm">
                <select name="sort">
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="rating_desc">Best Rated</option>
                </select>
                <input type="number" name="min_price" placeholder="Min Price">
                <input type="number" name="max_price" placeholder="Max Price">
                <input type="text" name="amenities" placeholder="Amenities (e.g., WiFi, Pool)">
                <input type="hidden" name="location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
                <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($_GET['check_in'] ?? ''); ?>">
                <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($_GET['check_out'] ?? ''); ?>">
            </form>
        </div>
 
        <div class="hotel-list">
            <?php
            include 'db.php';
            $location = $_GET['location'] ?? '';
            $min_price = $_GET['min_price'] ?? 0;
            $max_price = $_GET['max_price'] ?? 10000;
            $amenities = $_GET['amenities'] ?? '';
            $sort = $_GET['sort'] ?? 'price_asc';
 
            $query = "SELECT * FROM hotels WHERE price BETWEEN ? AND ?";
            $params = [$min_price, $max_price];
 
            if ($location) {
                $query .= " AND location LIKE ?";
                $params[] = "%$location%";
            }
            if ($amenities) {
                $query .= " AND amenities LIKE ?";
                $params[] = "%$amenities%";
            }
 
            if ($sort == 'price_asc') {
                $query .= " ORDER BY price ASC";
            } elseif ($sort == 'price_desc') {
                $query .= " ORDER BY price DESC";
            } elseif ($sort == 'rating_desc') {
                $query .= " ORDER BY rating DESC";
            }
 
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='hotel-card'>
                    <img src='{$row['image']}' alt='{$row['name']}'>
                    <div class='hotel-card-content'>
                        <h3>{$row['name']}</h3>
                        <p>{$row['location']} - \${$row['price']}/night</p>
                        <p>Rating: {$row['rating']}/5</p>
                        <p>Amenities: {$row['amenities']}</p>
                        <p>{$row['description']}</p>
                        <button class='book-btn' onclick=\"window.location.href='booking.php?hotel_id={$row['id']}&check_in={$_GET['check_in']}&check_out={$_GET['check_out']}'\">Book Now</button>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
 
    <script>
        document.getElementById('filterForm').addEventListener('change', function() {
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            window.location.href = `hotels.php?${params}`;
        });
    </script>
</body>
</html>
