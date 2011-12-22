#!/usr/bin/php
<?php

$builder = new EmailBuilder();
$builder->buildEmailVision();


class EmailBuilder{
	protected $htmlFile = 'index.html';
	protected $htmlCounter = 2;
	protected $plainFile = 'plain.txt';
	protected $plainCounter = 1;
	protected $settingsFile = 'config/settings.ini';
	protected static $settings = array();
	protected $processTemplates = false;

	public function __construct(){
		if(!is_readable($this->settingsFile)){
			throw new Exception("Settings file not readable. Make sure you set it up at config/settings.ini");
		}
		$newSettings = parse_ini_file($this->settingsFile, true);
		foreach($newSettings as $group => $settings){
			if(isset(self::$settings[$group])){
				self::$settings[$group] = array_merge(self::$settings[$group], $settings);
			}
			else{
				self::$settings[$group] = $settings;
			}
		}
		$this->resetCounters();
		if(isset(self::$settings['international'])){
			$this->processTemplates = true;
			//TODO: Other setup for processing multiple templates here
		}
	}
	
	protected function resetCounters(){
		if(isset(self::$settings['email']['mirror'])&&self::$settings['email']['mirror'] === 'false'){
			$this->htmlCounter = 1;
		}
		else{
			$this->htmlCounter = 2;
		}
		$this->plainCounter = 1;
	}

	public function setHtmlFile($filename){
		$this->htmlFile = $filename;
	}
	
	public function setPlainFile($filename){
		$this->plainFile = $filename;
	}
	
	protected function replaceHtmlLink($match){
		$newLink = 'href="http://'.$match["url"].'?CRE='.$this->htmlCounter;
		foreach(self::$settings['tracking']['html'] as $param => $value){
			$newLink.='&'.$param.'='.$value;
		}
		fwrite(STDOUT, 'HTML link ' . $this->htmlCounter . ': ' . $newLink . "\n");
		$this->htmlCounter++;
		return $newLink;
	}
	
	protected function replaceTextLink($match){
		$newLink = 'href="http://'.$match["url"].'?CRE='.$this->plainCounter;
		foreach(self::$settings['tracking']['text'] as $param => $value){
			$newLink.='&'.$param.'='.$value;
		}
		fwrite(STDOUT, 'Plain link ' . $this->plainCounter . ': ' . $newLink . "\n");
		$this->plainCounter++;
		return $newLink;
	}
	
	protected function replaceAllLinks($contents, $mode = 'html'){
		$linkTagPattern = '/href="http:\/\/(?P<url>[\w\d\.\/-]+)"/';
		$textLinkPattern = '/http:\/\/(?P<url>[\w\d\.\/-]+)/';
		
		if($mode === 'html'){
			$pattern = $linkTagPattern;
			$callback = 'self::replaceHtmlLink';
		}
		else{
			$pattern = $textLinkPattern;
			$callback = 'self::replaceTextLink';
		}
		
		$contents = preg_replace_callback($pattern, $callback, $contents, -1, $count);
		fwrite(STDOUT, "\n**\n\n");
		fwrite(STDOUT, 'Total '. $mode . ' links: ' . $count . "\n");
		fwrite(STDOUT, "\n====================\n\n");
		return $contents;
	}

	protected function prepareHtmlForSending($html){
		$html = $this->replaceAllLinks($html, 'html');
		$html = preg_replace('/<title>/','<title>'.self::$settings['email']['subject'], $html);
		$commentsPattern = '/<!--[\w\d\s\'":\.\n\*↓↥\/.,@]+-->/';
		$html = preg_replace_callback($commentsPattern, function($match){return "";}, $html, -1, $count);
		$html = preg_replace_callback('/\n[\s]+/', function($match){return "\n";}, $html, -1, $spaces);
		if(isset(self::$settings['email']['minify']) && self::$settings['email']['minify']==="true"){
			$html = preg_replace_callback('/\n/', function($match){return "";}, $html, -1, $newlines);
			fwrite(STDOUT, "Minified!\n");
		}
		fwrite(STDOUT, 'Removed ' . $count . " comments\n");
		fwrite(STDOUT, 'Removed ' . $spaces . " whitespace characters\n");
		fwrite(STDOUT, "\n====================\n\n");
		return $html;
	}
	
	protected function getContents($file){
		$fileHandle = fopen($file, 'r');
		$contents = fread($fileHandle, filesize($file));
		fclose($fileHandle);
		return $contents;
	}
	
	public function buildEmailVision(){
		if(!is_dir('export')){
			mkdir('export', 0755);
		}
		
		fwrite(STDOUT, "Building for Email Vision\n");
		
		$emv = fopen('export/emv.txt', 'w');
		
		$htmlContents = $this->getContents($this->htmlFile);
		$plainContents = $this->getContents($this->plainFile);
				
		$htmlContents = $this->prepareHtmlForSending($htmlContents);
		$plainContents = $this->replaceAllLinks($plainContents, 'plain');
		
		fwrite($emv, "[EMV TEXTPART]\n");
		fwrite($emv, $plainContents);
		fwrite($emv, "\n\n");
		fwrite($emv, "[EMV HTMLPART]\n");
		fwrite($emv, $htmlContents);
		fclose($emv);
		
		fwrite(STDOUT, "Exported html and plain text for Campaign Commander to export/emv.txt\n");
		fwrite(STDOUT, "\n====================\n");
		$this->resetCounters();
	}
	
	public function build(){
		fwrite(STDOUT, "Building for standard output\n");
		$htmlContents = $this->getContents($this->htmlFile);
		$plainContents = $this->getContents($this->plainFile);
		
		$htmlContents = $this->prepareHtmlForSending($htmlContents);
		$plainContents = $this->replaceAllLinks($plainContents, 'plain');
		
		if(!is_dir('export')){
			mkdir('export', 0755);
		}
		$htmlOutput = fopen('export/index.html', 'w');
		$plainOutput = fopen('export/plain.txt', 'w');
		
		fwrite($htmlOutput, $htmlContents);
		fwrite($plainOutput, $plainContents);
		fclose($htmlOutput);
		fclose($plainOutput);
		
		fwrite(STDOUT, "Exported html and plain text emails to export/\n");
		fwrite(STDOUT, "\n====================\n");
		$this->resetCounters();
	}
	
}