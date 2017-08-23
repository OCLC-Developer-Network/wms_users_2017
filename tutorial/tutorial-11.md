# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 11
Views can be use content to be dynamically generated. Data is passed into the view via an associative array.
The key of the array is the name of the variable in the view.

Example:
```php
$this->view->render($response, 'bib.html', [
                'bib' => $bib
        ]);
```
#### Make the Bib View Dynamic

1. Open bib.html
2. Use bib variable passed from route and Bib object methods to fill in fields in bib.html
- bib.getTitle()
- bib.getRecord()

```php
{% extends "layout.html" %}

{% block title %}{{bib.getTitle()}}{% endblock %}
{% block content %}
<h1>{{bib.getTitle()}}</h1>
<div id="record">
<h4>Raw MARC</h4>
<div id="raw_record">
<pre>
{{bib.getRecord()}}
</pre>
</div>
</div>
{% endblock %}
```
