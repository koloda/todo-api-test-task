To run project, please use next command:
    `php ./vendor/bin/sail up -d`

Then run migrations:
    `php ./vendor/bin/sail artisan migrate`

To run tests:
    `php ./vendor/bin/sail test`

You can seed database with test data:
    `php ./vendor/bin/sail artisan db:seed`

Project will be available on http://localhost:80

To run queries you should provide Authorization header with Bearer token. Then you can generate token with next command:
    `php ./vendor/bin/sail artisan token:generate`
(Note than DB should be seeded before generating token)

You can find OpenApi specification in file `openapi.yaml` in root directory of project.

For check code-style you can use next command:
    `php ./vendor/bin/sail pint --test`


Notes: 
 - I've used Laravel Sail for running project in Docker. It's not necessary to use it, but it's more convenient for me.
 - In real project I would use MySQL Recursive CTE (https://www.mysqltutorial.org/mysql-recursive-cte/) for getting tasks tree, but I think it's not necessary for test task.
