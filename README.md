## Swagify Catalog service

This project is for the Swagify Catalog backend service. It is build using PHP 7.4, Laravel 7.

To run project need to update .env file. 
Copy config from .env.development and put in .env file. 

Make sure mysql configurations are correct depending on your local machine.

```
# Usefull commands
# To install fresh db setup
php artisan migrate:fresh

# To insert dummy seed data
php artisan db:seed
```