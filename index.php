<!DOCTYPE html>
<html lang="en">
<head>
  <title>Unisex Parlour</title>
  <meta name="keywords" content="PHP,PostgreSQL,Homepage">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #ffecd2, #fcb69f);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Arial', sans-serif;
      color: #333;
    }
    .container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .btn-group-vertical {
      width: 100%;
    }
    .btn {
      margin-bottom: 10px;
      padding: 15px;
      font-size: 1.2em;
      border: none;
      border-radius: 10px;
      transition: background 0.3s, transform 0.3s;
    }
    .btn-login {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: white;
    }
    .btn-register {
      background: linear-gradient(to right, #ff416c, #ff4b2b);
      color: white;
    }
    .btn-admin {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: white;
    }
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 2.5em;
      color: #ff6347;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Welcome to Unisex Parlour</h2>
  <div class="btn-group-vertical">
    <a href="login.php" class="btn btn-login btn-lg">Login</a>
    <a href="register.php" class="btn btn-register btn-lg">Register</a>
    <a href="admin.php" class="btn btn-admin btn-lg">Admin</a>
  </div>
</div>

</body>
</html>
