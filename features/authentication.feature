@authentication
Feature: Catch Auth Code
  As a library cataloger
  I want to establish a login session
  so that I can to view a bib record
  
  @vcr_successAccessToken @success
  Scenario: Successfully Get Access Token
    Given I am not following redirects
    When I go to "/catch_auth_code?code=auth_12384794"
    Then the response is a redirect
    And the response has a header "Location" with a value of "/"

  @fail
  Scenario: Failed Get Access Token with bad WSkey/secret
    When I go to "/catch_auth_code?error=invalid_client_id&error_description=WSKey is invalid&http_code=401"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_client_id" in the "div#error_content > p#status" element
    And I should see "Message - WSKey is invalid" in the "div#error_content > p#message" element
      
  @fail  
  Scenario: Fail to Get Auth Code with Expired WSKey
    When I go to "/catch_auth_code?error=invalid_client_id&error_description=WSKey is expired&http_code=401"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_client_id" in the "div#error_content > p#status" element
    And I should see "Message - WSKey is expired" in the "div#error_content > p#message" element
  
  @fail  
  Scenario: Fail to Get Auth Code with Cancelled WSKey
    When I go to "/catch_auth_code?error=invalid_client_id&error_description=WSKey has been canceled&http_code=401"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_client_id" in the "div#error_content > p#status" element
    And I should see "Message - WSKey has been canceled" in the "div#error_content > p#message" element
  
  @fail
  Scenario: Fail to Get Auth Code with Expired Service
    When I go to "/catch_auth_code?error=invalid_scope&error_description=Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Expired]&http_code=403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_scope" in the "div#error_content > p#status" element
    And I should see "Message - Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Expired]" in the "div#error_content > p#message" element

  @fail  
  Scenario: Fail to Get Auth Code with Cancelled Service
    When I go to "/catch_auth_code?error=invalid_scope&error_description=Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Canceled]&http_code=403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_scope" in the "div#error_content > p#status" element
    And I should see "Message - Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Canceled]" in the "div#error_content > p#message" element

  @fail
  Scenario: Failed Get Access Token Wrong Institution
    When I go to "/catch_auth_code?error=access_denied_institution&error_description=this key does not have permission to view contextInstitutionId {128807}&http_code=403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - access_denied_institution" in the "div#error_content > p#status" element
    And I should see "Message - this key does not have permission to view contextInstitutionId {128807}" in the "div#error_content > p#message" element
  
  @fail
  Scenario: Fail to Get Auth Code for Service (wskey) not on WSKey
    When I go to "/catch_auth_code?error=invalid_scope&error_description=Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Not on key]&http_code=403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_scope" in the "div#error_content > p#status" element
    And I should see "Message - Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Not on key]" in the "div#error_content > p#message" element
  
  @fail
  Scenario: Fail to Get Auth Code Redirect Uri mismatch with WSKey
    When I go to "/catch_auth_code?error=redirect_uri_mismatch&error_description=redirect_uri supplied value of {http://localhost/catch_auth_code} does not match expected value&http_code=403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - redirect_uri_mismatch" in the "div#error_content > p#status" element
    And I should see "Message - redirect_uri supplied value of {http://localhost/catch_auth_code} does not match expected value" in the "div#error_content > p#message" element
    
  @fail
  Scenario: Failure no Authenticating Institution ID
    When I go to "/catch_auth_code?error=invalid_scope&error_description=Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Not on key]&http_code=403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - invalid_scope" in the "div#error_content > p#status" element
    And I should see "Message - Invalid scope(s): WorldCatMetadataAPI (WorldCat Metadata API) [Not on key]" in the "div#error_content > p#message" element   