# Smart Soil Monitoring System

Laravel-based soil monitoring platform with Python-assisted sensor collection and ANN-based soil and fertilizer recommendation.

## What the system does

The system connects a web UI, a Laravel backend, a Python ML service, and an ESP32 or Arduino sensor device.

1. The user opens the Testing page in the browser.
2. Laravel starts a Python sensor script.
3. The Python script talks to the ESP32 or Arduino over USB serial.
4. The device sends N, P, K, pH, temperature, and humidity readings.
5. Python averages the readings and sends the result back to Laravel.
6. Laravel stores the result and shows the soil analysis and recommendation in the UI.
7. The FastAPI service in scripts/ml_service.py can also be used to generate ANN-based soil and fertilizer predictions.

## Project layout

- app/ contains controllers, middleware, and models.
- routes/ contains the web and API routes.
- resources/views/ contains the Blade pages for the dashboard, testing, history, admin, and prediction views.
- scripts/ contains the Python service and sensor scripts.
- resources/ contains the trained model files and datasets used by the ML layer.
- database/ contains migrations, factories, and seeders.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- Python 3.12 or compatible version
- MySQL or another Laravel-supported database
- ESP32 or Arduino board with the required sensors

## Installation

1. Install PHP dependencies.

```bash
composer install
```

2. Install frontend dependencies.

```bash
npm install
```

3. Create or update your environment file.

```bash
copy .env.example .env
php artisan key:generate
```

4. Configure the important values in .env.

- APP_URL
- DB_CONNECTION
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

5. Run the database migrations.

```bash
php artisan migrate
```

6. Create the storage symlink if needed.

```bash
php artisan storage:link
```

## Python setup

Install the Python packages used by the sensor and ML scripts.

```bash
pip install -r requirements.txt
pip install fastapi uvicorn numpy pandas scikit-learn tensorflow
```

If your Python executable path is different from the one hardcoded in app/Http/Controllers/TestingController.php, update that path before running sensor tests.

## How to run the system

Open separate terminals and start each part in this order.

1. Start the Laravel app.

```bash
php artisan serve
```

2. Start the frontend build watcher.

```bash
npm run dev
```

3. Start the FastAPI prediction service from the scripts folder.

```bash
cd scripts
uvicorn ml_service:app --host 127.0.0.1 --port 8000 --reload
```

4. Connect the ESP32 or Arduino device by USB and upload the matching sketch.

5. Open the Testing page in the browser.

```text
http://localhost:8000/testing
```

## How the testing flow works

The Testing page triggers the Laravel controller that starts the Python sensor script. The Python script connects to the serial device, collects readings for the selected duration, averages the values, and returns a JSON payload. Laravel then saves the result and shows it on the testing and history pages.

The main request flow is:

1. Browser -> Laravel TestingController
2. Laravel -> scripts/sensor_test.py
3. Python -> ESP32 or Arduino over serial
4. Python -> Laravel API or controller response
5. Laravel -> database and Blade views

The prediction flow is separate:

1. Laravel sends nutrient and pH inputs to the FastAPI service.
2. scripts/ml_service.py loads the ANN model and supporting datasets.
3. The service returns soil classification and fertilizer recommendation data.
4. Laravel displays the result in the prediction view.

## Important pages and endpoints

- /dashboard - main authenticated dashboard
- /testing - sensor testing page
- /history - stored soil test history
- /prediction/form - prediction form
- /api/sensor-readings - sensor reading API endpoints
- /api/predictions - prediction API endpoints

## Device setup

### ESP32 or Arduino

Use the matching .ino file in the project root:

- esp32_sensor_communication.ino
- esp32_serial_communication.ino
- arduino_sensor_communication.ino

Upload the sketch to your board, then open the serial monitor to confirm the sensor readings are being sent correctly.

### Wiring notes

Follow the corresponding setup guide in:

- [ESP32_SETUP_GUIDE.md](ESP32_SETUP_GUIDE.md)
- [ARDUINO_SETUP_GUIDE.md](ARDUINO_SETUP_GUIDE.md)

## Troubleshooting

- If the Testing page opens but no data appears, confirm that the Python script can detect the serial port.
- If the FastAPI prediction fails, confirm the service is running on 127.0.0.1:8000.
- If Laravel cannot save data, check the database settings in .env and run migrations again.
- If frontend pages load without styling, run npm run dev and refresh the browser.
- If the system cannot find Python, update the hardcoded path in app/Http/Controllers/TestingController.php.

## Testing and validation

The project includes test and validation references in:

- TESTING_DOCUMENTATION.md
- ANN_Accuracy_Validation_Summary.md
- accuracy_validation_report_2025-10-07_09-37-39.json
- ANN_Validation_Report_2025-10-07_09-58-16.json

Run the Laravel test suite with:

```bash
php artisan test
```

## Notes

- The project is designed for local development and capstone demonstration use.
- For production, review authentication, API protection, and environment security before deployment.
- The ML model files and datasets are stored in resources/ and are loaded directly by the Python service.

## License

This project follows the license defined by the repository owner.
