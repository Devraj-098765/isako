<!DOCTYPE html>
<html>
<head>
  <title>Weather Data</title>
  <style>
    table {
      border-collapse: collapse;
    }
    th, td {
      padding: 5px;
      border: 1px solid black;
    }
  </style>
</head>
<body>
  <h1>Weather Data</h1>
  <!-- creating a table -->
  <table>
    <tr>
      <th>Description</th>
      <th>Temperature (k)</th>
      <th>Wind Speed (km/h)</th>
      <th>City</th>
      <th>Pressure (hPa)</th>
      <th>Humidity (%)</th>
      <th>Date/Time</th>
    </tr>

  <?php
  // Connect to database, create database, and select it
  $mysqli = new mysqli("sql205.epizy.com", "epiz_34239581", "VyWrsjdaSprX","epiz_34239581_devvv");

  // Create table if it doesn't exist
  mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS weather_data(
    description VARCHAR(20),
    temperature INT(10),
    wind_speed INT(10),
    city VARCHAR(20),
    pressure INT(10),
    humidity INT(10),
    icon VARCHAR(10),
    dt INT(20)
  )");

  // URL for openweathermap API call
  $url = 'https://api.openweathermap.org/data/2.5/weather?q=Ontario&appid=f3d44717a14140b7ad16a6c7c649fb55';

  // Get data from openweathermap and store in JSON object
  $data = file_get_contents($url);
  $json = json_decode($data, true);

  // Fetch required fields from JSON object
  $description = $json['weather'][0]['description'];
  $temperature = $json['main']['temp'];
  $wind_speed = $json['wind']['speed'];
  $city = $json['name'];
  $pressure = $json['main']['pressure'];
  $humidity = $json['main']['humidity'];
  $icon = $json['weather'][0]['icon'];
  $dt = $json['dt'];

  // Insert data into weather_data table
  mysqli_query($mysqli, "INSERT INTO weather_data(description, temperature, wind_speed, city, pressure, humidity, icon, dt) VALUES ('$description', $temperature, $wind_speed, '$city',  $pressure, $humidity, '$icon', $dt)");

  // Build SQL query to retrieve weather data of the past week
  $sql = "SELECT * FROM weather_data WHERE dt >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 WEEK))";

  // Execute SQL query and retrieve results
  $result = mysqli_query($mysqli, $sql);

  // Loop through results and display data
  while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td>" . $row['temperature'] . "</td>";
    echo "<td>" . $row['wind_speed'] . "</td>";
    echo "<td>" . $row['city'] . "</td>";
    echo "<td>" . $row['pressure'] . "</td>";
    echo "<td>" . $row['humidity'] . "</td>";
    echo "<td>" . date('Y-m-d H:i:s', $row['dt']) . "</td>";
    echo "</tr>";
  }

  // End the table
  echo "</table>";
  ?>

</body>
</html>
