# Project: moodle_statistic
Statistics about course, modules and clustered course-modules. Get an impress of your moodle usage.

## Course structure
To use the tool without any changes you must have a course structure like this:


><pre>
- current-semestername
-- faculty 1
-- faculty 2
-- ...
-- further institutions / no faculties (with idnumer: further[...])
--- department 1
--- department 2
--- ...
- archiv / old semester
-- semester a
--- faculty 1
--- faculty 2
--- faculty 3
--- ...
--- further institutions / no faculties (with idnumer: further[...])
---- department 1
---- department 2
---- ...
-- semester b
--- faculty 1
--- faculty 2
--- faculty 3
--- ...
--- further institutions / no faculties (with idnumer: further[...])
---- department 1
---- department 2
---- ...
-- semester c
--- faculty 1
--- faculty 2
--- faculty 3
--- ...
--- further institutions / no faculties (with idnumer: further[...])
---- department 1
---- department 2
---- ...
-- semester d
--- faculty 1
--- faculty 2
--- faculty 3
--- ...
--- further institutions / no faculties (with idnumer: further[...])
---- department 1
---- department 2
---- ...
</pre>
## Use it
You need admin rights to get a view on the tool, or you use it outside of moodle without any security checks

## Installation
Way 1: Copy or symlink the moodle_statistic folder in your root of moodle to handle the access with moodle.

Way 2: Copy / check out the project into any directory and use the tool without any security checks
<br/>

**ANY changes?**

EDIT the config.php in folder 'system'