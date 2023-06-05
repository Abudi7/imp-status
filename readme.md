# System Status Project
In this project, we have developed a web application using the Symfony framework and MySQL database to manage system statuses. The application allows Admin to create, edit, and delete system statuses,and regular users can subscribe and unsubscribe to them. Additionally, it provides a list of system statuses and their current status.

## Features
The application has the following features:

- User authentication and authorization.
- Adding, editing, and deleting system statuses.
- Subscribing and unsubscribing to system statuses.
- Displaying the list of system statuses.


## Technologies Used
The project utilizes the following technologies:

* Symfony 6.2: PHP web application framework.
* MySQL: Relational database management system.
* Twig: Template engine used for generating HTML.
* Bootstrap 5: CSS framework used for styling the web pages.
- HTML, CSS, JavaScript
- Font Awesome

## Installation
To install the project, follow the steps below:

1. Clone the repository to my machine.
2. Install the required dependencies using the composer install command.
3. Configure the database connection in the .env file.
4. Create the database using the php bin/console doctrine:database:create command.
5. Create the database schema using the php bin/console doctrine:schema:create command.

## Usage
To use the application, follow the steps below:

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

* Controllers: Responsible for handling HTTP requests, responses, and logic for different application routes.
  - HomeController: Handles the homepage and displays the system statuses.
  - SecurityController: Handles user authentication and authorization.
  - MailerController: This controller handles email notifications and sending system status updates to subscribed users.
  - RegisterController: This controller handles user registration and account creation.
  - ScheduledMaintenanceController: This controller manages the scheduled maintenance functionality, including creating, editing, and deleting maintenance events.
  - StatusController: This controller handles the CRUD operations for system statuses, allowing admins to create, edit, and delete status updates.
  - SystemController: This controller handles the CRUD operations for systems, including creating, editing, and deleting systems.
  - SubscriptionController: This controller manages user subscriptions to system statuses, allowing users to subscribe or unsubscribe from specific systems.
  - SystemStatusController: This controller manages the CRUD operations for system status updates, allowing admins to create, edit, and delete status updates for specific systems.
  - TemplateController: This controller handles the management of templates used in the application, including creating, editing, and deleting templates.

* Entities: Represent the data models in the application and interact with the database.
  - User: Represents a user of the application.
  - SystemStatus: Represents a system status, including system name, status, and subscribers.
  - Status: This entity represents a system status and contains information such as the status name.
  - Subscription: This entity represents a subscription made by a user to receive status updates for a specific system.
  - Template: This entity represents a template used in the application for generating system status notifications.
  - System: This entity represents a system and contains information such as the system name and associated status updates.

* Forms: Define the structure and validation rules for input forms in the application.
  - SystemStatusType: Used for creating and editing system statuses.

* Templates: Written in Twig and define the structure and layout of the application pages.
  - base.html.twig: Contains the base HTML structure for all pages.
  - home/index.html.twig: Displays the list of system statuses.
  - security/login.html.twig: Displays the login form.
  - system_status/new.html.twig: Displays the form for creating a new system status.
  - system_status/edit.html.twig: Displays the form for editing an existing system status.

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

## Updates 

### (17/05/2023)
* 17/05/2023: Added maintenance functionality to notify subscribed users about system maintenance.
* 17/05/2023: Implemented incident notification feature to inform users about system incidents.
* 17/05/2023: Refactored code for improved readability and performance.

### (26/05/2023)
* Implement scheduled maintenance calendar view

  - Created a new route and controller action for displaying the scheduled maintenance calendar.
  - Added a repository method to fetch system statuses with the "Maintenance" status.
  - Updated the Twig template to render the maintenance events in a monthly calendar.
  - Styled the calendar using Bootstrap classes for responsive layout.
  - Set the background color of maintenance events to red using RGB value.

  ### Features (05/06/2023)

- Display a monthly calendar with maintenance events highlighted
- Navigate between months using previous and next buttons
- View details of each maintenance event
- Responsive design for mobile and desktop devices

## Credits
This project was created by [ Abdulrhman Alshalal ](https://www.linkedin.com/in/abdulrhman-alshalal-556642196/?originalSubdomain=at) and is licensed under the [Anton Paar](https://www.anton-paar.com/at-de/)