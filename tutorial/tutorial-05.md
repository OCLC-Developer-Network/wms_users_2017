# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 5 - Creating Models Using Test Driven Development

#### Test Setup
1. Create a file called phpunit.xml
2. Open phpunit.xml
3. Configure how phpunit should run
    1. set directory where tests live
    2. specify logging of tests
    3. specify what files are being tested
    4. add listener for PHP_VCR to handle mocks
```php
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php"
         colors="true"
         processIsolation="false"
         stopOnFailure="false"
         syntaxCheck="false"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         testSuiteLoaderClass="PHPUnit_Runner_StandardTestSuiteLoader">     
    <testsuites>
    <testsuite>
      <directory>tests</directory>
    </testsuite>
  </testsuites>
  <logging>
    <log type="coverage-clover" target="clover.xml"/>
    <log type="coverage-html" target="tests/codeCoverage" charset="UTF-8"/>
  </logging>
  <filter>
    <whitelist>
        <directory suffix=".php">app/model</directory>
    </whitelist> 
  </filter>  
  <listeners>
      <listener class="PHPUnit_Util_Log_VCR" file="vendor/php-vcr/phpunit-testlistener-vcr/PHPUnit/Util/Log/VCR.php" />
    </listeners>
  </phpunit>
```
4. In tests directory create a file named bootstrap.php
5. In the tests directory create a directory named mocks and add the mocks
6. Require vendor autoload file
```php
    require_once __DIR__ . '/../vendor/autoload.php';
```
7. Setup HTTP mocking
```php
    \VCR\VCR::configure()->setCassettePath(__DIR__ . '/mocks');
    \VCR\VCR::configure()->enableRequestMatchers(array('method', 'url', 'host'));
```

#### Write your first test

1. In tests directory create a file named BibTest.php to test your Bib Class 
2. Open BibTest.php and add use statements for class you want to use (WSKey and Access Token)
```php
    use OCLC\Auth\WSKey;
    use OCLC\Auth\AccessToken;
``` 
3. define the BibTest Class as extending PHPUnit_Framework_TestCase
```php
    class BibTest extends \PHPUnit_Framework_TestCase
```
4. Create a setup function in the BibTest Class. This runs before every test case.
    1. Create mock Access Token object that returns a specific value
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
5. Write for Test creating a Bib
    1. Create a new Bib object
    2. Test that it is an instance of a Bib object
    3. Pass bib variable to next test
```php
    function testCreateBib(){
        $bib = new Bib();
        $this->assertInstanceOf('Bib', $bib);
        return $bib;
    }
```
6. Make the test pass by creating Bib class and constructor
    1. In the app/model directory create a file named Bib.php to represent the Bib Class
    2. Open Bib.php and declare Bib class
    3. Create a constructor for the Bib class
    ```php
        function __contruct() {
        }
    ```   
7. Run tests
```bash
vendor/bin/phpunit
```

### Setting a Record
1. Make sure testCreateBib passes
2. Load a MARCXML file into the existing bib object.
3. Get the first record in the collection and set this to be the "record" attribute in the bib object.
4. Test record attribute of the bib object is a "File_MARC_Record".
5. Pass thh bib variable to the next test.

```php
    /**
     * Set File_MARC_Record
     * @depends testCreateBib
     */
    function testSetRecord($bib){
        $records = new File_MARCXML(file_get_contents(__DIR__ . '/mocks/marcRecord.xml'), File_MARC::SOURCE_STRING);
        $record = $records->next();
        $bib->setRecord($record);
        $this->assertAttributeInstanceOf("File_MARC_Record", 'record', $bib);
        return $bib;
    }
```

6. Create a setRecord function in the Bib Class
    1. Make sure the $record is a File_MARC_record object. Otherwise throw an Exception
    2. Set record attribute equal to record value
```php
    public function setRecord($record){
        if (!is_a($record, 'File_MARC_Record')) {
            Throw new \BadMethodCallException('You must pass a valid File_MARC_Record');
        }
        $this->record = $record;
    }
    
```

7. Run tests
```bash
vendor/bin/phpunit
```

#### Get Record
1. Write a test for getting a record
    1. Make sure testSetRecord passes
    2. Test that it is an instance of a File_MARC_record object
    3. Pass bib variable to next test
```php
    /**
     * Get Record
     * @depends testSetRecord
     */
    function testGetRecord($bib){
        $this->assertInstanceOf("File_MARC_Record", $bib->getRecord());
        return $bib;
    }
```
2. Write function to get a record in Bib class
```php
    public function getRecord()
    {
        return $this->record;
    }
```
3. Run tests
```bash
vendor/bin/phpunit
```


#### Get Id
1. Write a test for getting a record
    1. Make sure testGetRecord passes
    2. Test getId method returns appropriate value.
    3. Pass bib variable to next test
```php
    /**
     * Get Id
     * @depends testGetRecord
     */
    function testGetId($bib) {
        $this->assertEquals("70775700", $bib->getId());
    }
```
2. Write function to get the Id in Bib class
```php
    public function getId()
    {
        if (empty($this->id)){
            if (is_numeric($this->record->getField('001')->getData())) {
                $this->id = $this->record->getField('001')->getData();
            } else {
             $this->id = substr($this->record->getField('001')->getData(), 3);
            }
            
        }
        return $this->id;
    }
```

3. Run tests
```bash
vendor/bin/phpunit
```

#### Get OCLCNumber
1. Write a test for getting a record
    1. Make sure testGetRecord passes
    2. Test that getOCLCNumber method returns the appropriate value
    3. Pass bib variable to next test
```php
    /**
     * Get OCLCNumber
     * @depends testGetRecord
     */
    function testGetOCLCNumber($bib) {
        $this->assertEquals("ocm70775700", $bib->getOCLCNumber());
    }
    
```
2. Write function to get a record in Bib class
    1. Use built-in function on File_MARC_Record object to get the data in the 001
```php
    public function getOCLCNumber()
    {
        return $this->record->getField('001')->getData();
    }
```

3. Run tests
```bash
vendor/bin/phpunit
```

#### Get Title
1. Write a test for getting a record
    1. Make sure testGetRecord passes
    2. Test that getTitle method returns appropriate value
    3. Pass bib variable to next test
```php
    /**
     * Get Title
     * @depends testGetRecord
     */
    function testGetTitle($bib) {
        $this->assertEquals("Dogs and cats", $bib->getTitle());
    }
```    
2. Write function to get a record in Bib class
    1. Use built-in functions on File_MARC_Record object to get the data
```php
    public function getTitle()
    {
        $title = $this->record->getField('24.', true)->getSubfield('a')->getData();
        if ($this->record->getField('24.', true)->getSubfield('b')){
            $title .= $this->record->getField('24.', true)->getSubfield('b')->getData();
        }
        $title = trim($title, " /");
        return $title;
    }
```
3. Run tests
```bash
vendor/bin/phpunit
```

#### Get Author
1. Write a test for getting a record
    1. Make sure testGetRecord passes
    2. Test that getAuthor method returns appropriate value
```php
    /**
     * Get Author
     * @depends testGetRecord
     */
    function testGetAuthor($bib) {
        $this->assertEquals("Jenkins, Steve", $bib->getAuthor());
    }
```
2. Write function to get a record in Bib class
    1. Use built-in functions on File_MARC_Record object to get the data
```php
    public function getAuthor()
    {
        if ($this->record->getField('100', true)) {
            $author = $this->record->getField('100', true)->getSubfield('a')->getData();
        } elseif ($this->record->getField('110', true)) {
            $author = $this->record->getField('110', true)->getSubfield('a')->getData();
        } elseif ($this->record->getField('111', true)){
            $author = $this->record->getField('111', true)->getSubfield('a')->getData();
        } elseif ($this->record->getField('700', true)) {
            $author = $this->record->getField('700', true)->getSubfield('a')->getData();
        } elseif ($this->record->getField('710', true)) {
            $author = $this->record->getField('710', true)->getSubfield('a')->getData();
        } elseif ($this->record->getField('711', true)) {
            $author = $this->record->getField('711', true)->getSubfield('a')->getData();
        } else {
            $author = "";
        }
        $author = rtrim($author, ',');
        if (strpos($author, ".") > 0) {
            $author= rtrim($author, '.');
        }
        return $author;
    }
```
3. Run tests
```bash
vendor/bin/phpunit
```

#### Getting a Bib from the API
1. Tell tests what file to use for mocks
2. Find a Bib
3. Test that object returned is an instance of a Bib
4. Pass bib variable to next test
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
5. Make test pass by creating a static "find" function for the Bib
    1. Make sure a a valid OCLC Number is passed in
    2. Make sure a valid Access Token is passed in
    3. Create a url for the request
    4. Create an HTTP client
    5. Create an array of headers
    6. try to make the HTTP request
        1. If successful
            1. Parse the response body XML
            2. Pull out MARC record string and use it to create a File_MARC_Record
        2. If fails
            1. Pass response off to BibError::parseError to handle
```php 
    public static function find($oclcnumber, $accessToken){
        if (!is_numeric($oclcnumber)){
            Throw new \BadMethodCallException('You must pass a valid OCLC Number');
        } elseif (!is_a($accessToken, '\OCLC\Auth\AccessToken')) {
            Throw new \BadMethodCallException('You must pass a valid Access Token');
        }
        $url = static::$serviceUrl . $oclcnumber . '?classificationScheme=LibraryOfCongress&holdingLibraryCode=MAIN';
        $client = new Client();
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

6. Run tests
```bash
vendor/bin/phpunit
```

#### Write test for getting MarcRecord object
1. Make sure testGetBib passes
2. Test that getRecord method on bib object returns a File_MARC_Record
3. Pass bib variable to next test
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

4. Run tests
```bash
vendor/bin/phpunit
```
    
#### Write test for getting values from Bib
1. Make sure testParseMarc passes
2. Test that getID method on bib object returns a value of 70775700 
3. Test that getOCLCNumber method on bib object returns a value of ocm70775700
4. Test that getTitle method on bib object returns a value of Dogs and cats
5. Test that getAuthor method on bib object returns a value of Jenkins, Steve
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

6. Run tests
```bash
vendor/bin/phpunit
```       

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
    1. Create a new BibError object
    2. Make sure response is not HTML
    3. Parse the XML response
    4. Extract key field: code, message, detail
    5. Return BibError object
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

**[on to Part 6](tutorial-06.md)**

**[back to Part 4](tutorial-04.md)**