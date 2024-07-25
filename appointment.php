<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    $user_id = $_SESSION['user_id'];
    $appointment_date = pg_escape_string($_POST['appointment_date']);
    $gender = pg_escape_string($_POST['gender']);
    $slot = pg_escape_string($_POST['slot']);
    $additional_details = pg_escape_string($_POST['additional_details']);

    // Generate XML data
    $xml_data = "<appointment>
                    <name>" . htmlspecialchars($user_id) . "</name>
                    <gender>" . htmlspecialchars($gender) . "</gender>
                    <date>" . htmlspecialchars($appointment_date) . "</date>
                    <slot>" . htmlspecialchars($slot) . "</slot>
                    <status>pending</status>
                    <additional_details>" . htmlspecialchars($additional_details) . "</additional_details>
                </appointment>";

    // Insert appointment data into the database
    $sql = "INSERT INTO public.appointments (user_id, appointment_date, gender, slot, additional_details, status, appointment_pass) 
            VALUES ('$user_id', '$appointment_date', '$gender', '$slot', '$additional_details', 'pending', '$xml_data')";
    $ret = pg_query($dbconn, $sql);

    if ($ret) {
        echo "Appointment saved successfully!";
    } else {
        echo "Something went wrong: " . pg_last_error($dbconn);
    }
}

// Fetch booked appointments
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM public.appointments WHERE user_id = '$user_id'";
$result = pg_query($dbconn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Appointment Booking</title>
  <meta name="keywords" content="PHP,PostgreSQL,Appointment">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Book Your Appointment</h2>
  <form method="post">
  
    <div class="form-group">
      <label for="appointment_date">Date of Appointment:</label>
      <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
    </div>
    
    <div class="form-group">
      <label for="gender">Gender:</label>
      <select class="form-control" id="gender" name="gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="slot">Slot:</label>
      <select class="form-control" id="slot" name="slot" required>
        <option value="slot1">Slot 1</option>
        <option value="slot2">Slot 2</option>
        <option value="slot3">Slot 3</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="additional_details">Additional Details:</label>
      <textarea class="form-control" id="additional_details" name="additional_details" rows="3"></textarea>
    </div>
     
    <input type="submit" name="submit" class="btn btn-primary" value="Book Appointment">
  </form>
  
  <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">HOME</button>

  <h2>Your Appointments</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Date</th>
        <th>Slot</th>
        <th>Status</th>
        <th>Print Pass</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = pg_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['appointment_date']; ?></td>
        <td><?php echo $row['slot']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
          <?php if ($row['status'] == 'accepted') { ?>
            <a href="pass.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Print Pass</a>
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
