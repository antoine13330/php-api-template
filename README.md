# php-api-template
A template created with vanilla PHP for creating APIs

* [2021-01-29] - First version released | Writing the documentation
* [2021-02-01] - Documentation update | Controller documentation written. Writing the other parts.

# Getting started

## Routes

All routes must be listed in the **routes.php** file.
Path: src / Routes / routes.php

![RouteImage](https://i.imgur.com/lqdVAVU.png)

The recommended method for routes in version 1.0 is always the **POST** method, since with it is possible to redirect precisely which method must be executed inside the controller by the private ways method.

It is desirable that every route redirects to the **resource** method, this method is present in all controllers and it redirects to the desired path (See more about this in the controllers section).

## Controllers

All controllers need to extend the parent class **Controller**, the **Controller** class that has the **resource** method responsible for redirecting internal paths within the controller.

The **ways** method must be implemented in all controllers, and in it the distribution of the controller's internal paths must be implemented. __(See image below)__

![ControllerImage](https://i.imgur.com/dc6uLOe.png)

##### Method Ways:

The ways method is responsible for handling the internal redirection and authentication of each route. The $ways array must contain as its index the "resource" and as its only two properties the method of the controller to be executed and the type of authentication, respectively.

The **resource** index of the **ways** method must always be sent as a parameter named as an **underline** (_) and the **resource** method will check if this **resource** exists within the **ways** method of the respective controller of that route and will execute the method if it is found , otherwise it will return an error. __(See below for an example of requesting a route using the ways)__.

![WayRequest](https://i.imgur.com/U7rbMEB.png)

>To access the values received in the request, just declare as a parameter of the method a variable called $data, it contains all the values sent in the request with the exception of the way (value declared with the underline).

![MethodWithvalue](https://i.imgur.com/ZMMtSjK.png)

## Models


## Database
## Middleware
## Helpers
