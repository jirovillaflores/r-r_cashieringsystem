<?php
$connection = mysqli_connect("localhost", "root", "", "r&r_dbs");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM cuslist WHERE id = $id";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) !== 1) {
    echo "Customer not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);
$fullName = "{$row['lname']}, {$row['fname']} {$row['mname']}";
$photo = "uploads/" . $row['filename'];
$idnum = $row['idnum'] ?? 'N/A';
$contact = $row['mobile'] ?? 'N/A';
$address = $row['addr'] ?? 'N/A';
$citizenship = $row['cit'] ?? 'N/A';
$civil_status = $row['civ'] ?? 'N/A';
$gender = $row['gen'] ?? 'N/A';
$memberId = $row['id'];

$validUntil = date('F j, Y', strtotime('+5 year'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Card | R & R HARDWARE & CONSTRUCTION SUPPLIES</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
        }

        .card {
            width: 600px;
            margin: auto;
            border: 2px solid #facc15;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            border: 1px solid #d1d5db;
            vertical-align: middle;
        }

        .photo {
            width: 140px;
            text-align: center;
        }

        .photo img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #facc15;
        }

        h2 {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
        }

        p {
            margin: 4px 0;
            font-size: 14px;
            color: #374151;
        }

        .action-buttons {
            margin-top: 20px;
        }

        button {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        @media print {
            button {
                display: none;
            }

            body {
                background-color: white;
                padding: 0;
            }

            .card {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>

<div class="card" id="membershipCard">
    <table>
        <tr>
            <td class="photo" rowspan="9">
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="Customer Photo">
            </td>
            <td colspan="2">
                <h2>R & R HARDWARE & CONSTRUCTION SUPPLIES</h2>
                <p><strong>Membership Card</strong></p>
            </td>
        </tr>
         <tr><td><strong>Customer ID:</strong></td><td><?php echo $idnum; ?></td></tr>
         <tr><td><strong>Full Name:</strong></td><td><?php echo htmlspecialchars($fullName); ?></td></tr>
               <tr><td><strong>Contact:</strong></td><td><?php echo htmlspecialchars($contact); ?></td></tr>
        <tr><td><strong>Address:</strong></td><td><?php echo htmlspecialchars($address); ?></td></tr>
        <tr><td><strong>Citizenship:</strong></td><td><?php echo htmlspecialchars($citizenship); ?></td></tr>
        <tr><td><strong>Civil Status:</strong></td><td><?php echo htmlspecialchars($civil_status); ?></td></tr>
        <tr><td><strong>Gender:</strong></td><td><?php echo htmlspecialchars($gender); ?></td></tr>
        <tr><td><strong>Valid Until:</strong></td><td><?php echo $validUntil; ?></td></tr>
    </table>
</div>

<div class="action-buttons">
    <button onclick="window.print()">🖨️ Print Membership Card</button>
    <button onclick="downloadCard()">📥 Download as PNG</button>
    <button type="button" onclick="window.location.href='cuslist.php'">← Back to Customer List</button>
</div>

<script>
function downloadCard() {
    const card = document.getElementById('membershipCard');
    html2canvas(card).then(canvas => {
        const link = document.createElement('a');
        link.download = 'membership_card_<?php echo $memberId; ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>

</body>
</html>
