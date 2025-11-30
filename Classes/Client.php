<?php
require_once('Connection.php');

class Users extends Dbh
{
    public function signup($email, $hashed_password)
    {
        
        $search = $this->connect()->prepare('SELECT email FROM user WHERE email = ?');
        $search->bind_param('s', $email);
        $search->execute();
        $search->store_result();

        if ($search->num_rows > 0) {
            return 3; // Email already exists
        }

        $stmt = $this->connect()->prepare(
            'INSERT INTO user (email, pass, created_at) VALUES (?, ?, NOW())'
        );

        $stmt->bind_param('ss', $email, $hashed_password);
        $result = $stmt->execute();

        if ($result) {
            return 1; // Success
        } else {
            return 2; // Database error
        }
    }
  

    public function login($email, $pass) {
    session_start();

    $stmt = $this->connect()->prepare("SELECT id, email, pass FROM user WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // USER FOUND?
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()){
             $hashed_password = $row['pass'];

        // PASSWORD CHECK
        if (password_verify($pass, $hashed_password)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            $redirect = ($_SESSION['id'] == 1)
                ? '../public/admin/home.php'
                : '../public/customers/home.php';

            return $redirect;

        } else {
            return 9; // incorrect password
        }
    }
    } else {
        return 8; // user not found
    }
    }

    public function bookNow($add, $contact, $userId, $orderId) {
    $stmt = $this->connect()->prepare("INSERT INTO orders (order_id, user_id, quantity, total_amount, status, address, contact) VALUES (?, ?, ?, ?, 'pending', ?, ?)");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // User not found
    }
}

     public function userOrders($id) {

    $conn = $this->connect();
    if (!$conn) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM user u INNER JOIN orders o ON o.user_id = u.id WHERE u.id = ?";
    
    
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();


    return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
}
}