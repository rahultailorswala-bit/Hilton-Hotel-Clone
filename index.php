<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilton Hotels - Home</title>
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
        header {
            background: #1a2a44;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
        }
        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        .search-bar form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .search-bar input, .search-bar button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-bar input {
            flex: 1;
            min-width: 200px;
            border: 1px solid #ddd;
        }
        .search-bar button {
            background: #ff6f61;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-bar button:hover {
            background: #e55a50;
        }
        .featured {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
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
        .hotel-card h3 {
            color: #1a2a44;
        }
        .hotel-card p {
            color: #666;
        }
        @media (max-width: 768px) {
            .search-bar form {
                flex-direction: column;
            }
            .search-bar input, .search-bar button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Hilton Hotels</h1>
            <p>Find Your Perfect Stay</p>
        </header>
 
        <div class="search-bar">
            <form id="searchForm">
                <input type="text" name="location" placeholder="Destination" required>
                <input type="date" name="check_in" required>
                <input type="date" name="check_out" required>
                <button type="submit">Search</button>
            </form>
        </div>
 
        <div class="featured">
            <?php
            include 'db.php';
            $stmt = $pdo->query("SELECT * FROM hotels LIMIT 3");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='hotel-card'>
                    <img src='{$row['image']}' alt='{$row['name']}'>
                    <div class='hotel-card-content'>
                        <h3>{$row['name']}</h3>
                        <p>{$row['location']} - \${$row['price']}/night</p>
                        <p>Rating: {$row['rating']}/5</p>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
 
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            window.location.href = `hotels.php?${params}`;
        });
    </script>
</body>
</html>
