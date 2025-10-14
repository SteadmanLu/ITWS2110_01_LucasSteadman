const weatherApiKey = '9e76a65717fb1510783a4d169c7d4262'; // I added this so that I didn't have to give AI my API key that it could then give it to other people

const weatherBase = "https://api.openweathermap.org/data/2.5/weather";
const cocktailBase = "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=";

const weatherDiv = document.getElementById("weather");
const cocktailDiv = document.getElementById("cocktail");
const searchBtn = document.getElementById("searchBtn");
const cityInput = document.getElementById("cityInput");

let unit = "metric"; // default Celsius

// Toggle °C / °F
document.querySelectorAll("input[name='unit']").forEach((radio) => {
  radio.addEventListener("change", (e) => {
    unit = e.target.value;
    getWeather(cityInput.value || "New York");
  });
});

searchBtn.addEventListener("click", () => {
  const city = cityInput.value || "New York";
  getWeather(city);
});

// Auto load New York
window.onload = () => {
  getWeather("New York");
};

async function getWeather(city) {
  try {
    const res = await fetch(`${weatherBase}?q=${city}&appid=${weatherApiKey}&units=${unit}`);
    const data = await res.json();
    if (data.cod !== 200) throw new Error(data.message);

    const { name } = data;
    const country = data.sys.country;
    const temp = data.main.temp.toFixed(1);
    const desc = data.weather[0].description;
    const icon = data.weather[0].icon;

    weatherDiv.innerHTML = `
      <h2>${name}, ${country}</h2>
      <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="${desc}">
      <p><strong>${temp}°${unit === "metric" ? "C" : "F"}</strong> — ${desc}</p>
    `;

    // Pick a cocktail based on temperature/condition
    let mood = "random";
    if (desc.includes("rain")) mood = "comfort";
    else if (temp >= (unit === "metric" ? 25 : 77)) mood = "cool";
    else if (temp <= (unit === "metric" ? 10 : 50)) mood = "warm";

    getCocktail(mood);

  } catch (err) {
    weatherDiv.innerHTML = `<p>Error: ${err.message}</p>`;
    cocktailDiv.innerHTML = "";
  }
}

async function getCocktail(mood) {
  const moodMap = {
    cool: ["Lemonade", "Iced Coffee", "Fruit Punch"],
    warm: ["Hot Chocolate", "Spiced Tea"],
    comfort: ["Banana Smoothie", "Honey Milk"],
    random: ["Shirley Temple", "Virgin Mojito", "Iced Tea"]
  };

  // Pick a drink name from the list based on mood
  const choices = moodMap[mood] || moodMap.random;
  const drinkName = choices[Math.floor(Math.random() * choices.length)];

  // Fetch non-alcoholic drink list and try to match our selection
  const res = await fetch(`https://www.thecocktaildb.com/api/json/v1/1/search.php?s=${drinkName}`);
  const data = await res.json();

  if (data.drinks && data.drinks.length > 0) {
    const d = data.drinks[0];
    cocktailDiv.innerHTML = `
      <h3>Suggested Drink: ${d.strDrink}</h3>
      <img src="${d.strDrinkThumb}" alt="${d.strDrink}">
      <p>${d.strCategory} • ${d.strAlcoholic}</p>
      <p><em>${d.strInstructions}</em></p>
    `;
  } else {
    // fallback: truly random non-alcoholic drink
    const fallback = await fetch("https://www.thecocktaildb.com/api/json/v1/1/filter.php?a=Non_Alcoholic");
    const list = await fallback.json();
    const rand = list.drinks[Math.floor(Math.random() * list.drinks.length)];

    cocktailDiv.innerHTML = `
      <h3>Suggested Drink: ${rand.strDrink}</h3>
      <img src="${rand.strDrinkThumb}" alt="${rand.strDrink}">
      <p>Non-Alcoholic</p>
      <p><em>Perfect for this kind of weather!</em></p>
    `;
  }
}