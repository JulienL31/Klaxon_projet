@echo off
setlocal ENABLEDELAYEDEXPANSION
title KLAXON - Setup DB depuis .env

REM ====== OPTIONS =========
set "DROP_IF_EXISTS=1"        REM 1 = drop puis recreate ; 0 = ne pas drop
set "IMPORT_DUMP="            REM chemin vers un dump optionnel (ex: C:\dumps\klaxon.sql)
set "RUN_LARAVEL=1"           REM 1 = key:generate + migrate/seed après la DB
REM ========================

REM -- Trouver mysql.exe si pas dans le PATH (chemins courants)
where mysql >NUL 2>&1
IF ERRORLEVEL 1 (
  for %%D in ("C:\Program Files\MySQL\MySQL Server 8.0\bin" ^
              "C:\Program Files\MySQL\MySQL Server 5.7\bin" ^
              "C:\xampp\mysql\bin" ^
              "C:\wamp64\bin\mysql\mysql8.0.31\bin" ^
              "C:\wamp64\bin\mysql\mysql8.0.30\bin") do (
    if exist "%%~D\mysql.exe" set "PATH=%%~D;!PATH!"
  )
  where mysql >NUL 2>&1 || (
    echo ❌ mysql.exe introuvable. Ajoute le dossier bin de MySQL au PATH.
    pause & exit /b 1
  )
)

if not exist ".env" (
  echo ❌ .env introuvable dans ce dossier. Place ce script a la racine du projet.
  pause & exit /b 1
)

REM -- Lire les variables du .env via PowerShell (garde les #commentaires intacts)
for /f "usebackq tokens=1,* delims=" %%L in (`
  powershell -NoProfile -Command ^
    "$kv=@{}; Get-Content '.env' | ?{$_ -match '^\s*DB_(HOST|PORT|DATABASE|USERNAME|PASSWORD)\s*='} | ForEach-Object{ $k,$v=$_.Split('=',2); $kv[$k.Trim()]=($v.Trim()); }; $kv.GetEnumerator() | %%{ $_.Key+'='+$_.Value }"
`) do (
  for /f "tokens=1,2 delims==" %%A in ("%%L") do (
    set "%%A=%%B"
  )
)

REM -- Valeurs par defaut au cas ou
if not defined DB_HOST set "DB_HOST=127.0.0.1"
if not defined DB_PORT set "DB_PORT=3306"

echo === Lecture .env ===
echo DB_HOST=%DB_HOST%
echo DB_PORT=%DB_PORT%
echo DB_DATABASE=%DB_DATABASE%
echo DB_USERNAME=%DB_USERNAME%
echo (DB_PASSWORD masque)
echo.

if not defined DB_DATABASE (
  echo ❌ DB_DATABASE manquant dans .env
  pause & exit /b 1
)
if not defined DB_USERNAME (
  echo ❌ DB_USERNAME manquant dans .env
  pause & exit /b 1
)
if not defined DB_PASSWORD (
  echo ❌ DB_PASSWORD manquant dans .env
  pause & exit /b 1
)

REM -- Mot de passe root MySQL (masque)
for /f "usebackq delims=" %%P in (`
  powershell -NoProfile -Command "$p=Read-Host 'Mot de passe root MySQL' -AsSecureString; $B=[Runtime.InteropServices.Marshal]::SecureStringToBSTR($p); [Runtime.InteropServices.Marshal]::PtrToStringAuto($B)"
`) do set "ROOT_PASS=%%P"

echo.
echo ➤ Configuration de la base et des droits selon .env ...

set "SQL_CMDS="
if "%DROP_IF_EXISTS%"=="1" set "SQL_CMDS=%SQL_CMDS%DROP DATABASE IF EXISTS `%DB_DATABASE%`; "
set "SQL_CMDS=%SQL_CMDS%CREATE DATABASE IF NOT EXISTS `%DB_DATABASE%` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; "
set "SQL_CMDS=%SQL_CMDS%CREATE USER IF NOT EXISTS '%DB_USERNAME%'@'localhost' IDENTIFIED BY '%DB_PASSWORD%'; "
set "SQL_CMDS=%SQL_CMDS%CREATE USER IF NOT EXISTS '%DB_USERNAME%'@'%' IDENTIFIED BY '%DB_PASSWORD%'; "
set "SQL_CMDS=%SQL_CMDS%GRANT ALL PRIVILEGES ON `%DB_DATABASE%`.* TO '%DB_USERNAME%'@'localhost'; "
set "SQL_CMDS=%SQL_CMDS%GRANT ALL PRIVILEGES ON `%DB_DATABASE%`.* TO '%DB_USERNAME%'@'%'; "
set "SQL_CMDS=%SQL_CMDS%FLUSH PRIVILEGES; "

mysql --protocol=TCP -h %DB_HOST% -P %DB_PORT% -u root -p%ROOT_PASS% -e "%SQL_CMDS%"
IF ERRORLEVEL 1 (
  echo ❌ Echec de la configuration MySQL (mot de passe root ? droits ?)
  pause & exit /b 1
)

echo ✅ Base %DB_DATABASE% et utilisateur %DB_USERNAME% configures.
echo.

REM -- Import d'un dump si fourni
if defined IMPORT_DUMP (
  if exist "%IMPORT_DUMP%" (
    echo ➤ Import du dump: "%IMPORT_DUMP%"
    mysql --protocol=TCP -h %DB_HOST% -P %DB_PORT% -u root -p%ROOT_PASS% %DB_DATABASE% < "%IMPORT_DUMP%"
    IF ERRORLEVEL 1 (
      echo ❌ Echec d'import du dump.
      pause & exit /b 1
    )
    echo ✅ Dump importe.
  ) else (
    echo ⚠️  IMPORT_DUMP defini mais introuvable: "%IMPORT_DUMP%"
  )
)

REM -- Partie Laravel optionnelle
if "%RUN_LARAVEL%"=="1" (
  where php >NUL 2>&1 || (echo ⚠️ PHP introuvable, saute la partie Laravel.& goto :ENDLARA)
  if not exist ".env" (
    if exist ".env.example" copy ".env.example" ".env" >NUL
  )
  echo ➤ Laravel: key:generate + migrations
  php artisan key:generate --force
  if defined IMPORT_DUMP (
    php artisan migrate --force
  ) else (
    php artisan migrate:fresh --seed --force
  )
  echo ✅ Laravel OK.
)
:ENDLARA

echo.
echo 🎉 Fini. DB=%DB_DATABASE% / USER=%DB_USERNAME% / PORT=%DB_PORT%
pause
exit /b 0
