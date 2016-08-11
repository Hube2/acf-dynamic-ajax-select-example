# ACf Dynamic AJAX Select Example

***This example only works in ACF5***

This repo is an example of how to dynamically load an ACF select field based on the selection made in
another ACF select field. This example can be used as the basis of understanding how to extend ACF to
use AJAX for the creation of dyanmic fields. Every field type in ACF will need slightly different code
to get this to work. This is presented in the hope that it will help others to better understand how
to extend the functionality of ACF.

See the comments in the files for comments about the code and what it is doing. Please note that the
PHP in this example uses OOP rather than simple functions.

In this example I have set up two custom post types. The first custom post type is named 'State' and it
is used to populate a list of US States. The second custom post type is named 'City' and it is used to
populate a list of cities that are located in each state. The City post type includes a select
field to select a stated from the list of states.

The file cpt-ui-export.txt is an export from the plugin 
[Custom Post Type UI](https://github.com/WebDevStudios/custom-post-type-ui) which is what I generally 
use to set up custom post types. It can be used if you want to test out this example.

The JSON files are exports from ACF of the three field groups I used for this excercise. You can import these
if you want to test this example.

If you want to test this you'll need to add some states and cities yourself

The goal is then to add two select fields to the "Post" post type. A user will select a state in the state
select field and this will cause the cities in that state to by dynamically populated with its related cities.

For the rest of the explanation see the comments in the PHP and JS files
