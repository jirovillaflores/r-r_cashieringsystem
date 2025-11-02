<?php
$con = mysqli_connect("localhost", "root", "", "r&r_dbs");

if (!isset($_GET['id'])) {
    echo "Customer not selected. <a href='cuslist.php'>Go back</a>";
    exit();
}

$customer_id = $_GET['id'];

// Get customer name
$customer_query = "SELECT lname, fname, mname FROM cuslist WHERE id = $customer_id";
$customer_result = mysqli_query($con, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);
$cname = $customer['lname'] . ', ' . $customer['fname'] . ' ' . $customer['mname'];

// Handle order submission
if (isset($_POST['add_order'])) {
    $pid = $_POST['pid'];
    $qty = $_POST['qty'];

    $product_query = "SELECT product, price FROM products WHERE id = $pid";
    $product_result = mysqli_query($con, $product_query);
    $product = mysqli_fetch_assoc($product_result);

    $item_name = $product['product'];
    $price = $product['price'];
    $total = $price * $qty;
    $sdate = date("Y-m-d");

    $insert = "INSERT INTO product_solds (s_item, s_price, s_qty, s_total, s_cus, s_date) 
               VALUES ('$item_name', '$price', '$qty', '$total', '$customer_id', '$sdate')";
    mysqli_query($con, $insert);
}

// Product list
$product_list_query = "SELECT id, product FROM products ORDER BY product ASC";
$product_list_result = mysqli_query($con, $product_list_query);

// Customer orders
$order_query = "SELECT s_item, s_price, s_qty, s_total FROM product_solds WHERE s_cus = '$customer_id' ORDER BY s_id ASC";
$order_result = mysqli_query($con, $order_query);

// Grand total
$total_query = "SELECT SUM(s_total) AS total FROM product_solds WHERE s_cus = '$customer_id'";
$total_result = mysqli_fetch_assoc(mysqli_query($con, $total_query));
$grand_total = $total_result['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Order | R & R Hardware</title>
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
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        label {
            display: inline-block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }

        button {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #059669;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
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

        .total-row {
            font-weight: bold;
            background-color: #fef3c7;
        }

        .billing-btn {
            background-color: #2563eb;
            margin-top: 20px;
        }

        .billing-btn:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            button, .billing-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>R & R HARDWARE AND CONSTRUCTION SUPPLIES</h1>
</header>

<div class="container">
    <h3>Add Order for: <?php echo htmlspecialchars($cname); ?></h3>

    <form method="POST">
        <label>Product:</label>
        <select name="pid" required>
            <option value="">-- Select Product --</option>
            <?php while($row = mysqli_fetch_assoc($product_list_result)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['product']; ?></option>
            <?php } ?>
        </select>

        <label>Quantity:</label>
        <input type="number" name="qty" min="1" required>

        <button type="submit" name="add_order">➕ Add Order</button>
    </form>

    <h3>Current Orders</h3>
    <table>
        <tr>
            <th>Item</th>
            <th>Price (₱)</th>
            <th>Qty</th>
            <th>Total (₱)</th>
        </tr>
        <?php while ($item = mysqli_fetch_assoc($order_result)) { ?>
            <tr>
                <td><?php echo $item['s_item']; ?></td>
                <td><?php echo number_format($item['s_price'], 2); ?></td>
                <td><?php echo $item['s_qty']; ?></td>
                <td><?php echo number_format($item['s_total'], 2); ?></td>
            </tr>
        <?php } ?>
        <tr class="total-row">
            <td colspan="3">Grand Total:</td>
            <td>₱<?php echo number_format($grand_total, 2); ?></td>
        </tr>
    </table>

    <a href="paybill.php?id=<?php echo $customer_id; ?>">
        <button class="billing-btn">🧾 Proceed to Billing</button>
    </a>
</div>

</body>
</html>

<?php mysqli_close($con); ?>
