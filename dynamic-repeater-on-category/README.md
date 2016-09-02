#Dynamic Repeater Base on Term

***This example only works in ACF5***

In this example we will create a repeater field on a Post that is dynamically populated based on
the selection of a term in a taxonomy. The content of the repeater will be taken from a repeater
that attached to the term.

The repeater on the term allows you to add a list of "Features". On the post these "Features" are
dynamically added and require adding a "Value" for each "Feature"

See the comments in the file "extras.php" for more information on how the post type and taxonomy
are set up. Please make sure you read the important note in this file about removing the standard
WP taxonomy meta box when registering a taxonomy.

See comments in other files to see how it works.

Please not, like all my examples in this repo, I use generic names for all files, functions and classes.
These files, functions and classes should be renamed so that they do not conflict with other code.
There are specific things that must be edited to match your use, for example, field keys and such.

If you want to try out this example you will need to create some terms in the taxonomy and populate
the "Features" repeater with some content.

This example has a lot of things in it that people might find useful.
* How to dynamically delete all the rows of a repeater
* How to dynamically add rows to a repeater
* How to dynamically populate the sub fields on each row of a repeater
* and of course, how to do an AJAX request based on a taxonomy field