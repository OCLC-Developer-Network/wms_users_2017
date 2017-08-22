# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 10

#### Displaying the Bibliographic Record
1. Screen that displays bib (/bib/oclcnum)
2. Calling the model within the route
	1. This is where you could have a controller – keeping it simple here

1. Get the OCLC Number you want to look up. It can come from POSTED form data or the url
    a. If the OCLC Number came in the url in $oclcnumber
    b. If OCLC Number came in form data, if so store it in the $oclcnumber
    c. Otherwise return an error page
    d. If you have an OCLC Number, use the Bib class to find the record
    e. check to make sure Bib is returned
        - if so, return the bib record display
        - if not, return the error page display
```php
    if (isset($args['oclcnumber'])){
        $oclcnumber = $args['oclcnumber'];
        $_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName(), ['oclcnumber' => $args['oclcnumber']]);
    } elseif ($request->getParam('oclcnumber')) {
        $oclcnumber = $request->getParam('oclcnumber');
        $_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName()) ."?" . http_build_query($request->getQueryParams());
    } else {
        $this->logger->addInfo("No OCLC Number present");
        return $this->view->render($response, 'error.html', [
                'error' => 'No OCLC Number present',
                'error_message' => 'Sorry you did not pass in an OCLC Number'
        ]);
    }
    $bib = Bib::find($oclcnumber, $_SESSION['accessToken']);
    
    if (is_a($bib, "Bib")){
        
        return $this->view->render($response, 'bib.html', [
                'bib' => $bib
        ]);
    }else {
        // catch the error
    }
```

#### Adding Authentication
1. First thing that needs to happen is get an Access Token (or make sure it has one); call to model is going to take access token
