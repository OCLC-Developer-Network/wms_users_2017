# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 6

1. Model
	1. Test Driven Development (aka write the tests first) 
	   1. In tests directory create a file named BibTest.php to test your Bib Class 
	   2. Open BibTest.php and define the BibTest Class as extending PHPUnit_Framework_TestCase
	   3. Create a setup function. This runs before every test case.
	       a. Create mock Access Token object that returns a specific value
	   4. Write for Test creating a Bib
	   5. Test getting a Bib
	       a. mocks
	   6. Write test for getting values from Bib
	   7. Write test for getting MarcRecord object
	
	2. Create the Bib Class
	   1. In the app/model directory create a file named Bib.php to represent the Bib Class
	   2. Open Bib.php and declare Bib class
	   3. Create a constructor for the Bib class
	   4. Create a static "find" function for the Bib
	   5. Create function to retrieve Id
       6. Create function to retrieve OCLCNumber
       7. Create function to retrieve title
       8. Create function to retrieve author
	4. 
	2. Writing tests first – test driven development
		1. E.g. bibtest.php before bib.php
		2. Assures that code is testable
		3. Mocks – just download to X directory
	3. Make a class for each resource, each action is a method
		1. E.g. getTitle
	4. Classes: bib, biberror
		1. Static method find – why treating this like constructor?
		2. Why avoiding overloading the constructor
		3. All the HTTP stuff (slides)
	5. Authentication
		1. CCG / Access Token
