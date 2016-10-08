#!/bin/sh
git pull
php artisan down
php artisan migrate
gulp --production
chmod -R 777 storage bootstrap/cache public/forge public/uploads public/filemanager/userfiles
php artisan optimize
chmod -R 777 storage bootstrap/cache public/forge public/uploads public/filemanager/userfiles
php artisan up
