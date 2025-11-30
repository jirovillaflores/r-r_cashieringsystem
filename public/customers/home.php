<?php
session_start();

$id = $_SESSION['id'];


include('../Classes/Client.php');
$view = new Users();
$row = $view->userOrders($id);


    echo "Welcome, user" . htmlspecialchars($_SESSION['email']) . " !";
?>
<br><br><br><br>
Order Number: <br>
<input type="text" value="108">
<input type="text" name="" id="address" placeholder="Address"><br>
<input type="text" name="" id="contact" placeholder="Contact"><br>
<input type="number" name="" id="amount" value="1090"><br>

<button class="--btn-book" 
data-user-id="<?php echo htmlspecialchars($_SESSION['id']) ?>"
data-room-id="23">Book now!</button>

<table>
    <th>
        <tr>Order ID</tr>
        <tr>User</tr>
        <tr>Amount</tr>
    </th>

    <?php foreach ($row as $data) { ?>
        <tbody>
            <tr>1</tr>
            <tr>2</tr>
            <tr>3</tr>
        </tbody>
        <?php } ?>
</table>



<script src="../../assets/js/jquery.js"></script>
<script>
    $(document).ready(function(e){ 
    e.preventDefault() 
        const add = $('#address').val();
        const contact = $('#contact').val();
        const amount = $('#amount').val();
        const userId = $(this).data('user-id');
        const roomId = $(this).data('room-id');
 
            console.log(amount , roomId , add , contact)

                $.ajax({
                    url: '../handlers/book.php',
                    method: "post",
                    data: {
                        'book': true,
                        'address': add,
                    'amount': amount,
                    'contact': contact,
                    'user_id': userId,
                    'room_id': roomId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.success)
            } else {
                alert(response.error)
            }
        },
        error: function(xhr, response) {
            console.log(xhr + response)
        }
    });
    });

    </script>
