<?php
		// --------------------------------------------------------------------------------------------------------------
		/*  
			TITLE:			Client Preview Page	
			VERSION:		2.6
			DATE:			03-05-2016
			AUTHOR:			Lee Redmond
			NOTES:			Wellcom / BBH php page previewer.
			
			FEATURES:		2.6 	Change to XML creative - developer [eg. Lee Redmond] and role [eg. HTML Animation] added
									Background set - change colour theme to either White, Grey or White
							2.5		Share Preview buttom with email link
							2.4		Date Stamp added to header infomation
							2.3		Import of XML datapreview information
							2.2		HTML5 Preview integration
							2.1		!NOT IMPLEMENTED YET - DISABLED! Packaged ZIP download feature - all swfs, jpg's, png's are zipped when page loads and download button available.
							2.0		Logical file size of the creative added to info bar eg. Size: 39kb - displays 'n/a' if no Flash or Backup exists
							1.9 	Minor text change 'Format' is now Creative
							1.8 	Exclamation icon prompt display's when creative is misssing.
							1.7 	Graceful Degradation if no Flash Plug-in available to Backup, and finally if no Backup available display User Prompt.
							1.6 	Display User Prompt if no Flash or Backup is available.
							1.5 	RegExp dimension calculator based on banner list names.
							1.4 	Dynamic 'Co-Partner' logo branding - set this using the $coPartner variable ie Space66 + Atomic / Space66 + BBH logos.
							1.3 	Responsive CSS for Desktop / Tablet / Mobile.
							1.2 	Backup preview mode / defaults to backup mode if Flash is not available.
							1.1 	Drop-down menu selector.
							
		
		*/

		$sVersion		  	= '2.6';
		$sPreviewDownload  	= "https://github.com/SantaUnion/Wellcom-Creative-Previewer.git";

		// --------------------------------------------------------------------------------------------------------------
		// Bespoke Declarations for the preview:
		
        
		$creativesXML 	= simplexml_load_file("creatives.xml");     // load xml creatives data
		$coPartner		= (string)$creativesXML-> agency; 			// eg. "BBH"; // Set Co-Partner client ie. Default / BBH / Atomic	
		$client 			= (string)$creativesXML-> client; 			// eg. "Audi";
		$title 			= (string)$creativesXML-> campaign; 			// eg. "Approved  Used";
		$route			= (string)$creativesXML-> route; 			// eg. "Standard HTML5";
		$clickTag 		= (string)$creativesXML-> clicktag; 			// eg. "http://www.audi.co.uk/audi/used-cars/audi-approved-used.html"; // Click Through
		$developer		= (string)$creativesXML-> developer	;		// eg. "Lee Redmond"; // name
		$role			= (string)$creativesXML-> role	;			// eg. "HTML5 Animation"; // role
		$swfPrefix 		= (string)$creativesXML-> prefix;			// eg. "audi_approved_used_";
		
		$bannerList  	= array ();
	
		foreach($creativesXML->creatives->children() as $creative) {
		  	// echo $child->creatives->getName() . ": " . $child . "<br>";
			$newCreative = (string)$creative;
			// echo $newCreative . "<br />";
			$newCreative = (string)$creative;
			array_push ($bannerList, $newCreative);
		}
		
		

	
	
		
		// --------------------------------------------------------------------------------------------------------------
		// Theme Colour:	
				
		$theme =	 $_GET['theme'];
		if ($theme == "white" || $theme == NULL) $themename = "white";
		if ($theme == "grey" ) $themename = "grey";
		if ($theme == "black" ) $themename = "black";
		
	
		
		
		// --------------------------------------------------------------------------------------------------------------
		// Logo - Colour Theme:				
		$logo			= array("white" 				=> "/creative-previewer/assets/wellcomlogo-white-theme.png",
								"grey"				=> "/creative-previewer/assets/wellcomlogo-white-theme.png",
								"black"				=> "/creative-previewer/assets/wellcomlogo-black-theme.png"
						);				
		
				
		$logo			= $logo[$themename];
		
		$exclamation	= "/creative-previewer/assets/exclamation.png";	
		
		
		// --------------------------------------------------------------------------------------------------------------
		// Set Cookie for preview mode ie Flash or Backup:
		
		$mode =	 $_GET['mode'];
		if ($mode == "flash" || $mode == NULL) $modeValue = "flash";
		if ($mode == "backup") $modeValue = "backup";
		if ($mode == "html5") $modeValue = "html5";
		setcookie("PreviewMode",$modeValue, time()+3600*24);

		// --------------------------------------------------------------------------------------------------------------
		// Get format of the creative:		
		
		$format = $_GET["format"];
		if (!$format) $format = $bannerList[0];
		$dimensions 	= preg_match("/\d{2,}[x]\d{2,}/", $format, $size); // regular expression looking for dimension ie 120x600, 728x90, 300x250, etc
		$xPos 			= strpos($size[0], "x");
		$swfW 			= $xPos ? substr($size[0], 0, $xPos) : "300";
		$swfH 			= $xPos ? substr($size[0], $xPos+1) : "250";
		
		/*
		echo "<p>format 	= " . $format . "</p>";
		echo "<p>swfW 		= " . $swfW . "</p>";
		echo "<p>swfH 		= " . $swfH . "</p>";
		echo "<p>filename 	= " . $swfPrefix . $format . "</p>";
		*/
		
		
		
		// --------------------------------------------------------------------------------------------------------------
		// Set values for the dropdown:	
				
		$formatLinkStr = "";
		foreach($bannerList as $f)
		{
			if ($f == $format) 
			{ 
				// Current creative
				$backupPrefix	= $swfPrefix . $f; // echo "backupPrefix  = " . $backupPrefix;
				$formatLinkStr .= "<option value='index.php?format=$f&mode=$modeValue&theme=$themename' selected>$f</option>";
			}
			else $formatLinkStr .= "<option value='index.php?format=$f&mode=$modeValue&theme=$themename'>$f</option>";
		}
		
		
		
		// --------------------------------------------------------------------------------------------------------------
		// Check for backup file extension
		
		$backupGIF 		= $backupPrefix. ".gif";
		$backupJPG 		= $backupPrefix. ".jpg";
		$backupPNG 		= $backupPrefix. ".png";
	
		
		$backupFiles	= array ($backupGIF , $backupJPG, $backupPNG);
		
		// $bBackupExists
		
		foreach($backupFiles as $filename)
		{
			// echo $filetype;
			if ($data = @getimagesize($filename ) && $filename == $backupPrefix. ".gif" ) {$backupSuffix	= ".gif"; /* echo "its a gif" */ ;}
			if ($data = @getimagesize($filename ) && $filename == $backupPrefix. ".jpg" ) {$backupSuffix	= ".jpg"; /* echo "its a jpg" */ ;}
			if ($data = @getimagesize($filename ) && $filename == $backupPrefix. ".png" ) {$backupSuffix	= ".png"; /* echo "its a png" */ ;}
		}
			
	?>

<!doctype html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
	<head>
		<title><?php echo $client . " | " . $title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <!-- CSS -------------------------------------------------------------- -->

        <!-- Font Awesome -->
        <link rel="stylesheet" href="/creative-previewer/assets/fonts/font-awesome/css/font-awesome.min.css">
        
        <!-- Colour Themes -->
        <?php 
			if ($themename == "white") {
				echo "<link rel='stylesheet' type='text/css' href='/creative-previewer/assets/creative-preview-default.css' />"; 
			} else if ($themename == "grey") {
				echo "<link rel='stylesheet' type='text/css' href='/creative-previewer/assets/creative-preview-grey.css' />"; 			
			} else {
				echo "<link rel='stylesheet' type='text/css' href='/creative-previewer/assets/creative-preview-black.css' />"; 	
			}

		?>





		<style type="text/css">
			
		
			
			iframe { border:none; margin:0; }
			
			/* Disable share button */
			#share-button { display:none;}
		
			#background-theme { float:right;}
			
			/* Version -  Button */	
			#version {	background:#cc9933; width:100px; height:50px; text-align:center; font-size:11px; 
						color:#000; position:fixed; 
						
						bottom:-25px; right:-48px;
						-ms-transform: rotate(-45deg); /* IE 9 */
						-webkit-transform: rotate(-45deg); /* Chrome, Safari, Opera */
						transform: rotate(-45deg);
			}
			#version  a { color:#333; width:100%; height:100%; display: block; line-height:20px;}
			
			
			/* Share Button */		
			#share-button				{ display:inline-block; float:right; margin-left:10px;}
			#share-button a:hover 		{ color:#666;}
			
			
			
        </style>
        
        <!-- JS -------------------------------------------------------------- -->
		
		<script src="/creative-previewer/assets/swfobject.js" type="text/javascript"></script>
        <script type="text/javascript">
			// SWFObject
			//<![CDATA[
			swfobject.embedSWF('<?php echo $swfPrefix, $format, ".swf" ?>','flash','<?php echo $swfW ?>','<?php echo $swfH ?>','9.0.0','','',{flashVars:'clickTag=<?php echo $clickTag ?>'});
			
			//]]>
		</script>
        
        <script type="text/javascript">
		
			var idFooter =	document.getElementById("footer");
		
			// var myVar = setInterval(alertFunc, 50);
			
			
			function alertFunc() {
				console.log ("Hello!");
			}
		
		</script>
        
        
	</head>
    
	<body>
    	
       <!-- HEADER -------------------------------------------------------------- -->
    
        <div id="header">
        
        	<div id="logo"><img src="<?php echo $logo ?>" border="0" title="Space66" /></div>
            
            
            <div id="title"><?php echo $client ?></div>
            <div id="desc">
                <span class="info">Campaign: <b><?php echo $title ?></b>&nbsp;&nbsp;<span class="iota">&Iota;</span>&nbsp;&nbsp;</span>
                <span class="info"><?php echo $role ?>: <b><?php echo $developer ?></b>&nbsp;&nbsp;<span class="iota">&Iota;</span>&nbsp;&nbsp;</span>
                <span class="info">Route: <b><?php echo $route ?></b>&nbsp;&nbsp;<span class="iota">&Iota;</span>&nbsp;&nbsp;</span>
                <span class="info">Creative: <b><form id="selector"><select name="dropdown" id="dropdown"><?php echo $formatLinkStr ?></select></form></b>&nbsp;&nbsp;<span class="iota">&Iota;</span>&nbsp;&nbsp;
                
				<!-- Get file size of creative -->
				<?php
				 	
					 $bannerSWF				= $swfPrefix. $format . ".swf";
					 $bannerBackup			= $swfPrefix. $format . $backupSuffix;
					 $bannerHTML5			=  $swfPrefix. $format;
					 
					 $nFlashExists 			=  file_exists($bannerSWF);
					 $nBackupExists 		=  file_exists($bannerBackup);
					 $nHTML5Exists 			=  file_exists($swfPrefix. $format);
					
					
					
					if($modeValue == 'flash' && $nFlashExists == 1) // Is this in Flash mode and does Flash Exist
					{
						$filename			= $swfPrefix. $format . ".swf";
						$fileSize 			= round (filesize($filename)/1024, 2) . 'kb';
						// $modificationDate	= getModificationDate($filename);
						$modificationDate	= str_replace("-", " ", getModificationDate($filename));
					} 
					else if($modeValue == 'backup' && $nBackupExists == 1 && $backupSuffix != "") // Is this in BAckup mode and does Backup Exist
					{
						$filename			= $swfPrefix. $format . $backupSuffix;
						$fileSize 			= round (filesize($filename)/1024, 2) . 'kb';
						// $modificationDate	= getModificationDate($filename);
						$modificationDate	= str_replace("-", " ", getModificationDate($filename));

					} 
					else if($modeValue == 'html5' && $nBackupExists == 1) // Does HTML5 exist
					{
						$filename			= $swfPrefix. $format;
						$directorySize 		= dirsize ($filename);
						$fileSize 			= round ($directorySize/1024, 2) . 'kb';
						// $modificationDate	= getModificationDate($filename);
						$modificationDate	= str_replace("-", " ", getModificationDate($filename));
						
						

					}
					else
					{
						$fileSize 			= 'n/a'; // No Flash, No Backup or no HTML5! - n/a
						$modificationDate	= "n/a";
					}
					
					// ------------------------------------------------------------
					// Get Modification Date Stamp
					// ------------------------------------------------------------
					
					function getModificationDate ($fileName) {
						
						// Get Modification Date format
						// $modDate			= date ("F d Y H:i:s", filemtime($fileName));
						// $modDate			= date ("Y-m-d H:i", filemtime($fileName));
						$modDate			= date ("d-M-Y H:i", filemtime($fileName));
						
					

						return  $modDate;
						
						
					}
					
					
					// ------------------------------------------------------------
					// Get Directory Content Size
					// ------------------------------------------------------------
					
					function dirsize($dir)
					{
					  @$dh = opendir($dir);
					  $size = 0;
					  while ($file = @readdir($dh))
					  {
						if ($file != "." and $file != "..") 
						{
						  $path = $dir."/".$file;
						  if (is_dir($path))
						  {
							$size += dirsize($path); // recursive in sub-folders
						  }
						  elseif (is_file($path))
						  {
							$size += filesize($path); // add file
						  }
						}
					  }
					  @closedir($dh);
					  return $size;
					}
									
					// ------------------------------------------------------------
					
					
					function getUrl() {
					  $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
					  $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
					  $url .= $_SERVER["REQUEST_URI"];
					  
					  $url = str_replace('&amp;', '&', $url);
					  
					  return $url;
					}
					
					
					
									
				?>
				
                <!-- Display Creative Size -->
				<span class="info">Size: <b><?php echo $fileSize ?></b>&nbsp;&nbsp;<span class="iota">&Iota;&nbsp;&nbsp;</span></span>
                
                <!-- Display Creative Size -->
				<span class="info">Modified: <b><?php echo $modificationDate ?></b>&nbsp;&nbsp;</span>
                
                
                
                <!-- Get Modification Date of Creative -->
                
                
                <?php 
				
				
				
				?>
           
           
           
           
           
            </div>
            
            <script>
				// Drop down menu redirects
				var urlmenu = document.getElementById( 'dropdown' );
				urlmenu.onchange = function() {
					  window.location.assign(this.options[ this.selectedIndex ].value);
				};
			</script>
         
            
        </div> <!-- End of #header -->
        
       <hr />
       
       <!-- CONTENT -------------------------------------------------------------- -->
       
       <div id="content">
        
         
       <!-- Switch out content-->
       
       <?php
          
          // --------------------------------------------------
          // Check for FLASH MODE
          // --------------------------------------------------
		  
		  $swfBanner		= $swfPrefix. $format . ".swf";
		  $swfBackup		= $swfPrefix. $format . $backupSuffix;
		  
		  $bFlashExists 	=  file_exists($swfBanner);
		  $bBackupExists 	=  file_exists($swfBackup);
		  
		
		  
		  
		  
		  if($modeValue == 'flash'){
							  
				if ($bFlashExists == 1 || $bBackupExists == 1 ) {
					
					if ($bFlashExists == 1) {
							echo "<div id='flash'>" ;
								if ($bBackupExists == 1) {
									echo "<p><img src='" . $exclamation . "' width='40' height='35' alt='!' /></br></br>Oops! Flash plugin is not available.</p>";
									echo "<img src='" .  $swfBackup . "' width='". $swfW ."' height='". $swfH ."' alt='backup' />";
								} else {
									echo "<p><img src='" .  $exclamation . "' width='40' height='35' alt='!' /></br></br>Oops! Flash and Backup previews are not available for this creative.</p>";
								}
							echo "</div>";
					}
					
					elseif ($bBackupExists == 1) {
							echo  "<p><img src='" . $exclamation . "' width='40' height='35' alt='!' /></br></br>Flash preview is not available for this creative.</p>";
							
							if ($backupSuffix != "") {
								echo "<img src='" . $swfBackup . "' width='". $swfW ."' height='". $swfH ."' alt='backup' />";
							}
					}
			  
				} else
				{
					echo  "<p><img src='" . $exclamation . "' width='40' height='35' alt='!' /></br></br>Oops! Flash and Backup previews are not available for this creative.</p>";
				}
		  
		  }
		  
		  // --------------------------------------------------
          // Check for BACKUP MODE
          // --------------------------------------------------
		  
		if($modeValue == 'backup'  ){
		
			$backupBanner = $swfPrefix. $format . $backupSuffix;
			
			// echo "$backupSuffix = " + $backupSuffix;
			// echo "format	= " + $swfPrefix;
			
			if (file_exists($backupBanner) && $backupSuffix != "") {
					echo
					"<div id='backup'> 
					<img src='" .  $swfPrefix . $format . $backupSuffix . "' width='". $swfW ."' height='". $swfH ."' alt='backup' /> 
					</div>";				  
				
			 } else {
					echo "<p><img src='" . $exclamation . "' width='40' height='35' alt='!' /></br></br>Oops! Backup preview is not available for this creative.</p>";
					/* echo "<p>$backupBanner</p>"; */
			}
		
		}
		
		// --------------------------------------------------
        // Check for HTML5 MODE
       	// --------------------------------------------------
		
		if($modeValue == 'html5'){
			
			$html5banner		= $swfPrefix. $format;
	
			if (file_exists($html5banner)) {
					echo "<iframe src='" . $html5banner . "/index.html' align='center' width='". $swfW ."' height='". $swfH ."'></iframe>";
				  
				
			 } else {
					echo "<p><img src='" . $exclamation . "' width='40' height='35' alt='!' /></br></br>Oops! HTML5 preview is not available for this creative.</p>";
					/* echo "<p>$backupBanner</p>"; */
			}
			
			
			
			
			
			
		}
		
		
			  
      ?>
      
    
      
      
      <?php

		// ------------------------------------------------------------
		// DEVLOPEMENT: Create Zip off files on server and download link as package
		// ------------------------------------------------------------
		
		// current directory

		/*
		$dir = getcwd(). "/";
		
		//get all text files with a .swf .gif .jpg extension.
		$aSWF = glob($directory . "*.swf");
		$aGIF = glob($directory . "*.gif");
		$aJPG = glob($directory . "*.jpg");
	
		$aCampagnFiles = array_merge((array)$aSWF, (array)$aGIF, (array)$aJPG);
		
		
		$date = date("Y/m/d");
		$date = str_replace("/", "", $date);
		$zipName = $swfPrefix . "_package_" . $date . ".zip"; 
		
		$zip = new ZipArchive();
		$zip->open($dir . $zipName, ZipArchive::CREATE);
		
		foreach($aCampagnFiles as $zipfile) { $zip->addFile($zipfile); ?><div> <?php echo $b; ?></div><?php }
		
		$zip->addFile($aCampagnFiles);
		$zip->close();
		*/
		
		
		
	?>
    
     
       </div> <!-- End of #content -->
        
       <!-- DEVELOPMENT PANEL -------------------------------------------------------------- -->

         <div id="dev"> 
       		<p>banner name: <?php echo $swfPrefix. $format.$backupSuffix ?></p>
            <p>width: <?php echo $swfW ?></p>
            <p>height: <?php echo $swfH ?></p>
            <?php
				
				$filename = $swfBanner;
				$fileSize = round (filesize($filename)/1024, 2);
				echo $fileSize.'kb';
			
			?>
            
            
       </div> <!-- End of Development panel -->
       
       
        <hr />
       
       <!-- FOOTER -------------------------------------------------------------- -->
       
       <div id="footer">
            
            <!-- Preview mode -->
            <span id="preview-mode">Preview Mode:&nbsp; 
            <?php
				
				if ($modeValue == "html5") echo "<a href='index.php?format=$format&mode=flash&theme=" . $themename . "'>Flash</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='index.php?format=$format&mode=backup&theme=" . $themename . "'>Backup</a>&nbsp;&nbsp;|&nbsp;&nbsp;<strong>HTML5</strong>";
				if ($modeValue == "backup") echo "<a href='index.php?format=$format&mode=flash&theme=" . $themename . "'>Flash</a>&nbsp;&nbsp;|&nbsp;&nbsp;<strong>Backup</strong>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='index.php?format=$format&mode=html5&theme=" . $themename . "'>HTML5</a>";
				if ($modeValue == "flash") echo "<strong>Flash</strong>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='index.php?format=$format&mode=backup&theme=" . $themename . "'>Backup</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='index.php?format=$format&mode=html5&theme=" . $themename . "'>HTML5</a>";
			?>
           	</span>
            
               <!-- Share Link -->   
             <span id="share-button" title="Share preview link"><a href="mailto:?subject=<?php echo  "Creative Preview: " . $client . " | ". $title ?>&body=<?php  echo " %0D%0A %0D%0A Campaign:%0D%0A" . $client . " - " . $title . " %0D%0A %0D%0A" . "Creative Preview link:%0D%0A"  . getUrl() ; ?> "><i class="fa fa-share" aria-hidden="true"></i></a></span>
            
            
            <!-- Colour theme -->
            <?php
				$borderStyle = " style='border:1px solid rgba(243,146,0, 0.7)' ";
				if ($themename == "white") 	{ $whiteborder = $borderStyle;  } else {  $whiteborder = " style='border:1px solid #fff' "; }
				if ($themename == "grey") 	{ $greyborder = $borderStyle; } else { $greyborder = " style='border:1px solid #ddd' ";}
				if ($themename == "black") 	{ $blackborder = $borderStyle; } else { $blackborder = " style='border:1px solid #000' ";}
		
			?>
            
            <span id="background-theme">
                  <span class="theme" id="theme-white" <?php echo $whiteborder; ?><?php echo 'theme-on' ?>> <?php  echo "<a href='index.php?format=$format&mode=" . $modeValue . "&theme=white' title='White mode'></a>"  ?> </span>
                  <span class="theme" id="theme-grey" <?php echo $greyborder; ?>> <?php  echo "<a href='index.php?format=$format&mode=" . $modeValue . "&theme=grey' title='Grey mode'></a>"  ?> </span>
                  <span class="theme" id="theme-black" <?php echo $blackborder; ?>> <?php  echo "<a href='index.php?format=$format&mode=" . $modeValue . "&theme=black' title='Black mode'></a>"  ?> </span>
            </span>
            
            
             
          

            
            
            
       </div> <!-- End of footer -->
       
       <!-- Version label -->
       <div><?php echo "<span id='version' target='_blank' title='Creative Previewer " . $sVersion . "'><a href=" . $sPreviewDownload . " target='_blank'>" . $sVersion . "</a></span>"; ?></div>
    
    	
    </body>
</html>
