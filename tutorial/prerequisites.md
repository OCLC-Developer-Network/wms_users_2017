# OCLC DEVCONNECT 2017 Demonstration Application
## Prerequisites

Please note that you will need a laptop on which you have installation privileges in order to complete this workshop.

1. Create a [GitHub account](https://github.com/).
2. Create a [WorldCat.org account](https://www.oclc.org/en/user/create-account.html).
3. Send us your WorldCat.org account username via email. We will use this information to create a WSKey for you to use in the workshop.
	* To: devnet@oclc.org
	* Subject: WMS API Workshop WSKey
	* Message: My WorldCat.org username is *insert-your-username-here*. Please create a WSKey for me to use in the workshop.
4. Install the appropriate version of [Git](https://git-scm.com/downloads) for your operating system.
5. Install the appropriate Bitnami stack for your operating system.
	* [Mac](https://bitnami.com/stack/mamp)
	* [Windows](https://bitnami.com/stack/wamp)
	* [Linux](https://bitnami.com/stack/lamp)
6. Use a text editor to uncomment xdebug in /installation_directory/php/etc/php.ini.
	* https://community.bitnami.com/t/where-is-php-ini/409



4. Install web server (whichever we decide) / PHP
    1. Install Bitnami with PHP 7 (you will need a laptop on which you have installation privileges)
		1. Bitnami Console in Windows to do command line things
	2. Enable xdebug
		1. Uncomment xdebug in php.ini
	3. Ensure Bitnami version of PHP is in your path
		1. Edit your bash_profile on the Mac
		2. How does this work on Windows??