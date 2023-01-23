## Laravel CSV Download/Process Jobs

The project is based on the below boilerplate. You click the button and a job is sent to download a CSV file using streams and dispatches a second job to read that file as a stream of chunks.

<img width="1199" alt="image" src="https://user-images.githubusercontent.com/1534598/214048048-8c04fc28-b575-4f0a-9f95-8c4963bfa7ed.png">


<img width="1032" alt="image" src="https://user-images.githubusercontent.com/1534598/214045582-777b43d1-de8b-45b7-b62e-7eb51054ffac.png">


## Original boilerplate

# Laravel 8  & TailwindCSS 2 skeleton

Whether you’re watching my tutorials or you are interested in cloning this repo where TailwindCSS is already implemented in an empty Laravel 8 project!

•	Author: Code With Dary <br>
•	Twitter: [@codewithdary](https://twitter.com/codewithdary) <br>
•	Instagram: [@codewithdary](https://www.instagram.com/codewithdary/) <br>

## Requirements
•	PHP 7.3 or higher

## Version
This Laravel framework is running on a version of 8.48.2 and TailwindCSS is running on 2.2.4.

## Usage <br>
Clone the repository <br>
```
git clone git@github.com:codewithdary/laravel8-tailwindcss2.git
```

Change directories into laravel8-tailwindcss2 <br>
```
cd laravel8-tailwindcss2/
```

Install composer <br>
```
composer install
```

Create the .env file by duplicating the .env.example file <br>
```
cp .env.example .env
```

Set the APP_KEY value <br>
```
php artisan key:generate
```

Clear your cache & config (OPTIONAL)
``` 
php artisan cache:clear && php artisan config:clear
```

Finally, run your project in the browser!
```
php artisan serve
```
