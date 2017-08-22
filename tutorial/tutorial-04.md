# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 4 - Dependency Management

1. We'll be using a tool called [Composer](https://getcomposer.org/) to manage our project's dependencies. By "dependencies" we mean the code libraries and other external resources that our project requires in order to run. This includes the OCLC PHP Authentication Library, the MVC framework Slim, the MARC record parser we'll need, and so on.
2. In your project file, create a file called `composer.json`.
	1. `$ touch composer.json`
3. Open `composer.json` in your text editor.
4. Copy and paste [this text](https://raw.githubusercontent.com/OCLC-Developer-Network/wms_users_2017/master/composer.json) into the file.
5. Save the file.
6. In your terminal window, enter this command to download the Composer installer:
	1. `$ curl -s https://getcomposer.org/installer | php`
7. Enter this command to install these dependencies:
	1. `$ php composer.phar install`
8. Now, we want to commit our `composer.json` file to our GitHub repository, but there is some prep we must do first. You'll notice that the command in the previous step created a `/vendor/` directory in your project that contains all of the external resources you installed. We do *not* want to put these files in version control.
	1. To tell git to ignore the `/vendor/` directory, create a `.gitignore` file:
		1. `$ touch .gitignore`
	2. Open `.gitignore` in your text editor.
	3. Copy and paste [this text](https://github.com/OCLC-Developer-Network/wms_users_2017/blob/master/.gitignore) into the file.
		1. (This includes a few resources other than `/vendor/`, but we'll get to those later. :wink:)
9. We're now ready to commit our changes to GitHub. To view local changes not yet commited, enter this command:
	1. `$ git status`
	2. This command should output text telling you that your composer.json and .gitignore files are not yet committed.
10. Add these files to the repository:
	1. `$ git add --all`
11. Commit your changes:
	1. `$ git commit -m "added composer.json and .gitignore"`
	2. In between the quotes, you can enter whatever description of the changes you would like.
12. Push your changes to your remote repository:
	1. `$ git push origin master`


**Once you've confirmed that your changes were successfully committed and pushed, move on to [Tutorial Part 5](tutorial-05.md).**