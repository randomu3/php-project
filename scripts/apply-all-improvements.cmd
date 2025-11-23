@echo off
chcp 65001 >nul
powershell -ExecutionPolicy Bypass -File "%~dp0apply-all-improvements.ps1"
pause
