<?php
	require 'aws-autoloader.php';
//	ini_set(display_errors,1);
 	$s3 = new Aws\S3\S3Client([
		'region'  => 'sa-east-1',
		'version' => 'latest',
		'credentials' => [
		    'key'    => "AKIAI2N4IICNZX6VYJKA",
		    'secret' => "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",
		]
	]);

	// Send a PutObject request and get the result object.
	$key = 'Hola';

	$result = $s3->putObject([
		'Bucket' => 'dietetica-cioc',
		'Key'    => $key,
		'SourceFile' => 'Captura.PNG'
	]);

	// Print the body of the result by indexing into the result object.
	var_dump($result);
?>
