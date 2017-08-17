# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 6

1. Model
	1. Identify resources you need to work with, what actions you need to perform on them
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
