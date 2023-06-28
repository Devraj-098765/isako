let weather = {
  apiKey: "f3d44717a14140b7ad16a6c7c649fb55",
  fetchWeather: function (city) {
    const cachedData = localStorage.getItem(city); // Check if data is available in local storage
    if (cachedData) {
      const data = JSON.parse(cachedData);
      this.displayWeather(data);
    } else {
      fetch(
        "https://api.openweathermap.org/data/2.5/weather?q=" +
          city +
          "&units=metric&appid=" +
          this.apiKey
      )
        .then((response) => {
          if (!response.ok) {
            alert("No weather found.");
            throw new Error("No weather found.");
          }
          return response.json();
        })
        .then((data) => {
          this.displayWeather(data);
          localStorage.setItem(city, JSON.stringify(data)); // Store data in local storage
        })
        .catch((error) => console.log(error));
    }
  },
  displayWeather: function (data) {
    // Display weather information on the webpage
    const { name } = data;
    const { icon, description } = data.weather[0];
    const { temp, humidity } = data.main;
    const { speed } = data.wind;
    document.querySelector(".city").innerText = "Weather in " + name;
    document.querySelector(".icon").src =
      "https://openweathermap.org/img/wn/" + icon + ".png";
    document.querySelector(".description").innerText = description;
    document.querySelector(".temp").innerText = temp + "°C";
    document.querySelector(".humidity").innerText =
      "Humidity: " + humidity + "%";
    document.querySelector(".wind").innerText =
      "Wind speed: " + speed + " km/h";
    document.querySelector(".weather").classList.remove("loading");
    document.body.style.backgroundImage =
      "url('https://source.unsplash.com/1600x900/?" + name + "')";
  },
  search: function () {
    // Trigger weather search based on user input
    const city = document.querySelector(".search-bar").value;
    this.fetchWeather(city);
  },
};

document
  .querySelector(".search button")
  .addEventListener("click", function () {
    weather.search();
  });

document.querySelector(".search-bar").addEventListener("keyup", function (event) {
  if (event.key == "Enter") {
    weather.search();
  }
});

weather.fetchWeather("Ontario");

// Get the current time in the user's local timezone
const now = new Date();
const timeOptions = {
  hour: "numeric",
  minute: "numeric",
  second: "numeric",
  hour12: true,
};
const time = now.toLocaleString("en-US", timeOptions);

// Display the time
document.querySelector(".time").innerText = "Current time: " + time;
