<?php
session_start();
$db = new mysqli("localhost", "root", "", "r&r_dbs");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid customer ID.");
}

$id = intval($_GET['id']);

$stmt = $db->prepare("SELECT * FROM cuslist WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Details | R & R HARDWARE</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        header {
            background-color: #374151;
            color: #facc15;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #111827;
        }

        form label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-top: 6px;
        }

        img {
            display: block;
            margin: 20px auto;
            border-radius: 6px;
            border: 2px solid #e5e7eb;
            width: 150px;
            height: 100px;
            object-fit: cover;
        }

        .back-btn {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 25px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>R & R HARDWARE AND CONSTRUCTION SUPPLIES</h1>
</header>

<div class="container">
    <h2>Customer Details (Read-Only)</h2>
    <form>

    
        <label>Mobile Number:</label>
        <input type="text" value="<?= htmlspecialchars($row['mobile']) ?>" readonly>

        <label>First Name:</label>
        <input type="text" value="<?= htmlspecialchars($row['fname']) ?>" readonly>

        <label>Middle Name:</label>
        <input type="text" value="<?= htmlspecialchars($row['mname']) ?>" readonly>

        <label>Last Name:</label>
        <input type="text" value="<?= htmlspecialchars($row['lname']) ?>" readonly>

        <label>Address:</label>
        <input type="text" value="<?= htmlspecialchars($row['addr']) ?>" readonly>

        <label>Citizenship:</label>
        <input type="text" value="<?= htmlspecialchars($row['cit']) ?>" readonly>

        <label>Civil Status:</label>
        <input type="text" value="<?= htmlspecialchars($row['civ']) ?>" readonly>

        <label>Gender:</label>
        <input type="text" value="<?= htmlspecialchars($row['gen']) ?>" readonly>

        <label>Uploaded Image:</label>
        <img src="uploads/<?= htmlspecialchars($row['filename']) ?>" alt="Customer Image">

        <button type="button" class="back-btn" onclick="window.location.href='cuslist.php'">← Back to Customer List</button>
    </form>
</div>

</body>
</html>
<?php
else:
    echo "Customer record not found.";
endif;
?>
