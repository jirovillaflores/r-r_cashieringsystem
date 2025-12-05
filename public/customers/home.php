<?php
session_start();

// ID from session
$id = $_SESSION['id'];

include('../../Classes/Client.php');
$view = new Users();
$row = $view->UserOrders($id);

echo "Welcome, user " . htmlspecialchars($_SESSION['email']) . "!";
?>
<br><br>

Order Number:<br>
<input type="text" value="108"><br>
<input type="text" name="" id="address" value="" placeholder="Address"><br>
<input type="text" name="" id="contact" value="" placeholder="Contact"><br>
<input type="number" id="amount" value="1090"><br>

<button class="--btn-book"
        data-user-id="<?php echo htmlspecialchars($_SESSION['id']); ?>"
        data-room-id="23">
    Book now!
</button>

<br><br>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if (is_array($row)) {
        foreach ($row as $data) { ?>
            <tr>
                <td><?= htmlspecialchars($data['user_id']); ?></td>
                <td><?= htmlspecialchars($data['amount']); ?></td>
            </tr>
    <?php 
        }
    } else {
        echo "<tr><td colspan='3'>No orders found.</td></tr>";
    }
    ?>
    </tbody>
</table>


<script src="../../assets/js/jquery.js"></script>
<script>
$(document).ready(function() {

    $('#--btn-book').on('click', function(e) {
        e.preventDefault();

        const add = $('#address').val();
        const contact = $('#contact').val();
        const amount = $('#amount').val();

        const userId = $(this).data('user-id');  
        const roomId = $(this).data('room-id');

        console.log(amount, roomId, add, contact);

        $.ajax({
            url: '../handlers/book.php',
            method: "POST",
            data: {
                book: true,
                address: add,
                amount: amount,
                contact: contact,
                user_id: userId,
                room_id: roomId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.success);
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Request failed.");
            }
        });
    });
});
</script>
