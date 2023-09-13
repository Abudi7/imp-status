# IMP Project
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
6. Install WSL (Windows Subsystem for Linux) for a Linux development environment by running wsl --install.
This step is required if you're using Windows.
7. Install Apache2 web server. You can typically do this using a package manager like apt if you're on a Linux distribution. 
8. Install MariaDB (or any other database server of your choice) for your database needs. Use the package manager or MariaDB's official website for installation.
9. Configure the Apache2 server to serve your Symfony application. This includes setting up virtual hosts, configuring document roots, and enabling necessary modules.
10.Access the application in your web browser by navigating to http://localhost:Port. Replace Port with the port number you've configured in your Apache2 virtual host configuration.

## Usage
To use the application, follow the steps below:

1. Start the web server using the symfony server:start command.
2. Open a web browser and navigate to http://localhost:8080/.
3. Register an account or login with an existing account.
4. Add, edit, or delete system statuses as needed.
5. Subscribe or unsubscribe to system statuses.
6. View the list of system statuses and their current status.
7. Additionally, in the last updates to the Imp, we have implemented the following features:

    7.1. Admins can now create and edit maintenance and incident templates in the database.

    7.2. Admins can send emails using these templates with optional attachments.

    7.3. Admins can view and edit system details and scheduled maintenance events.

    7.4. The system now allows the management of both current and future maintenance events.

    7.5. Maintenance events now have a start and end date.

    7.6. Improved user interface and design for better user experience.

    7.7. Admins can change the status of an event to "concluded" and set the end date automatically.

    7.8. Admins can also cansel and change future maintenance events


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
  - SubscriptionController: This controller manages user subscriptions to system statuses, allowing users to subscribe or unsubscribe from specific systems.
  - TemplateController: This controller handles the management of templates used in the application, including creating, editing, and deleting templates.
  - CalendarController: Manages calendar-related functionality.
  - ContactController: Handles the contact form and email functionality.
  - EventController: Manages event-related functionality.

* Entities: Represent the data models in the application and interact with the database.
  - User: Represents a user of the application.
  - Subscription: This entity represents a subscription made by a user to receive status updates for a specific system.
  - Template: This entity represents a template used in the application for generating system status notifications.
  - Event: Represents an event in the application, which can be either maintenance or incident-related.
  - System: This entity represents a system and contains information such as the system name and associated events.

* Forms: Define the structure and validation rules for input forms in the application.
  - ContactType: Used for creating and handling the contact form.
  - EventsType: Used for creating and handling event-related forms, including both maintenance and incident events.
  - SystemType: Used for creating and handling system-related forms, including adding and editing system information.
  - StatusType: Used for creating and handling system status forms, enabling admins to create and edit system status updates.
* Repository: Data repositories responsible for querying the database and retrieving data for various entities.
  - EventsRepository: Manages queries related to events, including fetching event data. 
  - SubscriptionRepository: Handles database queries related to user subscriptions to system statuses.
  - SystemRepository: Manages queries related to systems, including retrieving system information.
  - TemplateRepository: Responsible for querying template data used in the application.
  - UserRepository: Handles database queries related to user data, including user authentication and authorization.
  - StatusRepository: Manages queries related to system status updates, including fetching and manipulating status data.

* Templates: Written in Twig and define the structure and layout of the application pages.
  - base.html.twig: Contains the base HTML structure for all pages.
  - home/index.html.twig: Displays the list of system statuses.
  - security/login.html.twig: Displays the login form.
  - template/index.html.twig: Displays the list of templates.
  - template/new.html.twig: Displays the form for creating a new template.
  - template/show.html.twig: Displays details of a specific template.
  - template/edit.html.twig: Displays the form for editing an existing template.
  - contact/index.html.twig: Displays the contact form.
  - event/index.html.twig: Displays the list of events and provides actions for creating and managing events.
  - event/show.html.twig: Displays details of a specific event.
  - event/edit.html.twig: Displays the form for editing an existing event.
  - calendar/index.html.twig: Displays calendar-related functionality.


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

13. app_template: This route is used to display all the templates in the application.

14. app_template_new: This route is used to add a new template.

15. app_template_show: This route is used to display a particular template. The route requires an id parameter that represents the template to be displayed.

16. app_template_edit: This route is used to edit a particular template. The route requires an id parameter that represents the template to be edited.

17. get_template_content: This route is used to retrieve the content of a template. The route requires an id parameter that represents the template from which to retrieve content.

18. get_template_subject: This route is used to retrieve the subject of a template. The route requires an id parameter that represents the template from which to retrieve the subject.

19. app_websocket: This route is used for WebSocket communication.

20. app_calender: This route is used for calendar-related functionality.

21. app_contact: This route is used to access the contact form.

22. app_events: This route is used for event-related functionality.

23. app_events_new_maintenance: This route is used to create a new maintenance event for a system.

24. app_events_new_incident: This route is used to create a new incident event for a system.

25. app_events_system: This route is used to access events related to a specific system.

26. app_events_edit_maintenance: This route is used to edit a maintenance event.

27. app_events_resolve_incident: This route is used to resolve an incident event.

28. get_system_status: This route is used to retrieve the system status.

29. app_events_concluding: This route is used to conclude an event.

30. app_events_edit: This route is used to edit an event.

31. app_system_future_maintenance: This route is used to access future maintenance events for a system.

32. app_mailer: This route is used for email-related functionality.


## Twig Extensions
* AppExtension: This extension contains custom Twig filters and functions used in the templates.

## Conclusion
This project demonstrates the use of Symfony, MySQL, Twig, and Bootstrap to create a web application that manages system statuses. The application provides basic functionality such as user authentication and authorization, creating, editing, and deleting system statuses, and subscribing and unsubscribing to system statuses.

## Updates

#### (17/05/2023)
* 17/05/2023: Added maintenance functionality to notify subscribed users about system maintenance.
* 17/05/2023: Implemented incident notification feature to inform users about system incidents.
* 17/05/2023: Refactored code for improved readability and performance.

#### (26/05/2023)
* Implement scheduled maintenance calendar view
  - Created a new route and controller action for displaying the scheduled maintenance calendar.
  - Added a repository method to fetch system statuses with the "Maintenance" status.
  - Updated the Twig template to render the maintenance events in a monthly calendar.
  - Styled the calendar using Bootstrap classes for a responsive layout.
  - Set the background color of maintenance events to red using RGB value.

#### Features (05/06/2023)
- Display a monthly calendar with maintenance events highlighted
- Navigate between months using previous and next buttons
- View details of each maintenance event
- Responsive design for mobile and desktop devices

#### (09/06/2023)
* Updated frontend h3 headings for improved accessibility and consistency.

#### (06/09/2023)
* Updated the maintenance method to send emails as HTML for improved email formatting.

#### (05/09/2023)
* Fixed permissions in the project.

#### (04/09/2023)
* Modified the email code in the events Controller.

#### (31/08/2023)
* Updated the .env file and tested contact email functionality.

#### (30/08/2023)
* Built a contact feature for the application.

#### (24/08/2023)
* Implemented dynamic email template population.

#### (23/08/2023)
* Implemented dynamic status based on events table.

#### (18/08/2023)
* Added event management functionality.

#### (17/08/2023)
* Modified the incident method.

#### (16/08/2023)
* Updated the code in the frontend home.

#### (10/08/2023)
* Merged a pull request related to events.

#### (09/08/2023)
* Implemented the event MVC, including index and new pages.

#### (07/08/2023)
* Modified the system and subscription functionalities and created events.

#### (04/08/2023)
* Updated the Template entity and controller.

#### (03/08/2023)
* Added "Deactive System" functionality to the Admin dashboard.

#### (27/07/2023)
* Modified the Websocket script for the client-side.

#### (25/07/2023)
* Modified the method to send notifications to users subscribed to systems.

#### (19/07/2023)
* Updated the Calendar script.

#### (10/07/2023)
* Updated the Websocket implementation.

#### (05/07/2023)
* Modified the Calendar script to convert to the client time zone.

#### (06/06/2023)
* Implemented AJAX JS functionality to send HTTP requests and retrieve data from the database.

#### (05/06/2023)
* Implemented responsive design for the maintenance calendar.

#### (02/06/2023)
* Styled the calendar table and improved visibility of maintenance updates.
* Additional Routes: Added new routes for calendar-related functionality, contact form, and event management.


## Credits
This project was created by [ Abdulrhman Alshalal ](https://www.linkedin.com/in/abdulrhman-alshalal-556642196/?originalSubdomain=at) and is licensed under the [Anton Paar](https://www.anton-paar.com/at-de/)