# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 4 - MVC Project Organization
	
#### Model View Controller
1. Seperate Code by what it does
    - Data handling
    - Business logic
    - Display
2. Makes customization easier

#### Structure of MVC
1. App directory
    - we've created `app` directory already
    - holds our MVC components
2. Model directory
    - we've created `app/model` already
    - holds our model files
3. Views directory
    - `app/views`
    - holds our view files
4. Config directory
    - `app/config`
    - holds our configuration files
5. Controller directory (we're not using this. The APIs we're using and our routes.php file will perform these tasks.)

#### Folders for Testing
1. Tests directory
    - we've created this already
    - holds our unit tests
2. Feature directory
    - We'll create this later
    - holds our automated behavior-driven acceptance tests

**[on to Part 5](tutorial-05.md)**

**[back to Part 3](tutorial-03.md)**