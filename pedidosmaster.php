<?php

// id_pedidos
// tipo_pedido
// numero
// fecha_data
// fecha_hora
// id_fornecedor
// id_transportadora
// id_prazos
// comentarios
// id_representante
// comissao_representante
// id_cliente
// status

?>
<?php if ($pedidos->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_pedidosmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($pedidos->id_pedidos->Visible) { // id_pedidos ?>
		<tr id="r_id_pedidos">
			<td class="col-sm-2"><?php echo $pedidos->id_pedidos->FldCaption() ?></td>
			<td<?php echo $pedidos->id_pedidos->CellAttributes() ?>>
<span id="el_pedidos_id_pedidos">
<span<?php echo $pedidos->id_pedidos->ViewAttributes() ?>>
<?php echo $pedidos->id_pedidos->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
		<tr id="r_tipo_pedido">
			<td class="col-sm-2"><?php echo $pedidos->tipo_pedido->FldCaption() ?></td>
			<td<?php echo $pedidos->tipo_pedido->CellAttributes() ?>>
<span id="el_pedidos_tipo_pedido">
<span<?php echo $pedidos->tipo_pedido->ViewAttributes() ?>>
<?php echo $pedidos->tipo_pedido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->numero->Visible) { // numero ?>
		<tr id="r_numero">
			<td class="col-sm-2"><?php echo $pedidos->numero->FldCaption() ?></td>
			<td<?php echo $pedidos->numero->CellAttributes() ?>>
<span id="el_pedidos_numero">
<span<?php echo $pedidos->numero->ViewAttributes() ?>>
<?php echo $pedidos->numero->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->fecha_data->Visible) { // fecha_data ?>
		<tr id="r_fecha_data">
			<td class="col-sm-2"><?php echo $pedidos->fecha_data->FldCaption() ?></td>
			<td<?php echo $pedidos->fecha_data->CellAttributes() ?>>
<span id="el_pedidos_fecha_data">
<span<?php echo $pedidos->fecha_data->ViewAttributes() ?>>
<?php echo $pedidos->fecha_data->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->fecha_hora->Visible) { // fecha_hora ?>
		<tr id="r_fecha_hora">
			<td class="col-sm-2"><?php echo $pedidos->fecha_hora->FldCaption() ?></td>
			<td<?php echo $pedidos->fecha_hora->CellAttributes() ?>>
<span id="el_pedidos_fecha_hora">
<span<?php echo $pedidos->fecha_hora->ViewAttributes() ?>>
<?php echo $pedidos->fecha_hora->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
		<tr id="r_id_fornecedor">
			<td class="col-sm-2"><?php echo $pedidos->id_fornecedor->FldCaption() ?></td>
			<td<?php echo $pedidos->id_fornecedor->CellAttributes() ?>>
<span id="el_pedidos_id_fornecedor">
<span<?php echo $pedidos->id_fornecedor->ViewAttributes() ?>>
<?php echo $pedidos->id_fornecedor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_transportadora->Visible) { // id_transportadora ?>
		<tr id="r_id_transportadora">
			<td class="col-sm-2"><?php echo $pedidos->id_transportadora->FldCaption() ?></td>
			<td<?php echo $pedidos->id_transportadora->CellAttributes() ?>>
<span id="el_pedidos_id_transportadora">
<span<?php echo $pedidos->id_transportadora->ViewAttributes() ?>>
<?php echo $pedidos->id_transportadora->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_prazos->Visible) { // id_prazos ?>
		<tr id="r_id_prazos">
			<td class="col-sm-2"><?php echo $pedidos->id_prazos->FldCaption() ?></td>
			<td<?php echo $pedidos->id_prazos->CellAttributes() ?>>
<span id="el_pedidos_id_prazos">
<span<?php echo $pedidos->id_prazos->ViewAttributes() ?>>
<?php echo $pedidos->id_prazos->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->comentarios->Visible) { // comentarios ?>
		<tr id="r_comentarios">
			<td class="col-sm-2"><?php echo $pedidos->comentarios->FldCaption() ?></td>
			<td<?php echo $pedidos->comentarios->CellAttributes() ?>>
<span id="el_pedidos_comentarios">
<span<?php echo $pedidos->comentarios->ViewAttributes() ?>>
<?php echo $pedidos->comentarios->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_representante->Visible) { // id_representante ?>
		<tr id="r_id_representante">
			<td class="col-sm-2"><?php echo $pedidos->id_representante->FldCaption() ?></td>
			<td<?php echo $pedidos->id_representante->CellAttributes() ?>>
<span id="el_pedidos_id_representante">
<span<?php echo $pedidos->id_representante->ViewAttributes() ?>>
<?php echo $pedidos->id_representante->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->comissao_representante->Visible) { // comissao_representante ?>
		<tr id="r_comissao_representante">
			<td class="col-sm-2"><?php echo $pedidos->comissao_representante->FldCaption() ?></td>
			<td<?php echo $pedidos->comissao_representante->CellAttributes() ?>>
<span id="el_pedidos_comissao_representante">
<span<?php echo $pedidos->comissao_representante->ViewAttributes() ?>>
<?php echo $pedidos->comissao_representante->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
		<tr id="r_id_cliente">
			<td class="col-sm-2"><?php echo $pedidos->id_cliente->FldCaption() ?></td>
			<td<?php echo $pedidos->id_cliente->CellAttributes() ?>>
<span id="el_pedidos_id_cliente">
<span<?php echo $pedidos->id_cliente->ViewAttributes() ?>>
<?php echo $pedidos->id_cliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->status->Visible) { // status ?>
		<tr id="r_status">
			<td class="col-sm-2"><?php echo $pedidos->status->FldCaption() ?></td>
			<td<?php echo $pedidos->status->CellAttributes() ?>>
<span id="el_pedidos_status">
<span<?php echo $pedidos->status->ViewAttributes() ?>>
<?php echo $pedidos->status->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
