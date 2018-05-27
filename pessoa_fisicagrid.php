<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pessoa_fisica_grid)) $pessoa_fisica_grid = new cpessoa_fisica_grid();

// Page init
$pessoa_fisica_grid->Page_Init();

// Page main
$pessoa_fisica_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoa_fisica_grid->Page_Render();
?>
<?php if ($pessoa_fisica->Export == "") { ?>
<script type="text/javascript">

// Form object
var fpessoa_fisicagrid = new ew_Form("fpessoa_fisicagrid", "grid");
fpessoa_fisicagrid.FormKeyCountName = '<?php echo $pessoa_fisica_grid->FormKeyCountName ?>';

// Validate form
fpessoa_fisicagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_telefone");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->telefone->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_celular");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->celular->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fpessoa_fisicagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nome_pessoa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "sobrenome_pessoa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefone", false)) return false;
	if (ew_ValueChanged(fobj, infix, "_email", false)) return false;
	if (ew_ValueChanged(fobj, infix, "celular", false)) return false;
	return true;
}

// Form_CustomValidate event
fpessoa_fisicagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpessoa_fisicagrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($pessoa_fisica->CurrentAction == "gridadd") {
	if ($pessoa_fisica->CurrentMode == "copy") {
		$bSelectLimit = $pessoa_fisica_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$pessoa_fisica_grid->TotalRecs = $pessoa_fisica->ListRecordCount();
			$pessoa_fisica_grid->Recordset = $pessoa_fisica_grid->LoadRecordset($pessoa_fisica_grid->StartRec-1, $pessoa_fisica_grid->DisplayRecs);
		} else {
			if ($pessoa_fisica_grid->Recordset = $pessoa_fisica_grid->LoadRecordset())
				$pessoa_fisica_grid->TotalRecs = $pessoa_fisica_grid->Recordset->RecordCount();
		}
		$pessoa_fisica_grid->StartRec = 1;
		$pessoa_fisica_grid->DisplayRecs = $pessoa_fisica_grid->TotalRecs;
	} else {
		$pessoa_fisica->CurrentFilter = "0=1";
		$pessoa_fisica_grid->StartRec = 1;
		$pessoa_fisica_grid->DisplayRecs = $pessoa_fisica->GridAddRowCount;
	}
	$pessoa_fisica_grid->TotalRecs = $pessoa_fisica_grid->DisplayRecs;
	$pessoa_fisica_grid->StopRec = $pessoa_fisica_grid->DisplayRecs;
} else {
	$bSelectLimit = $pessoa_fisica_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pessoa_fisica_grid->TotalRecs <= 0)
			$pessoa_fisica_grid->TotalRecs = $pessoa_fisica->ListRecordCount();
	} else {
		if (!$pessoa_fisica_grid->Recordset && ($pessoa_fisica_grid->Recordset = $pessoa_fisica_grid->LoadRecordset()))
			$pessoa_fisica_grid->TotalRecs = $pessoa_fisica_grid->Recordset->RecordCount();
	}
	$pessoa_fisica_grid->StartRec = 1;
	$pessoa_fisica_grid->DisplayRecs = $pessoa_fisica_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pessoa_fisica_grid->Recordset = $pessoa_fisica_grid->LoadRecordset($pessoa_fisica_grid->StartRec-1, $pessoa_fisica_grid->DisplayRecs);

	// Set no record found message
	if ($pessoa_fisica->CurrentAction == "" && $pessoa_fisica_grid->TotalRecs == 0) {
		if ($pessoa_fisica_grid->SearchWhere == "0=101")
			$pessoa_fisica_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pessoa_fisica_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$pessoa_fisica_grid->RenderOtherOptions();
?>
<?php $pessoa_fisica_grid->ShowPageHeader(); ?>
<?php
$pessoa_fisica_grid->ShowMessage();
?>
<?php if ($pessoa_fisica_grid->TotalRecs > 0 || $pessoa_fisica->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($pessoa_fisica_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> pessoa_fisica">
<div id="fpessoa_fisicagrid" class="ewForm ewListForm form-inline">
<?php if ($pessoa_fisica_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($pessoa_fisica_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_pessoa_fisica" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_pessoa_fisicagrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$pessoa_fisica_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pessoa_fisica_grid->RenderListOptions();

// Render list options (header, left)
$pessoa_fisica_grid->ListOptions->Render("header", "left");
?>
<?php if ($pessoa_fisica->id_pessoa->Visible) { // id_pessoa ?>
	<?php if ($pessoa_fisica->SortUrl($pessoa_fisica->id_pessoa) == "") { ?>
		<th data-name="id_pessoa" class="<?php echo $pessoa_fisica->id_pessoa->HeaderCellClass() ?>"><div id="elh_pessoa_fisica_id_pessoa" class="pessoa_fisica_id_pessoa"><div class="ewTableHeaderCaption"><?php echo $pessoa_fisica->id_pessoa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_pessoa" class="<?php echo $pessoa_fisica->id_pessoa->HeaderCellClass() ?>"><div><div id="elh_pessoa_fisica_id_pessoa" class="pessoa_fisica_id_pessoa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa_fisica->id_pessoa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa_fisica->id_pessoa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa_fisica->id_pessoa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pessoa_fisica->nome_pessoa->Visible) { // nome_pessoa ?>
	<?php if ($pessoa_fisica->SortUrl($pessoa_fisica->nome_pessoa) == "") { ?>
		<th data-name="nome_pessoa" class="<?php echo $pessoa_fisica->nome_pessoa->HeaderCellClass() ?>"><div id="elh_pessoa_fisica_nome_pessoa" class="pessoa_fisica_nome_pessoa"><div class="ewTableHeaderCaption"><?php echo $pessoa_fisica->nome_pessoa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome_pessoa" class="<?php echo $pessoa_fisica->nome_pessoa->HeaderCellClass() ?>"><div><div id="elh_pessoa_fisica_nome_pessoa" class="pessoa_fisica_nome_pessoa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa_fisica->nome_pessoa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa_fisica->nome_pessoa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa_fisica->nome_pessoa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pessoa_fisica->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
	<?php if ($pessoa_fisica->SortUrl($pessoa_fisica->sobrenome_pessoa) == "") { ?>
		<th data-name="sobrenome_pessoa" class="<?php echo $pessoa_fisica->sobrenome_pessoa->HeaderCellClass() ?>"><div id="elh_pessoa_fisica_sobrenome_pessoa" class="pessoa_fisica_sobrenome_pessoa"><div class="ewTableHeaderCaption"><?php echo $pessoa_fisica->sobrenome_pessoa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sobrenome_pessoa" class="<?php echo $pessoa_fisica->sobrenome_pessoa->HeaderCellClass() ?>"><div><div id="elh_pessoa_fisica_sobrenome_pessoa" class="pessoa_fisica_sobrenome_pessoa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa_fisica->sobrenome_pessoa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa_fisica->sobrenome_pessoa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa_fisica->sobrenome_pessoa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pessoa_fisica->telefone->Visible) { // telefone ?>
	<?php if ($pessoa_fisica->SortUrl($pessoa_fisica->telefone) == "") { ?>
		<th data-name="telefone" class="<?php echo $pessoa_fisica->telefone->HeaderCellClass() ?>"><div id="elh_pessoa_fisica_telefone" class="pessoa_fisica_telefone"><div class="ewTableHeaderCaption"><?php echo $pessoa_fisica->telefone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefone" class="<?php echo $pessoa_fisica->telefone->HeaderCellClass() ?>"><div><div id="elh_pessoa_fisica_telefone" class="pessoa_fisica_telefone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa_fisica->telefone->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa_fisica->telefone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa_fisica->telefone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pessoa_fisica->_email->Visible) { // email ?>
	<?php if ($pessoa_fisica->SortUrl($pessoa_fisica->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $pessoa_fisica->_email->HeaderCellClass() ?>"><div id="elh_pessoa_fisica__email" class="pessoa_fisica__email"><div class="ewTableHeaderCaption"><?php echo $pessoa_fisica->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $pessoa_fisica->_email->HeaderCellClass() ?>"><div><div id="elh_pessoa_fisica__email" class="pessoa_fisica__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa_fisica->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa_fisica->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa_fisica->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pessoa_fisica->celular->Visible) { // celular ?>
	<?php if ($pessoa_fisica->SortUrl($pessoa_fisica->celular) == "") { ?>
		<th data-name="celular" class="<?php echo $pessoa_fisica->celular->HeaderCellClass() ?>"><div id="elh_pessoa_fisica_celular" class="pessoa_fisica_celular"><div class="ewTableHeaderCaption"><?php echo $pessoa_fisica->celular->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="celular" class="<?php echo $pessoa_fisica->celular->HeaderCellClass() ?>"><div><div id="elh_pessoa_fisica_celular" class="pessoa_fisica_celular">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa_fisica->celular->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa_fisica->celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa_fisica->celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$pessoa_fisica_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pessoa_fisica_grid->StartRec = 1;
$pessoa_fisica_grid->StopRec = $pessoa_fisica_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pessoa_fisica_grid->FormKeyCountName) && ($pessoa_fisica->CurrentAction == "gridadd" || $pessoa_fisica->CurrentAction == "gridedit" || $pessoa_fisica->CurrentAction == "F")) {
		$pessoa_fisica_grid->KeyCount = $objForm->GetValue($pessoa_fisica_grid->FormKeyCountName);
		$pessoa_fisica_grid->StopRec = $pessoa_fisica_grid->StartRec + $pessoa_fisica_grid->KeyCount - 1;
	}
}
$pessoa_fisica_grid->RecCnt = $pessoa_fisica_grid->StartRec - 1;
if ($pessoa_fisica_grid->Recordset && !$pessoa_fisica_grid->Recordset->EOF) {
	$pessoa_fisica_grid->Recordset->MoveFirst();
	$bSelectLimit = $pessoa_fisica_grid->UseSelectLimit;
	if (!$bSelectLimit && $pessoa_fisica_grid->StartRec > 1)
		$pessoa_fisica_grid->Recordset->Move($pessoa_fisica_grid->StartRec - 1);
} elseif (!$pessoa_fisica->AllowAddDeleteRow && $pessoa_fisica_grid->StopRec == 0) {
	$pessoa_fisica_grid->StopRec = $pessoa_fisica->GridAddRowCount;
}

// Initialize aggregate
$pessoa_fisica->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pessoa_fisica->ResetAttrs();
$pessoa_fisica_grid->RenderRow();
if ($pessoa_fisica->CurrentAction == "gridadd")
	$pessoa_fisica_grid->RowIndex = 0;
if ($pessoa_fisica->CurrentAction == "gridedit")
	$pessoa_fisica_grid->RowIndex = 0;
while ($pessoa_fisica_grid->RecCnt < $pessoa_fisica_grid->StopRec) {
	$pessoa_fisica_grid->RecCnt++;
	if (intval($pessoa_fisica_grid->RecCnt) >= intval($pessoa_fisica_grid->StartRec)) {
		$pessoa_fisica_grid->RowCnt++;
		if ($pessoa_fisica->CurrentAction == "gridadd" || $pessoa_fisica->CurrentAction == "gridedit" || $pessoa_fisica->CurrentAction == "F") {
			$pessoa_fisica_grid->RowIndex++;
			$objForm->Index = $pessoa_fisica_grid->RowIndex;
			if ($objForm->HasValue($pessoa_fisica_grid->FormActionName))
				$pessoa_fisica_grid->RowAction = strval($objForm->GetValue($pessoa_fisica_grid->FormActionName));
			elseif ($pessoa_fisica->CurrentAction == "gridadd")
				$pessoa_fisica_grid->RowAction = "insert";
			else
				$pessoa_fisica_grid->RowAction = "";
		}

		// Set up key count
		$pessoa_fisica_grid->KeyCount = $pessoa_fisica_grid->RowIndex;

		// Init row class and style
		$pessoa_fisica->ResetAttrs();
		$pessoa_fisica->CssClass = "";
		if ($pessoa_fisica->CurrentAction == "gridadd") {
			if ($pessoa_fisica->CurrentMode == "copy") {
				$pessoa_fisica_grid->LoadRowValues($pessoa_fisica_grid->Recordset); // Load row values
				$pessoa_fisica_grid->SetRecordKey($pessoa_fisica_grid->RowOldKey, $pessoa_fisica_grid->Recordset); // Set old record key
			} else {
				$pessoa_fisica_grid->LoadRowValues(); // Load default values
				$pessoa_fisica_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pessoa_fisica_grid->LoadRowValues($pessoa_fisica_grid->Recordset); // Load row values
		}
		$pessoa_fisica->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pessoa_fisica->CurrentAction == "gridadd") // Grid add
			$pessoa_fisica->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pessoa_fisica->CurrentAction == "gridadd" && $pessoa_fisica->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pessoa_fisica_grid->RestoreCurrentRowFormValues($pessoa_fisica_grid->RowIndex); // Restore form values
		if ($pessoa_fisica->CurrentAction == "gridedit") { // Grid edit
			if ($pessoa_fisica->EventCancelled) {
				$pessoa_fisica_grid->RestoreCurrentRowFormValues($pessoa_fisica_grid->RowIndex); // Restore form values
			}
			if ($pessoa_fisica_grid->RowAction == "insert")
				$pessoa_fisica->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pessoa_fisica->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pessoa_fisica->CurrentAction == "gridedit" && ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT || $pessoa_fisica->RowType == EW_ROWTYPE_ADD) && $pessoa_fisica->EventCancelled) // Update failed
			$pessoa_fisica_grid->RestoreCurrentRowFormValues($pessoa_fisica_grid->RowIndex); // Restore form values
		if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pessoa_fisica_grid->EditRowCnt++;
		if ($pessoa_fisica->CurrentAction == "F") // Confirm row
			$pessoa_fisica_grid->RestoreCurrentRowFormValues($pessoa_fisica_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pessoa_fisica->RowAttrs = array_merge($pessoa_fisica->RowAttrs, array('data-rowindex'=>$pessoa_fisica_grid->RowCnt, 'id'=>'r' . $pessoa_fisica_grid->RowCnt . '_pessoa_fisica', 'data-rowtype'=>$pessoa_fisica->RowType));

		// Render row
		$pessoa_fisica_grid->RenderRow();

		// Render list options
		$pessoa_fisica_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pessoa_fisica_grid->RowAction <> "delete" && $pessoa_fisica_grid->RowAction <> "insertdelete" && !($pessoa_fisica_grid->RowAction == "insert" && $pessoa_fisica->CurrentAction == "F" && $pessoa_fisica_grid->EmptyRow())) {
?>
	<tr<?php echo $pessoa_fisica->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pessoa_fisica_grid->ListOptions->Render("body", "left", $pessoa_fisica_grid->RowCnt);
?>
	<?php if ($pessoa_fisica->id_pessoa->Visible) { // id_pessoa ?>
		<td data-name="id_pessoa"<?php echo $pessoa_fisica->id_pessoa->CellAttributes() ?>>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->OldValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_id_pessoa" class="form-group pessoa_fisica_id_pessoa">
<span<?php echo $pessoa_fisica->id_pessoa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->id_pessoa->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->CurrentValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_id_pessoa" class="pessoa_fisica_id_pessoa">
<span<?php echo $pessoa_fisica->id_pessoa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->id_pessoa->ListViewValue() ?></span>
</span>
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->nome_pessoa->Visible) { // nome_pessoa ?>
		<td data-name="nome_pessoa"<?php echo $pessoa_fisica->nome_pessoa->CellAttributes() ?>>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_nome_pessoa" class="form-group pessoa_fisica_nome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->nome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->nome_pessoa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->OldValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_nome_pessoa" class="form-group pessoa_fisica_nome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->nome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->nome_pessoa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_nome_pessoa" class="pessoa_fisica_nome_pessoa">
<span<?php echo $pessoa_fisica->nome_pessoa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->nome_pessoa->ListViewValue() ?></span>
</span>
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
		<td data-name="sobrenome_pessoa"<?php echo $pessoa_fisica->sobrenome_pessoa->CellAttributes() ?>>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_sobrenome_pessoa" class="form-group pessoa_fisica_sobrenome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->sobrenome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->sobrenome_pessoa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->OldValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_sobrenome_pessoa" class="form-group pessoa_fisica_sobrenome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->sobrenome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->sobrenome_pessoa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_sobrenome_pessoa" class="pessoa_fisica_sobrenome_pessoa">
<span<?php echo $pessoa_fisica->sobrenome_pessoa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->sobrenome_pessoa->ListViewValue() ?></span>
</span>
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->telefone->Visible) { // telefone ?>
		<td data-name="telefone"<?php echo $pessoa_fisica->telefone->CellAttributes() ?>>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_telefone" class="form-group pessoa_fisica_telefone">
<input type="text" data-table="pessoa_fisica" data-field="x_telefone" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->telefone->EditValue ?>"<?php echo $pessoa_fisica->telefone->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->OldValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_telefone" class="form-group pessoa_fisica_telefone">
<input type="text" data-table="pessoa_fisica" data-field="x_telefone" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->telefone->EditValue ?>"<?php echo $pessoa_fisica->telefone->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_telefone" class="pessoa_fisica_telefone">
<span<?php echo $pessoa_fisica->telefone->ViewAttributes() ?>>
<?php echo $pessoa_fisica->telefone->ListViewValue() ?></span>
</span>
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $pessoa_fisica->_email->CellAttributes() ?>>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica__email" class="form-group pessoa_fisica__email">
<input type="text" data-table="pessoa_fisica" data-field="x__email" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->_email->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->_email->EditValue ?>"<?php echo $pessoa_fisica->_email->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->OldValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica__email" class="form-group pessoa_fisica__email">
<input type="text" data-table="pessoa_fisica" data-field="x__email" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->_email->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->_email->EditValue ?>"<?php echo $pessoa_fisica->_email->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica__email" class="pessoa_fisica__email">
<span<?php echo $pessoa_fisica->_email->ViewAttributes() ?>>
<?php echo $pessoa_fisica->_email->ListViewValue() ?></span>
</span>
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->celular->Visible) { // celular ?>
		<td data-name="celular"<?php echo $pessoa_fisica->celular->CellAttributes() ?>>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_celular" class="form-group pessoa_fisica_celular">
<input type="text" data-table="pessoa_fisica" data-field="x_celular" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->celular->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->celular->EditValue ?>"<?php echo $pessoa_fisica->celular->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->OldValue) ?>">
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_celular" class="form-group pessoa_fisica_celular">
<input type="text" data-table="pessoa_fisica" data-field="x_celular" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->celular->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->celular->EditValue ?>"<?php echo $pessoa_fisica->celular->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pessoa_fisica_grid->RowCnt ?>_pessoa_fisica_celular" class="pessoa_fisica_celular">
<span<?php echo $pessoa_fisica->celular->ViewAttributes() ?>>
<?php echo $pessoa_fisica->celular->ListViewValue() ?></span>
</span>
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="fpessoa_fisicagrid$x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->FormValue) ?>">
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="fpessoa_fisicagrid$o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pessoa_fisica_grid->ListOptions->Render("body", "right", $pessoa_fisica_grid->RowCnt);
?>
	</tr>
<?php if ($pessoa_fisica->RowType == EW_ROWTYPE_ADD || $pessoa_fisica->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpessoa_fisicagrid.UpdateOpts(<?php echo $pessoa_fisica_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pessoa_fisica->CurrentAction <> "gridadd" || $pessoa_fisica->CurrentMode == "copy")
		if (!$pessoa_fisica_grid->Recordset->EOF) $pessoa_fisica_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pessoa_fisica->CurrentMode == "add" || $pessoa_fisica->CurrentMode == "copy" || $pessoa_fisica->CurrentMode == "edit") {
		$pessoa_fisica_grid->RowIndex = '$rowindex$';
		$pessoa_fisica_grid->LoadRowValues();

		// Set row properties
		$pessoa_fisica->ResetAttrs();
		$pessoa_fisica->RowAttrs = array_merge($pessoa_fisica->RowAttrs, array('data-rowindex'=>$pessoa_fisica_grid->RowIndex, 'id'=>'r0_pessoa_fisica', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pessoa_fisica->RowAttrs["class"], "ewTemplate");
		$pessoa_fisica->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pessoa_fisica_grid->RenderRow();

		// Render list options
		$pessoa_fisica_grid->RenderListOptions();
		$pessoa_fisica_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pessoa_fisica->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pessoa_fisica_grid->ListOptions->Render("body", "left", $pessoa_fisica_grid->RowIndex);
?>
	<?php if ($pessoa_fisica->id_pessoa->Visible) { // id_pessoa ?>
		<td data-name="id_pessoa">
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_pessoa_fisica_id_pessoa" class="form-group pessoa_fisica_id_pessoa">
<span<?php echo $pessoa_fisica->id_pessoa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->id_pessoa->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->nome_pessoa->Visible) { // nome_pessoa ?>
		<td data-name="nome_pessoa">
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pessoa_fisica_nome_pessoa" class="form-group pessoa_fisica_nome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->nome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->nome_pessoa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pessoa_fisica_nome_pessoa" class="form-group pessoa_fisica_nome_pessoa">
<span<?php echo $pessoa_fisica->nome_pessoa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->nome_pessoa->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_nome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
		<td data-name="sobrenome_pessoa">
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pessoa_fisica_sobrenome_pessoa" class="form-group pessoa_fisica_sobrenome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->sobrenome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->sobrenome_pessoa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pessoa_fisica_sobrenome_pessoa" class="form-group pessoa_fisica_sobrenome_pessoa">
<span<?php echo $pessoa_fisica->sobrenome_pessoa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->sobrenome_pessoa->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_sobrenome_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->telefone->Visible) { // telefone ?>
		<td data-name="telefone">
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pessoa_fisica_telefone" class="form-group pessoa_fisica_telefone">
<input type="text" data-table="pessoa_fisica" data-field="x_telefone" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->telefone->EditValue ?>"<?php echo $pessoa_fisica->telefone->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pessoa_fisica_telefone" class="form-group pessoa_fisica_telefone">
<span<?php echo $pessoa_fisica->telefone->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->telefone->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_telefone" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_telefone" value="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->_email->Visible) { // email ?>
		<td data-name="_email">
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pessoa_fisica__email" class="form-group pessoa_fisica__email">
<input type="text" data-table="pessoa_fisica" data-field="x__email" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->_email->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->_email->EditValue ?>"<?php echo $pessoa_fisica->_email->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pessoa_fisica__email" class="form-group pessoa_fisica__email">
<span<?php echo $pessoa_fisica->_email->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->_email->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x__email" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($pessoa_fisica->_email->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pessoa_fisica->celular->Visible) { // celular ?>
		<td data-name="celular">
<?php if ($pessoa_fisica->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pessoa_fisica_celular" class="form-group pessoa_fisica_celular">
<input type="text" data-table="pessoa_fisica" data-field="x_celular" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->celular->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->celular->EditValue ?>"<?php echo $pessoa_fisica->celular->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pessoa_fisica_celular" class="form-group pessoa_fisica_celular">
<span<?php echo $pessoa_fisica->celular->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->celular->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="x<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pessoa_fisica" data-field="x_celular" name="o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" id="o<?php echo $pessoa_fisica_grid->RowIndex ?>_celular" value="<?php echo ew_HtmlEncode($pessoa_fisica->celular->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pessoa_fisica_grid->ListOptions->Render("body", "right", $pessoa_fisica_grid->RowIndex);
?>
<script type="text/javascript">
fpessoa_fisicagrid.UpdateOpts(<?php echo $pessoa_fisica_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pessoa_fisica->CurrentMode == "add" || $pessoa_fisica->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pessoa_fisica_grid->FormKeyCountName ?>" id="<?php echo $pessoa_fisica_grid->FormKeyCountName ?>" value="<?php echo $pessoa_fisica_grid->KeyCount ?>">
<?php echo $pessoa_fisica_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pessoa_fisica->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pessoa_fisica_grid->FormKeyCountName ?>" id="<?php echo $pessoa_fisica_grid->FormKeyCountName ?>" value="<?php echo $pessoa_fisica_grid->KeyCount ?>">
<?php echo $pessoa_fisica_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pessoa_fisica->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpessoa_fisicagrid">
</div>
<?php

// Close recordset
if ($pessoa_fisica_grid->Recordset)
	$pessoa_fisica_grid->Recordset->Close();
?>
<?php if ($pessoa_fisica_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($pessoa_fisica_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($pessoa_fisica_grid->TotalRecs == 0 && $pessoa_fisica->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pessoa_fisica_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pessoa_fisica->Export == "") { ?>
<script type="text/javascript">
fpessoa_fisicagrid.Init();
</script>
<?php } ?>
<?php
$pessoa_fisica_grid->Page_Terminate();
?>
