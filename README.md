# LogadApp Router

LogadApp Router is a PHP router class that provides routing capabilities for building web applications.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
- [Contributing](#contributing)
- [License](#license)

## Installation

To use LogadApp Router in your PHP project, you can either manually download the source code or install it via Composer.

###  Composer Installation
You can install LogadApp Router using Composer by running the following command:

   ```bash
   composer require logadapp/router
```

## Usage
To start using LogadApp Router, follow these steps:

1. Include the composer autoload file in your PHP script:

    ```php
   require_once 'vendor/autoload.php';
   ```

2. Create a new instance of the Router class:
    ```php
   $router = new LogadApp\Router\Router();
    ```

3. Define routes using the available HTTP methods (GET, POST, PATCH, DELETE) and their respective handler callbacks:

    ```php
   $router->get('/', function () {
       // Handle GET request for the root path
   });

   $router->post('/users', function () {
       // Handle POST request for the /users path
   });
   
   $router->group('/auth', function() use ($router) {
       $router->post('/login', [AuthController::class, 'login']);
   
       $router->post('/register', function () {
           // Handle POST request for the /auth/register path
       });
   }
   ```

4. Run the router to handle incoming requests:
    ```php
   $router->run();
   ```
   
For more usages, check index.php or the examples folder.

## Features
- Simple and lightweight PHP router
- Support for common HTTP methods: GET, POST, PATCH, DELETE
- Route grouping and prefixing
- Customizable 404 (Not Found) and 405 (Method Not Allowed) handlers
- Custom route found handlers
- Integration with classes as callback handlers


## Contributing
As always, contributions are welcome!
