<?php
// printbill.php

$con = mysqli_connect("localhost", "root", "", "r&r_dbs");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Manila');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing customer ID.");
}
$cid = intval($_GET['id']);

$stmt = mysqli_prepare($con, "SELECT cname, bill, pay, mchange, bdate, VAT FROM cus_pay WHERE cid = ?");
mysqli_stmt_bind_param($stmt, "i", $cid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("No payment record found for customer ID: $cid");
}

$payment = mysqli_fetch_assoc($result);
$cname = $payment['cname'];
$bill_with_vat = $payment['bill'];
$pay = $payment['pay'];
$mchange = $payment['mchange'];
$vat = $payment['VAT'];

$dt = new DateTime($payment['bdate'], new DateTimeZone('UTC'));
$dt->setTimezone(new DateTimeZone('Asia/Manila'));
$b_date = $dt->format("Y-m-d h:i:sa");

$stmt_items = mysqli_prepare($con, "SELECT s_item, s_qty, s_price, s_total FROM product_solds WHERE s_cus = ?");
mysqli_stmt_bind_param($stmt_items, "i", $cid);
mysqli_stmt_execute($stmt_items);
$item_result = mysqli_stmt_get_result($stmt_items);

$subtotal = 0;
$items = [];
while ($row = mysqli_fetch_assoc($item_result)) {
    $items[] = $row;
    $subtotal += floatval($row['s_total']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Receipt | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
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
        max-width: 700px;
        background: white;
        margin: 0 auto;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h2, h3 {
        text-align: center;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
    }
    p.center {
        text-align: center;
        color: #4b5563;
        margin: 5px 0 20px;
        font-size: 0.9rem;
    }
    hr {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 20px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        border-bottom: 1px solid #d1d5db;
        padding: 12px 15px;
        font-size: 0.95rem;
    }
    th {
        text-align: left;
        background-color: #f3f4f6;
        color: #374151;
        font-weight: 700;
    }
    td.right {
        text-align: right;
    }
    tbody tr:hover {
        background-color: #e0e7ff;
    }
    .summary-table td {
        border: none;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 1rem;
    }
    .summary-table tr td:first-child {
        text-align: left;
    }
    .summary-table tr td:last-child {
        text-align: right;
    }
    .summary-table tr strong {
        font-weight: 700;
        color: #111827;
    }
    .summary-table tr.change-negative td {
        color: #b91c1c; /* red */
    }
    .print-btn {
        text-align: center;
        margin-top: 30px;
    }
    .print-btn button, .print-btn a {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 8px 5px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }
    .print-btn button:hover, .print-btn a:hover {
        background-color: #1d4ed8;
    }
    .print-btn a {
        line-height: 2.2;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>

<header>R & R HARDWARE AND CONSTRUCTION SUPPLIES</header>

<div class="container receipt">
    <h2>Receipt</h2>
    <p class="center">
        Looc, Brgy. II, Bais City, Philippines<br>
        VAT Reg TIN: 486-704-368-00000 | Tel: (035) 423-6145
    </p>
    <hr>
    <p><strong>Customer:</strong> <?php echo htmlspecialchars($cname); ?><br>
       <strong>Date/Time:</strong> <?php echo $b_date; ?></p>

    <h3>Products Purchased</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="right">Qty</th>
                <th class="right">Price</th>
                <th class="right">Subtotal</th>
                <th class="right">VAT (12%)</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item):
                $item_vat = round($item['s_total'] * 0.12, 2);
                $item_total = round($item['s_total'] + $item_vat, 2);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['s_item']); ?></td>
                <td class="right"><?php echo intval($item['s_qty']); ?></td>
                <td class="right">₱<?php echo number_format($item['s_price'], 2); ?></td>
                <td class="right">₱<?php echo number_format($item['s_total'], 2); ?></td>
                <td class="right">₱<?php echo number_format($item_vat, 2); ?></td>
                <td class="right">₱<?php echo number_format($item_total, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>
    <table class="summary-table">
        <tr>
            <td><strong>Subtotal</strong></td>
            <td class="right">₱<?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <tr>
            <td><strong>Total VAT</strong></td>
            <td class="right">₱<?php echo number_format($vat, 2); ?></td>
        </tr>
        <tr>
            <td><strong>Total Bill</strong></td>
            <td class="right"><strong>₱<?php echo number_format($bill_with_vat, 2); ?></strong></td>
        </tr>
        <tr>
            <td><strong>Payment</strong></td>
            <td class="right">₱<?php echo number_format($pay, 2); ?></td>
        </tr>
        <tr class="<?php echo ($mchange >= 0) ? '' : 'change-negative'; ?>">
            <td><strong><?php echo ($mchange >= 0) ? 'Change' : 'Remaining Balance'; ?></strong></td>
            <td class="right">
                ₱<?php echo number_format(abs($mchange), 2); ?>
            </td>
        </tr>
    </table>

    <hr>
    <p class="center" style="font-style: italic;">Thank you for your purchase!</p>
    <p class="center">This is a system-generated receipt.</p>

    <div class="print-btn">
        <button onclick="window.print()">🖨️ Print Receipt</button>
        <button id="downloadBtn">📥 Download as PNG</button>
        <a href="cuslist.php">← Back to Customer List</a>
    </div>
</div>

<script>
document.getElementById('downloadBtn').addEventListener('click', function () {
    const receipt = document.querySelector('.receipt');
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
