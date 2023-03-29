<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Weather</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="Location" placeholder="location" required>
        <input type="text" name="Days" placeholder="forecast length(days)" required>
        <button type="submit" name="Submit">Get Data</button>
    </form>
    <div id="chart-container" style="width: 800px; height: 400px;">
      <canvas id="weather" style="width: 400px; height: 400px; text-align: center;"></canvas>
      <canvas id="air" style="width: 400px; height: 400px; text-align: center;"></canvas>
    </div>

    
</body>
</html>
<script>
    const w = document.getElementById('weather');
    const aq = document.getElementById('air');
    <?php
    if(isset($_POST['Location']) && isset($_POST['Days']) && isset($_POST['Submit'])){
        $name = $_POST['Location'];
        $day = $_POST['Days'];
        $response = file_get_contents('https://geocoding-api.open-meteo.com/v1/search?name=' . $name);
        $response = json_decode($response);
        
        $lat = $response->results[0]->latitude;
        $long = $response->results[0]->longitude;
        
        $response1 = file_get_contents('https://air-quality-api.open-meteo.com/v1/air-quality?latitude=' . $lat . "&longitude=" . $long . "&hourly=pm10,pm2_5,carbon_monoxide");
        $response1 = json_decode($response1);
        echo "new Chart(aq, {
            type: 'line',
            data: {
              labels: " . json_encode($response1->hourly->time) . ",
              datasets: [{
                label: 'PM10',
                data: " . json_encode($response1->hourly->pm10) . ",
                borderWidth: 1,
                borderColor: '#ff0000'
              },
              {
                label: 'PM2.5',
                data: " . json_encode($response1->hourly->pm2_5) . ",
                borderWidth: 1,
                borderColor: '#ff0000'
              },
              {
                label: 'Carbon Monoxide',
                data: " . json_encode($response1->hourly->carbon_monoxide) . ",
                borderWidth: 1,
                borderColor: '#ff0000'
              },
            ]
            },
            options: {
              title: {
                display: true,
                text: 'Air Quality'
              },
              legend: {
                position: 'bottom'
              },
              scales: {
                y: {
                  
                  beginAtZero: true
                }
              }
            }
          });";

        $response3 = file_get_contents('https://api.open-meteo.com/v1/forecast?latitude=' . $lat . "&longitude=" . $long . "&hourly=temperature_2m,rain&forecast_days=". $day ."&timezone=auto");
        $response3 = json_decode($response3);
        echo "new Chart(weather, {
            type: 'line',
            data: {
              labels: " . json_encode($response3->hourly->time) . ",
              datasets: [{
                label: 'Temp(c)',
                data: " . json_encode($response3->hourly->temperature_2m) . ",
                borderWidth: 1,
                borderColor: '#ff0000'
              },
              {
                label: 'Rain(mm)',
                data: " . json_encode($response3->hourly->rain) . ",
                borderWidth: 1,
                borderColor: '#ff0000'
              }
              ]
            }
          });";
    }
    ?>
    
</script># barry
