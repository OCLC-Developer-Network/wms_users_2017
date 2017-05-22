<?php
// Copyright 2014 OCLC
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

/**
 * A class that represents an Error
 *
 */
class BibError
{
	
	protected $code;
	protected $message;
	protected $detail;
    
    /**
     * Get Error Code
     *
     * @return string
     */
    function getCode()
    {   
        return $this->code;
    }
    
    /**
     * Get Error Message
     *
     * @return string
     */
    function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Get Error Detail
     *
     * @return string
     */
    function getDetail()
    {
        return $this->detail;
    }
    
    /**
     * Parse the response body for the error information
     * 
     * @param string $error
     * @return array Error
     */
    static function parseError($error){
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
    
    
}