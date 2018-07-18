<?php 
	require __DIR__ . '/lib/google-search-results.php';
	$query = [
    "q" => "inurl:index.php?id=",
    "google_domain" => "google.com",
    "api_key" => "AIzaSyADm0Fn_NL1kD_4xUI1Bi5003juooSg-W0",
	];
	$serp = new GoogleSearchResults();
	$json_results = $serp.json($query);
	echo var_dump($json_results);
?>