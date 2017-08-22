# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 6

#### Test Driven Development (aka write the tests first) 
1. In tests directory create a file named bootstrap.php
2. Require vendor autoload file
```php
    require_once __DIR__ . '/../vendor/autoload.php';
```
3. Setup HTTP mocking
```php
    \VCR\VCR::configure()->setCassettePath(__DIR__ . '/mocks');
    \VCR\VCR::configure()->enableRequestMatchers(array('method', 'url', 'host'));
```
4. In tests directory create a file named BibTest.php to test your Bib Class 
5. Open BibTest.php and add use statements for class you want to use (WSKey and Access Token)
```php
    use OCLC\Auth\WSKey;
    use OCLC\Auth\AccessToken;
``` 
6. define the BibTest Class as extending PHPUnit_Framework_TestCase
```php
    class BibTest extends \PHPUnit_Framework_TestCase
```
7. Create a setup function in the BibTest Class. This runs before every test case.
    a. Create mock Access Token object that returns a specific value
    ```php
        function setUp()
        {   
            $options = array(
                    'authenticatingInstitutionId' => 128807,
                    'contextInstitutionId' => 128807,
                    'scope' => array('WorldCatMetadataAPI')
            );
            $this->mockAccessToken = $this->getMockBuilder(AccessToken::class)
            ->setConstructorArgs(array('client_credentials', $options))
            ->getMock();
            
            $this->mockAccessToken->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('tk_12345'));
        }
    ```
8. Write for Test creating a Bib
    a. Create a new Bib object
    b. Test that it is an instance of a Bib object
```php
    function testCreateBib(){
        $bib = new Bib();
        $this->assertInstanceOf('Bib', $bib);
    }
```
9. Test getting a Bib
    a. Tell tests what file to use for mocks
    b. Find a Bib
    c. Test that object returned is an instance of a Bib
    d. Pass bib variable to next test
```php
    /**
     *@vcr bibSuccess
     */
    function testGetBib(){
        $bib = Bib::find(70775700, $this->mockAccessToken);
        $this->assertInstanceOf('Bib', $bib);
        return $bib;
    }
```
10. Write test for getting MarcRecord object
    a. Make sure testGetBib passes
    b. Test that getRecord method on bib object returns a File_MARC_Record
    c. Pass bib variable to next test
```php
    /**
     * can parse Single Bib string
     * @depends testGetBib
     */
    function testParseMarc($bib)
    {
        $this->assertInstanceOf("File_MARC_Record", $bib->getRecord());
        return $bib;
    }
```
    
11. Write test for getting values from Bib
    a. Make sure testParseMarc passes
    b. Test that getID method on bib object returns a value of 70775700 
    c. Test that getOCLCNumber method on bib object returns a value of ocm70775700
    d. Test that getTitle method on bib object returns a value of Dogs and cats
    e. Test that getAuthor method on bib object returns a value of Jenkins, Steve
```php
    /**
     * can parse Single Copy string
     * @depends testParseMarc
     */
    function testParseLiterals($bib)
    {
        $this->assertEquals("70775700", $bib->getId());
        $this->assertEquals("ocm70775700", $bib->getOCLCNumber());
        $this->assertEquals("Dogs and cats", $bib->getTitle());
        $this->assertEquals("Jenkins, Steve", $bib->getAuthor());
    }
```

#### Create the Bib Class
1. In the app/model directory create a file named Bib.php to represent the Bib Class
2. Open Bib.php and declare Bib class
3. Create a constructor for the Bib class
```php
    function __contruct() {
    }
```    
4. Create function to retrieve Id
```php
    function getID() {
        return $this->id;
    }
```
5. Create function to retrieve OCLCNumber
```php
    function getOCLCNumber() {
    }
```
6. Create function to retrieve title
```php
    function getTitle() {
    }
```   
7. Create function to retrieve author
```php
    function getAuthor() {
    }
```   
8. Create a static "find" function for the Bib
    a. Make sure a a valid OCLC Number is passed in
    b. Make sure a valid Access Token is passed in
    c. Create a url for the request
    d. Create an HTTP client
    e. Create an array of headers
    f. try to make the HTTP request
        i. If successful
            1. Parse the response body XML
            2. Pull out MARC record string and use it to create a File_MARC_Record
        ii. If fails
            1. Pass response off to BibError::parseError to handle
```php 
    public static function find($oclcnumber, $accessToken){
        if (!is_numeric($oclcnumber)){
            Throw new \BadMethodCallException('You must pass a valid OCLC Number');
        } elseif (!is_a($accessToken, '\OCLC\Auth\AccessToken')) {
            Throw new \BadMethodCallException('You must pass a valid Access Token');
        }
        $url = static::$serviceUrl . $oclcnumber . '?classificationScheme=LibraryOfCongress&holdingLibraryCode=MAIN';
        $client = new Client(
                [
                        'curl' => [
                                CURLOPT_SSLVERSION => '3'
                        ]]
                );
        $headers = array();
        $headers['Authorization'] = 'Bearer ' . $accessToken->getValue();
        $headers['Accept'] = 'application/atom+xml;content="application/vnd.oclc.marc21+xml"';
        
        try {
            $response = $client->request('GET', $url, ['headers' => $headers]);
            $response_body = simplexml_load_string($response->getBody());
            
            //We parse the MARCXML out of the Atom Response
            $response_body->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
            $response_body->registerXPathNamespace('rb', 'http://worldcat.org/rb');
            $marc_xml = $response_body->xpath('//atom:content/rb:response/child::*');
            //We want a File_MARC Record created from the MARC
            $records = new File_MARCXML($marc_xml[0]->asXML(), File_MARC::SOURCE_STRING);
            $bib = new Bib();
            $bib->id = $oclcnumber;
            $bib->record = $records->next();
            return $bib;
        } catch (RequestException $error) {
            return BibError::parseError($error);
        }
    }
```
9. All the HTTP stuff (slides)

#### Create the BibError Class
1. In the app/model directory create a file named BibError.php to represent the BibError Class
2. Open BibError.php and declare BibError class
```php
    class BibError {}
```
3. Create a constructor for the BibError class
```php
    function __contruct() {}
```
4. Create necessary variables: code, message, detail
```php
    protected code;
    protected message;
    protected detail;
```
5. Create function to retrieve error code
```php
    function getCode() {
        return $this->code;
    }
```
6. Create function to retrieve error message
```php
    function getMessage() {
        return $this->message;
    }
```    
7. Create function to retrieve error deail
```php
    function getDetail() {
        return $this->detail;
    }
```
8. Create static function to parse Error
    a. Create a new BibError object
    b. Make sure response is not HTML
    c. Parse the XML response
    d. Extract key field: code, message, detail
    e. Return BibError object
```php
   public static function parseError($error){ 
        $errorObject = new BibError();
        if (implode($error->getResponse()->getHeader('Content-Type')) !== 'text/html;charset=utf-8'){
            $error_response = simplexml_load_string($error->getResponse()->getBody());
            $errorObject->code = (integer) $error_response->code;
            $errorObject->message = (string) $error_response->message;
            $errorObject->detail = (string) $error_response->detail;
        } else {
            $errorObject->code = (integer) $error->getResponse()->getStatusCode();
        }
        return $errorObject;
   }
```   

