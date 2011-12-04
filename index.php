<!DOCTYPE html>
<html class="">
	<head>
		<meta charset="utf-8">
		<!-- The title should be the same as the email subject  -->
		<!-- Don't set it here as we do some magic in the build to replace it -->
		<title></title>
		<link rel="stylesheet" href="/css/debug.css">
		<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
  </head>
  <!-- 
    Notes on how to use this template:
    **********************************
    You don't need to have tracking on any links, this is sorted out in the config/settings.ini file and the command line tool
    
    Add comments liberally as they are all removed at build time anyway, don't forget to remove any LiveReload JS and the link to the debug stylesheet

    This file should be used to build the email locally as the base.html file is for export use only.  Test out your building blocks by including them as .html files in whatever order you like, but remember to set up the correct order in the settings.ini file for use with the build script.

    Any changes that are made outside the Email Body will need to be reflected in base.html, otherwise you're email may look a bit ropey!
   -->
	<body style="padding:0; margin:0;" class="">
		<!-- ↓ Full Width Wrapper ↓ -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="center">
					<!-- ↓ Centre aligned email ↓ -->
					<table cellpadding="0" cellspacing="0" border="0" width="600">
            <tr>
							<td align="left" style="font-family">
								<!-- ↓ Email Header ↓ -->
                <a href=""><img src="http://placehold.it/600x200&text=Image" alt="Image" style="display:block;"></a>
								<!-- ↥ END Email Header ↥ -->
							</td>
						</tr>
						<tr>
							<td width="600" height="200">
								<!-- ↓ Email Body ↓ -->
                
                <?php include("lib/header.html"); ?>

                <?php include("lib/promo.html"); ?>

								<!-- ↥ END Email Body ↥ -->
							</td>
						</tr>
					</table><!-- ↥ END Centre aligned email ↥ -->
				</td>
			</tr>
		</table><!-- ↥ END Full Width Wrapper ↥ -->
	</body>
</html>
