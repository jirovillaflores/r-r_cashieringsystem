<!-- <?php 

// header ('location: index.php');
?> -->


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="">
        <input type="email" id="email"><br>
        <input type="password" id="password"><br>
        <button class="btn-login">Log in</button>

    </form>
</body>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-login').on('click', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var password = $('#password').val();

                // if(password < 8) {
                //     alert("Password must be 8 characters " + password);
               
                if(password == '') {
                    alert("Password is empty " + password);
                }
                
            
            $.ajax({
                method: 'post',
                action: 'handlers/login.php',
                data: {
                    login_user: true,
                    email: 'email',
                    password: 'password'
                }
            })
        })    
    });
    </script> -->
   <!-- <?php
session_start();
include('login.php'); // Database connection file

// Handle login submission
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        $error = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="index.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
</head>
<body>

<div class="d">
      <a href="#" class="d1">
          <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
          Flowbite
      </a>

      <div class="s">
          <h1 class="f">Sign in to your account</h1>

          <?php if (isset($error)): ?>
              <p style="color: red; text-align: center;"><?php echo $error; ?></p>
          <?php endif; ?>

          <form action="" method="POST" class="form">
              <div>
                  <label for="email">Your email</label><br>
                  <input type="email" name="email" id="email" placeholder="name@company.com" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
              </div>

              <div>
                  <label for="password">Password</label><br>
                  <input type="password" name="password" id="password" placeholder="••••••••" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
              </div>

              <div class="check-box">
                  <label><input type="checkbox" name="remember"> Remember me</label>
                  <a href="#" style="font-size: 14px;">Forgot password?</a>
              </div>

              <button class="btn-click" name="submit" a href="home.php">Sign in</a></button>

              <p class="que">
                  Don’t have an account yet?
                  <a href="register.php" style="color: #2563eb; text-decoration: none;">Sign up</a>
              </p>
          </form>
      </div>
  </div>
</section>


</body>
</html>
