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
To run the project, make sure docker is installed, navigate to the root directory 
(where Dockerfile and docker-compose.yml are located), and run:

`docker compose up -d`

To view the www/html/index.php file, navigate in a browser to http://localhost:8008.

PHPMyAdmin is also available at http://localhost:8009.

Database connection details are available in environment variables - see accompanying .env file

To enter the container and run commands (eg. using composer):

`docker exec -it --user=www-data TwinklTestServer bash`

Feel free to amend the docker configuration and re-build if required 
(eg. to point to a different directory for the public website files by adding
a new mounted volume in docker-compose.yml). 

XDebug is installed - to use it you will need to set up path mappings in your IDE to 
point from the location of your local files to the /var folder of the container.
