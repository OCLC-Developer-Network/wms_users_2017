# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 7 - Route Basics

#### Routes - Telling the App what to do
Our application is supposed to have two "screens":
- Search box
- Display screen for a bibliographic record
To make our application work we have to tell it what urls to use for those two screens. This requires a routes.php file

1. In the app directory, create a routes.php file
2. Open the routes.php file
3. Define the route for the search screen
    - Add the HTTP method which will be used
    - Add the "path"
    - Return the view you want the application to display in the response
    - Give the route a name

```php
//display form
$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'search_form.html');
})->setName('display_search_form');
```
4. Define a basic route for the screen to display a bibliographic record

```php
//display bib route
$app->get('/bib[/{oclcnumber}]', function ($request, $response, $args){
    return $this->view->render($response, 'bib.html');
})->setName('display_bib');
```