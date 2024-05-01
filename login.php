<?php
session_start(); // Start the session

include 'components/connect.php'; // Include database connection

// Check if the user is already logged in via cookie
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Handle form submission
if(isset($_POST['submit'])){
   // Sanitize and fetch user input
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']); // Hash password for comparison
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Prepare and execute SQL to select user
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   // If user exists and email domain is @somaiya.edu, set session and redirect to home.php
   if($select_user->rowCount() > 0){
     $email_parts = explode('@', $email);
     if ($email_parts[1] === 'somaiya.edu') {
         $_SESSION['user'] = $row['id']; // Set user ID in session
         $_SESSION['role'] = $row['role']; // Set user role in session
         setcookie('user_id', $row['id'], time() + 60*60*24*30, '/'); // Set user ID in cookie
         header('location:home.php'); // Redirect to home page
         exit();
     } else {
         $message[] = 'You can only sign in with @somaiya.edu email address.';
     }
   }else{
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="css/style.css"> <!-- Add path to your CSS file -->
   <style>
      body, html {
         margin: 0;
         padding: 0;
         height: 100%;
      }

      .form-container {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
      }

      form {
         width: 250px;
         border: 1px solid #ccc;
         padding: 20px;
         border-radius: 5px;
         background-color: #f9f9f9;
         box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }

      .box {
         width: 100%;
         padding: 10px;
         margin-bottom: 10px;
         border: 1px solid #ccc;
         border-radius: 3px;
      }

      .btn {
         width: 100%;
         padding: 10px;
         background-color: darkred;
         color: #fff;
         border: none;
         border-radius: 3px;
         cursor: pointer;
      }

      .btn:hover {
         background-color: #0056b3;
      }

      .link {
         text-align: center;
         margin-top: 10px;
      }

      .link a {
         color: #007bff;
         text-decoration: none;
      }

      .link a:hover {
         text-decoration: underline;
      }
   </style>
</head>
<body>
   <section class="form-container">
      <form action="login.php" method="post" enctype="multipart/form-data" class="login" id="loginForm">
         <h3>Welcome back!</h3>
         <p>Your email <span>*</span></p>
         <input type="email" name="email" id="email" placeholder="enter your email" maxlength="50" required class="box">
         <p>Your password <span>*</span></p>
         <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
         <p class="link">Don't have an account? <a href="register.php">Register now</a></p>
         <input type="submit" name="submit" value="login now" class="btn">
      </form>
   </section>
   <?php include 'components/footer.php'; ?> <!-- Include your footer file -->
   <script src="js/script.js"></script> <!-- Add path to your JS file -->
   <script>
      // Function to validate email domain
      function validateEmail() {
         var email = document.getElementById("email").value;
         var email_parts = email.split("@");
         if (email_parts[1] !== "somaiya.edu") {
            alert("You can only sign in with @somaiya.edu email address.");
            return false;
         }
         return true;
      }

      // Add event listener to the form
      document.getElementById("loginForm").addEventListener("submit", function(event) {
         if (!validateEmail()) {
            event.preventDefault(); // Prevent form submission if email is not valid
         }
      });
   </script>
</body>
</html>
