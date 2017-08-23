# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 7

#### Configuration
1. In app directory create a config directory
2. In app/config create file config.yml
3. Edit config.yml so it contains a set of key value pairs with:
    - wskey key
    - wskey secret
    - principalID
    - principalIDNS
    - institution registry ID
	
```php
prod:
    wskey: test
    secret: secret
    principalID: id 
    principalIDNS: namespace
    institution: 128807
```
4. In app directory create a file name settings.php. This will store your application settings like whether or not to display errors
```php
    return [
        'settings' => [
                // Slim Settings
                'determineRouteBeforeAppMiddleware' => true,
                'displayErrorDetails' => true,
        ],
    ];
```

5. In app directory create files named dependencies.php . This will store some information which is accessible anywhere in the application
    a. add use statements for class you want to use (WSKey, Access Token, YAML)
    ```php
    use OCLC\Auth\WSKey;
    use OCLC\User;
    use Symfony\Component\Yaml\Yaml;
    ```
    b. Define a container for holding reusable information
    ```php
    // DIC configuration
    $container = $app->getContainer();
    ```
    c. Add the configuration file as an array to the contain as the "config"
    - Use Yaml parse to pull configuration file in as an associative array
    ```php    
    // -----------------------------------------------------------------------------
    // Service providers
    // -----------------------------------------------------------------------------
    
    $container['config'] = function ($c) {
        global $config_file;
        return Yaml::parse($config_file);
    };
    ```
    d. Add the logging mechanism to the container as "logger"
    - Create a new Monolog logger named "my_logger"
    - Create a handler to write log info to a file
    - Add handler for writing to file to Logger 
    ```php
    $container['logger'] = function($c) {
        $logger = new \Monolog\Logger('my_logger');
        $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
        $logger->pushHandler($file_handler);
        return $logger;
    };
    ```
    e. Create a WSkey object and add it to the container as "wskey"
    - Create an array of service name to request Access token for
    - Create an array of options to pass when creating a WSkey
    - Create a WSkey object using the wskey and secret value stored in the container, and the options array
    ```php    
    $container['wskey'] = function ($c) {
        
        $services = array('WorldCatMetadataAPI');
        $options = array('services' => $services);
        return new WSKey($c->get("config")['prod']['wskey'], $c->get("config")['prod']['secret'], $options);
    };
    ```
    f. Create a user object and add it to the container as "user"
    - Create a user based on the institution, principalID and principalIDNS stored in the container
    ```php    
    $container['user'] = function ($c) {
        return new User($c->get("config")['prod']['institution'], $c->get("config")['prod']['principalID'], $c->get("config")['prod']['principalIDNS']);
    };
    ```
    g. Create a View object and add it to the container as "view" 
    - Create a Twig view
        - tell it where views are stored
        - tell it where views should be cached
    - Set the basePath of the application
    - Set a global session variable
    ```php    
    // Register twif views on container
    $container['view'] = function ($container) {
        $view = new \Slim\Views\Twig('app/views', [
                'cache' => 'app/cache'
        ]);
        
        // Instantiate and add Slim specific extension
        $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
        $view->getEnvironment()->addGlobal('session', $_SESSION);
        
        return $view;
    };
    ```
#### Creating the Application Entry point
1. Create a file called index.php
2. Open index.php
3. Add require the vendor autoload file
```php
    require_once('vendor/autoload.php');
```
4. Start the session to keep track of state
```php
    session_start();
```
5. Create the App object and pass it configuration information
```php
    // instantiate the App object
    global $config_file;
    $config_file = file_get_contents(__DIR__ . '/app/config/config.yml');
    $config = require __DIR__ . '/app/settings.php';
    
    $app = new \Slim\App($config);
```
6. Load dependencies, middleware and routes
```php
    // Set up dependencies
    require __DIR__ . '/app/dependencies.php';
    // Register middleware
    require __DIR__ . '/app/middleware.php';
    // Register routes
    require __DIR__ . '/app/routes.php';
```
7. Start the application
```php
    // Get container
    $container = $app->getContainer();
        
    // Run application
    $app->run();
```
#### Application Authentication
Authentication happens repeatedly in our application so we want to create a reusable function to handle Authentication when we need it. To do this we're using something called a "Middleware".
The idea behind middleware is to allow us to intercept any request and tell the application to do something before and/or application request. 
In this case we're the application that anytime this function is called it should perform authentication BEFORE the client request.

1. In app directory create a file named "middleware.php"
2. Open middleware.php
3. Create a variable called $auth_mw to hold the anonymous function which performs authentication.
4. The function will take $request, $response, and $next parameters
5. Retrieve an Access token and set the session variable named "accessToken" equal to it
    a. Take the existing wskey object in your container and use the getAccessTokenWithClientCredentials method passing in:
    - institution from your config file as authenticating institution
    - institution from your config file as context institution
    - user from your container as the user
6. Tell the application to continue on its way.

```php
$auth_mw = function ($request, $response, $next) {
    $_SESSION['accessToken'] = $this->get("wskey")->getAccessTokenWithClientCredentials($this->get("config")['prod']['institution'], $this->get("config")['prod']['institution'], $this->get("user"));
    $response = $next($request, $response);
    return $response;
};