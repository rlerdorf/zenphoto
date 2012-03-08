<?php
/**
 * Clone Zenphoto tab
 *
 *
 * @package admin
 */

if (!defined('OFFSET_PATH')) define ('OFFSET_PATH', 4);
require_once(dirname(dirname(dirname(__FILE__))).'/admin-globals.php');

admin_securityChecks(NULL, currentRelativeURL());

printAdminHeader(gettext('utilities'),gettext('reference'));

?>
</head>
<body>
<?php printLogoAndLinks(); ?>
<div id="main">
<?php printTabs(); ?>
<div id="content">
	<h1><?php echo (gettext('Create a new install with symbolic links to the current Zenphoto scripts.')); ?></h1>
	<?php zp_apply_filter('admin_note','clone', ''); ?>
	<?php
	if (isset($success)) {
		if ($success) {
			?>
			<div class="notebox">
			<?php
			echo implode("\n", $msg)."\n";
			?>
			</div>
			<?php
		} else {
			?>
			<div class="errorbox">
			<?php
			echo implode("\n", $msg)."\n";
			?>
			</div>
			<?php
		}
	} else {
		?>
		<p class="notebox">
			<?php echo gettext('<strong>Note:</strong> Existing Zenphoto scripts will be removed from the target if they exist.')?>
		</p>
		<?php
	}
	?>

	</form>
	<br />
	<br />
	<?php

	$folderlist = array();
	if (isset($_POST['path'])) {
		$path = sanitize($_POST['path']);
	} else {
		$path = str_replace(WEBPATH,'/',SERVERPATH);
	}
	$downtitle = '.../'.basename($path);
	$uppath = str_replace('\\','/',dirname($path));

	$up = explode('/',$uppath);
	$uptitle = array_pop($up);
	if (!empty($up)) {
		$uptitle = array_pop($up).'/'.$uptitle;
	}
	if (!empty($up)) {
		$uptitle = '.../'.$uptitle;
	}

	if (substr($uppath, -1) != '/') {
		$uppath .= '/';
	}

	if (($dir=opendir($path))!==false) {
		while(($file=readdir($dir))!==false) {
			if($file!='.' && $file!='..') {
				if ((is_dir($path.$file))) {
					if ($file != trim(WEBPATH,'/')) {
						$folderlist[$file]=$path.$file.'/';
					}
				}
			}
		}
		closedir($dir);
	}

	?>
		<script type="text/javascript">
			// <!-- <![CDATA[
			function buttonAction(data) {
				$('#newDir').val(data);
				$('#changeDir').submit();
			}
			function folderChange() {
				$('#downbutton').attr('title','<?php echo $downtitle; ?>/'+$('#cloneFolder').val().replace(/\/$/,'').replace( /.*\//, '' ));
			}
			window.onload = function() {
				folderChange();
			}
			// ]]> -->
		</script>
		<form name="changeDir" id="changeDir" method="post">
			<input type="hidden" name="path" id="newDir" value = "" />
			<?php
			if (empty($folderlist)) {
				echo gettext('No subfolders in: ').' ';
			} else {
				echo gettext('Select the destination folder:').' ';
			}
			echo $path;
			if (!empty($folderlist)) {
				?>
				<select id="cloneFolder" name="cloneFolder" onchange="folderChange();">
				<?php	generateListFromArray(array(), $folderlist, false, true);	?>
				</select>
				<?php
			}
			?>
			<a id="upbutton" href="javascript:buttonAction('<?php echo $uppath; ?>');" title="<?php echo $uptitle; ?>"><img class="icon-position-top4" src="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/images/arrow_up.png" alt="" /></a>
			<span<?php if (empty($folderlist)) echo ' style="display:none;"'; ?>><a id="downbutton" href="javascript:buttonAction($('#cloneFolder').val());" title=""><img class="icon-position-top4" src="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/images/arrow_down.png" alt="" /></a></span>
		</form>
		<br clear="all" />
		<br />
		<br />
		<form name="cloneZenphoto" action="<?php echo WEBPATH.'/'.ZENFOLDER.'/'.PLUGIN_FOLDER.'/cloneZenphoto/clone.php'; ?>">
		<?php XSRFToken('cloneZenphoto');?>
		<input type="hidden" name="clone" value="true" />
		<?php XSRFToken('cloneZenphoto'); ?>
		<div class="buttons pad_button" id="cloneZP">
		<button class="tooltip" type="submit" title="<?php echo gettext("Clone the installation."); ?>">
			<img src="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/images/folder.png" alt="" /> <?php echo gettext("Clone Zenphoto"); ?>
		</button>
		</div>
		<br clear="all" />
		</form>

</div><!-- content -->
</div><!-- main -->
<?php printAdminFooter(); ?>
</body>
</html>