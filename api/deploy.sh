#!/bin/sh
php artisan down
git pull
php artisan migrate
gulp --production
chmod -R 777 storage bootstrap/cache public/forge public/uploads public/filemanager/userfiles
php artisan optimize
chmod -R 777 storage bootstrap/cache public/forge public/uploads public/filemanager/userfiles
php artisan up
