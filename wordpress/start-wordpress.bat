@echo off
rem Starts a local WordPress with the OSUS theme active. Close this window to stop it.
cd /d "%~dp0"
if "%PORT%"=="" set PORT=8881
echo.
echo  Starting WordPress... first run downloads WordPress, give it 1-2 minutes.
echo  Site:  http://127.0.0.1:%PORT%
echo  Admin: http://127.0.0.1:%PORT%/wp-admin   (user: admin  password: password)
echo.
npx --yes @wp-playground/cli@latest server --port=%PORT% --mount=osus:/wordpress/wp-content/themes/osus --blueprint=blueprint.json
pause
