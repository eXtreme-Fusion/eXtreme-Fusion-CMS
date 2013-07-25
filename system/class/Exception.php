<?php
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
*********************************************************/

class systemException extends Exception{}
class userException extends Exception{}
class argumentException extends Exception{};

class optException extends Exception 
{
	private $func;
	private $type;
	private $filename;
	public $directories;

	public function __construct($message = null, $code = null, $type=null, $file = null, $line = null, $function = null, $filename = null) {
		$this -> message = $message;
		$this -> code = $code;
		$this -> file = $file;
		$this -> line = $line;
		$this -> func = $function;
		$this -> type = $type;
		$this -> filename = $filename;
	}
	
	public function getFunction() {
		return $this -> func;
	}
	
	public function getType() {
		return $this -> type;
	}
	
	public function getFilename() {
		return $this -> filename;
	}
}

function optErrorHandler(optException $exc, $full = TRUE) 
{
	?> <div class="exception"> <?php if ($full) {
		ob_start();
		include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
		$getHeader = ob_get_contents();
		ob_end_clean();

		echo replaceException($getHeader);
	}
	echo '<h3>'.$exc->getType().' '.__('Internal error').' #'.$exc->getCode().'</h3>';
	echo '<div class="center"><span class="error">'.$exc->getMessage().'</span></div>';

	if($exc->getCode() >= 100) 
	{
		echo '<div class="status">'.__('Method').': "<em>'.$exc->getFunction().'</em>"; '.__('Templates').': "<em>'.$exc->getFilename().'</em>"; '.__('File').': "<em>'.$exc->getFile().'</em>"; '.__('Line').': "<em>'.$exc->getLine().'</em>"</div>';
	} 
	else
	{
		echo '<div class="status">'.__('Method').': "<em>'.$exc->getFunction().'</em>"; '.__('File').': "<em>'.$exc->getFile().'</em>"; '.__('Line').': "<em>'.$exc->getLine().'</em>"</div>';			
	}

	$trace = array_reverse($exc->getTrace()); ?>
	
		<h3><?php echo __('Error path'); ?></h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:5%;text-align:center">#</th>
					<th style="width:40%"><?php echo __('In file'); ?></th>
					<th style="width:45%"><?php echo __('Function'); ?></th>
					<th style="width:10%;text-align:center"><?php echo __('Line'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($trace as $number => $item) 
				{
					if(isset($item['class'])) 
					{
						$callback = $item['class'].$item['type'].$item['function'];
					} 
					else 
					{
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
		<br /><h3><?php echo __('Directories'); ?></h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:33%"><?php echo __('Directory'); ?></th>
					<th style="width:34%"><?php echo __('Path'); ?></th>
					<th style="width:33%"><?php echo __('Status'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($exc -> directories as $type => $data) 
				{
					if($data == NULL) 
					{
						$status = '<a class="Plus tip" title="'.__('N/A').'">'.__('N/A').'</a>';				
					} 
					elseif(is_dir($data)) 
					{
						$status = '<a class="IconStatusOK tip" title="'.__('Exist').'">'.__('Exist').'</a>';
					} 
					else 
					{
						$status = '<a class="IconMinus tip" title="'.__('Does not exist').'">'.__('Does not exist').'</a>';
					} ?>
					<tr class="tbl1 border_bottom">
						<td style="width:33%"><strong><?php echo($type) ?></strong></td>
						<td style="width:33%"><?php echo($data) ?></td>
						<td style="width:33%" class="center"><?php echo($status) ?></td>
					</tr>
				<?php } ?>

			</tbody>
		</table>
		<?php if ($full) { ?>
		<div class="tbl Buttons" style="width:200px;margin:10px auto;">
			<div class="center button-c">
				<span class="Cancel" onclick="history.back()"><strong><?php echo __('Back'); ?> <img style="position: absolute;" src="<?php echo ADDR_ADMIN; ?>/templates/images/icons/pixel/undo.png" alt="" ></strong></span>
			</div>
		</div>
		<?php } ?></div>

		<?php if ($full) {
				ob_start();
				include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
				$getFooter = ob_get_contents();
				ob_end_clean();
				echo replaceException($getFooter);
			}
}

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
                $message = __('The uploaded file exceeds the upload_max_filesize directive in php.ini.');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.');
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = __('The uploaded file was only partially uploaded.');
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = __('No file was uploaded.');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = __('Missing a temporary folder.');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = __('Failed to write file to disk.');
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = __('File upload stopped by extension.');
                break;

            default:
                $message = __('Unknown upload error.');
                break;
        }
        return $message;
    }
} 

function uploadErrorHandler(uploadException $exc, $full = TRUE) {
	?> <div class="exception"> <?php if ($full) {
		ob_start();
			include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
			$getHeader = ob_get_contents();
		ob_end_clean();
		echo replaceException($getHeader);
	}

	echo '<h3>'.__('Upload error').'</h3>
	<div class="center"><span class="error">'.$exc->getMessage().'</span></div>';

	if ($full) {
		ob_start();
		include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
		$getFooter = ob_get_contents();
		ob_end_clean();
		echo replaceException($getFooter);
	}
}

function systemErrorHandler(systemException $exc, $full = TRUE)
{
	?> <div class="exception"> <?php if ($full) {
		ob_start();
			include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
			$getHeader = ob_get_contents();
		ob_end_clean();
		echo replaceException($getHeader);
	}
	
	echo '<h3>'.__('System error').'</h3>
	<div class="center"><span class="error">'.$exc->getMessage().'</span></div>';
	$trace = array_reverse($exc->getTrace()); ?>
	
		<h3><?php echo __('Error path'); ?></h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:5%;text-align:center">#</th>
					<th style="width:40%"><?php echo __('In file'); ?></th>
					<th style="width:45%"><?php echo __('Function'); ?></th>
					<th style="width:10%;text-align:center"><?php echo __('Line'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if (!empty($trace))
					{	
						//var_dump($exc);
						foreach($trace as $number => $item) {
							if(isset($item['class'])) {
								$callback = $item['class'].$item['type'].$item['function'];
							} else {
								$callback = $item['function'];
							}
							echo '<tr class="tbl1 border_bottom">
							<td style="padding:6px;width:5%" class="center">'.$number.'</td>
							<td style="width:40%">'.(isset($item['file']) ? $item['file'] : '-----').'</td>
							<td style="width:45%">'.$callback.'</td>
							<td style="width:10%" class="center">'.(isset($item['line']) ? $item['line'] : '-----').'</td>
							</tr>';
						}
					}
					else
					{
						echo '<tr class="tbl1 border_bottom">
						<td style="padding:6px;width:5%" class="center">1</td>
						<td style="width:40%">'.$exc->getFile().'</td>
						<td style="width:45%">---</td>
						<td style="width:10%" class="center">'.$exc->getLine().'</td>
						</tr>';
					}
				?>
			</tbody>
		</table>
		<?php if ($full) { ?>
		<div class="tbl Buttons" style="width:200px;margin:10px auto;">
			<div class="center button-c">
				<span class="Cancel" onclick="history.back()"><strong><?php echo __('Back'); ?> <img style="position: absolute;" src="<?php echo ADDR_ADMIN; ?>/templates/images/icons/pixel/undo.png" alt="" ></strong></span>
			</div>
		</div>
		<?php } ?></div>
	<?php if ($full) {
		ob_start();
		include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
		$getFooter = ob_get_contents();
		ob_end_clean();
		echo replaceException($getFooter);
	}
}

function argumentErrorHandler(argumentException $exc, $full = TRUE)
{
	?> <div class="exception"> <?php if ($full) {
		ob_start();
			include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
			$getHeader = ob_get_contents();
		ob_end_clean();
		echo replaceException($getHeader);
	}
	echo '<h3>'.__('Function argument error').'</h3>
	<div class="error">'.__('Parameter of :parametr is wrong.', array(':parametr' => $exc->getMessage())).'</div>';
	$trace = array_reverse($exc->getTrace()); ?>
	
		<h3><?php echo __('Error path'); ?></h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:5%;text-align:center">#</th>
					<th style="width:40%"><?php echo __('In file'); ?></th>
					<th style="width:45%"><?php echo __('Function'); ?></th>
					<th style="width:10%;text-align:center"><?php echo __('Line'); ?></th>
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
					<td style="width:40%">'.(isset($item['file']) ? $item['file'] : '-----').'</td>
					<td style="width:45%">'.$callback.'</td>
					<td style="width:10%" class="center">'.(isset($item['line']) ? $item['line'] : '-----').'</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		<?php if ($full) { ?>
		<div class="tbl Buttons" style="width:200px;margin:10px auto;">
			<div class="center button-c">
				<span class="Cancel" onclick="history.back()"><strong><?php echo __('Back'); ?> <img style="position: absolute;" src="<?php echo ADDR_ADMIN; ?>/templates/images/icons/pixel/undo.png" alt="" ></strong></span>
			</div>
		</div>
		<?php } ?></div>
		<?php if ($full) {
			ob_start();
			include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
			$getFooter = ob_get_contents();
			ob_end_clean();
			echo replaceException($getFooter);
		}
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

function userErrorHandler(userException $exc, $full = TRUE) {
	
	?> <div class="exception"> <?php if ($full) {
		ob_start();
			include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
			$getHeader = ob_get_contents();
		ob_end_clean();
		echo replaceException($getHeader);
	}

	echo '<h3>'.__('User error').'</h3>
	<div class="center"><span class="error">'.$exc->getMessage().'</span></div>';

	if ($full) {
		ob_start();
		include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
		$getFooter = ob_get_contents();
		ob_end_clean();
		echo replaceException($getFooter);
	}
}

function PDOErrorHandler(PDOException $exc, $full = TRUE) {
	?> <div class="exception"> <?php if ($full) {
		ob_start();
			include DIR_ADMIN_TEMPLATES."pre".DS."exception_header.tpl";
			$getHeader = ob_get_contents();
		ob_end_clean();
		echo replaceException($getHeader);
	}
	
	if(strstr($exc->getMessage(), 'SQLSTATE[')) 
	{ 
        preg_match('/SQLSTATE\[(.*)\]: (.*)/', $exc->getMessage(), $matches);
		$code = $matches[1];
		$message = $matches[2];
    } 
	
	echo '<h3>'.__('PDO Error').' #SQLSTATE: '.$code.'</h3>
	<div class="center"><span class="error">'.$message.'</span></div>';
	$trace = array_reverse($exc->getTrace());
	
		$i = 0;
		foreach($trace as $number => $item) 
		{
			if(isset($item['class']) && $item['class'] === 'Data' && isset($item['args'][0]) && ! is_object($item['args'][0]) && $i<1) 
			{
				echo '<h3>'.__('PDO Queries').'</h3>'; 
				echo '<div class="center"><span class="error">'.$item['args'][0].'</span></div>';
				$i++;
			}
		} ?>
		
		<h3><?php echo __('Error path'); ?></h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:5%;text-align:center">#</th>
					<th style="width:40%"><?php echo __('In file'); ?></th>
					<th style="width:45%"><?php echo __('Function'); ?></th>
					<th style="width:10%;text-align:center"><?php echo __('Line'); ?></th>
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
					<td style="width:40%">'.(isset($item['file']) ? $item['file'] : '-----').'</td>
					<td style="width:45%">'.$callback.'</td>
					<td style="width:10%" class="center">'.(isset($item['line']) ? $item['line'] : '-----').'</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		<?php if ($full) { ?>
		<div class="tbl Buttons" style="width:200px;margin:10px auto;">
			<div class="center button-c">
				<span class="Cancel" onclick="history.back()"><strong><?php echo __('Back'); ?> <img style="position: absolute;" src="<?php echo ADDR_ADMIN; ?>/templates/images/icons/pixel/undo.png" alt="" ></strong></span>
			</div>
		</div>
		<?php } ?></div>
		<?php if ($full) {
			ob_start();
			include DIR_ADMIN_TEMPLATES."pre/exception_footer.tpl";
			$getFooter = ob_get_contents();
			ob_end_clean();
			echo replaceException($getFooter);
		}
}

function replaceException($text) 
{
	$replace = array(
		'{$html_harset}' 		=> 'utf-8',
		'{$ADDR_SITE}' 			=> ADDR_SITE,
		'{$DIR_ADMIN}' 			=> DIR_ADMIN,
		'{$ADDR_ADMIN_CSS}' 	=> ADDR_ADMIN_TEMPLATES.'stylesheet/',
		'{$ADDR_COMMON_CSS}' 	=> ADDR_COMMON_CSS,
		'{$FILE_SELF}' 			=> FILE_SELF,
		'{$DIR_ADMIN_IMAGES}' 	=> DIR_ADMIN_IMAGES,
		'{literal}' 			=> "",
		'{/literal}' 			=> ""
	);
	
	return str_replace(array_keys($replace), array_values($replace), $text);
}