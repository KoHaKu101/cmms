composer install
npm install
npm run dev

php artisan optimize
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan migrate
php artisan key:generate
