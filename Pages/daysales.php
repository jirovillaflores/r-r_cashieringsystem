<?php
$con = mysqli_connect("localhost", "root", "", "r&r_dbs");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Manila');
$date_today = date("Y-m-d");
$datetime_now = date("F d, Y h:i:s A T");

$query_totals = "SELECT SUM(bill) AS total_bills, SUM(pay) AS total_payments, SUM(mchange) AS total_changes FROM cus_pay WHERE DATE(bdate) = '$date_today'";
$result_totals = mysqli_query($con, $query_totals);
$row_totals = mysqli_fetch_assoc($result_totals);

$total_bills = $row_totals['total_bills'] ?? 0;
$total_payments = $row_totals['total_payments'] ?? 0;
$total_changes = $row_totals['total_changes'] ?? 0;

$query_details = "SELECT cname, bill, pay, mchange FROM cus_pay WHERE DATE(bdate) = '$date_today' ORDER BY cname DESC";
$result_details = mysqli_query($con, $query_details);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Daily Total Sales | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
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
    }
    .container {
        max-width: 900px;
        background: #fff;
        margin: 40px auto;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 0;
        font-weight: 700;
        color: #111827;
    }
    p.address {
        text-align: center;
        margin: 5px 0 25px;
        font-size: 0.9rem;
        color: #4b5563;
    }
    hr {
        border: none;
        border-top: 1px solid #d1d5db;
        margin: 20px 0 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }
    th, td {
        border: 1px solid #d1d5db;
        padding: 12px 15px;
        text-align: right;
    }
    th {
        background-color: #e5e7eb;
        font-weight: 600;
        color: #374151;
    }
    td.name {
        text-align: left;
        font-weight: 600;
        color: #1f2937;
    }
    tfoot tr {
        font-weight: 700;
        background-color: #f3f4f6;
    }
    tfoot td {
        color: #111827;
    }
    tfoot td.change {
        color: <?= ($total_changes < 0) ? '#dc2626' : '#16a34a' ?>;
    }
    td.change {
        color: inherit;
    }
    .signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 60px;
        font-size: 0.95rem;
        color: #374151;
    }
    .signatures div {
        width: 45%;
        text-align: center;
    }
    .signatures strong {
        display: block;
        margin-bottom: 60px;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .buttons {
        text-align: center;
        margin-top: 40px;
    }
    button {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 12px 25px;
        margin: 0 10px;
        font-size: 1rem;
        border-radius: 7px;
        cursor: pointer;
        transition: background-color 0.25s ease;
    }
    button:hover {
        background-color: #1d4ed8;
    }
    @media (max-width: 600px) {
        .container {
            margin: 20px 15px;
            padding: 20px;
        }
        .signatures {
            flex-direction: column;
            gap: 40px;
        }
        .signatures div {
            width: 100%;
        }
        button {
            width: 100%;
            margin: 10px 0;
        }
    }
</style>
</head>
<body>
<header>R & R HARDWARE AND CONSTRUCTION SUPPLIES</header>

<div class="container" id="salesReport">
    <p class="address">
        Looc, Brgy. II, Bais City, Philippines<br>
        VAT Reg TIN: 486-704-368-00000 | Tel: (035) 423-6145
    </p>
    <hr>

    <h2>Total Sales as of <?= $datetime_now ?></h2>

    <table id="salesTable" aria-label="Daily sales details">
        <thead>
            <tr>
                <th scope="col">Customer Name</th>
                <th scope="col">Bill (₱)</th>
                <th scope="col">Payment (₱)</th>
                <th scope="col">Change (₱)</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result_details) > 0): 
            while ($row = mysqli_fetch_assoc($result_details)): ?>
            <tr>
                <td class="name"><?= htmlspecialchars($row['cname']) ?></td>
                <td><?= number_format($row['bill'], 2) ?></td>
                <td><?= number_format($row['pay'], 2) ?></td>
                <td class="change" style="color: <?= ($row['mchange'] < 0) ? '#dc2626' : '#16a34a' ?>">
                    <?= number_format($row['mchange'], 2) ?>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="4" style="text-align:center; color:#6b7280;">No payments found for today.</td></tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: right;">TOTAL:</td>
                <td><?= "₱" . number_format($total_bills, 2) ?></td>
                <td><?= "₱" . number_format($total_payments, 2) ?></td>
                <td class="change"><?= "₱" . number_format($total_changes, 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div>
            <p><strong>Prepared by:</strong></p>
            <p style="margin-top: 50px; font-weight: 700;">VENZ HORACE D. CARABOT</p>
            <p>Finance Officer</p>
        </div>
        <div>
            <p><strong>Approved by:</strong></p>
            <p style="margin-top: 50px; font-weight: 700;">REYNALDO D. TOLOMIA</p>
            <p>Proprietor</p>
        </div>
    </div>

    <div class="buttons">
        <button onclick="window.print()">🖨️ Print</button>
        <button onclick="downloadCSV()">📁 Export as CSV</button>
    </div>
</div>

<script>
function downloadCSV() {
    const table = document.getElementById("salesTable");
    let csv = "";

    for (let row of table.rows) {
        let rowData = [];
        for (let cell of row.cells) {
            let text = cell.innerText.replace(/,/g, ""); // remove commas to avoid CSV issues
            rowData.push(`"${text}"`);
        }
        csv += rowData.join(",") + "\n";
    }

    const blob = new Blob([csv], {type: "text/csv"});
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = `Total_Sales_<?= date('Y-m-d_H-i-s') ?>.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
</body>
</html>
