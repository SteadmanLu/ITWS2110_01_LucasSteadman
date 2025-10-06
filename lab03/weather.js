const weatherApiKey = '9e76a65717fb1510783a4d169c7d4262';

window.onload = function () {
  getTodaysUselessFact();
};

function formatTime(timestamp, timezone) {
  const date = new Date((timestamp + timezone) * 1000);
  const hours = date.getUTCHours();
  const minutes = date.getUTCMinutes();
  const ampm = hours >= 12 ? 'PM' : 'AM';
  const displayHours = hours % 12 || 12;
  const displayMinutes = minutes.toString().padStart(2, '0');
  return `${displayHours}:${displayMinutes} ${ampm}`;
}

function getTimeUntil(timestamp, timezone, currentTime) {
  const targetTime = (timestamp + timezone) * 1000;
  const now = (currentTime + timezone) * 1000;
  const diff = targetTime - now;
  
  if (diff < 0) return null;
  
  const hours = Math.floor(diff / (1000 * 60 * 60));
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  
  if (hours > 0) {
    return `in ${hours}h ${minutes}m`;
  } else {
    return `in ${minutes}m`;
  }
}

function getWindDirection(degrees) {
  const directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
  const index = Math.round(degrees / 22.5) % 16;
  return directions[index];
}

function getWeather() {
  const weatherOutput = document.getElementById('weather-output');
  weatherOutput.innerHTML = '<div class="loading">Loading weather data...</div>';

  const url = `https://api.openweathermap.org/data/2.5/weather?q=Troy,US&units=imperial&appid=${weatherApiKey}`;

  fetch(url)
    .then(response => {
      if (!response.ok) throw new Error('Weather data unavailable');
      return response.json();
    })
    .then(data => {
      const iconUrl = `https://openweathermap.org/img/wn/${data.weather[0].icon}@4x.png`;
      const visibilityKm = (data.visibility / 1000).toFixed(1);
      const visibilityPercent = Math.min((data.visibility / 10000) * 100, 100);
      const windDir = data.wind.deg || 0;
      const windDirName = getWindDirection(windDir);
      
      const sunriseTime = formatTime(data.sys.sunrise, data.timezone);
      const sunsetTime = formatTime(data.sys.sunset, data.timezone);
      const sunriseCountdown = getTimeUntil(data.sys.sunrise, data.timezone, data.dt);
      const sunsetCountdown = getTimeUntil(data.sys.sunset, data.timezone, data.dt);
      
      let precipitationHTML = '';
      if (data.rain && data.rain['1h']) {
        precipitationHTML = `
          <div class="precipitation-alert">
            <div class="precipitation-icon">🌧️</div>
            <div class="precipitation-text">
              <strong>Rain:</strong> ${data.rain['1h']} mm in the last hour
            </div>
          </div>
        `;
      } else if (data.snow && data.snow['1h']) {
        precipitationHTML = `
          <div class="precipitation-alert">
            <div class="precipitation-icon">❄️</div>
            <div class="precipitation-text">
              <strong>Snow:</strong> ${data.snow['1h']} mm in the last hour
            </div>
          </div>
        `;
      }
      
      const output = `
        <div class="weather-location">${data.name}, ${data.sys.country}</div>
        ${precipitationHTML}
        <div class="weather-main">
          <div class="weather-icon-container">
            <img src="${iconUrl}" alt="${data.weather[0].description}" class="weather-icon">
            <div class="weather-description">${data.weather[0].description}</div>
          </div>
          <div class="weather-temp">${Math.round(data.main.temp)}°F</div>
        </div>
        <div class="weather-details">
          <div class="weather-detail-item">
            <div class="weather-detail-label">Feels Like</div>
            <div class="weather-detail-value">${Math.round(data.main.feels_like)}°F</div>
          </div>
          <div class="weather-detail-item">
            <div class="weather-detail-label">Humidity</div>
            <div class="weather-detail-value">${data.main.humidity}%</div>
          </div>
          <div class="weather-detail-item">
            <div class="weather-detail-label">Wind</div>
            <div class="weather-detail-value">
              <div class="wind-compass">
                <div class="compass-circle">
                  <div class="compass-arrow" style="transform: rotate(${windDir}deg)"></div>
                </div>
                <span>${Math.round(data.wind.speed)} mph ${windDirName}</span>
              </div>
            </div>
          </div>
          <div class="weather-detail-item">
            <div class="weather-detail-label">Visibility</div>
            <div class="weather-detail-value">
              <div class="visibility-meter">
                <span>${visibilityKm} km</span>
                <div class="visibility-bar">
                  <div class="visibility-fill" style="width: ${visibilityPercent}%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="sun-times">
          <div class="sun-time-item">
            <div class="sun-icon">🌅</div>
            <div class="sun-time-label">Sunrise</div>
            <div class="sun-time-value">${sunriseTime}</div>
            ${sunriseCountdown ? `<div class="sun-countdown">${sunriseCountdown}</div>` : ''}
          </div>
          <div class="sun-time-item">
            <div class="sun-icon">🌇</div>
            <div class="sun-time-label">Sunset</div>
            <div class="sun-time-value">${sunsetTime}</div>
            ${sunsetCountdown ? `<div class="sun-countdown">${sunsetCountdown}</div>` : ''}
          </div>
        </div>
      `;
      weatherOutput.innerHTML = output;
    })
    .catch(error => {
      weatherOutput.innerHTML = `<div class="error">Error fetching weather data. Please try again.</div>`;
      console.error(error);
    });
}

function getUselessFact() {
  const factOutput = document.getElementById('useless-fact-output');
  factOutput.innerHTML = '<div class="loading">Loading fact...</div>';

  const url = 'https://uselessfacts.jsph.pl/api/v2/facts/random?language=en';

  fetch(url, {
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Fact unavailable');
      return response.json();
    })
    .then(data => {
      const fact = `<p><strong>Useless Fact:</strong> ${data.text}</p>`;
      factOutput.innerHTML = fact;
    })
    .catch(error => {
      factOutput.innerHTML = `<div class="error">Error fetching useless fact. Please try again.</div>`;
      console.error(error);
    });
}

function getTodaysUselessFact() {
  const factOutput = document.getElementById('todays-fact-output');
  factOutput.innerHTML = '<div class="loading">Loading today\'s fact...</div>';

  const url = 'https://uselessfacts.jsph.pl/api/v2/facts/today?language=en';

  fetch(url, {
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Fact unavailable');
      return response.json();
    })
    .then(data => {
      const fact = `<p><strong>Today's Fact:</strong> ${data.text}</p>`;
      factOutput.innerHTML = fact;
    })
    .catch(error => {
      factOutput.innerHTML = `<div class="error">Error fetching today's fact. Please try again.</div>`;
      console.error(error);
    });
}
