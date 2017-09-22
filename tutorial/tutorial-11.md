# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 11 - Catching Errors

#### Displaying an error message for the user
1. Open routes.php
2. Find the bib display route
3. When a request doesn't return a Bib, return the Error view. Pass it the following variables:
- error
- error_message
- oclcnumber
```php
        return $this->view->render($response, 'error.html', [
            'error' => $bib->getStatus(),
            'error_message' => $bib->getMessage(),
            'oclcnumber' => $args['oclcnumber']
    ]);
```

#### Logging Errors
1. Open dependencies.php
2. Add a logging tool to your container
    1. Create a logger
    2. Tell the system how/where logger will log. We're logging to a file.
```php
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};
```
3. Open routes.php
4. Find the bib display route
5. When a request doesn't return a Bib, log the Error status and message
```php
    $this->logger->addInfo("API Call failed " . $bib->getStatus() . " " . $bib->getMessage());
```

**[on to Part 12](tutorial-12.md)**

**[back to Part 10](tutorial-10.md)**