# Setup new environment

1. Clone Repository
2. run command in folder "composer Install"
3. copy .env file provided by admin into root directory
3.1 or developer can generate their version too, run this command after generating/renaming .env file "php artisan key:generate"
   - cp .env.example .env
   - php artisan key:generate
4. expose public folder to the internet


**Note:** Make sure you have following packages installed
- php 8+ 
- composer