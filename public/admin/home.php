<?php
session_start();

    echo "Welcome admin " . htmlspecialchars($_SESSION['email']) . " !";
?>

<br><br><br><br>
<button>Add Item</button>