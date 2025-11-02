<?php
$con = mysqli_connect("localhost", "root", "", "r&r_dbs");

date_default_timezone_set("America/New_York");
$date = date("Y-m-d");

$query = "SELECT SUM(s_total) as btotal from item_solds WHERE s_date='$date'";
$result = mysqli_query($con, $query);

	while($rows = mysqli_fetch_array($result)){
		$total = $rows['btotal'];
		$bill_total = number_format($total, 2);
?>

<table border="1">

	<tr>
		<td><strong> Cashier Name </strong></td>
		<td><strong> VILLAFLORES, JIRO </strong></td>
	</tr>
	<tr>
		<td><strong> Overall Total Sold: </strong></td>
		<td><strong> <?php echo $bill_total; ?> </strong></td>
	</tr>

<?php } ?>	

</table>
	