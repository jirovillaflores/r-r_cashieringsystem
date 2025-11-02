<?php
$con = mysqli_connect("localhost", "root", "", "r&r_dbs");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the sold item ID from the URL
$sid = isset($_GET['s']) ? intval($_GET['s']) : 0;

if (!$sid) {
    die("Invalid order selected.");
}

// Get customer ID before deletion (to redirect back)
$cid_query = "SELECT s_cus FROM product_solds WHERE s_id = '$sid'";
$cid_result = mysqli_query($con, $cid_query);
$cid_row = mysqli_fetch_assoc($cid_result);

if (!$cid_row) {
    die("Order not found.");
}

$cid = $cid_row['s_cus'];

// Delete the order
$delete_query = "DELETE FROM product_solds WHERE s_id = '$sid'";
if (mysqli_query($con, $delete_query)) {
    // Redirect back to paybill.php for the same customer
    header("Location: paybill.php?id=$cid");
    exit();
} else {
    echo "Error deleting order: " . mysqli_error($con);
}
?>
