<h4>Zabezpieczenie rejestracji</h4>

<div class="tbl center">
	<div class="center">{$info}</div>
</div>
<div class="tbl">
	<div class="formLabel sep_1 grid_3">Kod:</div>
	<div class="formField grid_7"><strong>{$code}</strong></div>
</div>
<div class="tbl">
	<div class="formLabel sep_1 grid_3">Odpowiedź:</div>
	<div class="formField grid_7"><input type="text" name="sign_protection_code" /></div>
</div>
<input type="hidden" name="sign_protection_answer" value="{$answer}"/>