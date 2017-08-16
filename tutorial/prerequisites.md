# OCLC DEVCONNECT 2017 Demonstration Application
## Prerequisites

1. Create a [GitHub account](https://github.com/).
2. Create a [WorldCat.org account](https://www.oclc.org/en/user/create-account.html).
3. Send us your WorldCat.org account username via email. We will use this information to create a WSKey for you to use in the workshop.
	* To: devnet@oclc.org
	* Subject: WMS API Workshop WSKey
	* Message: My WorldCat.org username is *insert-your-username-here*. Please create a WSKey for me to use in the workshop.
4. Install web server (whichever we decide) / PHP
    1. Install Bitnami with PHP 7 (you will need a laptop on which you have installation privileges)
		1. Bitnami Console in Windows to do command line things
	2. Enable xdebug
		1. Uncomment xdebug in php.ini
	3. Ensure Bitnami version of PHP is in your path
		1. Edit your bash_profile on the Mac
		2. How does this work on Windows??