# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 11 - Catching Errors

#### Displaying an error message for the user
1. Open routes.php
2. Find the bib display route
3. When a request doesn't return a Bib, return the Error view. Pass it the following variables:
- error
- error_message
- error_detail
- oclcnumber
```php
        return $this->view->render($response, 'error.html', [
            'error' => $bib->getCode(),
            'error_message' => $bib->getMessage(),
            'error_detail' => $bib->getDetail(),
            'oclcnumber' => $args['oclcnumber']
    ]);
```
4. Create file views/error.html
```php
{% extends "layout.html" %}

{% block title %}System Error{% endblock %}
{% block content %}
    <h1>System Error</h1>
    <div id="error_content">
    <p id="status">Status - {{error}}</p>
    {%if error_message %}
    <p id="message">Message - {{error_message}}</p>
    {% endif %}
    {%if error_detail %}
    <p id="detail">{{error_detail}}</p>
    {% endif %}
    {%if oclcnumber %}
    <p id="oclcnumber">OCLC Number - {{oclcnumber}}</p>
    {% endif %}
    </div>
{% endblock %}
```

#### Logging Errors
1. Add a logger to the container
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
2. Log errors that occur in routing
    1. Open routes.php
    2. Find the bib display route
    3. When a request doesn't return a Bib, log the Error status and message
    ```php
        $this->logger->addInfo("API Call failed " . $bib->getCode() . " " . $bib->getMessage());
    ```
#### Handle authentication errors
1. Open middleware.php
2. Wrap code to get Access token in try/catch block
    1. Log error
    2. Display error page
```php
try {
    $_SESSION['accessToken'] = $this->get("wskey")->getAccessTokenWithClientCredentials($this->get("config")['prod']['institution'], $this->get("config")['prod']['institution'], $this->get("user"));
    $response = $next($request, $response);
    return $response;
}catch (Exception $e){
    $this->logger->addInfo('Failed to get Access Token' . $e->getMessage());
    return $this->view->render($response, 'error.html', [
            'error' => $e->getMessage()
    ]);
}
```

**[on to Part 12](tutorial-12.md)**

**[back to Part 10](tutorial-10.md)**