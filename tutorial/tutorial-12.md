# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 12 - Catching Errors

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
1. Open routes.php
2. Find the bib display route
3. When a request doesn't return a Bib, log the Error status and message
```php
    $this->logger->addInfo("API Call failed " . $bib->getStatus() . " " . $bib->getMessage());
```