<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/

class systemException extends Exception{}
class userException extends Exception{}

class uploadException extends Exception
{
    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = __('The uploaded file exceeds the upload_max_filesize directive in php.ini');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = __('The uploaded file was only partially uploaded');
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = __('No file was uploaded');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = __('Missing a temporary folder');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = __('Failed to write file to disk');
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = __('File upload stopped by extension');
                break;

            default:
                $message = __('Unknown upload error');
                break;
        }
        return $message;
    }
} 

function uploadErrorHandler(uploadException $exc) {
	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
		$getHeader = ob_get_contents();
	ob_end_clean();
	echo replaceException($getHeader);

	echo '<h3>Upload error</h3>
	<div class="error">'.$exc->getMessage().'</div>';

	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_footer.tpl";
		$getFooter = ob_get_contents();
	ob_end_clean();
	echo replaceException($getFooter);
}

function systemErrorHandler(systemException $exc)
{
	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
		$getHeader = ob_get_contents();
	ob_end_clean();
	echo replaceException($getHeader);
	echo '<h3>System error</h3>
	<div class="error">'.$exc->getMessage().'</div>';
	$trace = array_reverse($exc->getTrace()); ?>
	<div class="debug opt">
		<h3>Error path:</h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:5%">#</th>
					<th style="width:40%">W pliku</th>
					<th style="width:45%">Funkcja</th>
					<th style="width:10%">Linia</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($trace as $number => $item) {
					if(isset($item['class'])) {
						$callback = $item['class'].$item['type'].$item['function'];
					} else {
						$callback = $item['function'];
					}
					echo '<tr class="tbl1 border_bottom">
					<td style="padding:6px;width:5%" class="center">'.$number.'</td>
					<td style="width:40%">'.(isset($item['file']) ? basename($item['file']) : '----').'</td>
					<td style="width:45%">'.$callback.'</td>
					<td style="width:10%" class="center">'.(isset($item['line']) ? basename($item['line']) : '----').'</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		<div class="center" style="width:200px;margin:10px auto;">
			<span class="CancelButton" style="width:150px;"><strong class="o"><strong class="m"><strong>Back<img alt="" src="<?php ADDR_SITE ?>/templates/images/icons/pixel/undo.png"></strong></strong></strong></span>
		</div>
	</div>
	<?php
	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_footer.tpl";
		$getFooter = ob_get_contents();
	ob_end_clean();
	echo replaceException($getFooter);
}

function pagesErrorHandler($exc) {

	$trace = array_reverse($exc->getTrace());
	$error['Message'] = $exc->getMessage();
	foreach($trace as $number => $item) {
		if(isset($item['class'])) {
			$callback = $item['class'].$item['type'].$item['function'];
		} else {
			$callback = $item['function'];
		}
		$error[] = array(
			'Mallback' => $callback,
			'Number' => $number,
			'File' => basename($item['file']),
			'Line' => $item['line']
		);
	}
	return $error;
}



function userErrorHandler(userException $exc) {
	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
		$getHeader = ob_get_contents();
	ob_end_clean();
	echo replaceException($getHeader);

	echo '<h3>User error</h3>
	<div class="error">'.$exc->getMessage().'</div>';

	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_footer.tpl";
		$getFooter = ob_get_contents();
	ob_end_clean();
	echo replaceException($getFooter);
}



function PDOErrorHandler($exc) {
	ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
		$getHeader = ob_get_contents();
	ob_end_clean();
	echo replaceException($getHeader);

	echo '<h3>Error</h3>
	<div class="error">'.$exc->getMessage().'</div>';
	$trace = array_reverse($exc->getTrace()); ?>
	<div class="debug opt">
		<h3>Error path:</h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:5%">#</th>
					<th style="width:40%">W pliku</th>
					<th style="width:45%">Funkcja</th>
					<th style="width:10%">Linia</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($trace as $number => $item) {
					if(isset($item['class'])) {
						$callback = $item['class'].$item['type'].$item['function'];
					} else {
						$callback = $item['function'];
					}
					echo '<tr class="tbl1 border_bottom">
					<td style="padding:6px;width:5%" class="center">'.$number.'</td>
					<td style="width:40%">'.$exc->getFile().'</td>
					<td style="width:45%">'.$callback.'</td>
					<td style="width:10%" class="center">'.$item['line'].'</td>
					</tr>';
				} ?>
			</tbody>
		</table>
	</div>
	<?php
	ob_start();
		include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
		$getFooter = ob_get_contents();
	ob_end_clean();
	echo replaceException($getFooter);
}

function replaceException($text) {
	$search = array('{$Charset}','{$ADDR_SITE}','{$DIR_ADMIN}','{$ADDR_ADMIN_CSS}','{$FILE_SELF}','{$DIR_ADMIN_IMAGES}','{$CornerStart}','{$CornerEnd}','{literal}','{/literal}');
	$replace = array('utf-8',ADDR_SITE,DIR_ADMIN,ADDR_ADMIN_TEMPLATES.'stylesheet/',FILE_SELF,DIR_ADMIN_IMAGES,"<div class='corner4px'><div class='ctl'><div class='ctr'><div class='ctc'></div></div></div><div class='cc'>","</div><div class='cfl'><div class='cfr'><div class='cfc'></div></div></div></div>","","");
	return str_replace($search,$replace,$text);
}
