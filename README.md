FoundationCMS
==============
__FoundationCMS__ is our attempt at creating a user-friendly Content Management System.

## Zurb Foundation framework
The main focus of this CMS is the use of the [Zurb Foundation framework](http://foundation.zurb.com).
The [Zurb Foundation framework](http://foundation.zurb.com) is an advanced responsive front-end framework. It enables fast front-end development, is widely supported and has an active community.

## Widgets
The building blocks of __FoundationCMS__ are called Widgets (Yeah...not very creative). These widgets are small blocks of front-end code which can be inserted in a webpage.
Literally these are just blocks of HTML code with some specific CSS classes so it can be edited and repositioned.
#### Back-end code
At the back-end, the code of a widget encompasses a little bit more. Widgets are all placed in a certain folder on the webserver. This is a well-known location and all widgets placed in this folder will be automagically loaded. This makes it very easy to add new widgets.
A widget consists of the following parts:
* The normal view/controller - this is shown on the page
* The edit view/controller - this is shown in the Edit modal dialog.

#### Versioning
On every first edit of the day, the placement of the widgets are archived. This makes it possible to revert to a previous version of the website. It is also possible to create a snapshot manually.

Do note that this is only a snapshot of the widgets and its placement on the website. The content is not archived.
What this means in practice is that reverting to a previous version of the website will result in the same *layout* but with the latest content. However, the latest content could affect how the website looks in that version because the field __classes__ is also stored as part of the content.

## Content
___FoundationCMS___ uses one type of content for all of its widgets. The so called __content item__ is dynamic. The number and type of fields which a content item has is determined by the widget it is related to. This might sound a bit confusing at first, but I'll do my best to explain this the best I can to show how logical it actually is.

Because a widget in itself is nothing more than just a *window* to the content. The widget itself determines what parts of the content it wants to display and how it should be displayed.

For instance, we take a content item consisting of the fields: __title__, __leadtext__, __content__ and __classes__. The __FoundationPanel__ widget will display all the fields and incorporate the __classes__ into the ```class=""``` attribute of the encapsulating HTML element.

But when we display that very same content in the __FoundationHeading__ widget it will only show the __title__ field and incorporate the __classes__ into the ```class=""``` attribute of the encapsulating HTML element. The rest of the fields are unused.

#### Editing content
__Content items__ can only be added/modified/hidden through a widget. Clicking on a particular widget in __edit mode__ will bring up a dialog which allows you to edit its content. Only the fields supported by the widget are shown and editable.
The user should not need to concern about what other unused fields exist for that __content item__ in this particular context.

#### Exceptions
There are some exceptions where content are actually bound to the widget. For instance the photos uploaded using the __FoundationPhotos__ widget are bound to that specific widget. The photos can't be transferred. Deleting the widget will also delete the photos.

## Technologies
__FoundationCMS__ uses the following technologies:

| Technology                                                    | Description                         | Included in FoundationCMS |
| ------------------------------------------------------------- | ----------------------------------- |:-------------------------:|
| [Zurb Foundation framework](http://foundation.zurb.com)       | Responsive framework                |Yes                        |
| [GruntJS](http://gruntjs.com)                                 | Grunt task manager                  |No                         |
| [Compass](http://compass-style.org)                           | CSS authoring framework (scss/sass) |No                         |
| [RequireJS](http://requirejs.org)                             | JavaScript file and module loader   |Yes                        |
| [jQuery](http://jquery.com)                                   | jQuery                              |Yes                        |
| [Slick](http://kenwheeler.github.io/slick)                    | Carousel                            |Yes                        |
| [Dice](http://r.je/dice.html)                                 | PHP Dependency Injection Container  |Yes                        |
| [PHP](http://www.php.net)                                     | PHP Hypertext preprocessor          |No                         |
| [MySQL](http://www.mysql.com)                                 | MySQL Database                      |No                         |
*Technologies not included needs to be installed separately. Check their respective websites for installation instructions.*

## Distribution
In order to distribute __FoundationCMS__ you need to compile it first. This is easily done by running `grunt` in the root of the project folder on the command-line.
```
grunt
```
The distributable code will be placed in the `dist` folder. There you can find the cleaned-up, optimized and compiled __FoundationCMS__.
When installing __FoundationCMS__ to a webserver running PHP you can use the code in the `dist` folder.
