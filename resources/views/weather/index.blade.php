@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Weather Information</h4>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="location" placeholder="Enter location">
                            <button class="btn btn-dark" onclick="getWeather()">Search</button>
                        </div>
                    </div>

                    <div id="weather-info" class="d-none">
                        <div class="text-center">
                            <h2 id="city-name" class="mb-3"></h2>
                            <div class="weather-icon-container mb-3">
                                <img id="weather-icon" src="" alt="Weather icon" style="width: 100px; height: 100px;">
                            </div>
                            <h3 id="temperature" class="mb-3"></h3>
                            <p id="description" class="mb-4 text-capitalize"></p>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Humidity</h5>
                                            <p class="card-text"><span id="humidity"></span>%</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Wind Speed</h5>
                                            <p class="card-text"><span id="wind"></span> m/s</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="error-message" class="alert alert-danger mt-3 d-none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function getWeather() {
    const location = document.getElementById('location').value;
    const weatherInfo = document.getElementById('weather-info');
    const errorMessage = document.getElementById('error-message');

    if (!location) {
        errorMessage.textContent = 'Please enter a location';
        errorMessage.classList.remove('d-none');
        weatherInfo.classList.add('d-none');
        return;
    }

    // Show loading state
    weatherInfo.classList.add('d-none');
    errorMessage.classList.add('d-none');
    document.querySelector('button').disabled = true;
    document.querySelector('button').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

    fetch(`/weather/${encodeURIComponent(location)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            document.getElementById('city-name').textContent = data.name;
            document.getElementById('temperature').textContent = `${Math.round(data.main.temp)}°C`;
            document.getElementById('description').textContent = data.weather[0].description;
            document.getElementById('humidity').textContent = data.main.humidity;
            document.getElementById('wind').textContent = data.wind.speed;
            document.getElementById('weather-icon').src = `https://openweathermap.org/img/w/${data.weather[0].icon}.png`;

            weatherInfo.classList.remove('d-none');
            errorMessage.classList.add('d-none');
        })
        .catch(error => {
            errorMessage.textContent = error.message || 'Unable to fetch weather data';
            errorMessage.classList.remove('d-none');
            weatherInfo.classList.add('d-none');
        })
        .finally(() => {
            // Reset button state
            document.querySelector('button').disabled = false;
            document.querySelector('button').textContent = 'Search';
        });
}

// Add enter key support
document.getElementById('location').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        getWeather();
    }
});
</script>
@endpush 