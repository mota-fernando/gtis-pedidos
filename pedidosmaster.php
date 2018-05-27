<?php

// numero
// tipo_pedido
// fecha_data
// id_fornecedor
// id_cliente
// status

?>
<?php if ($pedidos->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_pedidosmaster" class="table ewViewTable ewMasterTable ewVertical hidden">
	<tbody>
<?php if ($pedidos->numero->Visible) { // numero ?>
		<tr id="r_numero">
			<td class="col-sm-2"><script id="tpc_pedidos_numero" class="pedidosmaster" type="text/html"><span><?php echo $pedidos->numero->FldCaption() ?></span></script></td>
			<td<?php echo $pedidos->numero->CellAttributes() ?>>
<script id="tpx_pedidos_numero" class="pedidosmaster" type="text/html">
<span id="el_pedidos_numero">
<span<?php echo $pedidos->numero->ViewAttributes() ?>>
<?php echo $pedidos->numero->ListViewValue() ?></span>
</span>
</script>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
		<tr id="r_tipo_pedido">
			<td class="col-sm-2"><script id="tpc_pedidos_tipo_pedido" class="pedidosmaster" type="text/html"><span><?php echo $pedidos->tipo_pedido->FldCaption() ?></span></script></td>
			<td<?php echo $pedidos->tipo_pedido->CellAttributes() ?>>
<script id="tpx_pedidos_tipo_pedido" class="pedidosmaster" type="text/html">
<span id="el_pedidos_tipo_pedido">
<span<?php echo $pedidos->tipo_pedido->ViewAttributes() ?>>
<?php echo $pedidos->tipo_pedido->ListViewValue() ?></span>
</span>
</script>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->fecha_data->Visible) { // fecha_data ?>
		<tr id="r_fecha_data">
			<td class="col-sm-2"><script id="tpc_pedidos_fecha_data" class="pedidosmaster" type="text/html"><span><?php echo $pedidos->fecha_data->FldCaption() ?></span></script></td>
			<td<?php echo $pedidos->fecha_data->CellAttributes() ?>>
<script id="tpx_pedidos_fecha_data" class="pedidosmaster" type="text/html">
<span id="el_pedidos_fecha_data">
<span<?php echo $pedidos->fecha_data->ViewAttributes() ?>>
<?php echo $pedidos->fecha_data->ListViewValue() ?></span>
</span>
</script>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
		<tr id="r_id_fornecedor">
			<td class="col-sm-2"><script id="tpc_pedidos_id_fornecedor" class="pedidosmaster" type="text/html"><span><?php echo $pedidos->id_fornecedor->FldCaption() ?></span></script></td>
			<td<?php echo $pedidos->id_fornecedor->CellAttributes() ?>>
<script id="tpx_pedidos_id_fornecedor" class="pedidosmaster" type="text/html">
<span id="el_pedidos_id_fornecedor">
<span<?php echo $pedidos->id_fornecedor->ViewAttributes() ?>>
<?php echo $pedidos->id_fornecedor->ListViewValue() ?></span>
</span>
</script>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
		<tr id="r_id_cliente">
			<td class="col-sm-2"><script id="tpc_pedidos_id_cliente" class="pedidosmaster" type="text/html"><span><?php echo $pedidos->id_cliente->FldCaption() ?></span></script></td>
			<td<?php echo $pedidos->id_cliente->CellAttributes() ?>>
<script id="tpx_pedidos_id_cliente" class="pedidosmaster" type="text/html">
<span id="el_pedidos_id_cliente">
<span<?php echo $pedidos->id_cliente->ViewAttributes() ?>>
<?php echo $pedidos->id_cliente->ListViewValue() ?></span>
</span>
</script>
</td>
		</tr>
<?php } ?>
<?php if ($pedidos->status->Visible) { // status ?>
		<tr id="r_status">
			<td class="col-sm-2"><script id="tpc_pedidos_status" class="pedidosmaster" type="text/html"><span><?php echo $pedidos->status->FldCaption() ?></span></script></td>
			<td<?php echo $pedidos->status->CellAttributes() ?>>
<script id="tpx_pedidos_status" class="pedidosmaster" type="text/html">
<span id="el_pedidos_status">
<span<?php echo $pedidos->status->ViewAttributes() ?>>
<?php echo $pedidos->status->ListViewValue() ?></span>
</span>
</script>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<div id="tpd_pedidosmaster" class="ewCustomTemplate"></div>
<script id="tpm_pedidosmaster" type="text/html">
<div id="ct_pedidos_master">trsafddddddddddddddddddddddddddddddddddddddddd
<?php

// numero
// tipo_pedido
// fecha_data
// id_fornecedor
// id_cliente
// status

?>
<?php if ($pedidos->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_pedidosmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
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
</div>
</script>
<script type="text/javascript">
ewVar.templateData = { rows: <?php echo ew_ArrayToJson($pedidos->Rows) ?> };
ew_ApplyTemplate("tpd_pedidosmaster", "tpm_pedidosmaster", "pedidosmaster", "<?php echo $pedidos->CustomExport ?>", ewVar.templateData.rows[0]);
jQuery("script.pedidosmaster_js").each(function(){ew_AddScript(this.text);});
</script>
<?php } ?>
