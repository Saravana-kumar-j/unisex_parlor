<?php
$host = "localhost";
$port = "5432";
$dbname = "myapp";
$user = "postgres";
$password = "postgres";  // Replace with your actual PostgreSQL password
$connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

$dbconn = pg_connect($connection_string);

if (!$dbconn) {
    die("Connection failed: " . pg_last_error());
}

session_start();

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = pg_escape_string($_POST['email']);
    $password = pg_escape_string($_POST['pwd']);

    $sql = "SELECT * FROM public.\"user\" WHERE email = '$email' AND password = '$password'";
    $result = pg_query($dbconn, $sql);

    if ($result) {
        if (pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: appointment.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Something went wrong: " . pg_last_error($dbconn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Unisex Parlor</title>
  <meta name="keywords" content="PHP,PostgreSQL,Login">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Login Here</h2>
  <form method="post">
  
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div>
    
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" required>
    </div>
     
    <input type="submit" name="submit" class="btn btn-primary" value="Login">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php'">Register here</button>
  </form>
  <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">HOME</button>
</div>

</body>
</html>
