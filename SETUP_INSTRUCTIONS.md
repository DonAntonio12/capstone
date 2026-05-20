# Smart Soil Monitoring System - Setup Instructions

## Overview

This system implements the recommended data flow where:
1. User sets test duration on the web interface
2. Python script communicates with ESP32 via USB Serial
3. ESP32 sends sensor data to Python script
4. Python script averages readings and sends results to Laravel
5. Laravel displays final results with soil analysis

## Prerequisites

### Software Requirements
- Python 3.7+ with pip
- Laravel 10+ with PHP 8.1+
- Arduino IDE with ESP32 board support
- USB cable for ESP32 connection

### Hardware Requirements
- ESP32-WROOM development board
- NPK soil sensor (or simulation)
- pH sensor
- DHT22 temperature/humidity sensor (optional)
- Breadboard and jumper wires

## Installation Steps

### 1. Laravel Setup

```bash
# Navigate to your Laravel project
cd /path/to/your/laravel/project

# Install dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

### 2. Python Dependencies

```bash
# Install Python dependencies
pip install -r requirements.txt

# Or install manually:
pip install pyserial==3.5 requests==2.31.0
```

### 3. ESP32 Setup

#### Hardware Connections
```
ESP32 Pin Connections:
- GPIO16 (RX) -> NPK Sensor TX
- GPIO17 (TX) -> NPK Sensor RX  
- GPIO36 (ADC) -> pH Sensor
- GPIO4 -> DHT22 Data
- 3.3V -> Sensors VCC
- GND -> Sensors GND
```

#### Software Setup
1. Open Arduino IDE
2. Install ESP32 board support
3. Open `esp32_serial_communication.ino`
4. Install required libraries:
   - DHT sensor library
   - SoftwareSerial (built-in)
5. Upload code to ESP32

### 4. Database Setup

Run the migration to create the sensor readings table:

```bash
php artisan migrate
```

## Usage Instructions

### 1. Start Laravel Server

```bash
php artisan serve
```

### 2. Connect ESP32

1. Connect ESP32 to computer via USB
2. Note the COM port (Windows) or device path (Linux/Mac)
3. The Python script will auto-detect the port

### 3. Run Sensor Test

1. Open the testing page: `http://localhost:8000/testing`
2. Select test duration (30s, 1min, 2min, 5min)
3. Click "Start Testing"
4. The system will:
   - Trigger Python script via Laravel command
   - Python script connects to ESP32 via USB Serial
   - ESP32 sends sensor data every 5 seconds
   - Python script averages readings after specified duration
   - Results are sent to Laravel and displayed on web interface

### 4. Manual Testing

You can also test components individually:

#### Test ESP32 Connection
```bash
# Check if ESP32 is detected
python scripts/sensor_test.py --duration 10 --farm-id 1
```

#### Test Laravel API
```bash
# Test sensor readings endpoint
curl -X POST http://localhost:8000/api/sensor-readings \
  -H "Content-Type: application/json" \
  -d '{"farm_id":1,"n":50,"p":30,"k":200,"ph":6.5,"temperature":25,"humidity":60}'
```

## File Structure

```
capstone/
├── app/
│   ├── Console/Commands/StartSensorTest.php
│   ├── Http/Controllers/Api/
│   │   ├── SensorReadingController.php
│   │   └── SensorTestController.php
│   └── Models/SensorReading.php
├── scripts/
│   └── sensor_test.py
├── resources/views/
│   └── testing.blade.php
├── routes/
│   └── api.php
├── database/migrations/
│   └── sensor_readings_table.php
├── esp32_serial_communication.ino
├── requirements.txt
└── SETUP_INSTRUCTIONS.md
```

## Troubleshooting

### ESP32 Not Detected
1. Check USB cable connection
2. Verify COM port in Device Manager (Windows)
3. Check if ESP32 drivers are installed
4. Try different USB cable

### Python Script Errors
1. Check if `pyserial` is installed: `pip install pyserial`
2. Verify ESP32 is connected and code is uploaded
3. Check serial port permissions (Linux/Mac)
4. Monitor ESP32 serial output for errors

### Laravel Errors
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Verify database migration ran successfully
3. Check API routes: `php artisan route:list`
4. Test command manually: `php artisan sensor:test 30 --farm-id=1`

### No Test Results
1. Check if ESP32 is sending data (use Serial Monitor)
2. Verify Python script is receiving data
3. Check Laravel API endpoint is working
4. Monitor Python script output for errors

## API Endpoints

### Sensor Test
- `POST /api/sensor-test/start` - Start sensor test
- `GET /api/sensor-test/status` - Get test status

### Sensor Readings
- `POST /api/sensor-readings` - Store sensor reading
- `GET /api/sensor-readings/latest` - Get latest reading
- `GET /api/sensor-readings/test-results` - Get test results with analysis

## Data Flow

1. **User Interface**: User selects duration and clicks "Start Testing"
2. **Laravel**: Calls `sensor:test` command with duration parameter
3. **Python Script**: 
   - Connects to ESP32 via USB Serial
   - Sends "START_TEST" command
   - Collects readings every 5 seconds for specified duration
   - Averages readings and calculates soil analysis
4. **ESP32**: 
   - Receives commands via Serial
   - Reads sensors and sends JSON data
   - Responds to "READ" commands with sensor values
5. **Laravel**: Receives averaged data and stores in database
6. **Web Interface**: Displays results with soil type and recommendations

## Customization

### Adding New Sensors
1. Update ESP32 code to read new sensor
2. Modify JSON response format
3. Update Python script to handle new data
4. Update Laravel migration and model
5. Update web interface to display new data

### Modifying Soil Analysis
1. Edit `determine_soil_type()` function in Python script
2. Edit `generate_recommendations()` function
3. Update thresholds based on your requirements

### Changing Test Duration
1. Modify duration options in `testing.blade.php`
2. Update validation in `SensorTestController.php`
3. Adjust Python script timing if needed

## Security Notes

- The system currently doesn't require authentication for sensor data
- Consider adding API authentication for production use
- Validate all sensor data before storing in database
- Sanitize user inputs on web interface

## Performance Optimization

- Python script uses efficient averaging with statistics module
- Laravel uses database transactions for data integrity
- Web interface uses AJAX for real-time updates
- Consider caching for frequently accessed data

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Monitor ESP32 Serial Monitor output
3. Check Python script console output
4. Verify all connections and configurations 