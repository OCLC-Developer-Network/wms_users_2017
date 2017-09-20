# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 3 - Version Control

1. We're going to use git to control versioning of our code, and host our code repositories on GitHub.
2. Fortunately, GitHub has provided detail directions on initializing a git repository and uploading the code to GitHub. Please follow the following directions:
	* [Mac](https://help.github.com/articles/adding-an-existing-project-to-github-using-the-command-line/#platform-mac)
	* [Windows](https://help.github.com/articles/adding-an-existing-project-to-github-using-the-command-line/#platform-windows)
	* [Linux](https://help.github.com/articles/adding-an-existing-project-to-github-using-the-command-line/#platform-linux)


1. We're going to use git to control versioning of our code, and host our code repositories on GitHub. First, create an empty repository on [github.com](https://github.com/) for your code.
	1. Click "New repository"
	2. Title your repository "wms_users_2017"
	3. Check the box next to "Initialize this repository with a README
	4. Click "Create repository"
2. In a terminal window, change into the following directory:
```bash
$ cd /your-bitnami-installation-directory/apache2/htdocs
```
3. Enter this command to clone the repository you just created:
```bash
$ git clone https://github.com/your-github-username/wms_users_2017.git
```
substituting your GitHub username for your-github-username.
4. You should now have a subdirectory called `wms_users_2017` with an empty README.md file in it.
```bash
$ cd wms_users_2017/
$ ls
README.md
```
5. Next, we're going to run a quick test to make sure you can make updates to your repository.
	1. Open README.md in your text editor.
	2. Make a change - any change - and save it.
	3. Back in your terminal window, run this command:
	```bash
	$ git status
	```
	You should see output like this:
	```bash
	swayh-1mbadu:wms-test-1 swayh$ git status
	On branch master
	Your branch is up-to-date with 'origin/master'.
	Changes not staged for commit:
	  (use "git add <file>..." to update what will be committed)
	  (use "git checkout -- <file>..." to discard changes in working directory)
		modified:   README.md
	no changes added to commit (use "git add" and/or "git commit -a")
	```
	4. Now, stage the changes to commit to your repository:
	```bash
	$ git add --all
	```
	5. Commit the changes:
	```bash
	$ git commit -m "a message about your commit"
	```
	6. Push the changes to your repository:
	```bash
	$ git push origin master
	```
	7. In your browser, head back to your GitHub repository at https://github.com/your-github-username/wms_users_2017, and you should see the changes you just made to your README file.

**Once you've confirmed that you can push to the GitHub repository you just created, move on to [Tutorial Part 4](tutorial-04.md).**