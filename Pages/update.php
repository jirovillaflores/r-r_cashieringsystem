<?php
session_start();
$db = new mysqli("localhost", "root", "", "r&r_dbs");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $db->query("SELECT * FROM cuslist WHERE id='$id'");

    if ($result && $row = $result->fetch_assoc()) {
        if (isset($_POST['update'])) {
            // $idnum = $_POST['idnum'];
            $mob = $_POST['mobile'];
            $fn = $_POST['fname'];
            $mn = $_POST['mname'];
            $ln = $_POST['lname'];
            $ad = $_POST['addr'];
            $cit = $_POST['cit'];
            $civ = $_POST['civ'];
            $gen = $_POST['gen'];

            $old_img = $_POST['old_img'];
            $new_img = $_FILES['new_img']['name'];
            $tempname = $_FILES["new_img"]["tmp_name"];
            $uimg = $new_img != '' ? $new_img : $old_img;

            if ($new_img != '') {
                if (!file_exists("uploads/" . $new_img)) {
                    move_uploaded_file($tempname, "uploads/" . $new_img);
                }
            }

            $sql = "UPDATE cuslist SET mobile='$mob', fname='$fn', mname='$mn', lname='$ln', 
                addr='$ad', cit='$cit', civ='$civ', gen='$gen', filename='$uimg' 
                WHERE id='$id'";

            if ($db->query($sql)) {
                echo "<script>alert('Customer updated successfully.'); window.location='cuslist.php';</script>";
                exit;
            } else {
                echo "Error: " . $db->error;
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Customer | R & R HARDWARE</title>
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
        }

        form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-top: 6px;
        }

        img {
            margin-top: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            width: 150px;
            height: 100px;
            object-fit: cover;
        }

        button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 25px;
            width: 100%;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
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
    <h2>Update Customer Details</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        
        <!-- <label>ID Number:</label> -->
        <!-- <input type="text" name="idnum" value="<?= htmlspecialchars($row['idnum']) ?>" required> -->
        
        <label>Mobile Number:</label>
        <input type="text" name="mobile" value="<?= htmlspecialchars($row['mobile']) ?>" required>

        <label>First Name:</label>
        <input type="text" name="fname" value="<?= htmlspecialchars($row['fname']) ?>" required>

        <label>Middle Name:</label>
        <input type="text" name="mname" value="<?= htmlspecialchars($row['mname']) ?>">

        <label>Last Name:</label>
        <input type="text" name="lname" value="<?= htmlspecialchars($row['lname']) ?>" required>

        <label>Address:</label>
        <input type="text" name="addr" value="<?= htmlspecialchars($row['addr']) ?>" required>

        <label>Citizenship:</label>
        <input type="text" name="cit" value="<?= htmlspecialchars($row['cit']) ?>">

        <label>Civil Status:</label>
        <input type="text" name="civ" value="<?= htmlspecialchars($row['civ']) ?>">

        <label>Gender:</label>
        <select name="gen">
            <option value="Male" <?= $row['gen'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $row['gen'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $row['gen'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label>Current Image:</label>
        <img src="uploads/<?= htmlspecialchars($row['filename']) ?>" alt="Customer Image">
        <input type="hidden" name="old_img" value="<?= htmlspecialchars($row['filename']) ?>">

        <label>Upload New Image:</label>
        <input type="file" name="new_img">

        <button type="submit" name="update">UPDATE</button>
    </form>
</div>

</body>
</html>
<?php
    } else {
        echo "No customer found.";
    }
}
?>
