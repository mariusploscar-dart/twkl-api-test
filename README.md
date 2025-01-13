# PHP Development Task
## Part One (~3 hours, at home)

Implementing a back-end API only (no front-end required):

* Create an API endpoint to allow a new user to sign up for a subscription.
* The user will need to supply first name, last name, email address, and to specify whether they are a student, teacher, parent, or private tutor. The payload should be POSTed to your endpoint as JSON.
* When a user signs up, save their details in a database and send a welcome email. The text of the email will depend on the type of user (student, teacher, etc.) - no need to actually compose the message and send the email, just demonstrate how you would do it.
* Implement a system that allows sign-ups to be validated. Initially, we want to check that the IP address of the user is not on a block list, and that the values supplied in the form do not include any special characters. Further validation rules may be required later, so build the solution in such a way that any further validation requirements could be added easily. Don't worry too much about implementation details of the checks (eg. you can just hard-code a block list), the important part here is to demonstrate that you can write code that can be easily extended to implement new functionality later.
* The endpoint should return a JSON response, whether the call was successful or not.
* Make sure to commit changes as you go with appropriate commit messages, so we can see the history.

You can use a framework or just plain PHP code if you wish (using a framework is recommended, otherwise this task will take a lot longer to do!), but the solution must use object oriented code and demonstrate your understanding of good programming practices. Use your own discretion in designing a database schema. There is no need for this to be overly complicated - just the minimum data structure needed to fulfil the task is fine.

What we are looking for:

* SOLID principles observed
* PSR-12 followed
* Good separation of concerns
* No security vulnerabilities
* No commented out code except where used to demonstrate a point (eg. for the welcome email)
* Code should be executable (no mis-typed variable names, missing use statements, etc.)
* Code should be concise and well laid out - no excessive use of blank lines, unused use statements, etc.
* Commit messages understandable and git history looks sensible

If your solution involves anything that you feel might require further explanation, or if you feel that some element of best practice is not applicable for some reason, please add comments to the code to explain the reason for your decision.

## Running the project
The project is based on the Laravel framework

To run the project, make sure docker is installed, navigate to the root directory
(where Dockerfile and docker-compose.yml are located), and follow these steps:

1. Create .env file using .env.example as a template and modify the values where needed (eg DB credentials - used in Docker container).

2. Build the containers:

`docker compose up --build -d`

3. Install necessary composer libraries:

`docker-compose exec app composer install`

4. Generate a Laravel key:

`docker-compose exec app php artisan key:generate`

Make sure the APP_KEY environment variable is set.

5. Install Laravel application:

`docker-compose exec app php artisan migrate`

6. To access the application, navigate in a browser to http://localhost:8080.

PHPMyAdmin is also available at http://localhost:8081.

## Use the API

Execute an HTTP request with the following parameters:

```
address: 'http://localhost:8080/api/signup'
method: 'POST'
headers: {
  'Content-Type': 'application/json
}
JSON body: {
    "first_name": "John",
    "last_name": "Doe",
    "email": "johnny.doe@fantasy.org",
    "type": "student"
}
```
\* values allowed for type field : 'student', 'teacher', 'parent', 'private_tutor'

Open PHPMyAdmin at http://localhost:8081 to check created users in the users table (select the database configured in .env > DB_DATABASE).

## Test using the API from a blocked IP address

In your .env file set TEST_BLOCKED_IP_ADDRESS to the IP address used to call the API. You should receive an error saying your IP address is blocked.

## Logs

Check confirmation / error log messages in ./storage/logs/laravel.log

## Cleanup

Destroy the containers

`docker-compose down`

To clean up docker build data if needed (eg change db credentials) run first:

`docker volume ls`

then get your docker volume name and use it to run:

`docker volume rm [your-docker-volume-dbdata]`