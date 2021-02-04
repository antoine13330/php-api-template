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

All Models must extend the parent class **Model**. __(All Models extending from the Model class have connection to the database by default)__

![ModelImage](https://i.imgur.com/6GvIl6k.png)

Models are responsible for manipulating data, making queries to the database and returning the result to the controller, which will later return to the user.

## Database

To connect to the database we use the **PDO connection**, the database configuration process is extremely simple, just fill in the necessary fields in the **db-config.php** file
>src / DB / db-config.php

![DbImage](https://i.imgur.com/zdupLmL.png)

Queries must be executed within Models, to use the connection to the Database, just use the variable **$conn** present in all Models that extend the **Model** class

![DbQueryImage](https://i.imgur.com/0lXYBj2.png)

>More information about PDO can be found in the official documentation <https://www.php.net/manual/pt_BR/book.pdo.php>
## Middleware

Middlewares can be found in the Middle folder
> src / Middleware

Middlewares must be created as functions in files and can be called within controllers. If you have global Middleware for all routes, such as the predefined authentication Middleware, it can be called in the **Controller** class within the **resource** method and extended to all controllers using the **resource** method.
![MiddlewareInController](https://i.imgur.com/tRUwJq9.png)
>Example of authentication middleware being used for all controllers that use resources (Standard Configuration)
If the resource method is not being used, middleware can be called within the individual methods.

##### Auth Middleware

By default, routes that extend the **Controller** class and use the **resource** method for internal routing pass through the Authentication Middleware, found in the **auth-middleware.php** file
> src / Middleware / auth-middleware.php

This middleware uses JWT standards to generate authentication tokens, the tokens are fully customizable to suit the demands and their settings can be found in the **AuthConfig.php** file
> src / Middleware / AuthConfig.php

[TokenConfig](https://i.imgur.com/shpcuta.png)

In the above variables it is possible to control the **duration of the token**, the **encryption key** and the **algorithm** to be used.

![VariablesToken](https://i.imgur.com/shpcuta.png)

>To generate a new token, just call the **generateToken** method, passing the token **user id as a parameter**. This is the **default** method, if you wanted to **change the token payload**, you need to access the **AuthConfig** class and change the methods of **generating and validating** the tokens.

![GenerateToken](https://i.imgur.com/3GhaFlr.png)

>Token generation example

## Helpers
