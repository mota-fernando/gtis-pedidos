<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($detalhe_pedido_grid)) $detalhe_pedido_grid = new cdetalhe_pedido_grid();

// Page init
$detalhe_pedido_grid->Page_Init();

// Page main
$detalhe_pedido_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detalhe_pedido_grid->Page_Render();
?>
<?php if ($detalhe_pedido->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdetalhe_pedidogrid = new ew_Form("fdetalhe_pedidogrid", "grid");
fdetalhe_pedidogrid.FormKeyCountName = '<?php echo $detalhe_pedido_grid->FormKeyCountName ?>';

// Validate form
fdetalhe_pedidogrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_numero_pedido");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detalhe_pedido->numero_pedido->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_produto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detalhe_pedido->id_produto->FldCaption(), $detalhe_pedido->id_produto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_preco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detalhe_pedido->preco->FldCaption(), $detalhe_pedido->preco->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantidade");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detalhe_pedido->quantidade->FldCaption(), $detalhe_pedido->quantidade->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantidade");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detalhe_pedido->quantidade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subtotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detalhe_pedido->subtotal->FldCaption(), $detalhe_pedido->subtotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subtotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detalhe_pedido->subtotal->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdetalhe_pedidogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "numero_pedido", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_produto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "desconto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "preco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "quantidade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "subtotal", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetalhe_pedidogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdetalhe_pedidogrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdetalhe_pedidogrid.Lists["x_id_produto"] = {"LinkField":"x_id_produto","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_produto","","",""],"ParentFields":[],"ChildFields":["x_preco"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"produtos"};
fdetalhe_pedidogrid.Lists["x_id_produto"].Data = "<?php echo $detalhe_pedido_grid->id_produto->LookupFilterQuery(FALSE, "grid") ?>";
fdetalhe_pedidogrid.Lists["x_desconto"] = {"LinkField":"x_porcentagem","Ajax":true,"AutoFill":false,"DisplayFields":["x_porcentagem","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"desconto"};
fdetalhe_pedidogrid.Lists["x_desconto"].Data = "<?php echo $detalhe_pedido_grid->desconto->LookupFilterQuery(FALSE, "grid") ?>";
fdetalhe_pedidogrid.Lists["x_preco"] = {"LinkField":"x_preco_produto","Ajax":true,"AutoFill":false,"DisplayFields":["x_preco_produto","","",""],"ParentFields":["x_id_produto"],"ChildFields":[],"FilterFields":["x_id_produto"],"Options":[],"Template":"","LinkTable":"produtos"};
fdetalhe_pedidogrid.Lists["x_preco"].Data = "<?php echo $detalhe_pedido_grid->preco->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($detalhe_pedido->CurrentAction == "gridadd") {
	if ($detalhe_pedido->CurrentMode == "copy") {
		$bSelectLimit = $detalhe_pedido_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$detalhe_pedido_grid->TotalRecs = $detalhe_pedido->ListRecordCount();
			$detalhe_pedido_grid->Recordset = $detalhe_pedido_grid->LoadRecordset($detalhe_pedido_grid->StartRec-1, $detalhe_pedido_grid->DisplayRecs);
		} else {
			if ($detalhe_pedido_grid->Recordset = $detalhe_pedido_grid->LoadRecordset())
				$detalhe_pedido_grid->TotalRecs = $detalhe_pedido_grid->Recordset->RecordCount();
		}
		$detalhe_pedido_grid->StartRec = 1;
		$detalhe_pedido_grid->DisplayRecs = $detalhe_pedido_grid->TotalRecs;
	} else {
		$detalhe_pedido->CurrentFilter = "0=1";
		$detalhe_pedido_grid->StartRec = 1;
		$detalhe_pedido_grid->DisplayRecs = $detalhe_pedido->GridAddRowCount;
	}
	$detalhe_pedido_grid->TotalRecs = $detalhe_pedido_grid->DisplayRecs;
	$detalhe_pedido_grid->StopRec = $detalhe_pedido_grid->DisplayRecs;
} else {
	$bSelectLimit = $detalhe_pedido_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($detalhe_pedido_grid->TotalRecs <= 0)
			$detalhe_pedido_grid->TotalRecs = $detalhe_pedido->ListRecordCount();
	} else {
		if (!$detalhe_pedido_grid->Recordset && ($detalhe_pedido_grid->Recordset = $detalhe_pedido_grid->LoadRecordset()))
			$detalhe_pedido_grid->TotalRecs = $detalhe_pedido_grid->Recordset->RecordCount();
	}
	$detalhe_pedido_grid->StartRec = 1;
	$detalhe_pedido_grid->DisplayRecs = $detalhe_pedido_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detalhe_pedido_grid->Recordset = $detalhe_pedido_grid->LoadRecordset($detalhe_pedido_grid->StartRec-1, $detalhe_pedido_grid->DisplayRecs);

	// Set no record found message
	if ($detalhe_pedido->CurrentAction == "" && $detalhe_pedido_grid->TotalRecs == 0) {
		if ($detalhe_pedido_grid->SearchWhere == "0=101")
			$detalhe_pedido_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detalhe_pedido_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$detalhe_pedido_grid->RenderOtherOptions();
?>
<?php $detalhe_pedido_grid->ShowPageHeader(); ?>
<?php
$detalhe_pedido_grid->ShowMessage();
?>
<?php if ($detalhe_pedido_grid->TotalRecs > 0 || $detalhe_pedido->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($detalhe_pedido_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> detalhe_pedido">
<div id="fdetalhe_pedidogrid" class="ewForm ewListForm form-inline">
<?php if ($detalhe_pedido_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($detalhe_pedido_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_detalhe_pedido" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_detalhe_pedidogrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$detalhe_pedido_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$detalhe_pedido_grid->RenderListOptions();

// Render list options (header, left)
$detalhe_pedido_grid->ListOptions->Render("header", "left");
?>
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->id_detalhe) == "") { ?>
		<th data-name="id_detalhe" class="<?php echo $detalhe_pedido->id_detalhe->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_id_detalhe" class="detalhe_pedido_id_detalhe"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->id_detalhe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_detalhe" class="<?php echo $detalhe_pedido->id_detalhe->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_id_detalhe" class="detalhe_pedido_id_detalhe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->id_detalhe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->id_detalhe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->id_detalhe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->numero_pedido) == "") { ?>
		<th data-name="numero_pedido" class="<?php echo $detalhe_pedido->numero_pedido->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_numero_pedido" class="detalhe_pedido_numero_pedido"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->numero_pedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numero_pedido" class="<?php echo $detalhe_pedido->numero_pedido->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_numero_pedido" class="detalhe_pedido_numero_pedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->numero_pedido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->numero_pedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->numero_pedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->id_produto) == "") { ?>
		<th data-name="id_produto" class="<?php echo $detalhe_pedido->id_produto->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_id_produto" class="detalhe_pedido_id_produto"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->id_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_produto" class="<?php echo $detalhe_pedido->id_produto->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_id_produto" class="detalhe_pedido_id_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->id_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->id_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->id_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->desconto) == "") { ?>
		<th data-name="desconto" class="<?php echo $detalhe_pedido->desconto->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_desconto" class="detalhe_pedido_desconto"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->desconto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="desconto" class="<?php echo $detalhe_pedido->desconto->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_desconto" class="detalhe_pedido_desconto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->desconto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->desconto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->desconto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->preco) == "") { ?>
		<th data-name="preco" class="<?php echo $detalhe_pedido->preco->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_preco" class="detalhe_pedido_preco"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->preco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="preco" class="<?php echo $detalhe_pedido->preco->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_preco" class="detalhe_pedido_preco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->preco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->preco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->preco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->quantidade) == "") { ?>
		<th data-name="quantidade" class="<?php echo $detalhe_pedido->quantidade->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_quantidade" class="detalhe_pedido_quantidade"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->quantidade->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="quantidade" class="<?php echo $detalhe_pedido->quantidade->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_quantidade" class="detalhe_pedido_quantidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->quantidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->quantidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->quantidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->subtotal) == "") { ?>
		<th data-name="subtotal" class="<?php echo $detalhe_pedido->subtotal->HeaderCellClass() ?>"><div id="elh_detalhe_pedido_subtotal" class="detalhe_pedido_subtotal"><div class="ewTableHeaderCaption"><?php echo $detalhe_pedido->subtotal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subtotal" class="<?php echo $detalhe_pedido->subtotal->HeaderCellClass() ?>"><div><div id="elh_detalhe_pedido_subtotal" class="detalhe_pedido_subtotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->subtotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detalhe_pedido->subtotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detalhe_pedido->subtotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$detalhe_pedido_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detalhe_pedido_grid->StartRec = 1;
$detalhe_pedido_grid->StopRec = $detalhe_pedido_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detalhe_pedido_grid->FormKeyCountName) && ($detalhe_pedido->CurrentAction == "gridadd" || $detalhe_pedido->CurrentAction == "gridedit" || $detalhe_pedido->CurrentAction == "F")) {
		$detalhe_pedido_grid->KeyCount = $objForm->GetValue($detalhe_pedido_grid->FormKeyCountName);
		$detalhe_pedido_grid->StopRec = $detalhe_pedido_grid->StartRec + $detalhe_pedido_grid->KeyCount - 1;
	}
}
$detalhe_pedido_grid->RecCnt = $detalhe_pedido_grid->StartRec - 1;
if ($detalhe_pedido_grid->Recordset && !$detalhe_pedido_grid->Recordset->EOF) {
	$detalhe_pedido_grid->Recordset->MoveFirst();
	$bSelectLimit = $detalhe_pedido_grid->UseSelectLimit;
	if (!$bSelectLimit && $detalhe_pedido_grid->StartRec > 1)
		$detalhe_pedido_grid->Recordset->Move($detalhe_pedido_grid->StartRec - 1);
} elseif (!$detalhe_pedido->AllowAddDeleteRow && $detalhe_pedido_grid->StopRec == 0) {
	$detalhe_pedido_grid->StopRec = $detalhe_pedido->GridAddRowCount;
}

// Initialize aggregate
$detalhe_pedido->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detalhe_pedido->ResetAttrs();
$detalhe_pedido_grid->RenderRow();
if ($detalhe_pedido->CurrentAction == "gridadd")
	$detalhe_pedido_grid->RowIndex = 0;
if ($detalhe_pedido->CurrentAction == "gridedit")
	$detalhe_pedido_grid->RowIndex = 0;
while ($detalhe_pedido_grid->RecCnt < $detalhe_pedido_grid->StopRec) {
	$detalhe_pedido_grid->RecCnt++;
	if (intval($detalhe_pedido_grid->RecCnt) >= intval($detalhe_pedido_grid->StartRec)) {
		$detalhe_pedido_grid->RowCnt++;
		if ($detalhe_pedido->CurrentAction == "gridadd" || $detalhe_pedido->CurrentAction == "gridedit" || $detalhe_pedido->CurrentAction == "F") {
			$detalhe_pedido_grid->RowIndex++;
			$objForm->Index = $detalhe_pedido_grid->RowIndex;
			if ($objForm->HasValue($detalhe_pedido_grid->FormActionName))
				$detalhe_pedido_grid->RowAction = strval($objForm->GetValue($detalhe_pedido_grid->FormActionName));
			elseif ($detalhe_pedido->CurrentAction == "gridadd")
				$detalhe_pedido_grid->RowAction = "insert";
			else
				$detalhe_pedido_grid->RowAction = "";
		}

		// Set up key count
		$detalhe_pedido_grid->KeyCount = $detalhe_pedido_grid->RowIndex;

		// Init row class and style
		$detalhe_pedido->ResetAttrs();
		$detalhe_pedido->CssClass = "";
		if ($detalhe_pedido->CurrentAction == "gridadd") {
			if ($detalhe_pedido->CurrentMode == "copy") {
				$detalhe_pedido_grid->LoadRowValues($detalhe_pedido_grid->Recordset); // Load row values
				$detalhe_pedido_grid->SetRecordKey($detalhe_pedido_grid->RowOldKey, $detalhe_pedido_grid->Recordset); // Set old record key
			} else {
				$detalhe_pedido_grid->LoadRowValues(); // Load default values
				$detalhe_pedido_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detalhe_pedido_grid->LoadRowValues($detalhe_pedido_grid->Recordset); // Load row values
		}
		$detalhe_pedido->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detalhe_pedido->CurrentAction == "gridadd") // Grid add
			$detalhe_pedido->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detalhe_pedido->CurrentAction == "gridadd" && $detalhe_pedido->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detalhe_pedido_grid->RestoreCurrentRowFormValues($detalhe_pedido_grid->RowIndex); // Restore form values
		if ($detalhe_pedido->CurrentAction == "gridedit") { // Grid edit
			if ($detalhe_pedido->EventCancelled) {
				$detalhe_pedido_grid->RestoreCurrentRowFormValues($detalhe_pedido_grid->RowIndex); // Restore form values
			}
			if ($detalhe_pedido_grid->RowAction == "insert")
				$detalhe_pedido->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detalhe_pedido->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detalhe_pedido->CurrentAction == "gridedit" && ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT || $detalhe_pedido->RowType == EW_ROWTYPE_ADD) && $detalhe_pedido->EventCancelled) // Update failed
			$detalhe_pedido_grid->RestoreCurrentRowFormValues($detalhe_pedido_grid->RowIndex); // Restore form values
		if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detalhe_pedido_grid->EditRowCnt++;
		if ($detalhe_pedido->CurrentAction == "F") // Confirm row
			$detalhe_pedido_grid->RestoreCurrentRowFormValues($detalhe_pedido_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detalhe_pedido->RowAttrs = array_merge($detalhe_pedido->RowAttrs, array('data-rowindex'=>$detalhe_pedido_grid->RowCnt, 'id'=>'r' . $detalhe_pedido_grid->RowCnt . '_detalhe_pedido', 'data-rowtype'=>$detalhe_pedido->RowType));

		// Render row
		$detalhe_pedido_grid->RenderRow();

		// Render list options
		$detalhe_pedido_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detalhe_pedido_grid->RowAction <> "delete" && $detalhe_pedido_grid->RowAction <> "insertdelete" && !($detalhe_pedido_grid->RowAction == "insert" && $detalhe_pedido->CurrentAction == "F" && $detalhe_pedido_grid->EmptyRow())) {
?>
	<tr<?php echo $detalhe_pedido->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalhe_pedido_grid->ListOptions->Render("body", "left", $detalhe_pedido_grid->RowCnt);
?>
	<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
		<td data-name="id_detalhe"<?php echo $detalhe_pedido->id_detalhe->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_id_detalhe" class="form-group detalhe_pedido_id_detalhe">
<span<?php echo $detalhe_pedido->id_detalhe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->id_detalhe->EditValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->CurrentValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_id_detalhe" class="detalhe_pedido_id_detalhe">
<span<?php echo $detalhe_pedido->id_detalhe->ViewAttributes() ?>>
<?php echo $detalhe_pedido->id_detalhe->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
		<td data-name="numero_pedido"<?php echo $detalhe_pedido->numero_pedido->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detalhe_pedido->numero_pedido->getSessionValue() <> "") { ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->numero_pedido->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<input type="text" data-table="detalhe_pedido" data-field="x_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" size="30" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->numero_pedido->EditValue ?>"<?php echo $detalhe_pedido->numero_pedido->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detalhe_pedido->numero_pedido->getSessionValue() <> "") { ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->numero_pedido->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<input type="text" data-table="detalhe_pedido" data-field="x_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" size="30" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->numero_pedido->EditValue ?>"<?php echo $detalhe_pedido->numero_pedido->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_numero_pedido" class="detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<?php echo $detalhe_pedido->numero_pedido->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
		<td data-name="id_produto"<?php echo $detalhe_pedido->id_produto->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_id_produto" class="form-group detalhe_pedido_id_produto">
<?php $detalhe_pedido->id_produto->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$detalhe_pedido->id_produto->EditAttrs["onchange"]; ?>
<select data-table="detalhe_pedido" data-field="x_id_produto" data-value-separator="<?php echo $detalhe_pedido->id_produto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto"<?php echo $detalhe_pedido->id_produto->EditAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->id_produto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto',url:'produtosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->id_produto->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_id_produto" class="form-group detalhe_pedido_id_produto">
<?php $detalhe_pedido->id_produto->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$detalhe_pedido->id_produto->EditAttrs["onchange"]; ?>
<select data-table="detalhe_pedido" data-field="x_id_produto" data-value-separator="<?php echo $detalhe_pedido->id_produto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto"<?php echo $detalhe_pedido->id_produto->EditAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->id_produto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto',url:'produtosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->id_produto->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_id_produto" class="detalhe_pedido_id_produto">
<span<?php echo $detalhe_pedido->id_produto->ViewAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
		<td data-name="desconto"<?php echo $detalhe_pedido->desconto->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_desconto" class="form-group detalhe_pedido_desconto">
<select data-table="detalhe_pedido" data-field="x_desconto" data-value-separator="<?php echo $detalhe_pedido->desconto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto"<?php echo $detalhe_pedido->desconto->EditAttributes() ?>>
<?php echo $detalhe_pedido->desconto->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->desconto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto',url:'descontoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->desconto->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_desconto" class="form-group detalhe_pedido_desconto">
<select data-table="detalhe_pedido" data-field="x_desconto" data-value-separator="<?php echo $detalhe_pedido->desconto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto"<?php echo $detalhe_pedido->desconto->EditAttributes() ?>>
<?php echo $detalhe_pedido->desconto->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->desconto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto',url:'descontoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->desconto->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_desconto" class="detalhe_pedido_desconto">
<span<?php echo $detalhe_pedido->desconto->ViewAttributes() ?>>
<?php echo $detalhe_pedido->desconto->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
		<td data-name="preco"<?php echo $detalhe_pedido->preco->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_preco" class="form-group detalhe_pedido_preco">
<select data-table="detalhe_pedido" data-field="x_preco" data-value-separator="<?php echo $detalhe_pedido->preco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco"<?php echo $detalhe_pedido->preco->EditAttributes() ?>>
<?php echo $detalhe_pedido->preco->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco") ?>
</select>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_preco" class="form-group detalhe_pedido_preco">
<select data-table="detalhe_pedido" data-field="x_preco" data-value-separator="<?php echo $detalhe_pedido->preco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco"<?php echo $detalhe_pedido->preco->EditAttributes() ?>>
<?php echo $detalhe_pedido->preco->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco") ?>
</select>
</span>
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_preco" class="detalhe_pedido_preco">
<span<?php echo $detalhe_pedido->preco->ViewAttributes() ?>>
<?php echo $detalhe_pedido->preco->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
		<td data-name="quantidade"<?php echo $detalhe_pedido->quantidade->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_quantidade" class="form-group detalhe_pedido_quantidade">
<input type="text" data-table="detalhe_pedido" data-field="x_quantidade" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->quantidade->EditValue ?>"<?php echo $detalhe_pedido->quantidade->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_quantidade" class="form-group detalhe_pedido_quantidade">
<input type="text" data-table="detalhe_pedido" data-field="x_quantidade" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->quantidade->EditValue ?>"<?php echo $detalhe_pedido->quantidade->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_quantidade" class="detalhe_pedido_quantidade">
<span<?php echo $detalhe_pedido->quantidade->ViewAttributes() ?>>
<?php echo $detalhe_pedido->quantidade->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
		<td data-name="subtotal"<?php echo $detalhe_pedido->subtotal->CellAttributes() ?>>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_subtotal" class="form-group detalhe_pedido_subtotal">
<input type="text" data-table="detalhe_pedido" data-field="x_subtotal" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->subtotal->EditValue ?>"<?php echo $detalhe_pedido->subtotal->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->OldValue) ?>">
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_subtotal" class="form-group detalhe_pedido_subtotal">
<input type="text" data-table="detalhe_pedido" data-field="x_subtotal" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->subtotal->EditValue ?>"<?php echo $detalhe_pedido->subtotal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detalhe_pedido_grid->RowCnt ?>_detalhe_pedido_subtotal" class="detalhe_pedido_subtotal">
<span<?php echo $detalhe_pedido->subtotal->ViewAttributes() ?>>
<?php echo $detalhe_pedido->subtotal->ListViewValue() ?></span>
</span>
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="fdetalhe_pedidogrid$x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->FormValue) ?>">
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="fdetalhe_pedidogrid$o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detalhe_pedido_grid->ListOptions->Render("body", "right", $detalhe_pedido_grid->RowCnt);
?>
	</tr>
<?php if ($detalhe_pedido->RowType == EW_ROWTYPE_ADD || $detalhe_pedido->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetalhe_pedidogrid.UpdateOpts(<?php echo $detalhe_pedido_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detalhe_pedido->CurrentAction <> "gridadd" || $detalhe_pedido->CurrentMode == "copy")
		if (!$detalhe_pedido_grid->Recordset->EOF) $detalhe_pedido_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detalhe_pedido->CurrentMode == "add" || $detalhe_pedido->CurrentMode == "copy" || $detalhe_pedido->CurrentMode == "edit") {
		$detalhe_pedido_grid->RowIndex = '$rowindex$';
		$detalhe_pedido_grid->LoadRowValues();

		// Set row properties
		$detalhe_pedido->ResetAttrs();
		$detalhe_pedido->RowAttrs = array_merge($detalhe_pedido->RowAttrs, array('data-rowindex'=>$detalhe_pedido_grid->RowIndex, 'id'=>'r0_detalhe_pedido', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detalhe_pedido->RowAttrs["class"], "ewTemplate");
		$detalhe_pedido->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detalhe_pedido_grid->RenderRow();

		// Render list options
		$detalhe_pedido_grid->RenderListOptions();
		$detalhe_pedido_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detalhe_pedido->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalhe_pedido_grid->ListOptions->Render("body", "left", $detalhe_pedido_grid->RowIndex);
?>
	<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
		<td data-name="id_detalhe">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_id_detalhe" class="form-group detalhe_pedido_id_detalhe">
<span<?php echo $detalhe_pedido->id_detalhe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->id_detalhe->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
		<td data-name="numero_pedido">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<?php if ($detalhe_pedido->numero_pedido->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->numero_pedido->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<input type="text" data-table="detalhe_pedido" data-field="x_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" size="30" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->numero_pedido->EditValue ?>"<?php echo $detalhe_pedido->numero_pedido->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_numero_pedido" class="form-group detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->numero_pedido->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_numero_pedido" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
		<td data-name="id_produto">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalhe_pedido_id_produto" class="form-group detalhe_pedido_id_produto">
<?php $detalhe_pedido->id_produto->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$detalhe_pedido->id_produto->EditAttrs["onchange"]; ?>
<select data-table="detalhe_pedido" data-field="x_id_produto" data-value-separator="<?php echo $detalhe_pedido->id_produto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto"<?php echo $detalhe_pedido->id_produto->EditAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->id_produto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto',url:'produtosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->id_produto->FldCaption() ?></span></button>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_id_produto" class="form-group detalhe_pedido_id_produto">
<span<?php echo $detalhe_pedido->id_produto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->id_produto->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_produto" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_produto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
		<td data-name="desconto">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalhe_pedido_desconto" class="form-group detalhe_pedido_desconto">
<select data-table="detalhe_pedido" data-field="x_desconto" data-value-separator="<?php echo $detalhe_pedido->desconto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto"<?php echo $detalhe_pedido->desconto->EditAttributes() ?>>
<?php echo $detalhe_pedido->desconto->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->desconto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto',url:'descontoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->desconto->FldCaption() ?></span></button>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_desconto" class="form-group detalhe_pedido_desconto">
<span<?php echo $detalhe_pedido->desconto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->desconto->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_desconto" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_desconto" value="<?php echo ew_HtmlEncode($detalhe_pedido->desconto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
		<td data-name="preco">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalhe_pedido_preco" class="form-group detalhe_pedido_preco">
<select data-table="detalhe_pedido" data-field="x_preco" data-value-separator="<?php echo $detalhe_pedido->preco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco"<?php echo $detalhe_pedido->preco->EditAttributes() ?>>
<?php echo $detalhe_pedido->preco->SelectOptionListHtml("x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_preco" class="form-group detalhe_pedido_preco">
<span<?php echo $detalhe_pedido->preco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->preco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_preco" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_preco" value="<?php echo ew_HtmlEncode($detalhe_pedido->preco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
		<td data-name="quantidade">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalhe_pedido_quantidade" class="form-group detalhe_pedido_quantidade">
<input type="text" data-table="detalhe_pedido" data-field="x_quantidade" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->quantidade->EditValue ?>"<?php echo $detalhe_pedido->quantidade->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_quantidade" class="form-group detalhe_pedido_quantidade">
<span<?php echo $detalhe_pedido->quantidade->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->quantidade->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_quantidade" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_quantidade" value="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
		<td data-name="subtotal">
<?php if ($detalhe_pedido->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detalhe_pedido_subtotal" class="form-group detalhe_pedido_subtotal">
<input type="text" data-table="detalhe_pedido" data-field="x_subtotal" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->subtotal->EditValue ?>"<?php echo $detalhe_pedido->subtotal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detalhe_pedido_subtotal" class="form-group detalhe_pedido_subtotal">
<span<?php echo $detalhe_pedido->subtotal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->subtotal->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="x<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detalhe_pedido" data-field="x_subtotal" name="o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" id="o<?php echo $detalhe_pedido_grid->RowIndex ?>_subtotal" value="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detalhe_pedido_grid->ListOptions->Render("body", "right", $detalhe_pedido_grid->RowCnt);
?>
<script type="text/javascript">
fdetalhe_pedidogrid.UpdateOpts(<?php echo $detalhe_pedido_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detalhe_pedido->CurrentMode == "add" || $detalhe_pedido->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detalhe_pedido_grid->FormKeyCountName ?>" id="<?php echo $detalhe_pedido_grid->FormKeyCountName ?>" value="<?php echo $detalhe_pedido_grid->KeyCount ?>">
<?php echo $detalhe_pedido_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detalhe_pedido->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detalhe_pedido_grid->FormKeyCountName ?>" id="<?php echo $detalhe_pedido_grid->FormKeyCountName ?>" value="<?php echo $detalhe_pedido_grid->KeyCount ?>">
<?php echo $detalhe_pedido_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detalhe_pedido->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetalhe_pedidogrid">
</div>
<?php

// Close recordset
if ($detalhe_pedido_grid->Recordset)
	$detalhe_pedido_grid->Recordset->Close();
?>
<?php if ($detalhe_pedido_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($detalhe_pedido_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($detalhe_pedido_grid->TotalRecs == 0 && $detalhe_pedido->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detalhe_pedido_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detalhe_pedido->Export == "") { ?>
<script type="text/javascript">
fdetalhe_pedidogrid.Init();
</script>
<?php } ?>
<?php
$detalhe_pedido_grid->Page_Terminate();
?>
