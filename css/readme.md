# The really useful email debugging css

This is a  small set of CSS styles to help debug html email production.

It checks for things such as missing alt tags, local images, lack of image attributes and more.

## How do I use it?

To use, simply link to this stylesheet and add class="debug" to the html tag. You can then setup any number of debug tests by adding the relevant class to the body tag.

Some of the checks dim images, others put a nice coloured border. If your code is broken, you'll get a red border and full opacity!

For example:
`<html class="debug">
  <head></head>
  <body class="local-images">
  </body>
</html>`

You can tell if you are in debug mode because the body will go orange or red, depending on whether you are currently testing (red) or not (orange).

## What can I check for?

* .block-images - Are your images setup with display:block
* .local-images - If they're not on a server, they're not any use
* .alt-text - Sometimes you need to write something, sometimes you don't, you always need the attribute though
* .real-links - If there isn't a "http://" then it's going to break
* .padding - Don't use this
* .margin - Or this
* .spacers - This will find if you've set either margin or padding
* .paragraphs - Potentially disasterous, avoid where possible and use <td> elements
* .query-string - Because we're building these emails using the command line tool, we don't want query strings on the links
* .text-align - You think it's on the left but Outlook doesn't (only applies for centre aligned emails)
* .cell-size - More of a helper than a rule, if you have an image in a cell you should set height and width, equally if you have text you might want to.  If you've got neither, you definitely should do.
* .image-links - Images inside anchors are ok.  Images inside anchors without border=0 are not.
* .table-atts - Make sure all your tables have the right attributes, namely cellpadding, cellspacing and border.  (I may add width to this list later)

## Why have you made this?

If you've ever coded up a 2000px+ high email with various levels of table recursion you are going to know why. Something breaks the build and you've got one hell of a task to find it!

Granted, this only checks for some basic things - it won't tell you if you have duplicated attributes, you've not closed a tag or a tag is incorrectly nested - but it goes some way to highlighting the issues you'll come across if you don't follow html email build best practise.

## What typical things should I do while building?

Here's  my checklist - it's not exhaustive but it helps:

* Don't space text using margin, padding or `<p>` tags
* Always make sure that you have set `align="left"` on your `<td>` if you want normal paragraph justification. Certain flavours of Outlook will pass centre alignment down the tree if you don't (and you have a centre aligned email)
* Links must always start with `http://` for Email Vision to recognise them (I assume this goes for other auto link finding software)
* Don't insert links with query strings - use a tool to generate the correct tracking parameters after you've built the email
* All images need an `alt` attribute, even if it is just empty
* Equally, set all images to `display:block`, essential if you are using them for layout (N.B. For the debug style sheet to work you must have `display:block`as the first declaration in your style sheet)
* Images have to be on a server accessible by the world. If they aren't, you're going to look very silly
* If things need to line up horizontally, you need to put them in different rows.  Don't rely on `<br/>` to do it, if the template is updated by users it *will* go wrong
* Make sure you have `border=0` on all images that are used in links
* This one is a hard one to test as Chrome automagically inserts a surrounding `<tr>` but if you find yourself with a `<table><td>` situation you'll be in trouble in Outlook land!