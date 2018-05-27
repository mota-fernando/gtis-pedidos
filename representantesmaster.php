<?php

// id_representantes
// id_pessoa

?>
<?php if ($representantes->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_representantesmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($representantes->id_representantes->Visible) { // id_representantes ?>
		<tr id="r_id_representantes">
			<td class="col-sm-2"><?php echo $representantes->id_representantes->FldCaption() ?></td>
			<td<?php echo $representantes->id_representantes->CellAttributes() ?>>
<span id="el_representantes_id_representantes">
<span<?php echo $representantes->id_representantes->ViewAttributes() ?>>
<?php echo $representantes->id_representantes->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($representantes->id_pessoa->Visible) { // id_pessoa ?>
		<tr id="r_id_pessoa">
			<td class="col-sm-2"><?php echo $representantes->id_pessoa->FldCaption() ?></td>
			<td<?php echo $representantes->id_pessoa->CellAttributes() ?>>
<span id="el_representantes_id_pessoa">
<span<?php echo $representantes->id_pessoa->ViewAttributes() ?>>
<?php echo $representantes->id_pessoa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
