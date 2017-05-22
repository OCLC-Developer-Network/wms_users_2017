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
 * 
 * @author Karen Coombs
 *
 * This takes two possible options: 
 * environment - what environment to generates mocks from. The default is from the production environment
 * filter - a comma seperated list of mock names to build
 * 
 * Example usage
 * php getMocks.php
 * php getMocks.php --filter="copySuccessNote"
 */

use Symfony\Component\Yaml\Yaml;
use OCLC\Auth\WSKey;
use OCLC\Auth\AccessToken;
use OCLC\User;

global $environment, $mockFolder, $retrievedToken, $mockValue, $wskey;

require __DIR__ . '/../vendor/autoload.php';

$shortopts  = "";
$shortopts .= "e::";
$shortopts .= "f::";

$longopts  = array(
    "environment::",
	"filter::",
);

$scriptOptions = getopt($shortopts, $longopts);

\VCR\VCR::turnOn();

if (isset($scriptOptions['environment'])){
    $cassettePath = 'mocks/' . $scriptOptions['environment'];
} else {
    $cassettePath = 'mocks';
}
\VCR\VCR::configure()->setCassettePath($cassettePath);

\VCR\VCR::insertCassette('accessToken'); 

$mockFolder = __DIR__ . "/mocks/";

// load the YAML for mocks
$mockBuilder = Yaml::parse(file_get_contents(__DIR__ . '/mockBuilder.yml'));

    // load the YAML for config
	$config = Yaml::parse(file_get_contents('../app/config/config.yml'));

    if (isset($scriptOptions['environment'])){
        $mockFolder .= $scriptOptions['environment'] . '/';
        $environment = $scriptOptions['environment'];
        AccessToken::$authorizationServer = $config[$environment]['authorizationServiceUrl'];
        WSKey::$testServer = TRUE;
        Bib::$serviceUrl = $config[$environment]['copyUrl'];
        Bib::$testServer = TRUE;
    } else {
        $environment = 'prod';
    }
    
    if (empty($config[$environment]['institution'])) {
    	Throw new \Exception('No valid config file present');
    }


// Go get an accessToken
$options =  array(
    'services' => array('WorldCatMetadataAPI')
);
$wskey = new WSKey($config[$environment]['wskey'], $config[$environment]['secret'], $options);

$user = new User($config[$environment]['institution'], $config[$environment]['principalID'], $config[$environment]['principalIDNS']);

$retrievedToken = $wskey->getAccessTokenWithClientCredentials($config[$environment]['institution'], $config[$environment]['institution'], $user);
\VCR\VCR::eject();

if (isset($scriptOptions['filter'])) {
	$filter = explode(",", $scriptOptions['filter']);
	$mockBuilder = array_filter(
			$mockBuilder,
			function ($key) use ($filter) {
				return in_array($key, $filter);
			},
			ARRAY_FILTER_USE_KEY
			);
}
foreach ($mockBuilder as $mock => $mockValues) {
	createMock($mock, $mockValues);
}

// delete the accessToken file
unlink($mockFolder . 'accessToken'); 

function createMock($mock, $mockValues = null){
    global $environment, $mockFolder, $retrievedToken, $mockValue, $wskey;
    
    if (file_exists($mockFolder . $mock)){
        unlink($mockFolder . $mock);
    }
    
    $mockFile = $mock;
    \VCR\VCR::insertCassette($mockFile);
    printf("Mock created for '%s'.\n", $mock);
    if (isset($mockValues['accessToken'])){
        $accessToken = new AccessToken('client_credentials', array('accessTokenString' => $mockValues['accessToken'], 'expiresAt' => '2018-08-30 18:25:29Z'));
    } else {
        $accessToken = $retrievedToken;
    }
    
    // call the appropriate function
    if (isset($mockValues['id'])){
        $bib = Bib::find($mockValues['id'], $accessToken);
    } else {
    	echo "How to create this mock hasn't been specified";
    }
    \VCR\VCR::eject();
    file_put_contents($mockFolder . $mockFile, str_replace("Bearer " . $accessToken->getValue(), "Bearer tk_12345", file_get_contents($mockFolder . $mockFile)));
}


?>