<?php
// Handle form submission
if (isset($_POST['confirm'])) {
    $con = mysqli_connect("localhost", "root", "", "r&r_dbs");

    if (!$con) die("Connection failed: " . mysqli_connect_error());

    $pay = isset($_POST['pay']) ? floatval($_POST['pay']) : 0;
    $cid = isset($_POST['id']) ? intval($_POST['id']) : 0;
    date_default_timezone_set('Asia/Manila');
    $b_date = date("Y-m-d h:i:sa");

    if ($cid <= 0 || $pay <= 0) die("Invalid input.");

    // Get customer name
    $stmt = mysqli_prepare($con, "SELECT lname, fname, mname FROM cuslist WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $cid);
    mysqli_stmt_execute($stmt);
    $customer_result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($customer_result) == 0) die("Customer not found.");
    $customer = mysqli_fetch_assoc($customer_result);
    $cname = $customer['lname'] . ', ' . $customer['fname'] . ' ' . $customer['mname'];

    // Get item total
    $stmt_items = mysqli_prepare($con, "SELECT s_total FROM product_solds WHERE s_cus = ?");
    mysqli_stmt_bind_param($stmt_items, "i", $cid);
    mysqli_stmt_execute($stmt_items);
    $item_result = mysqli_stmt_get_result($stmt_items);

    $subtotal = 0.00;
    while ($row = mysqli_fetch_assoc($item_result)) {
        $subtotal += floatval($row['s_total']);
    }

    $vat = round($subtotal * 0.12, 2);
    $bill_with_vat = round($subtotal + $vat, 2);
    $mchange = round($pay - $bill_with_vat, 2);

    // Insert/Update payment
    $stmt_insert = mysqli_prepare($con, "
        INSERT INTO cus_pay (cid, cname, bill, pay, mchange, bdate, VAT)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            cname = VALUES(cname), bill = VALUES(bill), pay = VALUES(pay),
            mchange = VALUES(mchange), bdate = VALUES(bdate), VAT = VALUES(VAT)
    ");
    mysqli_stmt_bind_param($stmt_insert, "isdddsd", $cid, $cname, $bill_with_vat, $pay, $mchange, $b_date, $vat);
    if (!mysqli_stmt_execute($stmt_insert)) {
        die("Error saving payment: " . mysqli_error($con));
    }
}
?>

<?php if (isset($_POST['confirm'])): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
    <style>
        body {
            margin: 0;
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
        }

        header {
            background-color: #374151;
            color: #facc15;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .nav-bar {
            display: flex;
            justify-content: center;
            background-color: #1f2937;
            padding: 10px;
            flex-wrap: wrap;
        }

        .nav-bar button {
            background-color: #facc15;
            color: #111827;
            border: none;
            padding: 10px 18px;
            margin: 5px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-bar button:hover {
            background-color: #eab308;
        }

        .receipt {
            background: white;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin: 0 0 5px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: right;
        }

        .left { text-align: left; }
        .total-row { font-weight: bold; background-color: #fef9c3; }

        .print-btn {
            text-align: center;
            margin-top: 20px;
        }

        .print-btn button {
            background-color: #facc15;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 5px;
        }

        .print-btn button:hover {
            background-color: #eab308;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: #374151;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>

<header>
    <h1>R & R HARDWARE AND CONSTRUCTION SUPPLIES</h1>
</header>

<div class="receipt" id="receiptArea">
    <h2>OFFICIAL RECEIPT</h2>
    <p>Looc, Brgy. II, Bais City, Philippines</p>
    <p>VAT Reg TIN: 486-704-368-00000 | Tel: (035) 423-6145</p>
    <hr>

    <p><strong>Customer:</strong> <?php echo htmlspecialchars($cname); ?></p>
    <p><strong>Date/Time:</strong> <?php echo $b_date; ?></p>

    <h3>Products Purchased</h3>
    <table>
        <tr>
            <th class="left">Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>VAT</th>
            <th>Total</th>
        </tr>
        <?php
        $stmt_items = mysqli_prepare($con, "SELECT s_item, s_qty, s_price, s_total FROM product_solds WHERE s_cus = ?");
        mysqli_stmt_bind_param($stmt_items, "i", $cid);
        mysqli_stmt_execute($stmt_items);
        $item_result = mysqli_stmt_get_result($stmt_items);

        while ($row = mysqli_fetch_assoc($item_result)):
            $item_vat = round($row['s_total'] * 0.12, 2);
            $item_total = round($row['s_total'] + $item_vat, 2);
        ?>
        <tr>
            <td class="left"><?php echo htmlspecialchars($row['s_item']); ?></td>
            <td><?php echo $row['s_qty']; ?></td>
            <td>₱<?php echo number_format($row['s_price'], 2); ?></td>
            <td>₱<?php echo number_format($row['s_total'], 2); ?></td>
            <td>₱<?php echo number_format($item_vat, 2); ?></td>
            <td>₱<?php echo number_format($item_total, 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <table>
        <tr>
            <th class="left">Description</th>
            <th>Amount</th>
        </tr>
        <tr><td class="left">Subtotal</td><td>₱<?php echo number_format($subtotal, 2); ?></td></tr>
        <tr><td class="left">VAT (12%)</td><td>₱<?php echo number_format($vat, 2); ?></td></tr>
        <tr class="total-row"><td class="left">Total Bill</td><td>₱<?php echo number_format($bill_with_vat, 2); ?></td></tr>
        <tr><td class="left">Payment</td><td>₱<?php echo number_format($pay, 2); ?></td></tr>
        <?php if ($mchange >= 0): ?>
            <tr><td class="left">Change</td><td>₱<?php echo number_format($mchange, 2); ?></td></tr>
        <?php else: ?>
            <tr><td class="left" style="color:red;">Remaining Balance</td><td style="color:red;">₱<?php echo number_format(abs($mchange), 2); ?></td></tr>
        <?php endif; ?>
    </table>

    <p style="text-align:center; font-style: italic; margin-top:20px;">Thank you for your purchase!</p>
    <p style="text-align:center;">This is a system-generated receipt.</p>

    <div class="print-btn">
        <button onclick="window.print()">🖨️ Print Receipt</button>
        <button id="downloadBtn">📥 Download as PNG</button>
    </div>
</div>

<a href="cuslist.php">← Back to Customer List</a>

<script>
document.getElementById('downloadBtn').addEventListener('click', function () {
    const receipt = document.getElementById('receiptArea');
    html2canvas(receipt).then(canvas => {
        const link = document.createElement('a');
        link.download = 'receipt_<?php echo $cid; ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
});
</script>
</body>
</html>
<?php endif; ?>
