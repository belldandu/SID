<head>
	<style>
		#i1, #i2 {
			background: none repeat scroll 0 0 #F2F2F2;
			border: 1px solid #2E2E2E;
			border-radius: 4px;
			box-shadow: 0 0 5px #000000;
			overflow: hidden;
			display: inline-block;
		}
		#i1 {
			margin: 0;
		}
		#i2 {
			padding: 2px;
			margin: 2px;
			min-width:5%;
			max-width:48%;
			min-height:5%;
			max-height:48%;
			position: important;
		}
		img {
			min-height:1px;
			max-height:300px;
			min-width:1px;
			max-width:300px;
			object-fit: scale-down;
		}
	</style>
</head>
<body>
	<div id="i1">
		<?php
			$path = "./";
			function getExtensionFromId ($name){
				$extensions = [
					IMAGETYPE_GIF => ['mime' => 'image/gif', 'ext' => 'gif'],
					IMAGETYPE_JPEG => ['mime' => 'image/jpeg', 'ext' => 'jpg'],
					IMAGETYPE_PNG => ['mime' => 'image/png', 'ext' => 'png'],
					IMAGETYPE_SWF => ['mime' => 'application/x-shockwave-flash', 'ext' => 'swf'],
					IMAGETYPE_PSD => ['mime' => 'image/psd', 'ext' => 'psd'],
					IMAGETYPE_BMP => ['mime' => 'image/bmp', 'ext' => 'bmp'],
					IMAGETYPE_TIFF_II => ['mime' => 'image/tiff', 'ext' => 'tiff'],
					IMAGETYPE_TIFF_MM => ['mime' => 'image/tiff', 'ext' => 'tiff'],
					IMAGETYPE_JPC => ['mime' => 'application/octet-stream', 'ext' => 'jpc'],
					IMAGETYPE_JP2 => ['mime' => 'image/jp2', 'ext' => 'jp2'],
					IMAGETYPE_JPX => ['mime' => 'application/octet-stream', 'ext' => 'jpf'],
					IMAGETYPE_JB2 => ['mime' => 'application/octet-stream', 'ext' => 'jb2'],
					IMAGETYPE_SWC => ['mime' => 'application/x-shockwave-flash', 'ext' => 'swc'],
					IMAGETYPE_IFF => ['mime' => 'image/iff', 'ext' => 'aiff'],
					IMAGETYPE_WBMP => ['mime' => 'image/vnd.wap.wbmp', 'ext' => 'wbmp'],
					IMAGETYPE_XBM => ['mime' => 'image/xbm', 'ext' => 'xmb'],
					IMAGETYPE_ICO => ['mime' => 'image/vnd.microsoft.icon', 'ext' => 'ico']
        ];
				// Add as many other Mime Types / File Extensions as you like
				return $extensions[exif_imagetype($name)];
			}

      $directory = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS);
      $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

			$files = [];

			foreach($iterator as $file) {
			    if (!$file->isFile() || !exif_imagetype($file->getPathname()))
			        continue;

			    list($width, $height, $type, $attr) = getimagesize($file->getPathname());

			    $files[] = [
			        "name"        => $file->getFilename(),
			        "ext"        => getExtensionFromId($file->getPathname())['ext'],
							"mime"				=> getExtensionFromId($file->getPathname())['mime'],
			        "path"        => $file->getPathname(),
			        "basename"    => $file->getBasename(".{$file->getExtension()}"),
			        "size"        => $file->getSize()/1000,
			        "width"        => $width,
			        "height"    => $height,
			        "attr"        => $attr,
			    ];
			}

			$totalFiles = count($files);

			require_once "paginator.class.php";
			$pages = new Paginator($totalFiles,15);
			$page = $pages->display_pages();
			$pagedrop = $pages->display_jump_menu();
			$max = $pages->display_items_per_page();
			echo "<center><div id=\"i1\">{$page}{$pagedrop}{$max}</div></center>";
			for ($i = $pages->limit_start; $i < $pages->limit_start + $pages->limit_end; $i++) {

			    if ($i >= $totalFiles)
			        break;

			    echo "<div id=\"i2\">";
			    echo "File Name: {$files[$i]['name']} <br/>";
			    echo "<a href=\"{$files[$i]['path']}\" target=\"_blank\" title=\"{$files[$i]['name']}\" alt=\"{$files[$i]['name']}\"><img src=\"{$files[$i]['path']}\"></a><br/>";
			    echo "Image Title: {$files[$i]['basename']}<br/>";
			    echo "Image Width: {$files[$i]['width']}<br/>";
			    echo "Image Height: {$files[$i]['height']}<br/>";
					echo "Image Mime: {$files[$i]['mime']}<br/>";
			    echo "Image Extension: {$files[$i]['ext']}<br/>";
			    echo "Image File Size: {$files[$i]['size']} KB<br/>";
			    echo "Attributes: {$files[$i]['attr']}";
			    echo "</div>";

			}
			echo "<center><div id=\"i1\">{$page}{$pagedrop}{$max}</div></center>";
		?>

	</div>
</body>
