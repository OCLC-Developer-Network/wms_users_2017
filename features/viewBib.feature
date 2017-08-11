@bib
Feature: View Bib Record
  As a library cataloger
  I want to view a bib record
  so that I can examine its properties
  
  Scenario: Successfully View Bib
    When I go to "/bib/70775700"
    Then I should see "Dogs and cats" in the "div#content > h1" element
    And I should see "Raw MARC" in the "div#record > h4" element 
    And I should see 1 "div#raw_record pre" elements