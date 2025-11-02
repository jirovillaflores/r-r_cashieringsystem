<?php
// delete.php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing customer ID.");
}

$cid = intval($_GET['id']);

$con = mysqli_connect("localhost", "root", "", "r&r_dbs");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: You can add checks here (e.g., if customer exists) before deleting.

$stmt = mysqli_prepare($con, "DELETE FROM cuslist WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $cid);

if (mysqli_stmt_execute($stmt)) {
    // Optionally, you could also delete related records (orders, payments) here if needed.
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    // Redirect back to customer list after deletion
    header("Location: cuslist.php?msg=Customer+deleted+successfully");
    exit;
} else {
    die("Error deleting customer: " . mysqli_error($con));
}
