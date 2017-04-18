# ACF Unique Repeater True/False Field

***This examples only works in ACF5***

This example shows how to create a true/false field in a repeater that will only allow the field to be
checked in one row of the repeater. It also prevents the currently checked item from being unchecked.
This basically turns the true/false field in the repeater into a multirow radio field.

To use this example create a repeater that includes a true/false field and then use the field key
to modify the JavaScript file in this example.

Please note that this does not force one of the true/false fields in one of rows to be checked until 
one of them has actually been checked. When I use this if no rows are check then I add code to my PHP
to assume that the field in the first row is checked.

***Note: I do not know if this will work with the UI Toggle added to the True/False field in version 5.5.0 of ACF. I have not tested it. My guess is that it will not.**
