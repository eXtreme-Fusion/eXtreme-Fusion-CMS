<?php

	function optShowDebugConsole($config, $templates, $masterPages)
	{
_e('<script type="text/javascript">
opt_console = window.open("","debug console","width=680,height=350,resizable,scrollbars=yes");
opt_console.document.write(\'<html>\');
opt_console.document.write(\'<head>\');
opt_console.document.write(\'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />\');
opt_console.document.write(\'<title>Debug Console</title>\');
opt_console.document.write(\'<style>\');
opt_console.document.write(\'body{\');
opt_console.document.write(\'	background: #ffffff;\');
opt_console.document.write(\'	font-family: Verdana, Arial, Tahoma, Helvetica;\');
opt_console.document.write(\'	font-size: 11px;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#info{\');
opt_console.document.write(\'	width: 100%;\');
opt_console.document.write(\'	padding: 0;\');
opt_console.document.write(\'	margin: 0;\');
opt_console.document.write(\'	border-spacing: 0;\');
opt_console.document.write(\'	border: 1px #333333 solid;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#info td.field{\');
opt_console.document.write(\'	margin: 0;\');
opt_console.document.write(\'	width: 30%;\');
opt_console.document.write(\'	color: #474747;\');
opt_console.document.write(\'	border-width: 1px 0 1px 0;\');
opt_console.document.write(\'	border-style: solid;\');
opt_console.document.write(\'	border-color: #ffffff #ffffff #b2b2b2 #ffffff;\');
opt_console.document.write(\'	background-color: #dadada;\');
opt_console.document.write(\'	font-size: 11px;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#info td.value{\');
opt_console.document.write(\'	margin: 0;\');
opt_console.document.write(\'	width: 70%;\');
opt_console.document.write(\'	border-width: 1px;\');
opt_console.document.write(\'	border-color: #ffffff #e4e4e4 #e4e4e4 #ffffff;\');
opt_console.document.write(\'	border-style: solid;\');
opt_console.document.write(\'	background-color: #efefef;\');
opt_console.document.write(\'	font-size: 11px;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#templates{\');
opt_console.document.write(\'	width: 100%;\');
opt_console.document.write(\'	padding: 0;\');
opt_console.document.write(\'	margin: 0;\');
opt_console.document.write(\'	margin-top: 4px;\');
opt_console.document.write(\'	border-spacing: 0;\');
opt_console.document.write(\'	border: 1px #333333 solid;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#templates thead td{\');
opt_console.document.write(\'	text-align: left;\');
opt_console.document.write(\'	padding: 3px 3px 3px 12px;\');
opt_console.document.write(\'	font-size: 12px;\');
opt_console.document.write(\'	color: #474747;\');
opt_console.document.write(\'	border-width: 1px 0 1px 0;\');
opt_console.document.write(\'	border-style: solid;\');
opt_console.document.write(\'	border-color: #ffffff #ffffff #b2b2b2 #ffffff;\');
opt_console.document.write(\'	background-color: #dadada;\');
opt_console.document.write(\'	font-weight: bold;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#templates tbody td{\');
opt_console.document.write(\'	background-color: #f5f5f5;\');
opt_console.document.write(\'	border-width: 0 1px 1px 0;\');
opt_console.document.write(\'	border-style: solid;\');
opt_console.document.write(\'	border-bottom-color: #d2d2d2;\');
opt_console.document.write(\'	border-right-color: #d2d2d2;\');
opt_console.document.write(\'	font-size: 10px;\');
opt_console.document.write(\'	margin-top: 3px;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'table#templates tbody tr.cached td{\');
opt_console.document.write(\'	background: #ededff;\');
opt_console.document.write(\'}\');
opt_console.document.write(\'</style>\');
opt_console.document.write(\'</head>\');
opt_console.document.write(\'<body>\');
opt_console.document.write(\'<h1>eXtreme-fusion v.5 - Debug Console</h1>\');
opt_console.document.write(\'<table id="info">\');
');

	foreach($config as $name => $value)
	{
		_e('opt_console.document.write(\'<tr>\');
opt_console.document.write(\'<td class="field">'.$name.'</td>\');
opt_console.document.write(\'<td class="value">'.$value.'</td>\');
opt_console.document.write(\'</tr>\');');
	}
	
	if(sizeof($masterPages) > 0)
	{
		_e('opt_console.document.write(\'<tr>\');
opt_console.document.write(\'<td class="field">Master pages</td>\');
opt_console.document.write(\'<td class="value">'.implode(', ', $masterPages).'</td>\');
opt_console.document.write(\'</tr>\');');
	}

_e('opt_console.document.write(\'</table>\');');

_e('
opt_console.document.write(\'<table id="templates">\');
opt_console.document.write(\'<thead>\');
opt_console.document.write(\'<tr>\');
opt_console.document.write(\' <td>Template</td>\');
opt_console.document.write(\' <td>Problems</td>\');
opt_console.document.write(\' <td>Cache</td>\');
opt_console.document.write(\' <td>Execution time</td>\');
opt_console.document.write(\'</tr>\');
opt_console.document.write(\'</thead>\');
opt_console.document.write(\'<tbody>\');
');
	if(sizeof($templates) > 0)
	{
		foreach($templates as $data)
		{
			if($data['cached'] == true)
			{
				_e('opt_console.document.write(\'<tr class="cached">\');');		
			}
			else
			{
				_e('opt_console.document.write(\'<tr>\');');
			}
			_e('opt_console.document.write(\' <td>'.addslashes($data['template']).'</td>\');
opt_console.document.write(\' <td>'.$data['problems'].'</td>\');
opt_console.document.write(\' <td>'.$data['cache'].'</td>\');
opt_console.document.write(\' <td>'.$data['exec'].' s</td>\');
opt_console.document.write(\'</tr>\');');	
		}
	}
	else
	{
			_e('opt_console.document.write(\' <td colspan="4">No template info provided because of "performance tuning" option enabled.</td>\');
opt_console.document.write(\'</tr>\');');
	}
_e('
opt_console.document.write(\'</tbody>\');
opt_console.document.write(\'</table>\');
opt_console.document.write(\'\');
opt_console.document.write(\'</body>\');
opt_console.document.write(\'</html>\');
opt_console.document.close();
</script>'); 
	} // end optShowDebugConsole();
	
	function optCompileCacheReset($filename, $compile)
	{
		if($filename == NULL)
		{
			$dir = opendir($compile);
			while($f = readdir($dir))
			{
				if(is_file($compile.$f))
				{
					unlink($compile.$f);
				}
			}
			closedir($dir);
			return 1;
		}
		elseif(file_exists($compile.optCompileFilename($filename).'.php'))
		{
				unlink($compile.optCompileFilename($filename).'.php');
			return 1;				
		}
		return 0;
	} // end optCompileCacheReset();
	
	function optCacheReset($filename, $id, $expireTime, $cache, $root)
	{
		if($filename == NULL && $id == NULL)
		{
			$dir = opendir($cache);
			while($f = readdir($dir))
			{
				if($expireTime != NULL)
				{
					$expire = optCheckExpire($f, $expireTime);
				}
				else
				{
					$expire = true; 
				}
				if(is_file($cache.$f) && $expire)
				{
					unlink($cache.$f);
				}
			}
			closedir($dir);
			return true;
		}
		elseif($filename == NULL)
		{
			$id = str_replace('|', '^', $id);
			$dir = glob($cache.$id.'*_*.*', GLOB_BRACE);
			foreach($dir as $file)
			{
				if($expireTime != NULL)
				{
					$expire = optCheckExpire($file, $expireTime);
				}
				else
				{
					$expire = true;
				}
				if(is_file($file) && $expire)
				{
					unlink($file);
				}
			}
			return true;
		}
		elseif($id == NULL)
		{
			$dir = glob($cache.'*_'.base64_encode(dirname($filename)).basename($filename).'*');
			foreach($dir as $file)
			{
				if($expireTime != NULL)
				{
					$expire = optCheckExpire($file, $expireTime);
				}
				else
				{
					$expire = true;
				}
				if(is_file($file) && $expire)
				{
					unlink($file);
				}
			}
			return true;
		}
		else
		{
			$id = str_replace('|', '^', $id);
			$dir = glob($cache.$id.'*_'.base64_encode(dirname($filename)).basename($filename).'*', GLOB_BRACE);
			foreach($dir as $file)
			{
				if($expireTime != NULL)
				{
					$expire = optCheckExpire($file, $expireTime);
				}
				else
				{
					$expire = true;
				}
				if(is_file($file) && $expire)
				{
					unlink($file);
				}
			}
			return true;		
		}
	} // end optCacheReset();
	
	function optCheckExpire($file, $time)
	{
		if($time == 0)
		{
			return true;
		}
		$f = @fopen($this -> cache.$this -> cacheFilename, 'r');
		if(!is_resource($f))
		{
			return false;
		}
		$head = fgets($f);
		fclose($f);
		if($head[0] == '<')
		{
			$head = str_replace(array('<'.'?php /*','*/?>'), '', $head);
			$header = @unserialize($head);
			if(!is_array($header))
			{
				return true;
			}			
		}
		else
		{
			$header = @unserialize($head);
			if(!is_array($header))
			{
				return true;
			}
		}
		if($header['timestamp'] < (time() - $time))
		{
			return true;
		}
		return false;
	} // end optCheckExpire();

	
	function optErrorMessage($tpl, $type, $message, $code, $filename = NULL)
	{
		// get callback information
		$dFile = 'Unknown';
		$dLine = '0';
		$dFunction = 'main';
		$trace = debug_backtrace();
		for($i = count($trace) - 1; $i >= 0; $i--)
		{
			if(isset($trace[$i]['class']))
			{
				if($trace[$i]['class'] == 'optClass' || $trace[$i]['class'] == 'optCompiler')
				{
					$dFile = $trace[$i]['file'];
					$dLine = $trace[$i]['line'];
					$dFunction = $trace[$i]['function'];
					break;				
				}
			}	
		}
		// Code processing
		if($code > 0 && $code < 100)
		{
			$n_type = 'eXtreme-fusion v.5:';
		}
		else
		{
			$n_type = 'eXtreme-fusion v.5 - Kompilator:';
		}
		if($type == E_USER_WARNING && $tpl -> showWarnings == true)
		{
			_e('<div class="warning opt">
			<p class="message"><strong> '.$n_type.' warning #'.$code.'</strong>:  '.$message.'</p>');
			if($code >= 100)
			{
				_e('<p class="location">Method: "<em>'.$dFunction.'</em>"; Template: "<em>'.$filename.'</em>"; File: "<em>'.$dFile.'</em>"; Line: "<em>'.$dLine.'</em>"</p>');
			}
			else
			{
				_e('<p class="location">Method: "<em>'.$dFunction.'</em>"; File: "<em>'.$dFile.'</em>"; Line: "<em>'.$dLine.'</em>"</p>');			
			}
			_e('</div>');
		}
		elseif($type == E_USER_ERROR)
		{
			// Send the exception
			$exception = new optException($message, $code, $n_type, $dFile, $dLine, $dFunction, $filename);
			
			$exception -> directories = array(
				'root' => $tpl->root,
				'compile' => $tpl->compile,
				'cache' => $tpl->cache,
				'plugins' => $tpl->plugins
			);			
			throw $exception;
		}
	} // end optErrorMessage();
?>
