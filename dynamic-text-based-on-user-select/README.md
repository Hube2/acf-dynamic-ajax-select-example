# ACf Text Fields based on User field

***This example only workds in ACF5***

This repo is an example of how to dynamically load some ACF text fields based on the selection made in
another user type field. This is another example can be used as the basis of understanding how to 
extend ACF to use AJAX for the creation of dyanmic fields. Every field type in ACF will need slightly 
different code to get this to work. This is presented in the hope that it will help others to better 
understand how to extend the functionality of ACF.

Please note that this will only work with a user field that allows a single selection.

See the comments in the files for comments about the code and what it is doing. Please note that the
PHP in this example uses OOP rather than simple functions.

In this example I have set up a field group that includes a User Type field. The field group is attached
to the "Post" post type. The JSON file is an export of this field group from ACF. You can import this
if you want to test this example. You'll need to populate some users on your site to see it in action.

For the rest of the explanation see the comments in the PHP and JS files
