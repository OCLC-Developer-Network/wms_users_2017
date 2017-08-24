# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 9 - Views
Views govern how content will be displayed on the screen. 
One view can be extended by another view allowing the layout to be controlled by a single file
Data can be passed into a view for display purposes. More on this later.
#### Create a basic layout for your views
1. In the app directory create a views directory
2. In app/views create layout.html
This is a basic HTML page with defined blocks of content such as:
- block head
- block title
- block content
- block footer
```php
<!DOCTYPE html>
<html>
    <head>
        {% block head %}
            <link rel="stylesheet" href="style.css" />
            <title>{% block title %}{% endblock %}</title>
        {% endblock %}
    </head>
    <body>
        <div id="content">{% block content %}{% endblock %}</div>
        <div id="footer">
            {% block footer %}
                
            {% endblock %}
        </div>
    </body>
</html>
```
#### Create search form view
1. In app/views create file search_form.html
2. Define which view is being extended
3. Override approriate blocks
- block title
- block content
```php
{% extends "layout.html" %}

{% block title %}Find OCLC Number{% endblock %}
{% block content %}
<h1>Search by OCLC Number</h1>
<form name="search" action="{{ path_for('display_bib') }}" method="GET">
<input type="text" name="oclcnumber" />
<input type="submit" name="search" value="Search"/>
</form>
{% endblock %}
```
#### Create base bib view
1. In app/views create file bib.html
2. Define which view is being extended
3. Override approriate blocks
- block title
- block content
```php
{% extends "layout.html" %}

{% block title %}Title{% endblock %}
{% block content %}
<h1>Title</h1>
<div id="record">
<h4>Raw MARC</h4>
<div id="raw_record">
<pre>
The record
</pre>
</div>
</div>
{% endblock %}
```