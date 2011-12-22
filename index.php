<?php
  $language = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'uk';
?>

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
-->
<body style="padding:0; margin:0;" class="">
<!-- ↓ Full Width Wrapper ↓ -->
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#e3e3e3">
  <tr>
    <td align="center">
      <table cellspacing="0" cellpadding="0" border="0" width="600">
        <tr>
          <td height="30" style="font-style:italic; font-family:Georgia, serif; font-size:12px; color:#999;"
              align="right" valign="middle">
            Can't see this mail? <a href="&&&" style="font-style:normal;color:#666;">View it online</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <!-- ↓ Centre aligned email ↓ -->
      <table cellpadding="0" cellspacing="0" border="0" width="600">

        <?php include("lib/" . $language . "/header.html"); ?>

        <?php include("lib/" . $language . "/promo.html"); ?>

        <?php include("lib/" . $language . "/scarlet.html"); ?>

        <?php include("lib/" . $language . "/spacer.html"); ?>

        <?php include("lib/" . $language . "/video.html"); ?>

        <?php include("lib/" . $language . "/spacer.html"); ?>

        <?php if($language != 'us'): ?>

        <?php include("lib/" . $language . "/gifts.html"); ?>

        <?php include("lib/" . $language . "/spacer.html"); ?>

        <?php endif; ?>

        <?php include("lib/" . $language . "/offers.html"); ?>

        <?php include("lib/" . $language . "/spacer.html"); ?>

        <?php include("lib/" . $language . "/footer.html"); ?>

        <?php include("lib/" . $language . "/spacer.html"); ?>
      </table>
      <!-- ↥ END Centre aligned email ↥ -->
    </td>
  </tr>
</table>
<!-- ↥ END Full Width Wrapper ↥ -->
</body>
</html>