# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 1

1. What we're going to build.
	* We're going to build a simple web application that allows you to search WorldCat bibliographic records by OCLC number and display selected metadata from the record.
2. In order to build this app, we're going to deploy the following tools and best practices:
	1. Model-view-controller architecture (specifically the [Slim micro framework](https://www.slimframework.com/) in PHP)
	2. The [WorldCat Metadata API](https://www.oclc.org/developer/develop/web-services/worldcat-metadata-api.en.html)
	3. OCLC's [PHP Authentication Library](https://github.com/OCLC-Developer-Network/oclc-auth-php) to support web service authentication
	4. Dependency management via [Composer](https://getcomposer.org/)
	5. An external [PHP library for parsing MARC records](http://pear.php.net/package/File_MARC/redirected)
	6. Behavior-driven testing via [Behat](http://behat.org/en/latest/)
	7. Continuous integration testing via [Travis CI](https://travis-ci.org/)

**If this piques your interest, read on for more!***