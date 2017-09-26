@error
Feature: View Error
  As a library cataloger
  I want to view a usable error message when something fails
  so that I can tell support what is wrong
  
  Scenario: Unsuccessfully View Bib - Invalid Token
    When I go to "/bib/401"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - 401" in the "div#error_content > p#status" element
    And I should see "Message - AccessToken {tk_12345} is invalid" in the "div#error_content > p#message" element
    And I should see "Authorization header: Bearer tk_12345" in the "div#error_content > p#detail" element

  Scenario: Unsuccessfully View Bib - Expired Token
    When I go to "/bib/403"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - 401" in the "div#error_content > p#status" element
    And I should see "Message - AccessToken {tk_12345} has expired" in the "div#error_content > p#message" element
    And I should see "Authorization header: Bearer tk_12345" in the "div#error_content > p#detail" element  
    
  Scenario: Unsuccessfully View Bib - Unknown OCLC Number
    When I go to "/bib/9999999999"
    Then I should see "System Error" in the "div#content > h1" element
    And I should see "Status - 404" in the "div#error_content > p#status" element
    And I should see "Unable to locate resource: 9999999999." in the "div#error_content > p#message" element      