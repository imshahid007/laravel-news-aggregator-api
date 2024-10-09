# News Aggregator, A project in PHP Laravel

This project fetch's the articles using News API, The Guardian API and New York Times API and saved them in Database.

## Prerequiste

-   Docker
-   Docker Compose
-   Composer
-   Apache HTTP Server (Optional, already bundled in docker-compose)
-   PHP 8.2 (Optional, already bundled in docker-compose)
-   MySQL (Optional, already bundled in docker-compose)

## Installation

After cloning the repository, you may need to install the dependencies using composer.

```
composer install
```

Copy (Clone) the .env.example file and update it with your values

```
cp .env.example .env
```

Add Database and API's configuration in the .env file

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_news_aggregator_api
DB_USERNAME=root
DB_PASSWORD=
```
You should set the valid relative API KEY. 
The API KEY's are responsible for fetching the regular updated content from the relative news source.

```
NEWS_API_SECRET_KEY=''
THE_GUARDIAN_API_SECRET_KEY=''
NY_TIMES_API_SECRET_KEY=''
```

Set the prefered Cache Duration for your application in the .env file as well.
```
CACHE_DURATION=300
```


Generate Application key

```
php artisan key:generate
```

Run the Laravel Migrations

```
php artisan migrate
```

Seed the Stock Seeder (Predefined values)

```
php artisan db:seed
```

Run the application

```
php artisan serve
```

Fetch the Articles once a day from all News Api Sources

For once only:

```
php artisan app:fetch-articles
```

For recurring, for once we need to set this in our server cron job settings.

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```


# REST API / Postman Collection

Here you can see the complete documentation for the REST API usage of this application
```
https://documenter.getpostman.com/view/6250866/2sAXxQcrTf
```


# Docker Setup
## Running
Normally, to run PHP application we just need to access it from browser by entering the address and the browser will render the page according to the instructions coded. 

**First, the DockerFile has been coded in a way to update all the dependencies and composer files but still if that doesn't work, follow the below ...**

**Start with docker (recommended)**
```
docker-compose up
```

By running the command docker-compose up, Docker Compose reads the docker-compose.yml file and starts the containers defined within it. It automatically creates the necessary networks, attaches volumes, and manages the dependencies between containers.



# Pest Testing Framework (Unit and Feature)
This application is using Pest PHP framework for Unit and integration testing

Run to view the tests
```
./vendor/bin/pest
```


# About Project and It's working

This project is built to utilise the different News API's in Laravel PHP framework and cache the Data

## Services
1. A Unique Class inside App\Services\NewsApiService was used to request HTTP calls to the api from NewsAPI.org

2. A Unique Class inside 'App\Services\TheGuardianApiService' was used to request HTTP calls to the api from theguardian.com

3. A Unique Class inside 'App\Services\NYTimesApiService' ewas used to request HTTP calls to the api from nytimes.com

4. 'ArticleAggregatorService' class was used to aggregate all the data given by the above services and save them to the database

## Resources
App\Http\Resources are used to modify any output response or behaviour towards the REST API request


## Request
'App\Http\Requests' were used to validate the user preference request all the time.

## Cache
Cache Duration can be changed from .env file 'CACHE_DURATION'

## Pint
Laravel Pint was used for code style so it stays clean and consistent.
