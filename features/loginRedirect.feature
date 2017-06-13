@login
Feature: Login Redirect
  As a library cataloger
  I want to login
  so that I can to view a bib record
  
  Background:
    Given I am not following redirects
  
  Scenario: Successfully Redirect Login
    When I go to "/bib/70775700"
    Then the response is a redirect
    And the response has a header "Location" with a value of "https://authn.sd00.worldcat.org/oauth2/authorizeCode?client_id=test&authenticatingInstitutionId=128807&contextInstitutionId=128807&redirect_uri=http%3A%2F%2Flocalhost%3A9090%2Fcatch_auth_code&response_type=code&scope=WorldCatMetadataAPI"