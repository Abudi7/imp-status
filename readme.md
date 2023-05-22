# System Status Project
In this project, we created a web application to manage system statuses. We used the Symfony framework and MySQL database.

## Features
The application has the following features:

User authentication and authorization.
Adding, editing, and deleting system statuses.
Subscribing and unsubscribing to system statuses.
Displaying the list of system statuses.


## Technologies Used
The following technologies were used in this project:

* Symfony 6.2: PHP web application framework.
* MySQL: Relational database management system.
* Twig: Template engine used for generating HTML.
* Bootstrap 5: CSS framework used for styling the web pages.

## Installation
To install the project, you need to follow these steps:

1. Clone the repository to my machine.
2. Install the required dependencies using the composer install command.
3. Configure the database connection in the .env file.
4. Create the database using the php bin/console doctrine:database:create command.
5. Create the database schema using the php bin/console doctrine:schema:create command.

## Usage
To use the application, follow these steps:

1. Start the web server using the symfony server:start command.
2. Open a web browser and navigate to http://localhost:8000/.
3. Register an account or login with an existing account.
4. Add, edit, or delete system statuses as needed.
5. Subscribe or unsubscribe to system statuses.
6. View the list of system statuses and their current status.

## Authentication
The application requires authentication to access certain features. Users can create a new account or log in to an existing one using the links in the navigation bar.

## Authorization
The application distinguishes between two types of users: admins and regular users. Only admins can create, edit or delete system status updates.

## System Status Updates
On the homepage, users can view a list of all the system status updates that have been posted. Users with admin privileges can create a new status update by clicking on the "Create New" button.

To view the details of a status update or edit/delete an existing one, click on the corresponding links in the "Actions" column of the status updates table.

## Code Overview
The project consists of the following main components:

* Controllers: The controllers handle HTTP requests and responses, and contain the logic for the different application routes.

* Entities: The entities represent the different data models in the application and are used to interact with the database.

* Forms: The forms define the structure and validation rules for the different input forms in the 
application.

* Templates: The templates are written in Twig and define the structure and layout of the different application pages.

## Controllers
* HomeController: This controller handles the homepage and displays the list of system statuses.
* SecurityController: This controller handles the user authentication and authorization.

## Entities
* User: This entity represents a user of the application.
* SystemStatus: This entity represents a system status and contains information such as the system name, status, and subscribers.

## Forms
* SystemStatusType: This form type is used to create and edit system statuses.

## Templates
* base.html.twig: This template contains the base HTML structure for all pages.
* home/index.html.twig: This template displays the list of system statuses.
* security/login.html.twig: This template displays the login form.
* system_status/new.html.twig: This template displays the form to create a new system status.
* system_status/edit.html.twig: This template displays the form to edit an existing system status.

## Available Routes
1. app_home: This route is used to display the homepage of the application.

2. app_home_subscribe: This route is used to subscribe to a system for which you want to receive status updates. The route requires an id parameter that represents the system to subscribe to.

3. app_allSubscription: This route is used to display all the subscriptions of the logged-in user.

4. app_register: This route is used to display the registration form.

5. app_login: This route is used to display the login form.

6. app_logout: This route is used to log out the user.

7. app_status: This route is used to display the status of all the systems that the user is subscribed to.

8. app_status_new: This route is used to add a new status update for a system.

9. app_subscription: This route is used to display all the subscriptions of the logged-in user.

10. app_subscription_unsubscribe: This route is used to unsubscribe from a system. The route requires an id parameter that represents the subscription to be deleted.

11. app_system: This route is used to display all the systems in the application.

12. app_system_new: This route is used to add a new system.

13. app_system_status: This route is used to display all the status updates for a particular system. The route requires an id parameter that represents the system to be displayed.

14. app_system_status_new: This route is used to add a new status update for a system.

15. app_system_status_show: This route is used to display a particular status update. The route requires an id parameter that represents the status update to be displayed.

16. app_system_status_edit: This route is used to edit a particular status update. The route requires an id parameter that represents the status update to be edited.

17. app_system_status_delete: This route is used to delete a particular status update. The route requires an id parameter that represents the status update to be deleted.

18. app_template: This route is used to display all the templates in the application.

19. app_template_new: This route is used to add a new template.

## Twig Extensions
* AppExtension: This extension contains custom Twig filters and functions used in the templates.

## Conclusion
This project demonstrates the use of Symfony, MySQL, Twig, and Bootstrap to create a web application that manages system statuses. The application provides basic functionality such as user authentication and authorization, creating, editing, and deleting system statuses, and subscribing and unsubscribing to system statuses.

## Updates (17/05/2023)
* 17/05/2023: Added maintenance functionality to notify subscribed users about system maintenance.
* 17/05/2023: Implemented incident notification feature to inform users about system incidents.
* 17/05/2023: Refactored code for improved readability and performance.

## Credits
This project was created by [ Abdulrhman Alshalal ](https://www.linkedin.com/in/abdulrhman-alshalal-556642196/?originalSubdomain=at) and is licensed under the [Anton Paar](https://www.anton-paar.com/at-de/)