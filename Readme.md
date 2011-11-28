# HTML Email Template

## Usage

To use the HTML Email template, grab this repository, `git clone git://github.com/anatomic/HTML-Email-Template.git` then re-initialise git by first deleting the `.git` folder (`rm -R -f .git`) and then re-initialising git using `git init`.  You can add remotes etc. like normal.

The template contains base files for html and plain text versions of an email, debug stylesheet and an image folder ready to go.

## Command Line Tool Features

The command line tool features some basic checks and replacements to help reduce the pain and frustration involved with getting and email ready to send.  So far it can find all the links in html and plain text files, add tracking codes (including a unique CRE value to show the position in the email), add the subject line into the `<title></title>` (this helps with reducing spam scores) and strips out html comments.  It's recommended that while you're devving an email you liberally include comments to help navigate the inception like web of tables that are required to get a rock solid layout, however, these comments are just dead weight when sending so it's best to get rid.

## Building for Email Vision

So far this tool is primarily used for building output for Email Vision's Campaign Commander software and can output a combined text file ready to copy and paste into the Campaign Commander message window.  Extensions are welcome to output files for other email marketing solutions, I'll certainly be looking into a way to produce separate html and plain text files that are ready to send.

## Images

While developing an email all image should be stored in the `img/` folder, this allows the debug css to check whether or not you are running your images locally.  Eventually you'll need to setup some hosting for your images so once you've finalised the images/structure for an email I'd suggest you put them on a server and work with them remotely.

## Debug CSS

This is still a huge work in progress, i.e. there's nothing in it!  However, this will be built up over the course of the next few projects I work on and will feature checks for such things as missing alt tags, local images, links missing the `http://`, etc.