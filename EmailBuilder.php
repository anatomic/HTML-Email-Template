#!/usr/bin/php
<?php

$builder = new EmailBuilder();
$builder->build();
exit(0);

/**
 * Created by IntelliJ IDEA.
 * User: Ian
 * Date: 22/12/2011
 * Time: 15:37
 */
class EmailBuilder
{
  const HTML_SHELL = 'base.html';
  const TEXT_SHELL = 'plain.txt';
  const LIBS_DIR = "lib";

  const HTML_PATTERN = '/href="http:\/\/(?P<url>[\w\d\.\/-]+)"/';
  const TEXT_PATTERN = '/http:\/\/(?P<url>[\w\d\.\/-]+)/';
  const COMMENTS_PATTERN = '/<!--[\w\d\s .↓↥\*\'"\/@\.:\n\,]+-->/';

  protected static $settings = array();

  public function __construct()
  {
    if (!is_readable('config/settings.ini')) {
      fwrite(STDOUT, "Settings file not readable.  Make sure you set it up in config/settings.ini\n");
      exit(1);
    }

    $newSettings = parse_ini_file('config/settings.ini', true);

    foreach ($newSettings as $group => $settings) {
      if (isset(self::$settings[$group])) {
        self::$settings[$group] = array_merge(self::$settings[$group], $settings);
      }
      else {
        self::$settings[$group] = $settings;
      }
    }
  }

  public function displayAvailableLanguages()
  {
    fwrite(STDOUT, "Available languages:\n");
    foreach (self::$settings['email']['language'] as $key => $language) {
      fwrite(STDOUT, $key + 1 . ") " . $language . "\n");
    }
  }

  public function displayAvailableIds()
  {
    fwrite(STDOUT, "Available IDs\n");
    foreach (self::$settings['email']['id'] as $key => $language) {
      fwrite(STDOUT, $key + 1 . ") " . $language . "\n");
    }
  }

  /**
   * To build an email we first need to get some options sorted out.
   * 1) Ask what ID it is to be built under
   * 2) Ask which language is being built
   * 3) See what tracking tags need to be added to links
   * 4) Build each variant of the email
   * 5) Add tracking to the links
   * 6) See if another email is to be built
   */
  public function build()
  {
    fwrite(STDOUT, "----------\n");
    $this->displayAvailableIds();
    fwrite(STDOUT, "Please choose an ID to build: ");
    fscanf(STDIN, "%d\n", $idIndex);
    $idIndex--;

    $this->displayAvailableLanguages();
    fwrite(STDOUT, "Please choose a language to build:");
    $langIndex = trim(fgets(STDIN)) - 1;

    $numTemplates = count(self::$settings['templates']['templateIds']);
    $htmlTags = self::$settings['tracking']['html'];
    $textTags = self::$settings['tracking']['text'];
    $id = self::$settings['email']['id'][$idIndex];
    $lang = self::$settings['email']['language'][$langIndex];
    $htmlCounter = 1;
    $textCounter = 1;

    fwrite(STDOUT, "You have chosen to build an email with ID: " . $id . " for the following language " . $lang . "\n");

    fwrite(STDOUT, "----------\n\n");

    foreach (self::$settings['templates']['templateId'] as $templateId) {

      $content = $this->buildEmailBody($templateId, $lang);

      $text = $this->getContents(self::LIBS_DIR . '/' . $lang . '/plain.txt');

      $html = $this->getContents(self::HTML_SHELL);
      $html = preg_replace('/\{% email_body %\}/', $content, $html);

      $html = preg_replace('/<title>/', '<title>' . self::$settings['email']['subject'], $html);
      $html = preg_replace_callback(self::COMMENTS_PATTERN, function($match)
      {
        return "";
      }, $html, -1, $comments);
      $html = preg_replace_callback('/\n[\s]+/', function($match)
      {
        return "\n";
      }, $html, -1, $spaces);
      if (isset(self::$settings['email']['minify']) && self::$settings['email']['minify'] === "true") {
        $html = preg_replace_callback('/\n/', function($match)
        {
          return "";
        }, $html, -1, $newlines);
        fwrite(STDOUT, "Minified!\n");
      }

      //replace all the links

      $htmlCallback = function($match) use ($id, $lang, $templateId, $htmlTags, $numTemplates, &$htmlCounter)
      {
        $newLink = 'http://' . $match["url"] . '?CRE=' . $htmlCounter . '&utm_content=' . $htmlCounter;
        foreach ($htmlTags as $param => $value) {
          if ($param == 'CMP' || $param == 'utm_campaign') {
            $newLink = $numTemplates > 1 ? $newLink . '&' . $param . '=' . $id . '_' . $templateId : $newLink . '&' . $param . '=' . $id;
          }
          else {
            $newLink .= '&' . $param . '=' . $value;
          }
        }
        $htmlCounter++;
        return $newLink;
      };

      $html = preg_replace_callback(self::HTML_PATTERN, $htmlCallback, $html, -1, $htmlLinks);

      $textCallback = function($match) use ($id, $lang, $templateId, $numTemplates, $textTags, &$textCounter)
      {
        $newLink = 'http://' . $match["url"] . '?CRE=' . $textCounter . '&utm_content=' . $textCounter;
        foreach ($textTags as $param => $value) {
          if ($param == 'CMP' || $param == 'utm_campaign') {
            $newLink = $numTemplates > 1 ? $newLink . '&' . $param . '=' . $id . '_' . $templateId : $newLink . '&' . $param . '=' . $id;
          }
          else {
            $newLink .= '&' . $param . '=' . $value;
          }
        }
        $textCounter++;
        return $newLink;
      };

      $text = preg_replace_callback(self::TEXT_PATTERN, $textCallback, $text, -1, $textLinks);

      fwrite(STDOUT, $textLinks . " total text links\n");
      fwrite(STDOUT, $htmlLinks . " total html links\n");

      fwrite(STDOUT, "\nThis is version " . $templateId . "\n");
      fwrite(STDOUT, "Export for Email Vision (y/n): ");
      if (trim(fgets(STDIN)) === 'y') {
        fwrite(STDOUT, "Enter name of file: ");
        $file = trim(fgets(STDIN));
        $this->outputEmailVision($file, $html, $text);
      }
    }

    fwrite(STDOUT, "\n----------\n\n");

    fwrite(STDOUT, "Would you like to build another mail (y/n): ");
    if (trim(fgets(STDIN)) === 'y') {
      $this->build();
    }
    else {
      exit(0);
    }

  }

  protected function getContents($file)
  {
    $fileHandle = fopen($file, 'r');
    $contents = fread($fileHandle, filesize($file));
    fclose($fileHandle);
    return $contents;
  }

  protected function buildEmailBody($template, $language)
  {
    $body = '';
    foreach (self::$settings['content'][$template] as $item) {
      $file = self::LIBS_DIR . '/' . $language . '/' . $item . '.html';
      $element = fopen($file, 'r');
      $body .= fread($element, filesize($file));
      $body .= "\n\n";
    }
    return $body;
  }

  protected function outputEmailVision($name, $html, $text)
  {
    fwrite(STDOUT, "Beginning output of template " . $name . "\n");
    $emv = fopen('export/' . $name . '.txt', 'w');
    fwrite($emv, "[EMV TEXTPART]\n");
    fwrite($emv, $text);
    fwrite($emv, "\n\n");
    fwrite($emv, "[EMV HTMLPART]\n");
    fwrite($emv, $html);
    fclose($emv);

    fwrite(STDOUT, "Exported html and plain text for Campaign Commander to export/" . $name . ".txt\n");
    fwrite(STDOUT, "\n====================\n");
  }
}