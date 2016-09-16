#Dynamic Field on Relationship

***This example only works in ACF5***

In this example we are going to populate some fields based on a selection made in a relationship field. This is
something that has come up several times on the ACF Support Forum. What people want to do is pupulate fields, like
title, except and maybe an image from the related post and allow the editor to change these values for the
relationship.

Please note, that this example will only deal with a single relationship field that allows only a single choice for
that field. It will not work on something like a repeater sub field to populate other values on the same row or
anything more complicated.

## Setup

In order to set up this example you will need to:
1. Create a relationship field
2. Create a title "text" field to be populated
3. Create an excerpt "textarea" field to be populated
4. Create and image field to be populated
5. Copy the field keys from the fields you've created into the JavaScript code example

(If you'd like to test this with what I set up I have included the JSON field group in the files)

## Code Comments
Look at the files and comments in them for information on what's going on and why.

Please note, like all my examples in this repo, I use generic names for all files, functions and classes.
These files, functions and classes should be renamed so that they do not conflict with other code. This is meant
as an example only and is not meant to just be copied and pasted into your functions.php file except for the
purpose of seeing this exact example in action.

Some of the things that this example does that might be usefull in other projects include
* How to get the selected value of a relationship field
* How to do an AJAX request using ACF
* How to populate other fields based on the results of an AJAX request
* How to populate and show an ACF image field
