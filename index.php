<!DOCTYPE html>
<?

	function cleanHTML($html) {
        
        // Remplace
		$replacements = array(
			'align=' => 'class=',
            'name=' => 'id=',
            ' — ' => ' &ndash; ',
            ' —, ' => ' &ndash;, ',
            ' <p>– ' => ' <p>&mdash; ',
            ' <p>– ' => ' <p>&mdash; ',
            '…' => '...',
            '</em> <em>' => ' ',
            '</strong> <strong>' => ' ',
            ' <em>.' => '<em>.',
            '.<em>' => '. <em>',
            ' <em>,' => '<em>,',
            '</em> ,' => '</em>,',
            '’ <em>' => '’<em>',
            '</em> .' => '</em>.',
            ',<strong>' => ', <strong>',
            ' .' => '.',
            ' ,' => ',',
            ' )' => ')',
            '( ' => '(',
            '« ' => '«&nbsp;',
            ' »' => '&nbsp;»',
            '«</em> ' => '«</em>&nbsp;',
            ' </em>»' => '&nbsp;</em>»',
            '<p>» ' => '<p>»&nbsp;',
            'n° ' => 'n°&nbsp;',
            ' ?' => '&nbsp;?',
            ' !' => '&nbsp;!',
            ' :' => '&nbsp;:',
            ' ;' => '&nbsp;;',
            ' €' => '&nbsp;€',
            ' & ' => ' &amp; ',
            '<p>à' => '<p>À', '<p>â' => '<p>Â', '<p>é' => '<p>É', '<p>è' => '<p>È', '<p>î' => '<p>Î', '<p>ô' => '<p>Ô',
            '<p><em>à' => '<p><em>À', '<p><em>â' => '<p><em>Â', '<p><em>é' => '<p><em>É', '<p><em>è' => '<p><em>È', '<p><em>î' => '<p><em>Î', '<p><em>ô' => '<p><em>Ô',
            '. à' => '. À', '. â' => '. Â', '. é' => '. É', '. è' => '. È', '. î' => '. Î', '. ô' => '. Ô',
            '«&nbsp;à' => '«&nbsp;À', '«&nbsp;â' => '«&nbsp;Â', '«&nbsp;é' => '«&nbsp;É', '«&nbsp;è' => '«&nbsp;È', '«&nbsp;î' => '«&nbsp;Î', '«&nbsp;ô' => '«&nbsp;Ô',
            '...' => '…',
            '<p>
	<p>
		 
	</p>
</p>' => '<p><br /></p>',
            '	<p>
	</p>
</p>' => '</p>'
		);
		
		$html = str_replace(array_keys($replacements), $replacements, $html);
        
        // Delete
        $delete = array('<p><br clear="all" /></p>','<br clear="all"/>');
        $html = str_replace($delete,"",$html);
        
        return $html;
    }
	
	if($_FILES) {
		
		$book = $_FILES["file"]["tmp_name"]; // uploaded temp file
		$book_name = str_replace(".epub","",$_FILES["file"]["name"])."_".time(); // new file name
		$epubs_dir = "epubs";
		$temp_dir = $book_name; 
		$new_book = $epubs_dir."/".$book_name.".epub";
		
		// Create writable temp dir
		mkdir($temp_dir,0777);
		if(!is_dir($epubs_dir)) mkdir($epubs_dir,0777);
		
		// Unzip archive to temp dir
		$zip = new ZipArchive;
		if ($zip->open($book) === TRUE) {
			$zip->extractTo($temp_dir);
			$zip->close();
		}
		
		// List all files in temp dir
		$epub_files = array();
		$i = 0;
		$it = new RecursiveDirectoryIterator($temp_dir);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->getFilename() === '.' || $file->getFilename() === '..') continue;
			else {
				$epub_files[$i]["path"] = str_replace($temp_dir."/","",$file->getPathname());
				$epub_files[$i]["name"] = $file->getFilename();
				$epub_files[$i]["realpath"] = $file->getRealpath();
			}
			$i++;
		}
		
		// Clean HTML files
		foreach($epub_files as $f) {
			$ext = pathinfo($f["realpath"], PATHINFO_EXTENSION);
			if($ext == "html" || $ext == "htm" || $ext == "xhtml") { // select HTML files
				$html = file_get_contents($f["realpath"]);
				$html = cleanHTML($html);
				fwrite(fopen($f["realpath"], 'w'),$html);
			}
		}
		
		// Rezip archive
		file_put_contents($new_book, base64_decode("UEsDBAoAAAAAAOmRAT1vYassFAAAABQAAAAIAAAAbWltZXR5cGVhcHBsaWNhdGlvbi9lcHViK3ppcFBLAQIUAAoAAAAAAOmRAT1vYassFAAAABQAAAAIAAAAAAAAAAAAIAAAAAAAAABtaW1ldHlwZVBLBQYAAAAAAQABADYAAAA6AAAAAAA=")); // Set epub mimetype
		$zip = new ZipArchive();
		$zip->open($new_book, ZipArchive::CREATE);
		foreach($epub_files as $f) {
			if($f["path"] == "mimetype" || is_dir($f["realpath"])) continue; // mimetype already included and if folder, do not add to zip
			else { // if folder, add to zip, delete file and continue;
				$zip->addFile($f["realpath"],$f["path"]);
				continue;
			}
		}
		$zip->close();
		
		// Delete temp dir and all files
		foreach($epub_files as $f) {
			if(is_dir($f["realpath"])) rmdir($f["realpath"]);
			else unlink($f["realpath"]);
		}
		rmdir($temp_dir);
		
		$success = '<p>Success ! <a href="'.$new_book.'">Download ePub</a></p>';
	}
	
	
?>

<html>
	<head>
		<title>php-epub-cleaner</title>
	</head>

	<body>
		
		<h1>php-epub-cleaner</h1>
		
		<p>Created by <a href="http://nokto.net/">Clément Bourgoin</a></p>
		
		<p>Please note that every epub files uploaded for cleaning will be cached on the server. This page should be used for demo purposes only. For production use and commercial files, please install your own version of the application. Source and instructions can be found on GitHub : <a href="https://github.com/iwazaru/php-epub-cleaner">Source</a> | <a href="https://raw.github.com/iwazaru/php-epub-cleaner/master/README.md">Readme</a></p>
		
		<? if(isset($success)) echo $success; ?>
		
		<form method="post" enctype="multipart/form-data" accept="application/epub+zip">
			<fieldset>
				<label for="file">ePub File :</label>
				<input type="file" name="file" id="file">
				<button>Clean</button>
			</fieldset>
		</form>
		
	</body>
</html>













