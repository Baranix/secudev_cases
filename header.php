<?php
	/* Repetitive functions and variables go here */


	function redirect($url)
	{
		// Redirect to another page when done
		ob_start();
		sleep(2);
		header("Location: " . $url);
		ob_end_flush();
		die();
	}

	require_once '/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php';

	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', 'p,b,a[href],i,u,div,table,tr,td,span,ul,li,ol,img[src]');
	$config->set('HTML.AllowedAttributes', 'src,alt,a.href');
	$purifier = new HTMLPurifier($config);
	//$clean_html = $purifier->purify($dirty_html);

?>