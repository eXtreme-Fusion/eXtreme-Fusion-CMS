{panel='Błąd wewnętrzny'}

<div class="error">{$Message}</div>

<div class="debug opt">
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
			{section=Error}
				<tr class="tbl1 border_bottom">
					<td style="padding:6px;width:5%" class="center">{$Error.Number}</td>
					<td style="width:40%">{$Error.File}</td>
					<td style="width:45%">{$Error.Callback}</td>
					<td style="width:10%" class="center">{$Error.Line}</td>
				</tr>
			{/section}
		</tbody>
	</table>
</div>
{/panel}