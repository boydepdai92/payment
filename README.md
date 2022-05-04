# How to run
1. Clone the project
2. Run ```composer install```
3. Copy file `.env.example` to `.env` 
4. Config your database config in .env file
5. Run ```php artisan migrate```
6. Test with route: ```domain/api/payment/create```
7. Default will return in case of successful payment, if you want to check for failed payment, please change the `ENABLE_FAIL_CASE` variable value to true in the .env file