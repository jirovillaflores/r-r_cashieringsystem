<?php
$con = mysqli_connect("localhost", "root", "", "r&r_dbs");

$cid = isset($_GET['id']) ? $_GET['id'] : 0;
if (!$cid) die("No customer selected.");

$cust_query = "SELECT id, lname, fname, mname FROM cuslist WHERE id = '$cid'";
$cust_result = mysqli_query($con, $cust_query);
if (!$cust_result || mysqli_num_rows($cust_result) == 0) die("Customer not found.");
$cust = mysqli_fetch_assoc($cust_result);
$cname = htmlspecialchars($cust['lname'] . ', ' . $cust['fname'] . ' ' . $cust['mname']);

$item_query = "SELECT * FROM product_solds WHERE s_cus='$cid' ORDER BY s_id ASC";
$item_result = mysqli_query($con, $item_query);

$total_query = "SELECT SUM(s_total) as btotal FROM product_solds WHERE s_cus='$cid'";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$btotal = isset($total_row['btotal']) ? floatval($total_row['btotal']) : 0.00;
$bill_total = number_format($btotal, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing | R & R HARDWARE</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
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
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        h2, h3 {
            color: #1f2937;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table, th, td {
            border: 1px solid #d1d5db;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #e5e7eb;
        }

        a button, button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        a button:hover, button:hover {
            background-color: #1d4ed8;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #f3f4f6;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        .back-button {
            margin: 15px 0;
        }

        .summary {
            font-weight: bold;
        }

        @media (max-width: 768px) {
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
    <h2>Payment Breakdown</h2>
    <p><strong>Customer:</strong> <?= $cname ?></p>

    <div class="back-button">
        <a href="addorder.php?id=<?= $cid ?>"><button type="button">← Back to Add Order</button></a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Items</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        mysqli_data_seek($item_result, 0); 
        while($row = mysqli_fetch_assoc($item_result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['s_item']) ?></td>
                <td>₱<?= number_format($row['s_price'], 2) ?></td>
                <td><?= $row['s_qty'] ?></td>
                <td>₱<?= number_format($row['s_total'], 2) ?></td>
                <td><a href="updatesold.php?s=<?= $row['s_id'] ?>"><button>Edit</button></a></td>
                <td><a href="deleteorder.php?s=<?= $row['s_id'] ?>"><button>Delete</button></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <h3>VAT Breakdown (12%)</h3>
    <table>
        <tr>
            <th>Item</th>
            <th>Total</th>
            <th>VAT (12%)</th>
        </tr>
        <?php 
        mysqli_data_seek($item_result, 0);
        $total_sales = 0;
        $total_vat = 0;
        while($row = mysqli_fetch_assoc($item_result)) { 
            $subtotal = $row['s_total'];
            $vat = $subtotal * 0.12;
            $total_sales += $subtotal;
            $total_vat += $vat;
        ?>
        <tr>
            <td><?= htmlspecialchars($row['s_item']) ?></td>
            <td>₱<?= number_format($subtotal, 2) ?></td>
            <td>₱<?= number_format($vat, 2) ?></td>
        </tr>
        <?php } 
        $grand_total = $total_sales + $total_vat;
        ?>
        <tr class="summary">
            <td colspan="2">Total VAT:</td>
            <td>₱<?= number_format($total_vat, 2) ?></td>
        </tr>
    </table>

    <h3>Billing Summary</h3>
    <table>
        <tr>
            <th>Total (No VAT)</th>
            <td>₱<?= number_format($total_sales, 2) ?></td>
        </tr>
        <tr>
            <th>VAT (12%)</th>
            <td>₱<?= number_format($total_vat, 2) ?></td>
        </tr>
        <tr class="summary">
            <th>Grand Total</th>
            <td><strong>₱<?= number_format($grand_total, 2) ?></strong></td>
        </tr>
    </table>

    <form method="POST" action="printbill.php">
        <label>Pay:</label>
        <input type="number" name="pay" step="0.01" required>
        <input type="hidden" name="id" value="<?= $cid ?>">
        <input type="hidden" name="btotal" value="<?= $grand_total ?>">
        <button type="submit" name="confirm">Confirm</button>
    </form>
</div>

</body>
</html>
