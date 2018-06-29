# Domain Tagging, Jr.
An application that showcases domains and their descriptions.

## Live Demo
Click Here

## Installation
After cloning repo, you want to first create your own .env file. 
There's currently a .env.example file you can copy from and fill in the blanks with certain variables that you want to designate.
First thing you want to do is create an APP KEY for your Laravel project, so from the root directory, type
> php artisan key:generate

If you look now at your .env file, the APP_KEY parameter should be auto completed.

Next, we want to compile some assets, so
> composer install

> npm install

> npm run prod

## Usage
To run the app server, just type
> php artisan serve
