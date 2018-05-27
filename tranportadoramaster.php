<?php

// id_empresa_transportadora
?>
<?php if ($tranportadora->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_tranportadoramaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($tranportadora->id_empresa_transportadora->Visible) { // id_empresa_transportadora ?>
		<tr id="r_id_empresa_transportadora">
			<td class="col-sm-2"><?php echo $tranportadora->id_empresa_transportadora->FldCaption() ?></td>
			<td<?php echo $tranportadora->id_empresa_transportadora->CellAttributes() ?>>
<span id="el_tranportadora_id_empresa_transportadora">
<span<?php echo $tranportadora->id_empresa_transportadora->ViewAttributes() ?>>
<?php echo $tranportadora->id_empresa_transportadora->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
