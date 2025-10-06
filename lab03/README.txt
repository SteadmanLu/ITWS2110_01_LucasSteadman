# Lab 3 - Weather & Trivia App - Lucas Steadman - 10/6/2025

Project Description
    This project is a dynamic web application that combines weather information with 
    entertaining trivia facts. The application fetches real-time data from two different 
    APIs and presents it in a modern, visually appealing interface.

Features
    Weather Display (OpenWeatherMap API)
        -Current temperature with feels-like temperature
        -Weather conditions with icon
        -Humidity percentage
        -Wind speed and direction with compass visualization
        -Visibility meter showing distance with progress bar
        -Sunrise and sunset times with countdown timers
        -Precipitation alerts for rain and snow (when present)

    ### Trivia Facts (Useless Facts API)
        -Today's fact - Loads automatically on page load
        -Random facts - On-demand fact generation
        -Double trouble - Get both weather and a random fact simultaneously

APIs Used
     1. OpenWeatherMap API
        -Purpose: Retrieve current weather data
        -Endpoint: `https://api.openweathermap.org/data/2.5/weather`
        -Documentation: https://openweathermap.org/current
        -Data Retrieved:
            -Temperature (current, feels like, min, max)
            -Weather conditions and icons
            -Wind speed and direction
            -Humidity and pressure
            -Visibility
            -Sunrise and sunset times
            -Precipitation data (rain/snow)

    2. Useless Facts API
        -Purpose: Retrieve interesting trivia facts
        -Endpoint: `https://uselessfacts.jsph.pl/api/v2/facts/`
        -Documentation: https://uselessfacts.jsph.pl/
        -Data Retrieved:
            -Daily fact (fact of the day)
            -Random facts on demand

Sources and Credits
    APIs
        - OpenWeatherMap API - https://openweathermap.org/api
        - Useless Facts API - https://uselessfacts.jsph.pl/

Problems and Solutions
    Some problems that I had were Time Zone Conversion, Wind Direction Display, and a Dynamic 
    Precipitation Display. For the first problem I used the timezone offset provided by the
    API to adjust the UTC time accordingly. For the second problem I created a conversion function to
    translate degrees into cardinal directions and added a visual compass. For the third problem
    I implemented conditional rendering that only shows precipitation alerts when data is present.