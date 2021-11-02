<?php

	/*
		Recibe un archivo, lo sube al S3 y devuelve la ruta.
	*/

	require 'aws-autoloader.php';
//	ini_set(display_errors,1);

	function subirArchivo($file){
		if($file['size'] > 0 && $file['size'] <= 1500000){ // 1.5mb
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			if($ext == 'png' || $ext == 'PNG' || $ext == 'jpg' || $ext == 'JPG' || $ext == 'jpeg' || $ext == 'JPEG'  || $ext == 'PDF' || $ext == 'pdf') {
				$s3 = new Aws\S3\S3Client([
					'region'  => 'sa-east-1',
					'version' => 'latest',
					'credentials' => [
					    'key'    => "AKIAI2N4IICNZX6VYJKA",
					    'secret' => "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",
					]
				]);

				// Send a PutObject request and get the result object.
				
				$result = $s3->putObject([
					'Bucket' => 'dietetica-cioc',
					'Key'    => date('Ymd-His').$file['name'],
					'SourceFile' => $file['tmp_name'],
					'ACL' 	=> 'public-read'
				]);
			
				// Print the body of the result by indexing into the result object.
				return $result['ObjectURL'] . PHP_EOL;
			}
		}
	}

?>
