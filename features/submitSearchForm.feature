Feature: Submit Search Form
  As a library cataloger
  I want submit a search for an OCLC Number
  so I can view the associated MARC record
  
  Scenario: Successfully Submit Search
    When I go to "/"
    And I fill in "oclcnumber" with "70775700"
    And I press "Search"
    Then I should see "Dogs and cats" in the "div#content > h1" element
    And I should see "Raw MARC" in the "div#record > h4" element 
    And I should see 1 "div#raw_record pre" elements