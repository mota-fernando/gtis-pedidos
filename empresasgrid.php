<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($empresas_grid)) $empresas_grid = new cempresas_grid();

// Page init
$empresas_grid->Page_Init();

// Page main
$empresas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_grid->Page_Render();
?>
<?php if ($empresas->Export == "") { ?>
<script type="text/javascript">

// Form object
var fempresasgrid = new ew_Form("fempresasgrid", "grid");
fempresasgrid.FormKeyCountName = '<?php echo $empresas_grid->FormKeyCountName ?>';

// Validate form
fempresasgrid.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fempresasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "razao_social", false)) return false;
	if (ew_ValueChanged(fobj, infix, "proprietario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefone", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direcao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "_email", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_endereco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "endereco_numero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nome_fantasia", false)) return false;
	return true;
}

// Form_CustomValidate event
fempresasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fempresasgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fempresasgrid.Lists["x_direcao"] = {"LinkField":"x_nome_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_pessoa","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pessoa_fisica"};
fempresasgrid.Lists["x_direcao"].Data = "<?php echo $empresas_grid->direcao->LookupFilterQuery(FALSE, "grid") ?>";
fempresasgrid.AutoSuggests["x_direcao"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $empresas_grid->direcao->LookupFilterQuery(TRUE, "grid"))) ?>;
fempresasgrid.Lists["x_id_endereco"] = {"LinkField":"x_id_endereco","Ajax":true,"AutoFill":false,"DisplayFields":["x_endereco","x_bairro","x_estado","x_cidade"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"endereco"};
fempresasgrid.Lists["x_id_endereco"].Data = "<?php echo $empresas_grid->id_endereco->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($empresas->CurrentAction == "gridadd") {
	if ($empresas->CurrentMode == "copy") {
		$bSelectLimit = $empresas_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$empresas_grid->TotalRecs = $empresas->ListRecordCount();
			$empresas_grid->Recordset = $empresas_grid->LoadRecordset($empresas_grid->StartRec-1, $empresas_grid->DisplayRecs);
		} else {
			if ($empresas_grid->Recordset = $empresas_grid->LoadRecordset())
				$empresas_grid->TotalRecs = $empresas_grid->Recordset->RecordCount();
		}
		$empresas_grid->StartRec = 1;
		$empresas_grid->DisplayRecs = $empresas_grid->TotalRecs;
	} else {
		$empresas->CurrentFilter = "0=1";
		$empresas_grid->StartRec = 1;
		$empresas_grid->DisplayRecs = $empresas->GridAddRowCount;
	}
	$empresas_grid->TotalRecs = $empresas_grid->DisplayRecs;
	$empresas_grid->StopRec = $empresas_grid->DisplayRecs;
} else {
	$bSelectLimit = $empresas_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($empresas_grid->TotalRecs <= 0)
			$empresas_grid->TotalRecs = $empresas->ListRecordCount();
	} else {
		if (!$empresas_grid->Recordset && ($empresas_grid->Recordset = $empresas_grid->LoadRecordset()))
			$empresas_grid->TotalRecs = $empresas_grid->Recordset->RecordCount();
	}
	$empresas_grid->StartRec = 1;
	$empresas_grid->DisplayRecs = $empresas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$empresas_grid->Recordset = $empresas_grid->LoadRecordset($empresas_grid->StartRec-1, $empresas_grid->DisplayRecs);

	// Set no record found message
	if ($empresas->CurrentAction == "" && $empresas_grid->TotalRecs == 0) {
		if ($empresas_grid->SearchWhere == "0=101")
			$empresas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$empresas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$empresas_grid->RenderOtherOptions();
?>
<?php $empresas_grid->ShowPageHeader(); ?>
<?php
$empresas_grid->ShowMessage();
?>
<?php if ($empresas_grid->TotalRecs > 0 || $empresas->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($empresas_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> empresas">
<div id="fempresasgrid" class="ewForm ewListForm form-inline">
<?php if ($empresas_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($empresas_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_empresas" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_empresasgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$empresas_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$empresas_grid->RenderListOptions();

// Render list options (header, left)
$empresas_grid->ListOptions->Render("header", "left");
?>
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
	<?php if ($empresas->SortUrl($empresas->id_perfil) == "") { ?>
		<th data-name="id_perfil" class="<?php echo $empresas->id_perfil->HeaderCellClass() ?>"><div id="elh_empresas_id_perfil" class="empresas_id_perfil"><div class="ewTableHeaderCaption"><?php echo $empresas->id_perfil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_perfil" class="<?php echo $empresas->id_perfil->HeaderCellClass() ?>"><div><div id="elh_empresas_id_perfil" class="empresas_id_perfil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->id_perfil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->id_perfil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->id_perfil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
	<?php if ($empresas->SortUrl($empresas->razao_social) == "") { ?>
		<th data-name="razao_social" class="<?php echo $empresas->razao_social->HeaderCellClass() ?>"><div id="elh_empresas_razao_social" class="empresas_razao_social"><div class="ewTableHeaderCaption"><?php echo $empresas->razao_social->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="razao_social" class="<?php echo $empresas->razao_social->HeaderCellClass() ?>"><div><div id="elh_empresas_razao_social" class="empresas_razao_social">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->razao_social->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->razao_social->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->razao_social->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
	<?php if ($empresas->SortUrl($empresas->proprietario) == "") { ?>
		<th data-name="proprietario" class="<?php echo $empresas->proprietario->HeaderCellClass() ?>"><div id="elh_empresas_proprietario" class="empresas_proprietario"><div class="ewTableHeaderCaption"><?php echo $empresas->proprietario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="proprietario" class="<?php echo $empresas->proprietario->HeaderCellClass() ?>"><div><div id="elh_empresas_proprietario" class="empresas_proprietario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->proprietario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->proprietario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->proprietario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
	<?php if ($empresas->SortUrl($empresas->telefone) == "") { ?>
		<th data-name="telefone" class="<?php echo $empresas->telefone->HeaderCellClass() ?>"><div id="elh_empresas_telefone" class="empresas_telefone"><div class="ewTableHeaderCaption"><?php echo $empresas->telefone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefone" class="<?php echo $empresas->telefone->HeaderCellClass() ?>"><div><div id="elh_empresas_telefone" class="empresas_telefone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->telefone->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->telefone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->telefone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
	<?php if ($empresas->SortUrl($empresas->direcao) == "") { ?>
		<th data-name="direcao" class="<?php echo $empresas->direcao->HeaderCellClass() ?>"><div id="elh_empresas_direcao" class="empresas_direcao"><div class="ewTableHeaderCaption"><?php echo $empresas->direcao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direcao" class="<?php echo $empresas->direcao->HeaderCellClass() ?>"><div><div id="elh_empresas_direcao" class="empresas_direcao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->direcao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->direcao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->direcao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
	<?php if ($empresas->SortUrl($empresas->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $empresas->_email->HeaderCellClass() ?>"><div id="elh_empresas__email" class="empresas__email"><div class="ewTableHeaderCaption"><?php echo $empresas->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $empresas->_email->HeaderCellClass() ?>"><div><div id="elh_empresas__email" class="empresas__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
	<?php if ($empresas->SortUrl($empresas->id_endereco) == "") { ?>
		<th data-name="id_endereco" class="<?php echo $empresas->id_endereco->HeaderCellClass() ?>"><div id="elh_empresas_id_endereco" class="empresas_id_endereco"><div class="ewTableHeaderCaption"><?php echo $empresas->id_endereco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_endereco" class="<?php echo $empresas->id_endereco->HeaderCellClass() ?>"><div><div id="elh_empresas_id_endereco" class="empresas_id_endereco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->id_endereco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->id_endereco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->id_endereco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
	<?php if ($empresas->SortUrl($empresas->endereco_numero) == "") { ?>
		<th data-name="endereco_numero" class="<?php echo $empresas->endereco_numero->HeaderCellClass() ?>"><div id="elh_empresas_endereco_numero" class="empresas_endereco_numero"><div class="ewTableHeaderCaption"><?php echo $empresas->endereco_numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="endereco_numero" class="<?php echo $empresas->endereco_numero->HeaderCellClass() ?>"><div><div id="elh_empresas_endereco_numero" class="empresas_endereco_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->endereco_numero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->endereco_numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->endereco_numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
	<?php if ($empresas->SortUrl($empresas->nome_fantasia) == "") { ?>
		<th data-name="nome_fantasia" class="<?php echo $empresas->nome_fantasia->HeaderCellClass() ?>"><div id="elh_empresas_nome_fantasia" class="empresas_nome_fantasia"><div class="ewTableHeaderCaption"><?php echo $empresas->nome_fantasia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome_fantasia" class="<?php echo $empresas->nome_fantasia->HeaderCellClass() ?>"><div><div id="elh_empresas_nome_fantasia" class="empresas_nome_fantasia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->nome_fantasia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->nome_fantasia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->nome_fantasia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$empresas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$empresas_grid->StartRec = 1;
$empresas_grid->StopRec = $empresas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($empresas_grid->FormKeyCountName) && ($empresas->CurrentAction == "gridadd" || $empresas->CurrentAction == "gridedit" || $empresas->CurrentAction == "F")) {
		$empresas_grid->KeyCount = $objForm->GetValue($empresas_grid->FormKeyCountName);
		$empresas_grid->StopRec = $empresas_grid->StartRec + $empresas_grid->KeyCount - 1;
	}
}
$empresas_grid->RecCnt = $empresas_grid->StartRec - 1;
if ($empresas_grid->Recordset && !$empresas_grid->Recordset->EOF) {
	$empresas_grid->Recordset->MoveFirst();
	$bSelectLimit = $empresas_grid->UseSelectLimit;
	if (!$bSelectLimit && $empresas_grid->StartRec > 1)
		$empresas_grid->Recordset->Move($empresas_grid->StartRec - 1);
} elseif (!$empresas->AllowAddDeleteRow && $empresas_grid->StopRec == 0) {
	$empresas_grid->StopRec = $empresas->GridAddRowCount;
}

// Initialize aggregate
$empresas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$empresas->ResetAttrs();
$empresas_grid->RenderRow();
if ($empresas->CurrentAction == "gridadd")
	$empresas_grid->RowIndex = 0;
if ($empresas->CurrentAction == "gridedit")
	$empresas_grid->RowIndex = 0;
while ($empresas_grid->RecCnt < $empresas_grid->StopRec) {
	$empresas_grid->RecCnt++;
	if (intval($empresas_grid->RecCnt) >= intval($empresas_grid->StartRec)) {
		$empresas_grid->RowCnt++;
		if ($empresas->CurrentAction == "gridadd" || $empresas->CurrentAction == "gridedit" || $empresas->CurrentAction == "F") {
			$empresas_grid->RowIndex++;
			$objForm->Index = $empresas_grid->RowIndex;
			if ($objForm->HasValue($empresas_grid->FormActionName))
				$empresas_grid->RowAction = strval($objForm->GetValue($empresas_grid->FormActionName));
			elseif ($empresas->CurrentAction == "gridadd")
				$empresas_grid->RowAction = "insert";
			else
				$empresas_grid->RowAction = "";
		}

		// Set up key count
		$empresas_grid->KeyCount = $empresas_grid->RowIndex;

		// Init row class and style
		$empresas->ResetAttrs();
		$empresas->CssClass = "";
		if ($empresas->CurrentAction == "gridadd") {
			if ($empresas->CurrentMode == "copy") {
				$empresas_grid->LoadRowValues($empresas_grid->Recordset); // Load row values
				$empresas_grid->SetRecordKey($empresas_grid->RowOldKey, $empresas_grid->Recordset); // Set old record key
			} else {
				$empresas_grid->LoadRowValues(); // Load default values
				$empresas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$empresas_grid->LoadRowValues($empresas_grid->Recordset); // Load row values
		}
		$empresas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($empresas->CurrentAction == "gridadd") // Grid add
			$empresas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($empresas->CurrentAction == "gridadd" && $empresas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$empresas_grid->RestoreCurrentRowFormValues($empresas_grid->RowIndex); // Restore form values
		if ($empresas->CurrentAction == "gridedit") { // Grid edit
			if ($empresas->EventCancelled) {
				$empresas_grid->RestoreCurrentRowFormValues($empresas_grid->RowIndex); // Restore form values
			}
			if ($empresas_grid->RowAction == "insert")
				$empresas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$empresas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($empresas->CurrentAction == "gridedit" && ($empresas->RowType == EW_ROWTYPE_EDIT || $empresas->RowType == EW_ROWTYPE_ADD) && $empresas->EventCancelled) // Update failed
			$empresas_grid->RestoreCurrentRowFormValues($empresas_grid->RowIndex); // Restore form values
		if ($empresas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$empresas_grid->EditRowCnt++;
		if ($empresas->CurrentAction == "F") // Confirm row
			$empresas_grid->RestoreCurrentRowFormValues($empresas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$empresas->RowAttrs = array_merge($empresas->RowAttrs, array('data-rowindex'=>$empresas_grid->RowCnt, 'id'=>'r' . $empresas_grid->RowCnt . '_empresas', 'data-rowtype'=>$empresas->RowType));

		// Render row
		$empresas_grid->RenderRow();

		// Render list options
		$empresas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($empresas_grid->RowAction <> "delete" && $empresas_grid->RowAction <> "insertdelete" && !($empresas_grid->RowAction == "insert" && $empresas->CurrentAction == "F" && $empresas_grid->EmptyRow())) {
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empresas_grid->ListOptions->Render("body", "left", $empresas_grid->RowCnt);
?>
	<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
		<td data-name="id_perfil"<?php echo $empresas->id_perfil->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="o<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="o<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_id_perfil" class="form-group empresas_id_perfil">
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->id_perfil->EditValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="x<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="x<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->CurrentValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_id_perfil" class="empresas_id_perfil">
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<?php echo $empresas->id_perfil->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="x<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="x<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="o<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="o<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->razao_social->Visible) { // razao_social ?>
		<td data-name="razao_social"<?php echo $empresas->razao_social->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_razao_social" class="form-group empresas_razao_social">
<input type="text" data-table="empresas" data-field="x_razao_social" name="x<?php echo $empresas_grid->RowIndex ?>_razao_social" id="x<?php echo $empresas_grid->RowIndex ?>_razao_social" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->razao_social->getPlaceHolder()) ?>" value="<?php echo $empresas->razao_social->EditValue ?>"<?php echo $empresas->razao_social->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="o<?php echo $empresas_grid->RowIndex ?>_razao_social" id="o<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_razao_social" class="form-group empresas_razao_social">
<input type="text" data-table="empresas" data-field="x_razao_social" name="x<?php echo $empresas_grid->RowIndex ?>_razao_social" id="x<?php echo $empresas_grid->RowIndex ?>_razao_social" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->razao_social->getPlaceHolder()) ?>" value="<?php echo $empresas->razao_social->EditValue ?>"<?php echo $empresas->razao_social->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_razao_social" class="empresas_razao_social">
<span<?php echo $empresas->razao_social->ViewAttributes() ?>>
<?php echo $empresas->razao_social->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="x<?php echo $empresas_grid->RowIndex ?>_razao_social" id="x<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="o<?php echo $empresas_grid->RowIndex ?>_razao_social" id="o<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_razao_social" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_razao_social" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->proprietario->Visible) { // proprietario ?>
		<td data-name="proprietario"<?php echo $empresas->proprietario->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_proprietario" class="form-group empresas_proprietario">
<input type="text" data-table="empresas" data-field="x_proprietario" name="x<?php echo $empresas_grid->RowIndex ?>_proprietario" id="x<?php echo $empresas_grid->RowIndex ?>_proprietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->proprietario->getPlaceHolder()) ?>" value="<?php echo $empresas->proprietario->EditValue ?>"<?php echo $empresas->proprietario->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="o<?php echo $empresas_grid->RowIndex ?>_proprietario" id="o<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_proprietario" class="form-group empresas_proprietario">
<input type="text" data-table="empresas" data-field="x_proprietario" name="x<?php echo $empresas_grid->RowIndex ?>_proprietario" id="x<?php echo $empresas_grid->RowIndex ?>_proprietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->proprietario->getPlaceHolder()) ?>" value="<?php echo $empresas->proprietario->EditValue ?>"<?php echo $empresas->proprietario->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_proprietario" class="empresas_proprietario">
<span<?php echo $empresas->proprietario->ViewAttributes() ?>>
<?php echo $empresas->proprietario->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="x<?php echo $empresas_grid->RowIndex ?>_proprietario" id="x<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="o<?php echo $empresas_grid->RowIndex ?>_proprietario" id="o<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_proprietario" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_proprietario" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->telefone->Visible) { // telefone ?>
		<td data-name="telefone"<?php echo $empresas->telefone->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_telefone" class="form-group empresas_telefone">
<input type="text" data-table="empresas" data-field="x_telefone" name="x<?php echo $empresas_grid->RowIndex ?>_telefone" id="x<?php echo $empresas_grid->RowIndex ?>_telefone" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($empresas->telefone->getPlaceHolder()) ?>" value="<?php echo $empresas->telefone->EditValue ?>"<?php echo $empresas->telefone->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_telefone" name="o<?php echo $empresas_grid->RowIndex ?>_telefone" id="o<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_telefone" class="form-group empresas_telefone">
<input type="text" data-table="empresas" data-field="x_telefone" name="x<?php echo $empresas_grid->RowIndex ?>_telefone" id="x<?php echo $empresas_grid->RowIndex ?>_telefone" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($empresas->telefone->getPlaceHolder()) ?>" value="<?php echo $empresas->telefone->EditValue ?>"<?php echo $empresas->telefone->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_telefone" class="empresas_telefone">
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<?php echo $empresas->telefone->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_telefone" name="x<?php echo $empresas_grid->RowIndex ?>_telefone" id="x<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_telefone" name="o<?php echo $empresas_grid->RowIndex ?>_telefone" id="o<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_telefone" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_telefone" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_telefone" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_telefone" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->direcao->Visible) { // direcao ?>
		<td data-name="direcao"<?php echo $empresas->direcao->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_direcao" class="form-group empresas_direcao">
<?php
$wrkonchange = trim(" " . @$empresas->direcao->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$empresas->direcao->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $empresas_grid->RowIndex ?>_direcao" style="white-space: nowrap; z-index: <?php echo (9000 - $empresas_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $empresas_grid->RowIndex ?>_direcao" id="sv_x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo $empresas->direcao->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>"<?php echo $empresas->direcao->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_direcao" data-value-separator="<?php echo $empresas->direcao->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $empresas_grid->RowIndex ?>_direcao" id="x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fempresasgrid.CreateAutoSuggest({"id":"x<?php echo $empresas_grid->RowIndex ?>_direcao","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->direcao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $empresas_grid->RowIndex ?>_direcao',url:'pessoa_fisicaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $empresas_grid->RowIndex ?>_direcao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->direcao->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="empresas" data-field="x_direcao" name="o<?php echo $empresas_grid->RowIndex ?>_direcao" id="o<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_direcao" class="form-group empresas_direcao">
<?php
$wrkonchange = trim(" " . @$empresas->direcao->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$empresas->direcao->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $empresas_grid->RowIndex ?>_direcao" style="white-space: nowrap; z-index: <?php echo (9000 - $empresas_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $empresas_grid->RowIndex ?>_direcao" id="sv_x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo $empresas->direcao->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>"<?php echo $empresas->direcao->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_direcao" data-value-separator="<?php echo $empresas->direcao->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $empresas_grid->RowIndex ?>_direcao" id="x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fempresasgrid.CreateAutoSuggest({"id":"x<?php echo $empresas_grid->RowIndex ?>_direcao","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->direcao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $empresas_grid->RowIndex ?>_direcao',url:'pessoa_fisicaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $empresas_grid->RowIndex ?>_direcao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->direcao->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_direcao" class="empresas_direcao">
<span<?php echo $empresas->direcao->ViewAttributes() ?>>
<?php echo $empresas->direcao->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_direcao" name="x<?php echo $empresas_grid->RowIndex ?>_direcao" id="x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_direcao" name="o<?php echo $empresas_grid->RowIndex ?>_direcao" id="o<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_direcao" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_direcao" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_direcao" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_direcao" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $empresas->_email->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas__email" class="form-group empresas__email">
<input type="text" data-table="empresas" data-field="x__email" name="x<?php echo $empresas_grid->RowIndex ?>__email" id="x<?php echo $empresas_grid->RowIndex ?>__email" size="30" maxlength="64" placeholder="<?php echo ew_HtmlEncode($empresas->_email->getPlaceHolder()) ?>" value="<?php echo $empresas->_email->EditValue ?>"<?php echo $empresas->_email->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x__email" name="o<?php echo $empresas_grid->RowIndex ?>__email" id="o<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas__email" class="form-group empresas__email">
<input type="text" data-table="empresas" data-field="x__email" name="x<?php echo $empresas_grid->RowIndex ?>__email" id="x<?php echo $empresas_grid->RowIndex ?>__email" size="30" maxlength="64" placeholder="<?php echo ew_HtmlEncode($empresas->_email->getPlaceHolder()) ?>" value="<?php echo $empresas->_email->EditValue ?>"<?php echo $empresas->_email->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas__email" class="empresas__email">
<span<?php echo $empresas->_email->ViewAttributes() ?>>
<?php echo $empresas->_email->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x__email" name="x<?php echo $empresas_grid->RowIndex ?>__email" id="x<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x__email" name="o<?php echo $empresas_grid->RowIndex ?>__email" id="o<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x__email" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>__email" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x__email" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>__email" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
		<td data-name="id_endereco"<?php echo $empresas->id_endereco->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_id_endereco" class="form-group empresas_id_endereco">
<select data-table="empresas" data-field="x_id_endereco" data-value-separator="<?php echo $empresas->id_endereco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" name="x<?php echo $empresas_grid->RowIndex ?>_id_endereco"<?php echo $empresas->id_endereco->EditAttributes() ?>>
<?php echo $empresas->id_endereco->SelectOptionListHtml("x<?php echo $empresas_grid->RowIndex ?>_id_endereco") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->id_endereco->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $empresas_grid->RowIndex ?>_id_endereco',url:'enderecoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $empresas_grid->RowIndex ?>_id_endereco"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->id_endereco->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="o<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="o<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_id_endereco" class="form-group empresas_id_endereco">
<select data-table="empresas" data-field="x_id_endereco" data-value-separator="<?php echo $empresas->id_endereco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" name="x<?php echo $empresas_grid->RowIndex ?>_id_endereco"<?php echo $empresas->id_endereco->EditAttributes() ?>>
<?php echo $empresas->id_endereco->SelectOptionListHtml("x<?php echo $empresas_grid->RowIndex ?>_id_endereco") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->id_endereco->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $empresas_grid->RowIndex ?>_id_endereco',url:'enderecoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $empresas_grid->RowIndex ?>_id_endereco"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->id_endereco->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_id_endereco" class="empresas_id_endereco">
<span<?php echo $empresas->id_endereco->ViewAttributes() ?>>
<?php echo $empresas->id_endereco->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="o<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="o<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
		<td data-name="endereco_numero"<?php echo $empresas->endereco_numero->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_endereco_numero" class="form-group empresas_endereco_numero">
<input type="text" data-table="empresas" data-field="x_endereco_numero" name="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($empresas->endereco_numero->getPlaceHolder()) ?>" value="<?php echo $empresas->endereco_numero->EditValue ?>"<?php echo $empresas->endereco_numero->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_endereco_numero" class="form-group empresas_endereco_numero">
<input type="text" data-table="empresas" data-field="x_endereco_numero" name="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($empresas->endereco_numero->getPlaceHolder()) ?>" value="<?php echo $empresas->endereco_numero->EditValue ?>"<?php echo $empresas->endereco_numero->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_endereco_numero" class="empresas_endereco_numero">
<span<?php echo $empresas->endereco_numero->ViewAttributes() ?>>
<?php echo $empresas->endereco_numero->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
		<td data-name="nome_fantasia"<?php echo $empresas->nome_fantasia->CellAttributes() ?>>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_nome_fantasia" class="form-group empresas_nome_fantasia">
<input type="text" data-table="empresas" data-field="x_nome_fantasia" name="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->nome_fantasia->getPlaceHolder()) ?>" value="<?php echo $empresas->nome_fantasia->EditValue ?>"<?php echo $empresas->nome_fantasia->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->OldValue) ?>">
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_nome_fantasia" class="form-group empresas_nome_fantasia">
<input type="text" data-table="empresas" data-field="x_nome_fantasia" name="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->nome_fantasia->getPlaceHolder()) ?>" value="<?php echo $empresas->nome_fantasia->EditValue ?>"<?php echo $empresas->nome_fantasia->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empresas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $empresas_grid->RowCnt ?>_empresas_nome_fantasia" class="empresas_nome_fantasia">
<span<?php echo $empresas->nome_fantasia->ViewAttributes() ?>>
<?php echo $empresas->nome_fantasia->ListViewValue() ?></span>
</span>
<?php if ($empresas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="fempresasgrid$x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->FormValue) ?>">
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="fempresasgrid$o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empresas_grid->ListOptions->Render("body", "right", $empresas_grid->RowCnt);
?>
	</tr>
<?php if ($empresas->RowType == EW_ROWTYPE_ADD || $empresas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fempresasgrid.UpdateOpts(<?php echo $empresas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($empresas->CurrentAction <> "gridadd" || $empresas->CurrentMode == "copy")
		if (!$empresas_grid->Recordset->EOF) $empresas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($empresas->CurrentMode == "add" || $empresas->CurrentMode == "copy" || $empresas->CurrentMode == "edit") {
		$empresas_grid->RowIndex = '$rowindex$';
		$empresas_grid->LoadRowValues();

		// Set row properties
		$empresas->ResetAttrs();
		$empresas->RowAttrs = array_merge($empresas->RowAttrs, array('data-rowindex'=>$empresas_grid->RowIndex, 'id'=>'r0_empresas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($empresas->RowAttrs["class"], "ewTemplate");
		$empresas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$empresas_grid->RenderRow();

		// Render list options
		$empresas_grid->RenderListOptions();
		$empresas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empresas_grid->ListOptions->Render("body", "left", $empresas_grid->RowIndex);
?>
	<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
		<td data-name="id_perfil">
<?php if ($empresas->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_empresas_id_perfil" class="form-group empresas_id_perfil">
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->id_perfil->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="x<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="x<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="o<?php echo $empresas_grid->RowIndex ?>_id_perfil" id="o<?php echo $empresas_grid->RowIndex ?>_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->razao_social->Visible) { // razao_social ?>
		<td data-name="razao_social">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_razao_social" class="form-group empresas_razao_social">
<input type="text" data-table="empresas" data-field="x_razao_social" name="x<?php echo $empresas_grid->RowIndex ?>_razao_social" id="x<?php echo $empresas_grid->RowIndex ?>_razao_social" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->razao_social->getPlaceHolder()) ?>" value="<?php echo $empresas->razao_social->EditValue ?>"<?php echo $empresas->razao_social->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_razao_social" class="form-group empresas_razao_social">
<span<?php echo $empresas->razao_social->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->razao_social->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="x<?php echo $empresas_grid->RowIndex ?>_razao_social" id="x<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_razao_social" name="o<?php echo $empresas_grid->RowIndex ?>_razao_social" id="o<?php echo $empresas_grid->RowIndex ?>_razao_social" value="<?php echo ew_HtmlEncode($empresas->razao_social->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->proprietario->Visible) { // proprietario ?>
		<td data-name="proprietario">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_proprietario" class="form-group empresas_proprietario">
<input type="text" data-table="empresas" data-field="x_proprietario" name="x<?php echo $empresas_grid->RowIndex ?>_proprietario" id="x<?php echo $empresas_grid->RowIndex ?>_proprietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->proprietario->getPlaceHolder()) ?>" value="<?php echo $empresas->proprietario->EditValue ?>"<?php echo $empresas->proprietario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_proprietario" class="form-group empresas_proprietario">
<span<?php echo $empresas->proprietario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->proprietario->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="x<?php echo $empresas_grid->RowIndex ?>_proprietario" id="x<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_proprietario" name="o<?php echo $empresas_grid->RowIndex ?>_proprietario" id="o<?php echo $empresas_grid->RowIndex ?>_proprietario" value="<?php echo ew_HtmlEncode($empresas->proprietario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->telefone->Visible) { // telefone ?>
		<td data-name="telefone">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_telefone" class="form-group empresas_telefone">
<input type="text" data-table="empresas" data-field="x_telefone" name="x<?php echo $empresas_grid->RowIndex ?>_telefone" id="x<?php echo $empresas_grid->RowIndex ?>_telefone" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($empresas->telefone->getPlaceHolder()) ?>" value="<?php echo $empresas->telefone->EditValue ?>"<?php echo $empresas->telefone->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_telefone" class="form-group empresas_telefone">
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->telefone->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_telefone" name="x<?php echo $empresas_grid->RowIndex ?>_telefone" id="x<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_telefone" name="o<?php echo $empresas_grid->RowIndex ?>_telefone" id="o<?php echo $empresas_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($empresas->telefone->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->direcao->Visible) { // direcao ?>
		<td data-name="direcao">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_direcao" class="form-group empresas_direcao">
<?php
$wrkonchange = trim(" " . @$empresas->direcao->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$empresas->direcao->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $empresas_grid->RowIndex ?>_direcao" style="white-space: nowrap; z-index: <?php echo (9000 - $empresas_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $empresas_grid->RowIndex ?>_direcao" id="sv_x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo $empresas->direcao->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>"<?php echo $empresas->direcao->EditAttributes() ?>>
</span>
<input type="hidden" data-table="empresas" data-field="x_direcao" data-value-separator="<?php echo $empresas->direcao->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $empresas_grid->RowIndex ?>_direcao" id="x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fempresasgrid.CreateAutoSuggest({"id":"x<?php echo $empresas_grid->RowIndex ?>_direcao","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->direcao->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $empresas_grid->RowIndex ?>_direcao',url:'pessoa_fisicaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $empresas_grid->RowIndex ?>_direcao"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->direcao->FldCaption() ?></span></button>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_direcao" class="form-group empresas_direcao">
<span<?php echo $empresas->direcao->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->direcao->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_direcao" name="x<?php echo $empresas_grid->RowIndex ?>_direcao" id="x<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_direcao" name="o<?php echo $empresas_grid->RowIndex ?>_direcao" id="o<?php echo $empresas_grid->RowIndex ?>_direcao" value="<?php echo ew_HtmlEncode($empresas->direcao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->_email->Visible) { // email ?>
		<td data-name="_email">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas__email" class="form-group empresas__email">
<input type="text" data-table="empresas" data-field="x__email" name="x<?php echo $empresas_grid->RowIndex ?>__email" id="x<?php echo $empresas_grid->RowIndex ?>__email" size="30" maxlength="64" placeholder="<?php echo ew_HtmlEncode($empresas->_email->getPlaceHolder()) ?>" value="<?php echo $empresas->_email->EditValue ?>"<?php echo $empresas->_email->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas__email" class="form-group empresas__email">
<span<?php echo $empresas->_email->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->_email->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x__email" name="x<?php echo $empresas_grid->RowIndex ?>__email" id="x<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x__email" name="o<?php echo $empresas_grid->RowIndex ?>__email" id="o<?php echo $empresas_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($empresas->_email->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
		<td data-name="id_endereco">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_id_endereco" class="form-group empresas_id_endereco">
<select data-table="empresas" data-field="x_id_endereco" data-value-separator="<?php echo $empresas->id_endereco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" name="x<?php echo $empresas_grid->RowIndex ?>_id_endereco"<?php echo $empresas->id_endereco->EditAttributes() ?>>
<?php echo $empresas->id_endereco->SelectOptionListHtml("x<?php echo $empresas_grid->RowIndex ?>_id_endereco") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->id_endereco->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $empresas_grid->RowIndex ?>_id_endereco',url:'enderecoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $empresas_grid->RowIndex ?>_id_endereco"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->id_endereco->FldCaption() ?></span></button>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_id_endereco" class="form-group empresas_id_endereco">
<span<?php echo $empresas->id_endereco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->id_endereco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="x<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_id_endereco" name="o<?php echo $empresas_grid->RowIndex ?>_id_endereco" id="o<?php echo $empresas_grid->RowIndex ?>_id_endereco" value="<?php echo ew_HtmlEncode($empresas->id_endereco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
		<td data-name="endereco_numero">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_endereco_numero" class="form-group empresas_endereco_numero">
<input type="text" data-table="empresas" data-field="x_endereco_numero" name="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($empresas->endereco_numero->getPlaceHolder()) ?>" value="<?php echo $empresas->endereco_numero->EditValue ?>"<?php echo $empresas->endereco_numero->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_endereco_numero" class="form-group empresas_endereco_numero">
<span<?php echo $empresas->endereco_numero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->endereco_numero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="x<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_endereco_numero" name="o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" id="o<?php echo $empresas_grid->RowIndex ?>_endereco_numero" value="<?php echo ew_HtmlEncode($empresas->endereco_numero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
		<td data-name="nome_fantasia">
<?php if ($empresas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empresas_nome_fantasia" class="form-group empresas_nome_fantasia">
<input type="text" data-table="empresas" data-field="x_nome_fantasia" name="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->nome_fantasia->getPlaceHolder()) ?>" value="<?php echo $empresas->nome_fantasia->EditValue ?>"<?php echo $empresas->nome_fantasia->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empresas_nome_fantasia" class="form-group empresas_nome_fantasia">
<span<?php echo $empresas->nome_fantasia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->nome_fantasia->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="x<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="empresas" data-field="x_nome_fantasia" name="o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" id="o<?php echo $empresas_grid->RowIndex ?>_nome_fantasia" value="<?php echo ew_HtmlEncode($empresas->nome_fantasia->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empresas_grid->ListOptions->Render("body", "right", $empresas_grid->RowIndex);
?>
<script type="text/javascript">
fempresasgrid.UpdateOpts(<?php echo $empresas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($empresas->CurrentMode == "add" || $empresas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $empresas_grid->FormKeyCountName ?>" id="<?php echo $empresas_grid->FormKeyCountName ?>" value="<?php echo $empresas_grid->KeyCount ?>">
<?php echo $empresas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($empresas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $empresas_grid->FormKeyCountName ?>" id="<?php echo $empresas_grid->FormKeyCountName ?>" value="<?php echo $empresas_grid->KeyCount ?>">
<?php echo $empresas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($empresas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fempresasgrid">
</div>
<?php

// Close recordset
if ($empresas_grid->Recordset)
	$empresas_grid->Recordset->Close();
?>
<?php if ($empresas_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($empresas_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($empresas_grid->TotalRecs == 0 && $empresas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empresas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($empresas->Export == "") { ?>
<script type="text/javascript">
fempresasgrid.Init();
</script>
<?php } ?>
<?php
$empresas_grid->Page_Terminate();
?>
