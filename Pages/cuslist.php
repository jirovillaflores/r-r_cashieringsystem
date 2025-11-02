<?php
$connection = mysqli_connect("localhost", "root", "", "r&r_dbs");

$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($connection, $_POST['search']);
}

$sql = "SELECT * FROM cuslist WHERE lname LIKE '%$search%' OR fname LIKE '%$search%' OR mname LIKE '%$search%' ORDER BY id ASC";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }

        header {
            background-color: #374151;
            color: #facc15;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .nav-bar {
            background-color: #1f2937;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 10px;
        }

        .nav-bar button {
            background-color: #facc15;
            color: #111827;
            border: none;
            margin: 5px;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .nav-bar button:hover {
            background-color: #eab308;
        }

        .container {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
        }

        h2 {
            margin-top: 10px;
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        button[type="submit"], .add-btn {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 16px;
            margin-left: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover, .add-btn:hover {
            background-color: #1d4ed8;
        }

        .add-btn {
            float: right;
            margin-bottom: 10px;
            background-color: #10b981;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
            border: 1px solid #d1d5db;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        img {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            background-color: #e5e7eb;
            color: #6b7280;
        }

        @media (max-width: 600px) {
            .nav-bar {
                flex-direction: column;
            }

            .add-btn {
                float: none;
                display: block;
                margin: 10px auto;
            }

            input[type="text"] {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>R & R HARDWARE AND CONSTRUCTION SUPPLIES</h1>
</header>

<div class="nav-bar">
    <button onclick="location.href='home.php'">🏠 Home</button>
    <button onclick="location.href='cuslist.php'">👥 Customer List</button>
    <button onclick="location.href='cusrec.php'">🧾 Customer Receipts</button>
    <button onclick="location.href='daysales.php'">📊 Day Sales</button>
</div>

<div class="container">
    <h2>Customer List</h2>

    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search customer..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">🔍 Search</button>
        <a href="addcus.php" class="add-btn">➕ Add Customer</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Photo</th>
                <th>Full Name</th>
                <th colspan="5">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['id'];
                    $ln = $row['lname'];
                    $fn = $row['fname'];
                    $mn = $row['mname'];
                    echo "<tr>
                        <td>$id</td>
                        <td><img src='uploads/{$row['filename']}' alt='Photo'></td>
                        <td>$ln, $fn $mn</td>
                        <td><a href='addorder.php?id=$id'>Add Order</a></td>
                        <td><a href='view.php?id=$id'>View</a></td>
                        <td><a href='update.php?id=$id'>Update</a></td>
                        <td><a href='delete.php?id=$id'>Delete</a></td>
                        <td><a href='viewcard.php?id=$id' target='_blank'>Generate ID</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>NO CUSTOMER(S) FOUND...</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="footer">
    &copy; <?php echo date('Y'); ?> R & R Hardware and Construction Supplies. All rights reserved.
</div>

</body>
</html>

<?php
mysqli_close($connection);
?>
