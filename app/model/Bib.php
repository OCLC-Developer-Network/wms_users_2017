<?php
use GuzzleHttp\Client, GuzzleHttp\Exception\RequestException, GuzzleHttp\Psr7\Response;

Class Bib {	
	public static $serviceUrl = 'https://worldcat.org/bib/data/';
	public static $testServer = FALSE;
	public static $userAgent = 'OCLC DevConnect demo';
	
	protected $id;
	protected $record;
	
	/**
	 * Getter for the ID
	 * @return string
	 */
	public function getId()
	{
		if (isset($this->id)){
			return $this->id;
		} else {
			return $this->record->getField('001')->getData();
		}
	}
	
	/**
	 * Getter for the OCLCNumber
	 * @return string
	 */
	public function getOCLCNumber()
	{
		return $this->record->getField('001')->getData();
	}
	
	/**
	 * Getter for the Title
	 * @return string
	 */
	public function getTitle()
	{
		$title = $this->record->getField('24.', true)->getSubfield('a')->getData();
		if ($this->record->getField('24.', true)->getSubfield('b')){
			$title .= $this->record->getField('24.', true)->getSubfield('b')->getData();
		}
		$title = trim($title, " /");
		return $title;
	}
	
	/**
	 * Getter for the Author
	 * @return string
	 */
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
	
	/**
	 * Getter for the record
	 * @return string
	 */
	public function getRecord()
	{
		return $this->record;
	}
	
	/**
	 * Construct the Bib object
	 *
	 */
	public function __construct(){
		
	}
	/**
	 * Find and retrieve a Bib by ID
	 *
	 * @param $id string
	 * @param $accessToken OCLC/Auth/AccessToken
	 * @return Bib or \Guzzle\Http\Exception\BadResponseException
	 */
	
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
			return Error::parseError($error);
		}
	}
}