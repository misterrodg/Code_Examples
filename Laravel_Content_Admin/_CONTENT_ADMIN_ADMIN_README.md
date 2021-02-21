This snippet is an example of streamlining code by splitting code out
to avoid code duplication, while allowing individual pages a level of
customizability.

Example was written for a content management system in Laravel 5.4.

- /listing is the page that admins arrive on to see existing content,
with a link to add

- /create is the page that admins arrive on to add new content

- /edit is the page that admins arrive on to edit existing content

- /form is the form and related JS, which adjusts to fit the context
of the containing page

- /content_model is the Laravel model used as a database intermediary

- /ContentController is the controller used to assemble blade/page data
