# The really useful email debugging css

This is a  small set of CSS styles to help debug html email production.

It checks for things such as missing alt tags, local images, lack of image attributes and more.

## USAGE

To use, simply link to this stylesheet and add class="debug" to the html tag. You can then setup any number of debug tests by adding the relevant class to the body tag.

Some of the checks dim images, others put a nice coloured border. If your code is broken, you'll get a red border and full opacity!

E.g.
<pre>
<html class="debug">
	<head></head>
	<body class="local-images">
	</body>
</html>
</pre>

## WHAT CAN I CHECK FOR?

* .block-images - Are your images setup with display:block
* .local-images - If they're not on a server, they're not any use
* .alt-text - Sometimes you need to write something, sometimes you don't, you always need the attribute though
* .real-links - If there isn't a "http://" then it's going to break