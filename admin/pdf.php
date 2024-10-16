<?php
// Include database connection configuration
include('../connection.php');

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Perform the query to fetch bookings data
$sql = "SELECT booking_id, name, city, date, time, service, status FROM booking"; // Adjust column names and table name as necessary
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Dynamic PDF Generate</title>
</head>

<body>
  <div class="container">
    <h1 class="text-center">PDF Generate in PHP</h1>
    <div class="card-body scrollable-table">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Book ID</th>
            <th scope="col">Name</th>
            <th scope="col">Location</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
            <th scope="col">Service</th>
            <th scope="col">PDF Generator</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) { ?>
                <tr>
                  <td><?php echo $row['booking_id']; ?></td>
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['city']; ?></td>
                  <td><?php echo $row['date']; ?></td>
                  <td><?php echo $row['time']; ?></td>
                  <td><?php echo $row['service']; ?></td>
                  <td>
                    <a target="_blank" href="print-details.php?id=<?=$row['booking_id']?>" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i></a>
                  </td>
                </tr>
              <?php 
              }
          } else {
              echo "<tr><td colspan='7' class='text-center'>No bookings found</td></tr>";
          } 
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
