## Installation
Go to project directory.

First of all you should install dependencies:

in case you have local installed php 8.2^    
`composer install`

in other case you should use docker:
  
- `docker-compose up -d`  
- `docker exec  todo-api-test-task-laravel.test-1 /bin/bash -c "composer install"`
- `docker-compose down --volumes`


Then you should copy .env.example to .env:
    
`cp .env.example .env`

To run project, please use next command:
    
`./vendor/bin/sail up -d --build`

Then run:
 - `./vendor/bin/sail artisan sail:install` and chose mysql
 - `./vendor/bin/sail artisan key:generate`

Then run migrations:

`./vendor/bin/sail artisan migrate`

## Running

To run tests:
    
`./vendor/bin/sail test` 

_Note that during first run some tests can fail because of test DB migrations. Next runs should be successful._

You can seed database with test data to check API:
    
`./vendor/bin/sail artisan db:seed`

Project will be available on http://localhost:80

To run queries you should provide Authorization header with Bearer token. Then you can generate token with next command:
    
`./vendor/bin/sail artisan token:generate`

_(Note that DB should be seeded before generating token)_

## Other

You can find OpenApi specification in file `openapi.yaml` in root directory of project.

For check code-style you can use next command:
    
`./vendor/bin/sail pint --test`


Notes: 
 - I've used Laravel Sail for running project in Docker. It's not necessary to use it, but it's more convenient for me.
 - In real project I would use MySQL Recursive CTE (https://www.mysqltutorial.org/mysql-recursive-cte/) for getting tasks tree, but I think it's not necessary for test task.
