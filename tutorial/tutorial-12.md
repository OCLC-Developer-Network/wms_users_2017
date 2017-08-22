# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 12 - Catching Errors

#### Displaying an error message for the user

```php
        return $this->view->render($response, 'error.html', [
            'error' => $bib->getStatus(),
            'error_message' => $bib->getMessage(),
            'oclcnumber' => $args['oclcnumber']
    ]);
```

#### Logging Errors

```php
    $this->logger->addInfo("API Call failed " . $bib->getStatus() . " " . $bib->getMessage());
```