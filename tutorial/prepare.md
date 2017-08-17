# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Preparing for the Workshop

Please note that you will need a laptop on which you have administrator privileges in order to complete this workshop.

1. Create a [GitHub account](https://github.com/).
2. Create a [WorldCat.org account](https://www.oclc.org/en/user/create-account.html).
3. Send us your WorldCat.org account username via email. We will use this information to create a WSKey for you to use in the workshop.
	* To: [devnet@oclc.org](mailto:devnet@oclc.org)
	* Subject: WMS API Workshop WSKey
	* Message: My WorldCat.org username is *insert-your-username-here*. Please create a WSKey for me to use in the workshop.
4. Install the appropriate version of [Git](https://git-scm.com/downloads) for your operating system.
5. Install the appropriate Bitnami stack for your operating system. Select the **latest version available**.
	* [Mac](https://bitnami.com/stack/mamp)
	* [Windows](https://bitnami.com/stack/wamp)
	* [Linux](https://bitnami.com/stack/lamp)
6. Enable XDebug for your Bitnami PHP installation.
	1. Open php.ini (in the /installation_directory/php/etc directory) in a text editor.
	2. Uncomment the following lines by removing the leading semicolon:
	```
	;zend_extension="/Applications/mampstack-7.1.7-0/php/lib/php/extensions/xdebug.so"
	;xdebug.remote_enable=true
	;xdebug.remote_host=127.0.0.1
	;xdebug.remote_port=9000
	;xdebug.remote_handler=dbgp
	;xdebug.profiler_enable=1
	;xdebug.profiler_output_dir=/tmp
	```
	3. Save the file.
7. Add the Bitnami PHP directory (/installation_directory/php/bin) to your PATH system variable.
	1. [Mac](https://stackoverflow.com/questions/30461201/how-do-i-edit-path-bash-profile-on-osx)
	2. [Windows](https://www.howtogeek.com/118594/how-to-edit-your-system-path-for-easy-command-line-access/)
	3. Linux (you probably already know what you're doing :wink:)
8. Let us know if you have any questions at [devnet@oclc.org](mailto:devnet@oclc.org). We're happy to help you get set up.