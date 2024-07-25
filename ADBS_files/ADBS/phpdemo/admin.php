<?php
session_start();

// Admin credentials
$admin_user = "admin";
$admin_password = "admin123";

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) && isset($_POST['admin_user']) && isset($_POST['admin_password'])) {
    if ($_POST['admin_user'] === $admin_user && $_POST['admin_password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        echo "Invalid admin credentials.";
    }
}

if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <form method="post">
        <label for="admin_user">Username:</label>
        <input type="text" id="admin_user" name="admin_user">
        <label for="admin_password">Password:</label>
        <input type="password" id="admin_password" name="admin_password">
        <input type="submit" value="Login">
    </form>
    <?php
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

if (isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Fetch the current XML data
    $sql = "SELECT appointment_pass FROM public.appointments WHERE id = '$appointment_id'";
    $result = pg_query($dbconn, $sql);
    $appointment = pg_fetch_assoc($result);
    $xml_data = $appointment['appointment_pass'];

    // Load and update the XML data
    $xml = simplexml_load_string($xml_data);
    if ($xml !== false) {
        $xml->status = $status;
        $updated_xml_data = $xml->asXML();
    } else {
        echo "Failed to parse XML data.";
        exit();
    }

    // Update the status in the table and XML data
    $sql = "UPDATE public.appointments SET status = '$status', appointment_pass = '$updated_xml_data' WHERE id = '$appointment_id'";
    $ret = pg_query($dbconn, $sql);

    if ($ret) {
        echo "Status and XML data updated successfully!";
    } else {
        echo "Something went wrong: " . pg_last_error($dbconn);
    }
}

if (isset($_POST['delete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    $sql = "DELETE FROM public.appointments WHERE id = '$appointment_id'";
    $ret = pg_query($dbconn, $sql);

    if ($ret) {
        echo "Appointment removed successfully!";
    } else {
        echo "Something went wrong: " . pg_last_error($dbconn);
    }
}

$sql = "SELECT appointments.*, public.\"user\".name FROM public.appointments JOIN public.\"user\" ON appointments.user_id = public.\"user\".id";
$result = pg_query($dbconn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin - Manage Appointments</title>
  <meta name="keywords" content="PHP,PostgreSQL,Admin">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    function confirmCompletion(appointmentId) {
        var confirmDelete = confirm("Are you sure you want to mark this appointment as completed? This will remove it from the list.");
        if (confirmDelete) {
            document.getElementById('deleteForm-' + appointmentId).submit();
        }
    }
  </script>
</head>
<body>

<div class="container">
  <h2>Manage Appointments</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>User</th>
        <th>Date</th>
        <th>Slot</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = pg_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['appointment_date']; ?></td>
        <td><?php echo $row['slot']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
          <form method="post" style="display:inline;">
            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
            <select name="status">
              <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
              <option value="accepted" <?php if ($row['status'] == 'accepted') echo 'selected'; ?>>Accepted</option>
              <option value="rejected" <?php if ($row['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
            </select>
            <input type="submit" name="update_status" value="Update">
          </form>
          <button onclick="confirmCompletion(<?php echo $row['id']; ?>)" class="btn btn-danger">Completed</button>
          <form id="deleteForm-<?php echo $row['id']; ?>" method="post" style="display:none;">
            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="delete_appointment" value="1">
          </form>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">HOME</button>
</div>

</body>
</html>
