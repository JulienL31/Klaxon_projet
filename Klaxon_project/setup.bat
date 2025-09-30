@echo off
setlocal enabledelayedexpansion
echo === KLAXON - INSTALLATION ===

IF NOT EXIST ".env" (
  copy .env.example .env >NUL
)

call composer install --no-interaction --prefer-dist
IF %ERRORLEVEL% NEQ 0 goto :error

call php artisan key:generate --force
IF %ERRORLEVEL% NEQ 0 goto :error

call php artisan migrate:fresh --seed --force
IF %ERRORLEVEL% NEQ 0 goto :error

call php artisan storage:link
echo ✅ Installation terminée. Lance: php artisan serve
goto :eof

:error
echo ❌ Une erreur est survenue. Vérifie PHP, Composer et MySQL.
exit /b 1
