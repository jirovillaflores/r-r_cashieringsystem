<?php
// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Retrieve input values
    $x = $_POST['x'];
    $y = $_POST['y'];
    $radio = $_POST['radio'];
    
    $result = ''; // Initialize variable

    // Perform operation based on selected radio button
 if ($radio == "a") {
    $result = $x + $y;
} else if ($radio == "m") {
    $result = $x * $y;
} else if ($radio == "d") {
    if ($y != 0) {
        $result = $x / $y;
    } else {
        $result = "Error: Division by zero!";
    }
} else if ($radio == "s") {
    $result = $x - $y;
} else if ($radio == "mod") {
    $result = $x % $y;
} else {
    $result = "Please select an operation.";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
</head>
<body>
    <h1>Simple Calculator</h1>
    <form action="" method="POST">
        <input type="number" name="x" required> <br><br>
        
        <label><input type="radio" name="radio" value="m"> Multiply (*)</label><br>
        <label><input type="radio" name="radio" value="d"> Divide (/)</label><br>
        <label><input type="radio" name="radio" value="s"> Subtract (-)</label><br><br>
        <label><input type="radio" name="radio" value="a"> Add (+)</label><br>
        <label><input type="radio" name="radio" value="mod"> Modulus (%)</label><br>

        <input type="number" name="y" required><br><br>

        <button type="submit" name="submit">Submit</button>
    </form>

    <?php if (isset($result)) { ?>
        <h2>Result: <span style="color: red;"><?php echo $result; ?></span></h2>
    <?php } ?>
</body>
</html>
    
