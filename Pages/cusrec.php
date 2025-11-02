<?php
$con = mysqli_connect("localhost", "root", "", "r&r_dbs");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all customers
$query = "SELECT id, lname, fname, mname FROM cuslist ORDER BY lname DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Customer Receipts | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
        margin: 0; 
        padding: 30px 0;
        color: #1f2937;
    }
    header {
        background-color: #374151;
        color: #facc15;
        padding: 20px;
        text-align: center;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 1.2px;
        margin-bottom: 40px;
    }
    .container {
        max-width: 800px;
        background: white;
        margin: 0 auto;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 30px;
        color: #111827;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #d1d5db;
        padding: 12px 15px;
        text-align: left;
        font-size: 1rem;
    }
    th {
        background-color: #f3f4f6;
        font-weight: 700;
        color: #374151;
    }
    tbody tr:hover {
        background-color: #e0e7ff;
    }
    .action a {
        display: inline-block;
        padding: 8px 15px;
        background-color: #2563eb;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    .action a:hover {
        background-color: #1d4ed8;
    }
</style>
</head>
<body>

<header>R & R HARDWARE AND CONSTRUCTION SUPPLIES</header>

<div class="container">
    <h2>Customer Receipts</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $cid = $row['id'];
                $cname = $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'];
                echo "<tr>
                        <td>$count</td>
                        <td>" . htmlspecialchars($cname) . "</td>
                        <td class='action'><a href='viewrec.php?id=$cid'>🧾 View Receipt</a></td>
                      </tr>";
                $count++;
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
