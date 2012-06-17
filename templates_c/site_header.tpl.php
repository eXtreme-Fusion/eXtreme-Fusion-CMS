<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->data['Theme']['Title']; ?></title>
		<meta charset="<?php echo optLocale($this,'Charset'); ?>" />
		<meta name="description" content="<?php echo $this->data['Theme']['Desc']; ?>" />
		<meta name="keywords" content="<?php echo $this->data['Theme']['Keys']; ?>" />
		
			<script> 
				var addr_images = "<?php echo $this->data['ADDR_IMAGES']; ?>";
				var addr_site = "<?php echo $this->data['ADDR_SITE']; ?>";
			</script>
		
		<link href="<?php echo $this->data['ADDR_FAVICON']; ?>" type="image/x-icon" rel="shortcut icon" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>grid.reset.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>grid.text.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>grid.960.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>jquery.ui.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>jquery.uniform.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>jquery.table.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>jquery.validationEngine.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>jquery.colorpicker.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>main.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['ADDR_CSS']; ?>facebox.css" media="screen" rel="stylesheet" />
		<link href="<?php echo $this->data['THEME_CSS']; ?>styles.css" media="screen" rel="stylesheet" />
		<script src="<?php echo $this->data['ADDR_JS']; ?>jquery.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>jquery.uniform.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>jquery.tooltip.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>jquery.dataTables.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>jquery.tzineClock.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>jquery.passwordStrengthMeter.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>admin-box.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>main.js"></script>
		<script src="<?php echo $this->data['ADDR_JS']; ?>facebox.js"></script>
		
		
		<script> 
			jQuery(function($){ 
				$('a[rel*=facebox]').facebox();
			});
		</script>
		
		<?php echo $this->data['Theme']['Tags']; ?>
	</head>
	<body>