@echo off
:loop
if exist "C:\xampp\htdocs\capstone\run_sensor.flag" (
    set /p duration=<C:\xampp\htdocs\capstone\run_sensor.flag
    del C:\xampp\htdocs\capstone\run_sensor.flag
    "C:\Users\Kenneth\AppData\Local\Programs\Python\Python38\python" C:\xampp\htdocs\capstone\scripts\sensor_test.py --duration %duration%
)
timeout /t 2 >nul
goto loop
