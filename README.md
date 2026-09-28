Hafiz Mobile ERP Phase 13

This folder is an update for the live site. It does not contain passwords.

Download this repository as a ZIP from GitHub, then upload it in Hostinger File Manager and extract it inside public_html. Do not replace the .env file.

Then in SSH:

cd domains/hafizmobile.shop/public_html
php artisan migrate --force
php artisan db:seed --force
php artisan view:clear
php artisan route:clear
php artisan cache:clear

The sidebar must say Phase 13.
