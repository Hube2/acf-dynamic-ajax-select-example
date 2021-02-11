# ACf Dynamic AJAX Field Examples

## Important Note
ACF In constantly changing. It is possible that any of the examples here may stop working with an update to ACF. I do not actively maintian these examples or continuously monitor them to make sure they are working. This repository was created to hold examples of some things that may help other. If you find that something is not working as expected you can open an issue, however, unless I have the free time the chances of me fixing the issue are small. This is why I will gladly take pull request for fixing problems with compatibility.

***MANY OF THESE EXAMPLES CURRENTLY DO NOT WORK WITH ACF >= 5.7.0 AND NEED TO BE REWORKED FOR THIS VERION. THE ONES THAT HAVE BEEN REWORKED HAVE NOTES ABOUT IT. THERE WAS A SIGNIFICANT CHANGE IN ACFs JS API IN VERSION 5.7.0***

***These examples only works in ACF5***

This repo contains several examples of using AJAX to dynamically load fields based on the values
in other fields. See the individual example folders for more information.

There are also some other examples here that are more complicated than what can be found in my
[ACF Filters & Functions Repo](https://github.com/Hube2/acf-filters-and-functions) becuase each
of these examples requires code in several files.

The following is a brief explanation of each example

**dynamic-fields-on-relationship:** This example shows how to load additional fields from a post selected
in a relationship field.

**dynamic-repeater-on-category:** This example is a bit more complicated than the ones below. It will load a
repeater field located on a post with values from a repeater field of the chosen post category.

**dynamic-select-example:** *updated JS to work with ACF >= 5.7* Load values into a select field based on a choice made in another select field

**dynamic-text-based-on-user-select:** Loads text fields based on the selection made for a user field

**repeater-ajax-load-more:** This is an example of how to create a "Load More" feature for a repeater field.

**unique-repeater-checkbox:** *updated JS to work with ACF >= 5.7*
This example shows how to create a true/false field in a repeater that will only 
allow the field to be checked in one row of the repeater. A multi-row radio field.

