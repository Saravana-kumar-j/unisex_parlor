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

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $name = pg_escape_string($_POST['name']);
    $email = pg_escape_string($_POST['email']);
    $password = pg_escape_string($_POST['pwd']);
    $mobno = pg_escape_string($_POST['mobno']);

    $sql = "INSERT INTO public.\"user\" (name, email, password, mobno) VALUES ('$name', '$email', '$password', '$mobno')";
    $ret = pg_query($dbconn, $sql);

    if ($ret) {
        echo "Data saved successfully";
    } else {
        echo "Something went wrong: " . pg_last_error($dbconn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Unisex Parlor</title>
  <meta name="keywords" content="PHP,PostgreSQL,Insert,Login">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Register Here</h2>
  <form method="post">
  
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
    </div>
    
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div>
    
    <div class="form-group">
      <label for="mobno">Mobile No:</label>
      <input type="number" class="form-control" maxlength="10" id="mobileno" placeholder="Enter Mobile Number" name="mobno" required>
    </div>
    
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" required>
    </div>
     
    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='login.php'">Go to Login</button>
  </form>
  <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">HOME</button>

</div>

</body>
</html>
