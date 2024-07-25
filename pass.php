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

if (isset($_GET['id'])) {
    $appointment_id = pg_escape_string($_GET['id']);

    // Fetch the appointment details
    $sql = "SELECT * FROM public.appointments WHERE id = '$appointment_id'";
    $result = pg_query($dbconn, $sql);
    $appointment = pg_fetch_assoc($result);

    if ($appointment) {
        $xml_data = $appointment['appointment_pass'];
        if ($xml_data) {
            // Load XML data
            $xml = simplexml_load_string($xml_data);

            if ($xml === false) {
                echo "<div class='alert alert-danger'>Failed to parse XML data.</div>";
            } else {
                // Display the appointment pass
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                  <title>Appointment Pass</title>
                  <meta name="keywords" content="PHP,PostgreSQL,Appointment Pass">
                  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
                  <style>
                    body {
                      background: #f8f9fa;
                      font-family: 'Arial', sans-serif;
                    }
                    .container {
                      margin-top: 30px;
                    }
                    .pass-details {
                      border: 1px solid #ddd;
                      padding: 15px;
                      border-radius: 5px;
                      background: #fff;
                    }
                  </style>
                </head>
                <body>
                <div class="container">
                  <h2>Appointment Pass</h2>
                  <div class="pass-details">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($xml->name); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($xml->gender); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($xml->date); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($xml->status); ?></p>
                    <p><strong>Slot:</strong> <?php echo htmlspecialchars($xml->slot); ?></p>
                    <p><strong>Additional Details:</strong> <?php echo htmlspecialchars($xml->additional_details); ?></p>
                  </div>
                </div>
                </body>
                </html>
                <?php
            }
        } else {
            echo "<div class='alert alert-warning'>No pass available for this appointment.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Appointment not found.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>No appointment ID provided.</div>";
}
?>
