<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>Időjárás Widget – Open-Meteo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef2f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .weather-widget {
      position: relative;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 20px 30px;
      width: 300px;
      text-align: center;
      transition: background 0.5s, color 0.5s;
    }

    .weather-widget.night {
      background: #1e1e2f;
      color: #eee;
    }

    .city {
      font-size: 1.4em;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .temperature {
      font-size: 2.5em;
      font-weight: bold;
      color: #0077cc;
    }

    .weather-widget.night .temperature {
      color: #66ccff;
    }

    .details {
      margin-top: 10px;
      font-size: 1em;
    }

    .updated {
      margin-top: 15px;
      font-size: 0.85em;
      color: #777;
    }

    .weather-widget.night .updated {
      color: #aaa;
    }

    .icon {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 1.8em;
    }

    .sun {
      color: gold;
    }

    .moon {
      color: white;
    }
  </style>
</head>
<body>

<div class="weather-widget" id="widget">
  <div class="icon" id="icon">☀️</div>
  <div class="city">Budapest</div>
  <div class="temperature" id="temperature">-- °C</div>
  <div class="details">
    Szél: <span id="wind">-- km/h</span>
  </div>
  <div class="updated" id="updated">Frissítés...</div>
</div>

<script>
  const widget = document.getElementById("widget");
  const temperatureEl = document.getElementById("temperature");
  const windEl = document.getElementById("wind");
  const updatedEl = document.getElementById("updated");
  const iconEl = document.getElementById("icon");

  const latitude = 47.4979;
  const longitude = 19.0402;

  async function loadWeather() {
    const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      if (data.current_weather) {
        const weather = data.current_weather;
        temperatureEl.textContent = `${weather.temperature} °C`;
        windEl.textContent = `${weather.windspeed} km/h`;
        updatedEl.textContent = `Frissítve: ${weather.time.replace("T", " ")}`;

        if (weather.is_day === 0) {
          // Éjszakai stílus
          widget.classList.add("night");
          iconEl.textContent = "🌙";
          iconEl.className = "icon moon";
        } else {
          // Nappali stílus
          widget.classList.remove("night");
          iconEl.textContent = "☀️";
          iconEl.className = "icon sun";
        }
      } else {
        updatedEl.textContent = "Nincs elérhető adat.";
      }
    } catch (error) {
      temperatureEl.textContent = "-- °C";
      windEl.textContent = "-- km/h";
      updatedEl.textContent = "Hiba történt az adatok lekérésekor.";
      console.error("Időjárás lekérési hiba:", error);
    }
  }

  loadWeather();
</script>

</body>
</html>
