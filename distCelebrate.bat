
cd dist 
if EXIST "api/" (
    cd api
    if EXIST .env.api.local.php  del .env.api.local.php  
    if EXIST composer.lock del composer.lock
    if EXIST composer del composer
    if EXIST "logs\" (
        cd  logs
        DEL /F/Q/S *.* > NUL
        cd ..
        RMDIR /Q/S logs
    )
     if EXIST "vendor\" (
        cd  vendor
        DEL /F/Q/S *.* > NUL
        cd ..
        RMDIR /Q/S vendor
    )
    cd ..
)

if EXIST "nppBackup/" (
    cd nppBackup
    DEL /F/Q/S *.* > NUL
    cd ..
    RMDIR /Q/S nppBackup
)

if EXIST favicon.ico del favicon.ico
if EXIST feather-sprite.svg  del feather-sprite.svg
if EXIST manifest.json del manifest.json
if EXIST robots.txt del robots.txt
if EXIST service-worker.js del service-worker.js
if EXIST .htaccess del .htaccess 
if EXIST .htaccess.remote del .htaccess.remote 
