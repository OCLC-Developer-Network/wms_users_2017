6.	Model
a.	Identify resources you need to work with, what actions you need to perform on them
b.	Writing tests first – test driven development
i.	E.g. bibtest.php before bib.php
ii.	Assures that code is testable
iii.	Mocks – just download to X directory
c.	Make a class for each resource, each action is a method
i.	E.g. getTitle
d.	Classes: bib, biberror
i.	Static method find – why treating this like constructor?
ii.	Why avoiding overloading the constructor
iii.	All the HTTP stuff (slides)
e.	Authentication
i.	CCG / Access Token
