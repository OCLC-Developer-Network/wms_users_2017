Feature: View Search Form
  As a library cataloger
  I want to view the search form
  so that search for an OCLC Number
  
  Scenario: Successfully View Search form
    When I go to "/"
    Then I should see "Search by OCLC Number" in the "h1" element
    And I should see 1 "form" elements
    And I should see 1 "input[name=oclcnumber]" elements
    And I should see 1 "input[name=search]" elements