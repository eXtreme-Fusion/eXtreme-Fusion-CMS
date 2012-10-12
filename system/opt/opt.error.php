<?php

class optException extends Exception {
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

function optErrorHandler(optException $exc) {
	echo '<h3>'.$exc->getType().' Błąd wewnętrzny: #'.$exc->getCode().'</h3>';
	echo '<div class="error">'.$exc->getMessage().'</div>';

	if($exc->getCode() >= 100) {
		echo '<div class="status">Metoda: "<em>'.$exc->getFunction().'</em>"; Templatka: "<em>'.$exc->getFilename().'</em>"; Plik: "<em>'.$exc->getFile().'</em>"; Linia: "<em>'.$exc->getLine().'</em>"</div>';
	} else {
		echo '<div class="status">Metoda: "<em>'.$exc->getFunction().'</em>"; Plik: "<em>'.$exc->getFile().'</em>"; Linia: "<em>'.$exc->getLine().'</em>"</div>';			
	}

	$trace = array_reverse($exc -> getTrace()); ?>

	<div class="debug opt">
		<h3>Ścieżka błędu:</h3>
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
					echo('<tr class="tbl1 border_bottom">
								<td style="padding:6px;width:5%" class="center">'.$number.'</td>
								<td style="width:40%">'.basename($item['file']).'</td>
								<td style="width:45%">'.$callback.'</td>
								<td style="width:10%" class="center">'.$item['line'].'</td>
							</tr>');
				} ?>
			</tbody>
		</table>
		<br /><h3>Katalogi</h3>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:33%">Katalog</th>
					<th style="width:34%">Ścieżka</th>
					<th style="width:33%">Status</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach($exc -> directories as $type => $data) {
					if($data == NULL) {
						$status = '<a class="Plus tip" title="n/d">n/d</a>';				
					} elseif(is_dir($data)) {
						$status = '<a class="IconStatusOK tip" title="Katalog istnieje">Istnieje</a>';
					} else {
						$status = '<a class="IconMinus tip" title="Katalog nieistnieje!">BŁAD!</a>';
					} ?>
					<tr class="tbl1 border_bottom">
						<td style="width:33%"><strong><?php echo($type) ?></strong></td>
						<td style="width:33%"><?php echo($data) ?></td>
						<td style="width:33%" class="center"><?php echo($status) ?></td>
					</tr>
				<?php } ?>

			</tbody>
		</table>
	</div>

<?php }