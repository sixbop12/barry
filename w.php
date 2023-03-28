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
        <input type="text" name="Location" required>
        <button type="submit" name="Submit">Get Data</button>
    </form>
    <div style="width: 1000px; height: 1000px">
        <canvas id="myChart"></canvas>
    </div>

    
</body>
</html>
<script>
    const ctx = document.getElementById('myChart');
    <?php
    if(isset($_POST['Location']) && isset($_POST['Submit'])){
        $name = $_POST['Location'];
        $response = file_get_contents('https://geocoding-api.open-meteo.com/v1/search?name=' . $name);
        $response = json_decode($response);
        
        $lat = $response->results[0]->latitude;
        $long = $response->results[0]->longitude;
        
        $response1 = file_get_contents('https://api.open-meteo.com/v1/forecast?latitude=' . $lat . "&longitude=" . $long . "&hourly=temperature_2m&daily=weathercode,sunrise,sunset&forecast_days=1&timezone=auto");
        $response1 = json_decode($response1);
        echo "new Chart(ctx, {
            type: 'line',
            data: {
              labels: " . json_encode($response1->hourly->time) . ",
              datasets: [{
                label: 'Temp',
                data: " . json_encode($response1->hourly->temperature_2m) . ",
                borderWidth: 1,
                borderColor: '#ff0000'
              }
            ]
            },
            options: {
              scales: {
                y: {
                  
                  beginAtZero: true
                }
              }
            }
          });";
    }
    ?>
    
</script>
