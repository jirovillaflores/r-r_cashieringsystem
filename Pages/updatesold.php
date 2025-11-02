<?php
$db = mysqli_connect("localhost", "root", "", "r&r_dbs");

if (isset($_GET['s'])) {
    $sid = intval($_GET['s']); // secure ID
    $sql = "SELECT * FROM product_solds WHERE s_id='$sid'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
</head>
<body>
    <h2>Update Product Sold</h2>
    <form method="POST">
        <input type="hidden" name="sid" value="<?php echo $sid; ?>">

        Item: <input type="text" value="<?php echo $row['s_item']; ?>" disabled>
        <input type="hidden" name="sitem" value="<?php echo $row['s_item']; ?>"><br><br>

        Price: <input type="text" value="<?php echo $row['s_price']; ?>" disabled>
        <input type="hidden" name="sprice" value="<?php echo $row['s_price']; ?>"><br><br>

        Quantity: <input type="number" name="qty" value="<?php echo $row['s_qty']; ?>" required><br><br>

        <input type="hidden" name="sdate" value="<?php echo $row['s_date']; ?>">
        <input type="hidden" name="s_cus" value="<?php echo $row['s_cus']; ?>">

        <button type="submit" name="update">Confirm</button>
    </form>
</body>
</html>
<?php
    } else {
        echo "Item not found.";
    }
}

// Process update
if (isset($_POST['update'])) {
    $sid = $_POST['sid'];
    $item = $_POST['sitem'];
    $sprice = $_POST['sprice'];
    $qty = $_POST['qty'];
    $total = $sprice * $qty;
    $sdate = $_POST['sdate'];
    $s_cus = $_POST['s_cus'];

    $update_sql = "UPDATE product_solds SET 
                    s_item='$item',
                    s_price='$sprice',
                    s_qty='$qty',
                    s_total='$total',
                    s_cus='$s_cus',
                    s_date='$sdate' 
                   WHERE s_id='$sid'";

    if (mysqli_query($db, $update_sql)) {
        header("Location: paybill.php?id=$s_cus"); // redirect back to receipt
        exit();
    } else {
        echo "Update error: " . mysqli_error($db);
    }
}
?>
