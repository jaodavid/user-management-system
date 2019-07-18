# User Management System
- Api Based User Management Using Laravel + Passport + Vue
---------------------------------

## Setup Project / Deploy instructions
- git clone https://github.com/jaodavid/user-management-system.git OR extract ZIP file
- cd to user-management-system
- composer install
- npm install
- cp .env.example .env
- php artisan key:generate
- Create an empty database for your project using the database tools you prefer
- In the .env file, add database information to allow Laravel to connect to the database
    - In the .env file fill in the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to match the credentials of the database     you just created. This will allow us to run migrations and seed the database in the next step.
- php artisan migrate
- php artisan db:seed
- php artisan passport:install
- php artisan serve
- enjoy :)
----------------------------------

# Author
- Mark Jourine David

