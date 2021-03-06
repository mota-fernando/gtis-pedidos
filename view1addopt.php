<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "view1info.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$view1_addopt = NULL; // Initialize page object first

class cview1_addopt extends cview1 {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'view1';

	// Page object name
	var $PageObjName = 'view1_addopt';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (view1)
		if (!isset($GLOBALS["view1"]) || get_class($GLOBALS["view1"]) == "cview1") {
			$GLOBALS["view1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_pessoa->SetVisibility();
		$this->nome_pessoa->SetVisibility();
		$this->sobrenome_pessoa->SetVisibility();
		$this->comissao->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $view1;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view1);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used

		$this->LoadRowValues(); // Load default values

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_id_pessoa"] = $this->id_pessoa->DbValue;
					$row["x_nome_pessoa"] = ew_ConvertToUtf8($this->nome_pessoa->DbValue);
					$row["x_sobrenome_pessoa"] = ew_ConvertToUtf8($this->sobrenome_pessoa->DbValue);
					$row["x_comissao"] = $this->comissao->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					ew_Header(FALSE, "utf-8", TRUE);
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_pessoa->CurrentValue = NULL;
		$this->id_pessoa->OldValue = $this->id_pessoa->CurrentValue;
		$this->nome_pessoa->CurrentValue = NULL;
		$this->nome_pessoa->OldValue = $this->nome_pessoa->CurrentValue;
		$this->sobrenome_pessoa->CurrentValue = NULL;
		$this->sobrenome_pessoa->OldValue = $this->sobrenome_pessoa->CurrentValue;
		$this->comissao->CurrentValue = NULL;
		$this->comissao->OldValue = $this->comissao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_pessoa->FldIsDetailKey) {
			$this->id_pessoa->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_pessoa")));
		}
		if (!$this->nome_pessoa->FldIsDetailKey) {
			$this->nome_pessoa->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nome_pessoa")));
		}
		if (!$this->sobrenome_pessoa->FldIsDetailKey) {
			$this->sobrenome_pessoa->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_sobrenome_pessoa")));
		}
		if (!$this->comissao->FldIsDetailKey) {
			$this->comissao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_comissao")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_pessoa->CurrentValue = ew_ConvertToUtf8($this->id_pessoa->FormValue);
		$this->nome_pessoa->CurrentValue = ew_ConvertToUtf8($this->nome_pessoa->FormValue);
		$this->sobrenome_pessoa->CurrentValue = ew_ConvertToUtf8($this->sobrenome_pessoa->FormValue);
		$this->comissao->CurrentValue = ew_ConvertToUtf8($this->comissao->FormValue);
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id_pessoa->setDbValue($row['id_pessoa']);
		if (array_key_exists('EV__id_pessoa', $rs->fields)) {
			$this->id_pessoa->VirtualValue = $rs->fields('EV__id_pessoa'); // Set up virtual field value
		} else {
			$this->id_pessoa->VirtualValue = ""; // Clear value
		}
		$this->nome_pessoa->setDbValue($row['nome_pessoa']);
		$this->sobrenome_pessoa->setDbValue($row['sobrenome_pessoa']);
		$this->comissao->setDbValue($row['comissao']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id_pessoa'] = $this->id_pessoa->CurrentValue;
		$row['nome_pessoa'] = $this->nome_pessoa->CurrentValue;
		$row['sobrenome_pessoa'] = $this->sobrenome_pessoa->CurrentValue;
		$row['comissao'] = $this->comissao->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_pessoa->DbValue = $row['id_pessoa'];
		$this->nome_pessoa->DbValue = $row['nome_pessoa'];
		$this->sobrenome_pessoa->DbValue = $row['sobrenome_pessoa'];
		$this->comissao->DbValue = $row['comissao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_pessoa
		// nome_pessoa
		// sobrenome_pessoa
		// comissao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_pessoa
		if ($this->id_pessoa->VirtualValue <> "") {
			$this->id_pessoa->ViewValue = $this->id_pessoa->VirtualValue;
		} else {
		if (strval($this->id_pessoa->CurrentValue) <> "") {
			$sFilterWrk = "`id_pessoa`" . ew_SearchString("=", $this->id_pessoa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_pessoa`, `nome_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoa_fisica`";
		$sWhereWrk = "";
		$this->id_pessoa->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_pessoa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_pessoa->ViewValue = $this->id_pessoa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_pessoa->ViewValue = $this->id_pessoa->CurrentValue;
			}
		} else {
			$this->id_pessoa->ViewValue = NULL;
		}
		}
		$this->id_pessoa->ViewCustomAttributes = "";

		// nome_pessoa
		$this->nome_pessoa->ViewValue = $this->nome_pessoa->CurrentValue;
		$this->nome_pessoa->ViewCustomAttributes = "";

		// sobrenome_pessoa
		$this->sobrenome_pessoa->ViewValue = $this->sobrenome_pessoa->CurrentValue;
		$this->sobrenome_pessoa->ViewCustomAttributes = "";

		// comissao
		$this->comissao->ViewValue = $this->comissao->CurrentValue;
		$this->comissao->ViewCustomAttributes = "";

			// id_pessoa
			$this->id_pessoa->LinkCustomAttributes = "";
			$this->id_pessoa->HrefValue = "";
			$this->id_pessoa->TooltipValue = "";

			// nome_pessoa
			$this->nome_pessoa->LinkCustomAttributes = "";
			$this->nome_pessoa->HrefValue = "";
			$this->nome_pessoa->TooltipValue = "";

			// sobrenome_pessoa
			$this->sobrenome_pessoa->LinkCustomAttributes = "";
			$this->sobrenome_pessoa->HrefValue = "";
			$this->sobrenome_pessoa->TooltipValue = "";

			// comissao
			$this->comissao->LinkCustomAttributes = "";
			$this->comissao->HrefValue = "";
			$this->comissao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_pessoa
			$this->id_pessoa->EditAttrs["class"] = "form-control";
			$this->id_pessoa->EditCustomAttributes = "";
			if (trim(strval($this->id_pessoa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_pessoa`" . ew_SearchString("=", $this->id_pessoa->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_pessoa`, `nome_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pessoa_fisica`";
			$sWhereWrk = "";
			$this->id_pessoa->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_pessoa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_pessoa->EditValue = $arwrk;

			// nome_pessoa
			$this->nome_pessoa->EditAttrs["class"] = "form-control";
			$this->nome_pessoa->EditCustomAttributes = "";
			$this->nome_pessoa->EditValue = ew_HtmlEncode($this->nome_pessoa->CurrentValue);
			$this->nome_pessoa->PlaceHolder = ew_RemoveHtml($this->nome_pessoa->FldCaption());

			// sobrenome_pessoa
			$this->sobrenome_pessoa->EditAttrs["class"] = "form-control";
			$this->sobrenome_pessoa->EditCustomAttributes = "";
			$this->sobrenome_pessoa->EditValue = ew_HtmlEncode($this->sobrenome_pessoa->CurrentValue);
			$this->sobrenome_pessoa->PlaceHolder = ew_RemoveHtml($this->sobrenome_pessoa->FldCaption());

			// comissao
			$this->comissao->EditAttrs["class"] = "form-control";
			$this->comissao->EditCustomAttributes = "";
			$this->comissao->EditValue = ew_HtmlEncode($this->comissao->CurrentValue);
			$this->comissao->PlaceHolder = ew_RemoveHtml($this->comissao->FldCaption());

			// Add refer script
			// id_pessoa

			$this->id_pessoa->LinkCustomAttributes = "";
			$this->id_pessoa->HrefValue = "";

			// nome_pessoa
			$this->nome_pessoa->LinkCustomAttributes = "";
			$this->nome_pessoa->HrefValue = "";

			// sobrenome_pessoa
			$this->sobrenome_pessoa->LinkCustomAttributes = "";
			$this->sobrenome_pessoa->HrefValue = "";

			// comissao
			$this->comissao->LinkCustomAttributes = "";
			$this->comissao->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->comissao->FormValue)) {
			ew_AddMessage($gsFormError, $this->comissao->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_pessoa
		$this->id_pessoa->SetDbValueDef($rsnew, $this->id_pessoa->CurrentValue, NULL, FALSE);

		// nome_pessoa
		$this->nome_pessoa->SetDbValueDef($rsnew, $this->nome_pessoa->CurrentValue, NULL, FALSE);

		// sobrenome_pessoa
		$this->sobrenome_pessoa->SetDbValueDef($rsnew, $this->sobrenome_pessoa->CurrentValue, NULL, FALSE);

		// comissao
		$this->comissao->SetDbValueDef($rsnew, $this->comissao->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("view1list.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_pessoa":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_pessoa` AS `LinkFld`, `nome_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoa_fisica`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_pessoa` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_pessoa, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Custom validate event
	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($view1_addopt)) $view1_addopt = new cview1_addopt();

// Page init
$view1_addopt->Page_Init();

// Page main
$view1_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fview1addopt = new ew_Form("fview1addopt", "addopt");

// Validate form
fview1addopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_comissao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1->comissao->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fview1addopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fview1addopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fview1addopt.Lists["x_id_pessoa"] = {"LinkField":"x_id_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_pessoa","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pessoa_fisica"};
fview1addopt.Lists["x_id_pessoa"].Data = "<?php echo $view1_addopt->id_pessoa->LookupFilterQuery(FALSE, "addopt") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$view1_addopt->ShowMessage();
?>
<form name="fview1addopt" id="fview1addopt" class="ewForm form-horizontal" action="view1addopt.php" method="post">
<?php if ($view1_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($view1->id_pessoa->Visible) { // id_pessoa ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_pessoa"><?php echo $view1->id_pessoa->FldCaption() ?></label>
		<div class="col-sm-10">
<select data-table="view1" data-field="x_id_pessoa" data-value-separator="<?php echo $view1->id_pessoa->DisplayValueSeparatorAttribute() ?>" id="x_id_pessoa" name="x_id_pessoa"<?php echo $view1->id_pessoa->EditAttributes() ?>>
<?php echo $view1->id_pessoa->SelectOptionListHtml("x_id_pessoa") ?>
</select>
</div>
	</div>
<?php } ?>
<?php if ($view1->nome_pessoa->Visible) { // nome_pessoa ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nome_pessoa"><?php echo $view1->nome_pessoa->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="view1" data-field="x_nome_pessoa" name="x_nome_pessoa" id="x_nome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($view1->nome_pessoa->getPlaceHolder()) ?>" value="<?php echo $view1->nome_pessoa->EditValue ?>"<?php echo $view1->nome_pessoa->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($view1->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_sobrenome_pessoa"><?php echo $view1->sobrenome_pessoa->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="view1" data-field="x_sobrenome_pessoa" name="x_sobrenome_pessoa" id="x_sobrenome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($view1->sobrenome_pessoa->getPlaceHolder()) ?>" value="<?php echo $view1->sobrenome_pessoa->EditValue ?>"<?php echo $view1->sobrenome_pessoa->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($view1->comissao->Visible) { // comissao ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_comissao"><?php echo $view1->comissao->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="view1" data-field="x_comissao" name="x_comissao" id="x_comissao" size="30" placeholder="<?php echo ew_HtmlEncode($view1->comissao->getPlaceHolder()) ?>" value="<?php echo $view1->comissao->EditValue ?>"<?php echo $view1->comissao->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
fview1addopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$view1_addopt->Page_Terminate();
?>
