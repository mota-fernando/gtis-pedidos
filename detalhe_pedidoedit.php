<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "detalhe_pedidoinfo.php" ?>
<?php include_once "pedidosinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$detalhe_pedido_edit = NULL; // Initialize page object first

class cdetalhe_pedido_edit extends cdetalhe_pedido {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'detalhe_pedido';

	// Page object name
	var $PageObjName = 'detalhe_pedido_edit';

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

		// Table object (detalhe_pedido)
		if (!isset($GLOBALS["detalhe_pedido"]) || get_class($GLOBALS["detalhe_pedido"]) == "cdetalhe_pedido") {
			$GLOBALS["detalhe_pedido"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["detalhe_pedido"];
		}

		// Table object (pedidos)
		if (!isset($GLOBALS['pedidos'])) $GLOBALS['pedidos'] = new cpedidos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'detalhe_pedido', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_detalhe->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_detalhe->Visible = FALSE;
		$this->numero_pedido->SetVisibility();
		$this->id_produto->SetVisibility();
		$this->desconto->SetVisibility();
		$this->preco->SetVisibility();
		$this->quantidade->SetVisibility();
		$this->subtotal->SetVisibility();

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
		global $EW_EXPORT, $detalhe_pedido;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($detalhe_pedido);
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "detalhe_pedidoview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_id_detalhe")) {
				$this->id_detalhe->setFormValue($objForm->GetValue("x_id_detalhe"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_detalhe"])) {
				$this->id_detalhe->setQueryStringValue($_GET["id_detalhe"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_detalhe->CurrentValue = NULL;
			}
		}

		// Set up master detail parameters
		$this->SetupMasterParms();

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("detalhe_pedidolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "detalhe_pedidolist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_detalhe->FldIsDetailKey)
			$this->id_detalhe->setFormValue($objForm->GetValue("x_id_detalhe"));
		if (!$this->numero_pedido->FldIsDetailKey) {
			$this->numero_pedido->setFormValue($objForm->GetValue("x_numero_pedido"));
		}
		if (!$this->id_produto->FldIsDetailKey) {
			$this->id_produto->setFormValue($objForm->GetValue("x_id_produto"));
		}
		if (!$this->desconto->FldIsDetailKey) {
			$this->desconto->setFormValue($objForm->GetValue("x_desconto"));
		}
		if (!$this->preco->FldIsDetailKey) {
			$this->preco->setFormValue($objForm->GetValue("x_preco"));
		}
		if (!$this->quantidade->FldIsDetailKey) {
			$this->quantidade->setFormValue($objForm->GetValue("x_quantidade"));
		}
		if (!$this->subtotal->FldIsDetailKey) {
			$this->subtotal->setFormValue($objForm->GetValue("x_subtotal"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_detalhe->CurrentValue = $this->id_detalhe->FormValue;
		$this->numero_pedido->CurrentValue = $this->numero_pedido->FormValue;
		$this->id_produto->CurrentValue = $this->id_produto->FormValue;
		$this->desconto->CurrentValue = $this->desconto->FormValue;
		$this->preco->CurrentValue = $this->preco->FormValue;
		$this->quantidade->CurrentValue = $this->quantidade->FormValue;
		$this->subtotal->CurrentValue = $this->subtotal->FormValue;
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
		$this->id_detalhe->setDbValue($row['id_detalhe']);
		$this->numero_pedido->setDbValue($row['numero_pedido']);
		$this->id_produto->setDbValue($row['id_produto']);
		$this->desconto->setDbValue($row['desconto']);
		$this->preco->setDbValue($row['preco']);
		$this->quantidade->setDbValue($row['quantidade']);
		$this->subtotal->setDbValue($row['subtotal']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_detalhe'] = NULL;
		$row['numero_pedido'] = NULL;
		$row['id_produto'] = NULL;
		$row['desconto'] = NULL;
		$row['preco'] = NULL;
		$row['quantidade'] = NULL;
		$row['subtotal'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_detalhe->DbValue = $row['id_detalhe'];
		$this->numero_pedido->DbValue = $row['numero_pedido'];
		$this->id_produto->DbValue = $row['id_produto'];
		$this->desconto->DbValue = $row['desconto'];
		$this->preco->DbValue = $row['preco'];
		$this->quantidade->DbValue = $row['quantidade'];
		$this->subtotal->DbValue = $row['subtotal'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_detalhe")) <> "")
			$this->id_detalhe->CurrentValue = $this->getKey("id_detalhe"); // id_detalhe
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->subtotal->FormValue == $this->subtotal->CurrentValue && is_numeric(ew_StrToFloat($this->subtotal->CurrentValue)))
			$this->subtotal->CurrentValue = ew_StrToFloat($this->subtotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_detalhe
		// numero_pedido
		// id_produto
		// desconto
		// preco
		// quantidade
		// subtotal

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_detalhe
		$this->id_detalhe->ViewValue = $this->id_detalhe->CurrentValue;
		$this->id_detalhe->ViewCustomAttributes = "";

		// numero_pedido
		$this->numero_pedido->ViewValue = $this->numero_pedido->CurrentValue;
		$this->numero_pedido->ViewCustomAttributes = "";

		// id_produto
		if (strval($this->id_produto->CurrentValue) <> "") {
			$sFilterWrk = "`id_produto`" . ew_SearchString("=", $this->id_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_produto`, `nome_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
		$sWhereWrk = "";
		$this->id_produto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_produto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_produto->ViewValue = $this->id_produto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_produto->ViewValue = $this->id_produto->CurrentValue;
			}
		} else {
			$this->id_produto->ViewValue = NULL;
		}
		$this->id_produto->ViewCustomAttributes = "";

		// desconto
		if (strval($this->desconto->CurrentValue) <> "") {
			$sFilterWrk = "`porcentagem`" . ew_SearchString("=", $this->desconto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `porcentagem`, `porcentagem` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `desconto`";
		$sWhereWrk = "";
		$this->desconto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->desconto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->desconto->ViewValue = $this->desconto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->desconto->ViewValue = $this->desconto->CurrentValue;
			}
		} else {
			$this->desconto->ViewValue = NULL;
		}
		$this->desconto->ViewCustomAttributes = "";

		// preco
		if (strval($this->preco->CurrentValue) <> "") {
			$sFilterWrk = "`preco_produto`" . ew_SearchString("=", $this->preco->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `preco_produto`, `preco_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
		$sWhereWrk = "";
		$this->preco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->preco, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_FormatCurrency($rswrk->fields('DispFld'), 2, -1, -1, -1);
				$this->preco->ViewValue = $this->preco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->preco->ViewValue = $this->preco->CurrentValue;
			}
		} else {
			$this->preco->ViewValue = NULL;
		}
		$this->preco->ViewCustomAttributes = "";

		// quantidade
		$this->quantidade->ViewValue = $this->quantidade->CurrentValue;
		$this->quantidade->ViewCustomAttributes = "";

		// subtotal
		$this->subtotal->ViewValue = $this->subtotal->CurrentValue;
		$this->subtotal->ViewCustomAttributes = "";

			// id_detalhe
			$this->id_detalhe->LinkCustomAttributes = "";
			$this->id_detalhe->HrefValue = "";
			$this->id_detalhe->TooltipValue = "";

			// numero_pedido
			$this->numero_pedido->LinkCustomAttributes = "";
			$this->numero_pedido->HrefValue = "";
			$this->numero_pedido->TooltipValue = "";

			// id_produto
			$this->id_produto->LinkCustomAttributes = "";
			$this->id_produto->HrefValue = "";
			$this->id_produto->TooltipValue = "";

			// desconto
			$this->desconto->LinkCustomAttributes = "";
			$this->desconto->HrefValue = "";
			$this->desconto->TooltipValue = "";

			// preco
			$this->preco->LinkCustomAttributes = "";
			$this->preco->HrefValue = "";
			$this->preco->TooltipValue = "";

			// quantidade
			$this->quantidade->LinkCustomAttributes = "";
			$this->quantidade->HrefValue = "";
			$this->quantidade->TooltipValue = "";

			// subtotal
			$this->subtotal->LinkCustomAttributes = "";
			$this->subtotal->HrefValue = "";
			$this->subtotal->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_detalhe
			$this->id_detalhe->EditAttrs["class"] = "form-control";
			$this->id_detalhe->EditCustomAttributes = "";
			$this->id_detalhe->EditValue = $this->id_detalhe->CurrentValue;
			$this->id_detalhe->ViewCustomAttributes = "";

			// numero_pedido
			$this->numero_pedido->EditAttrs["class"] = "form-control";
			$this->numero_pedido->EditCustomAttributes = "";
			if ($this->numero_pedido->getSessionValue() <> "") {
				$this->numero_pedido->CurrentValue = $this->numero_pedido->getSessionValue();
			$this->numero_pedido->ViewValue = $this->numero_pedido->CurrentValue;
			$this->numero_pedido->ViewCustomAttributes = "";
			} else {
			$this->numero_pedido->EditValue = ew_HtmlEncode($this->numero_pedido->CurrentValue);
			$this->numero_pedido->PlaceHolder = ew_RemoveHtml($this->numero_pedido->FldCaption());
			}

			// id_produto
			$this->id_produto->EditAttrs["class"] = "form-control";
			$this->id_produto->EditCustomAttributes = "";
			if (trim(strval($this->id_produto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_produto`" . ew_SearchString("=", $this->id_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_produto`, `nome_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `produtos`";
			$sWhereWrk = "";
			$this->id_produto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_produto->EditValue = $arwrk;

			// desconto
			$this->desconto->EditAttrs["class"] = "form-control";
			$this->desconto->EditCustomAttributes = "";
			if (trim(strval($this->desconto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`porcentagem`" . ew_SearchString("=", $this->desconto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `porcentagem`, `porcentagem` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `desconto`";
			$sWhereWrk = "";
			$this->desconto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->desconto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->desconto->EditValue = $arwrk;

			// preco
			$this->preco->EditAttrs["class"] = "form-control";
			$this->preco->EditCustomAttributes = "";
			if (trim(strval($this->preco->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`preco_produto`" . ew_SearchString("=", $this->preco->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `preco_produto`, `preco_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `id_produto` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `produtos`";
			$sWhereWrk = "";
			$this->preco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->preco, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][1] = ew_FormatCurrency($arwrk[$rowcntwrk][1], 2, -1, -1, -1);
			}
			$this->preco->EditValue = $arwrk;

			// quantidade
			$this->quantidade->EditAttrs["class"] = "form-control";
			$this->quantidade->EditCustomAttributes = "";
			$this->quantidade->EditValue = ew_HtmlEncode($this->quantidade->CurrentValue);
			$this->quantidade->PlaceHolder = ew_RemoveHtml($this->quantidade->FldCaption());

			// subtotal
			$this->subtotal->EditAttrs["class"] = "form-control";
			$this->subtotal->EditCustomAttributes = "";
			$this->subtotal->EditValue = ew_HtmlEncode($this->subtotal->CurrentValue);
			$this->subtotal->PlaceHolder = ew_RemoveHtml($this->subtotal->FldCaption());
			if (strval($this->subtotal->EditValue) <> "" && is_numeric($this->subtotal->EditValue)) $this->subtotal->EditValue = ew_FormatNumber($this->subtotal->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// id_detalhe

			$this->id_detalhe->LinkCustomAttributes = "";
			$this->id_detalhe->HrefValue = "";

			// numero_pedido
			$this->numero_pedido->LinkCustomAttributes = "";
			$this->numero_pedido->HrefValue = "";

			// id_produto
			$this->id_produto->LinkCustomAttributes = "";
			$this->id_produto->HrefValue = "";

			// desconto
			$this->desconto->LinkCustomAttributes = "";
			$this->desconto->HrefValue = "";

			// preco
			$this->preco->LinkCustomAttributes = "";
			$this->preco->HrefValue = "";

			// quantidade
			$this->quantidade->LinkCustomAttributes = "";
			$this->quantidade->HrefValue = "";

			// subtotal
			$this->subtotal->LinkCustomAttributes = "";
			$this->subtotal->HrefValue = "";
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
		if (!ew_CheckInteger($this->numero_pedido->FormValue)) {
			ew_AddMessage($gsFormError, $this->numero_pedido->FldErrMsg());
		}
		if (!$this->id_produto->FldIsDetailKey && !is_null($this->id_produto->FormValue) && $this->id_produto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_produto->FldCaption(), $this->id_produto->ReqErrMsg));
		}
		if (!$this->preco->FldIsDetailKey && !is_null($this->preco->FormValue) && $this->preco->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->preco->FldCaption(), $this->preco->ReqErrMsg));
		}
		if (!$this->quantidade->FldIsDetailKey && !is_null($this->quantidade->FormValue) && $this->quantidade->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantidade->FldCaption(), $this->quantidade->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->quantidade->FormValue)) {
			ew_AddMessage($gsFormError, $this->quantidade->FldErrMsg());
		}
		if (!$this->subtotal->FldIsDetailKey && !is_null($this->subtotal->FormValue) && $this->subtotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subtotal->FldCaption(), $this->subtotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->subtotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->subtotal->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// numero_pedido
			$this->numero_pedido->SetDbValueDef($rsnew, $this->numero_pedido->CurrentValue, NULL, $this->numero_pedido->ReadOnly);

			// id_produto
			$this->id_produto->SetDbValueDef($rsnew, $this->id_produto->CurrentValue, NULL, $this->id_produto->ReadOnly);

			// desconto
			$this->desconto->SetDbValueDef($rsnew, $this->desconto->CurrentValue, NULL, $this->desconto->ReadOnly);

			// preco
			$this->preco->SetDbValueDef($rsnew, $this->preco->CurrentValue, NULL, $this->preco->ReadOnly);

			// quantidade
			$this->quantidade->SetDbValueDef($rsnew, $this->quantidade->CurrentValue, NULL, $this->quantidade->ReadOnly);

			// subtotal
			$this->subtotal->SetDbValueDef($rsnew, $this->subtotal->CurrentValue, 0, $this->subtotal->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up master/detail based on QueryString
	function SetupMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pedidos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_numero"] <> "") {
					$GLOBALS["pedidos"]->numero->setQueryStringValue($_GET["fk_numero"]);
					$this->numero_pedido->setQueryStringValue($GLOBALS["pedidos"]->numero->QueryStringValue);
					$this->numero_pedido->setSessionValue($this->numero_pedido->QueryStringValue);
					if (!is_numeric($GLOBALS["pedidos"]->numero->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pedidos") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_numero"] <> "") {
					$GLOBALS["pedidos"]->numero->setFormValue($_POST["fk_numero"]);
					$this->numero_pedido->setFormValue($GLOBALS["pedidos"]->numero->FormValue);
					$this->numero_pedido->setSessionValue($this->numero_pedido->FormValue);
					if (!is_numeric($GLOBALS["pedidos"]->numero->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			if (!$this->IsAddOrEdit()) {
				$this->StartRec = 1;
				$this->setStartRecordNumber($this->StartRec);
			}

			// Clear previous master key from Session
			if ($sMasterTblVar <> "pedidos") {
				if ($this->numero_pedido->CurrentValue == "") $this->numero_pedido->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("detalhe_pedidolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_produto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_produto` AS `LinkFld`, `nome_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_produto` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_desconto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `porcentagem` AS `LinkFld`, `porcentagem` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `desconto`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`porcentagem` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->desconto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_preco":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `preco_produto` AS `LinkFld`, `preco_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`preco_produto` IN ({filter_value})', "t0" => "4", "fn0" => "", "f1" => '`id_produto` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->preco, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($detalhe_pedido_edit)) $detalhe_pedido_edit = new cdetalhe_pedido_edit();

// Page init
$detalhe_pedido_edit->Page_Init();

// Page main
$detalhe_pedido_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detalhe_pedido_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdetalhe_pedidoedit = new ew_Form("fdetalhe_pedidoedit", "edit");

// Validate form
fdetalhe_pedidoedit.Validate = function() {
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
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fdetalhe_pedidoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdetalhe_pedidoedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdetalhe_pedidoedit.Lists["x_id_produto"] = {"LinkField":"x_id_produto","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_produto","","",""],"ParentFields":[],"ChildFields":["x_preco"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"produtos"};
fdetalhe_pedidoedit.Lists["x_id_produto"].Data = "<?php echo $detalhe_pedido_edit->id_produto->LookupFilterQuery(FALSE, "edit") ?>";
fdetalhe_pedidoedit.Lists["x_desconto"] = {"LinkField":"x_porcentagem","Ajax":true,"AutoFill":false,"DisplayFields":["x_porcentagem","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"desconto"};
fdetalhe_pedidoedit.Lists["x_desconto"].Data = "<?php echo $detalhe_pedido_edit->desconto->LookupFilterQuery(FALSE, "edit") ?>";
fdetalhe_pedidoedit.Lists["x_preco"] = {"LinkField":"x_preco_produto","Ajax":true,"AutoFill":false,"DisplayFields":["x_preco_produto","","",""],"ParentFields":["x_id_produto"],"ChildFields":[],"FilterFields":["x_id_produto"],"Options":[],"Template":"","LinkTable":"produtos"};
fdetalhe_pedidoedit.Lists["x_preco"].Data = "<?php echo $detalhe_pedido_edit->preco->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $detalhe_pedido_edit->ShowPageHeader(); ?>
<?php
$detalhe_pedido_edit->ShowMessage();
?>
<form name="fdetalhe_pedidoedit" id="fdetalhe_pedidoedit" class="<?php echo $detalhe_pedido_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detalhe_pedido_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detalhe_pedido_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detalhe_pedido">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($detalhe_pedido_edit->IsModal) ?>">
<?php if ($detalhe_pedido->getCurrentMasterTable() == "pedidos") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pedidos">
<input type="hidden" name="fk_numero" value="<?php echo $detalhe_pedido->numero_pedido->getSessionValue() ?>">
<?php } ?>
<div class="ewEditDiv"><!-- page* -->
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
	<div id="r_id_detalhe" class="form-group">
		<label id="elh_detalhe_pedido_id_detalhe" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->id_detalhe->FldCaption() ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->id_detalhe->CellAttributes() ?>>
<span id="el_detalhe_pedido_id_detalhe">
<span<?php echo $detalhe_pedido->id_detalhe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->id_detalhe->EditValue ?></p></span>
</span>
<input type="hidden" data-table="detalhe_pedido" data-field="x_id_detalhe" name="x_id_detalhe" id="x_id_detalhe" value="<?php echo ew_HtmlEncode($detalhe_pedido->id_detalhe->CurrentValue) ?>">
<?php echo $detalhe_pedido->id_detalhe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
	<div id="r_numero_pedido" class="form-group">
		<label id="elh_detalhe_pedido_numero_pedido" for="x_numero_pedido" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->numero_pedido->FldCaption() ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->numero_pedido->CellAttributes() ?>>
<?php if ($detalhe_pedido->numero_pedido->getSessionValue() <> "") { ?>
<span id="el_detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detalhe_pedido->numero_pedido->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_numero_pedido" name="x_numero_pedido" value="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->CurrentValue) ?>">
<?php } else { ?>
<span id="el_detalhe_pedido_numero_pedido">
<input type="text" data-table="detalhe_pedido" data-field="x_numero_pedido" name="x_numero_pedido" id="x_numero_pedido" size="30" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->numero_pedido->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->numero_pedido->EditValue ?>"<?php echo $detalhe_pedido->numero_pedido->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $detalhe_pedido->numero_pedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
	<div id="r_id_produto" class="form-group">
		<label id="elh_detalhe_pedido_id_produto" for="x_id_produto" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->id_produto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->id_produto->CellAttributes() ?>>
<span id="el_detalhe_pedido_id_produto">
<?php $detalhe_pedido->id_produto->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$detalhe_pedido->id_produto->EditAttrs["onchange"]; ?>
<select data-table="detalhe_pedido" data-field="x_id_produto" data-value-separator="<?php echo $detalhe_pedido->id_produto->DisplayValueSeparatorAttribute() ?>" id="x_id_produto" name="x_id_produto"<?php echo $detalhe_pedido->id_produto->EditAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->SelectOptionListHtml("x_id_produto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->id_produto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_produto',url:'produtosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_produto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->id_produto->FldCaption() ?></span></button>
</span>
<?php echo $detalhe_pedido->id_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
	<div id="r_desconto" class="form-group">
		<label id="elh_detalhe_pedido_desconto" for="x_desconto" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->desconto->FldCaption() ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->desconto->CellAttributes() ?>>
<span id="el_detalhe_pedido_desconto">
<select data-table="detalhe_pedido" data-field="x_desconto" data-value-separator="<?php echo $detalhe_pedido->desconto->DisplayValueSeparatorAttribute() ?>" id="x_desconto" name="x_desconto"<?php echo $detalhe_pedido->desconto->EditAttributes() ?>>
<?php echo $detalhe_pedido->desconto->SelectOptionListHtml("x_desconto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $detalhe_pedido->desconto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_desconto',url:'descontoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_desconto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $detalhe_pedido->desconto->FldCaption() ?></span></button>
</span>
<?php echo $detalhe_pedido->desconto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
	<div id="r_preco" class="form-group">
		<label id="elh_detalhe_pedido_preco" for="x_preco" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->preco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->preco->CellAttributes() ?>>
<span id="el_detalhe_pedido_preco">
<select data-table="detalhe_pedido" data-field="x_preco" data-value-separator="<?php echo $detalhe_pedido->preco->DisplayValueSeparatorAttribute() ?>" id="x_preco" name="x_preco"<?php echo $detalhe_pedido->preco->EditAttributes() ?>>
<?php echo $detalhe_pedido->preco->SelectOptionListHtml("x_preco") ?>
</select>
</span>
<?php echo $detalhe_pedido->preco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
	<div id="r_quantidade" class="form-group">
		<label id="elh_detalhe_pedido_quantidade" for="x_quantidade" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->quantidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->quantidade->CellAttributes() ?>>
<span id="el_detalhe_pedido_quantidade">
<input type="text" data-table="detalhe_pedido" data-field="x_quantidade" name="x_quantidade" id="x_quantidade" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->quantidade->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->quantidade->EditValue ?>"<?php echo $detalhe_pedido->quantidade->EditAttributes() ?>>
</span>
<?php echo $detalhe_pedido->quantidade->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
	<div id="r_subtotal" class="form-group">
		<label id="elh_detalhe_pedido_subtotal" for="x_subtotal" class="<?php echo $detalhe_pedido_edit->LeftColumnClass ?>"><?php echo $detalhe_pedido->subtotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $detalhe_pedido_edit->RightColumnClass ?>"><div<?php echo $detalhe_pedido->subtotal->CellAttributes() ?>>
<span id="el_detalhe_pedido_subtotal">
<input type="text" data-table="detalhe_pedido" data-field="x_subtotal" name="x_subtotal" id="x_subtotal" size="4" placeholder="<?php echo ew_HtmlEncode($detalhe_pedido->subtotal->getPlaceHolder()) ?>" value="<?php echo $detalhe_pedido->subtotal->EditValue ?>"<?php echo $detalhe_pedido->subtotal->EditAttributes() ?>>
</span>
<?php echo $detalhe_pedido->subtotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$detalhe_pedido_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $detalhe_pedido_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $detalhe_pedido_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdetalhe_pedidoedit.Init();
</script>
<?php
$detalhe_pedido_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$detalhe_pedido_edit->Page_Terminate();
?>
