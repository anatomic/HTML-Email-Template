# HTML Email Template

## Usage

To use the HTML Email template, grab this repository, `git clone git://github.com/anatomic/HTML-Email-Template.git` then re-initialise git by first deleting the `.git` folder (`rm -R -f .git`) and then re-initialising git using `git init`.  You can add remotes etc. like normal.

The template contains base files for html and plain text versions of an email, debug stylesheet and an image folder ready to go.

## Images

While developing an email all image should be stored in the `img/` folder, this allows the debug css to check whether or not you are running your images locally.  Eventually you'll need to setup some hosting for your images so once you've finalised the images/structure for an email I'd suggest you put them on a server and work with them remotely.