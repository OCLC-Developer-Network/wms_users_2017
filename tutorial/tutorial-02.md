# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 2

1. Since we're using Bitnami as our local development stack, we need to create a directory for our project files in a place where Bitnami's web server can find them.
	1. In a terminal window, change into the directory where Bitnami will look for your project files.
		1. '''cd /your-bitnami-installation-directory/apache2/htdocs'''
			1. On a Mac, this directory will likely be '''/Applications/mampstack-7.1.7-0/apache2/htdocs/wms_users_2017'''
	2. Create a directory for your project files.
		1. '''mkdir wms_users_2017'''