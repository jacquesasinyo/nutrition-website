@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $food->turkish_description ?? $food->description }}</h1>

        @if($food->photo)
            <div class="mb-4">
                <img src="{{ asset($food->photo) }}" alt="{{ $food->description }}" class="rounded-lg shadow-md max-w-md mx-auto">
            </div>
        @endif

        <form id="nutritionForm">
            <div class="mb-3">
                <label for="quantity" class="form-label">Miktar:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control">
            </div>

            <div class="mb-3">
                <label for="measurement" class="form-label">Ölçü Birimi:</label>
                <select id="measurement" name="measurement" class="form-select">
                    <option value="Gram">Gram</option>
                    <option value="Çeyrek">Çeyrek</option>
                    <option value="Yarım">Yarım</option>
                    <option value="Adet">Adet</option>
                    <option value="Porsiyon (Orta)">Porsiyon (Orta)</option>
                    <option value="Kilogram">Kilogram</option>
                    <option value="Kase (200g)">Kase (200g)</option>
                    <option value="Çorba Kaşığı">Çorba Kaşığı</option>
                    <option value="Kase (Küçük)">Kase (Küçük)</option>
                    <option value="Kase (Orta)">Kase (Orta)</option>
                    <option value="Bardak (Orta)">Bardak (Orta)</option>
                    <option value="Yemek Kaşığı">Yemek Kaşığı</option>
                    <option value="Su Bardağı">Su Bardağı</option>
                </select>
            </div>

            <button type="button" class="btn btn-primary" onclick="updateNutrition()">Hesapla</button>
        </form>

        <div class="row mt-4">
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <div style="position: relative; width: 200px; height: 200px;">
                    <canvas id="nutritionChart"></canvas>
                    <div id="calorieLabel"
                         data-original="{{ $energyValue }}"
                         style="position: absolute; top: 37%; left: 50%; transform: translate(-50%, -50%);
                           font-size: 22px; font-weight: bold; text-align: center;">
                        {{ $energyValue }} kcal
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-between">
                    <div class="text-center">
                        <span style="color:#6abf4b; font-weight:bold;">Karbonhidrat</span>
                        <h3 id="carbValue" data-original="{{ $nutrients->firstWhere('nutrient_name', 'Karbonhidrat')->amount ?? 0 }}">
                            {{ $nutrients->firstWhere('nutrient_name', 'Karbonhidrat')->amount ?? 0 }} gr
                        </h3>
                    </div>
                    <div class="text-center">
                        <span style="color:#e67e22; font-weight:bold;">Protein</span>
                        <h3 id="proteinValue" data-original="{{ $nutrients->firstWhere('nutrient_name', 'Protein')->amount ?? 0 }}">
                            {{ $nutrients->firstWhere('nutrient_name', 'Protein')->amount ?? 0 }} gr
                        </h3>
                    </div>
                    <div class="text-center">
                        <span style="color:#f1c40f; font-weight:bold;">Yağ</span>
                        <h3 id="fatValue" data-original="{{ $nutrients->firstWhere('nutrient_name', 'Yağ')->amount ?? 0 }}">
                            {{ $nutrients->firstWhere('nutrient_name', 'Yağ')->amount ?? 0 }} gr
                        </h3>
                    </div>
                </div>
            </div>
        </div>


        <h3 class="mt-4">Besin Değerleri</h3>

        <table class="table mt-3">
            <thead>
            <tr>
                <h1><th>Nutrients</th></h1>
                <th>100 gr</th>
                <th><span id="selectedQuantity">1</span><span id="selectedMeasurement">Gram</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach($nutrients as $nutrient)
                <tr>
                    <td><strong>{{ $nutrient->nutrient_name }}</strong> ({{ $nutrient->unit_name }})</td>
                    <td>{{ $nutrient->amount }}</td>
                    <td class="nutrient-value" data-amount="{{ $nutrient->amount }}" data-original="{{ $nutrient->amount }}">
                        {{ $nutrient->amount }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>



        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            let nutritionChart = null;

            function updateNutrition() {
                let quantity = parseFloat(document.getElementById("quantity").value);
                let measurement = document.getElementById("measurement").value;
                let factor = getMeasurementFactor(measurement);


                document.getElementById("selectedQuantity").innerText = quantity;
                document.getElementById("selectedMeasurement").innerText = measurement;


                document.querySelectorAll('.nutrient-value').forEach(element => {
                    let originalAmount = parseFloat(element.getAttribute('data-original'));
                    element.innerText = (originalAmount * quantity * factor).toFixed(2);
                });

                updateMacroValues(quantity, factor);
            }

            function getMeasurementFactor(measurement) {
                let conversionFactors = {
                    'Gram': 0.01,
                    'Çeyrek': 0.25,
                    'Yarım': 0.5,
                    'Adet': 1,
                    'Porsiyon (Orta)': 1,
                    'Kilogram': 1000,
                    'Kase (200g)': 200,
                    'Çorba Kaşığı': 15,
                    'Kase (Küçük)': 150,
                    'Kase (Orta)': 250,
                    'Bardak (Orta)': 250,
                    'Yemek Kaşığı': 10,
                    'Su Bardağı': 200
                };
                return conversionFactors[measurement] || 1;
            }

            function updateMacroValues(quantity, factor) {
                let carb = parseFloat(document.getElementById("carbValue").getAttribute('data-original')) * quantity * factor;
                let protein = parseFloat(document.getElementById("proteinValue").getAttribute('data-original')) * quantity * factor;
                let fat = parseFloat(document.getElementById("fatValue").getAttribute('data-original')) * quantity * factor;

                document.getElementById("carbValue").innerText = carb.toFixed(2) + " gr";
                document.getElementById("proteinValue").innerText = protein.toFixed(2) + " gr";
                document.getElementById("fatValue").innerText = fat.toFixed(2) + " gr";
                let originalCalories = parseFloat(document.getElementById("calorieLabel").getAttribute('data-original')) || 0;
                let updatedCalories = (originalCalories * quantity * factor).toFixed(0);
                document.getElementById("calorieLabel").innerText = updatedCalories + " kcal";


                updateChart(carb, protein, fat);
            }

            function updateChart(carb, protein, fat) {
                let total = carb + protein + fat;
                let carbPercent = total > 0 ? (carb / total * 100).toFixed(1) : 0;
                let proteinPercent = total > 0 ? (protein / total * 100).toFixed(1) : 0;
                let fatPercent = total > 0 ? (fat / total * 100).toFixed(1) : 0;

                let ctx = document.getElementById('nutritionChart').getContext('2d');

                if (nutritionChart !== null && typeof nutritionChart.destroy === 'function') {
                    nutritionChart.destroy();
                }

                nutritionChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            `Karbonhidrat ${carbPercent}%`,
                            `Protein ${proteinPercent}%`,
                            `Yağ ${fatPercent}%`
                        ],
                        datasets: [{
                            data: [carb, protein, fat],
                            backgroundColor: ['#6abf4b', '#e67e22', '#f1c40f'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            document.addEventListener("DOMContentLoaded", function () {
                updateMacroValues(1, 1);
            });
        </script>

        <a href="{{ route('food.index') }}" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>
@endsection
