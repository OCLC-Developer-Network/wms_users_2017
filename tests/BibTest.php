<?php
// Copyright 2013 OCLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
// http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

use OCLC\Auth\WSKey;
use OCLC\Auth\AccessToken;

class BibTest extends \PHPUnit_Framework_TestCase
{
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
	
	/**
	 * Create Bib
	 */
	function testCreateBib(){
	    $bib = new Bib();
	    $this->assertInstanceOf('Bib', $bib);
	    return $bib;
	}
	
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
	
	/**
	 * Get Record
	 * @depends testSetRecord
	 */
	function testGetRecord($bib){
	    $this->assertInstanceOf("File_MARC_Record", $bib->getRecord());
	    return $bib;
	}
	
	/**
	 * Get Id
	 * @depends testGetRecord
	 */
	function testGetId($bib) {
	    $this->assertEquals("70775700", $bib->getId());
	}
	
	function testGetNumericId() {
		$bib = new Bib();
		$records = new File_MARCXML(file_get_contents(__DIR__ . '/mocks/marcRecord2.xml'), File_MARC::SOURCE_STRING);
		$record = $records->next();
		$bib->setRecord($record);
		$this->assertEquals("70775700", $bib->getId());
	}
	
	/**
	 * Get OCLCNumber
	 * @depends testGetRecord
	 */
	function testGetOCLCNumber($bib) {
	    $this->assertEquals("ocm70775700", $bib->getOCLCNumber());
	}
	
	/**
	 * Get Title
	 * @depends testGetRecord
	 */
	function testGetTitle($bib) {
	    $this->assertEquals("Dogs and cats", $bib->getTitle());
	}
	
	/**
	 * Get Author
	 * @depends testGetRecord
	 */
	function testGetAuthor($bib) {
	    $this->assertEquals("Jenkins, Steve", $bib->getAuthor());
	}

	/**
	 *@vcr bibSuccess
	 */
	function testGetBib(){
		$bib = Bib::find(70775700, $this->mockAccessToken);
		$this->assertInstanceOf('Bib', $bib);
		return $bib;
	}

	/**
	 * can parse Single Bib string
	 * @depends testGetBib
	 */
	function testParseMarc($bib)
	{
		$this->assertInstanceOf("File_MARC_Record", $bib->getRecord());
		return $bib;
	}
	
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
	
	/**
	 *@vcr bibSuccessJournal
	 */
	function testGetBibJournal(){
		$bib = Bib::find(6692485, $this->mockAccessToken);
		$this->assertInstanceOf('Bib', $bib);
		return $bib;
	}
	
	/**
	 * can parse Single Copy string
	 * @depends testGetBibJournal
	 */
	function testParseMarcJournal($bib)
	{
		$this->assertInstanceOf("File_MARC_Record", $bib->getRecord());
		return $bib;
	}
	
	/**
	 * can parse Single Bib string
	 * @depends testParseMarcJournal
	 */
	function testParseLiteralsJournal($bib)
	{
		$this->assertEquals("6692485", $bib->getId());
		$this->assertEquals("ocm06692485", $bib->getOCLCNumber());
		$this->assertEquals("JAMA :the journal of the American Medical Association.", $bib->getTitle());
		$this->assertEquals("American Medical Association", $bib->getAuthor());
	}
	
	
	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage You must pass a valid Access Token
	 */
	function testNoAccessToken()
	{
		$bib = Bib::find(6692485, "");
	}
	
	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage You must pass a valid Access Token
	 */
	function testInvalidAccessToken()
	{
		$bib = Bib::find(6692485, 'fala');
	}
	
	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage You must pass a valid OCLC Number
	 */
	function testNoId()
	{
		$bib = Bib::find(null, $this->mockAccessToken);
	}
	
	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage You must pass a valid File_MARC_Record
	 */
	function testBadMarc()
	{
		$bib = new Bib();
		$bib->setRecord('junk');
	}
	
}