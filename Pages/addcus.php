<?php
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "r&r_dbs";  

$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$statusMsg = '';
$redirect = false;
$targetDir = "/uploads";

if (isset($_POST["submit"])) {
    $mob = $_POST['mobile'];
    $fn = $_POST['fname'];
    $mn = $_POST['mname'];
    $ln = $_POST['lname'];
    $ad = $_POST['addr'];
    $cit = $_POST['cit'];
    $civ = $_POST['civ'];
    $gen = $_POST['gen'];

    if (!empty($_FILES["file"]["name"])) {
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $insert = $db->query("INSERT INTO cuslist (mobile, lname, fname, mname, addr, cit, civ, gen, filename) 
                                      VALUES ( '$mob', '$ln', '$fn', '$mn', '$ad', '$cit', '$civ', '$gen', '$fileName')");

                if ($insert) {
                    $cid = $db->insert_id;
                    $cname = "$ln, $fn $mn";

                    $insertPay = $db->query("INSERT INTO cus_pay (cid, cname) VALUES ('$cid', '$cname')");

                    if ($insertPay) {
                        $statusMsg = "Customer added successfully.";
                        $redirect = true;
                    } else {
                        $statusMsg = "Customer saved, but error adding to cus_pay: " . $db->error;
                    }
                } else {
                    $statusMsg = "File uploaded but saving customer info failed: " . $db->error;
                }
            } else {
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $statusMsg = "Only JPG, JPEG, PNG, & GIF files are allowed.";
        }
    } else {
        $statusMsg = "Please select a file to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Customer | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
        margin: 0; 
        padding: 0; 
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
        max-width: 600px;
        background: white;
        margin: 0 auto 60px;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h1 {
        font-weight: 700;
        color: #111827;
        margin-bottom: 25px;
        text-align: center;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #374151;
    }
    input[type="text"],
    select,
    input[type="file"] {
        padding: 10px 12px;
        font-size: 1rem;
        border: 1.5px solid #d1d5db;
        border-radius: 6px;
        outline-offset: 2px;
        transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    select:focus,
    input[type="file"]:focus {
        border-color: #2563eb;
    }
    button[type="submit"] {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 12px;
        font-size: 1.1rem;
        border-radius: 7px;
        cursor: pointer;
        font-weight: 700;
        transition: background-color 0.3s ease;
    }
    button[type="submit"]:hover {
        background-color: #1d4ed8;
    }
    .btn-back {
        margin-top: 20px;
        text-align: center;
    }
    .btn-back a {
        text-decoration: none;
        color: #2563eb;
        font-weight: 600;
        padding: 10px 20px;
        border: 2px solid #2563eb;
        border-radius: 7px;
        display: inline-block;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .btn-back a:hover {
        background-color: #2563eb;
        color: white;
    }
</style>
<script>
    function showMessageAndRedirect(message, redirect = false) {
        alert(message);
        if (redirect) {
            window.location.href = 'cuslist.php';
        }
    }
</script>
</head>
<body>

<header>R & R HARDWARE AND CONSTRUCTION SUPPLIES</header>

<div class="container">
    <?php if (!empty($statusMsg)) : ?>
        <script>
            showMessageAndRedirect(<?= json_encode($statusMsg) ?>, <?= $redirect ? 'true' : 'false' ?>);
        </script>
    <?php endif; ?>

    <h1>Add Customer</h1>
    <form action="" method="post" enctype="multipart/form-data" novalidate>
         <div>
            <label for="mobile">ID Number:</label>
            <input type="text" name="idnum" id="idnum" required>
        </div>
        <div>
            <label for="mobile">Mobile Number:</label>
            <input type="text" name="mobile" id="mobile" required>
        </div>
        <div>
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" required>
        </div>
        <div>
            <label for="mname">Middle Name:</label>
            <input type="text" name="mname" id="mname" required>
        </div>
        <div>
            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" required>
        </div>
        <div>
            <label for="addr">Address:</label>
            <input type="text" name="addr" id="addr" required>
        </div>
        <div>
            <label for="cit">Citizenship:</label>
            <input type="text" name="cit" id="cit" required>
        </div>
        <div>
            <label for="civ">Civil Status:</label>
            <input type="text" name="civ" id="civ" required>
        </div>
        <div>
            <label for="gen">Gender:</label>
            <select name="gen" id="gen" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div>
            <label for="file">Photo of Identification:</label>
            <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.gif" required>
        </div>
        <button type="submit" name="submit">Upload</button>
    </form>
    <div class="btn-back">
        <a href="cuslist.php">← Back to Customer List</a>
    </div>
</div>

</body>
</html>
