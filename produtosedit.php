<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "produtosinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$produtos_edit = NULL; // Initialize page object first

class cprodutos_edit extends cprodutos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'produtos';

	// Page object name
	var $PageObjName = 'produtos_edit';

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

		// Table object (produtos)
		if (!isset($GLOBALS["produtos"]) || get_class($GLOBALS["produtos"]) == "cprodutos") {
			$GLOBALS["produtos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["produtos"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'produtos', TRUE);

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
		$this->id_produto->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_produto->Visible = FALSE;
		$this->codigo_produto->SetVisibility();
		$this->nome_produto->SetVisibility();
		$this->modelo_produto->SetVisibility();
		$this->id_departamento_produto->SetVisibility();
		$this->id_marca_produto->SetVisibility();
		$this->status_produto->SetVisibility();
		$this->unidade_medida_produto->SetVisibility();
		$this->unidades->SetVisibility();
		$this->peso_produto->SetVisibility();
		$this->data_adicionado->SetVisibility();
		$this->hora_adicionado->SetVisibility();
		$this->preco_produto->SetVisibility();
		$this->descricao->SetVisibility();
		$this->ipi->SetVisibility();

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
		global $EW_EXPORT, $produtos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($produtos);
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
					if ($pageName == "produtosview.php")
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
			if ($objForm->HasValue("x_id_produto")) {
				$this->id_produto->setFormValue($objForm->GetValue("x_id_produto"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_produto"])) {
				$this->id_produto->setQueryStringValue($_GET["id_produto"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_produto->CurrentValue = NULL;
			}
		}

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
					$this->Page_Terminate("produtoslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "produtoslist.php")
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
		if (!$this->id_produto->FldIsDetailKey)
			$this->id_produto->setFormValue($objForm->GetValue("x_id_produto"));
		if (!$this->codigo_produto->FldIsDetailKey) {
			$this->codigo_produto->setFormValue($objForm->GetValue("x_codigo_produto"));
		}
		if (!$this->nome_produto->FldIsDetailKey) {
			$this->nome_produto->setFormValue($objForm->GetValue("x_nome_produto"));
		}
		if (!$this->modelo_produto->FldIsDetailKey) {
			$this->modelo_produto->setFormValue($objForm->GetValue("x_modelo_produto"));
		}
		if (!$this->id_departamento_produto->FldIsDetailKey) {
			$this->id_departamento_produto->setFormValue($objForm->GetValue("x_id_departamento_produto"));
		}
		if (!$this->id_marca_produto->FldIsDetailKey) {
			$this->id_marca_produto->setFormValue($objForm->GetValue("x_id_marca_produto"));
		}
		if (!$this->status_produto->FldIsDetailKey) {
			$this->status_produto->setFormValue($objForm->GetValue("x_status_produto"));
		}
		if (!$this->unidade_medida_produto->FldIsDetailKey) {
			$this->unidade_medida_produto->setFormValue($objForm->GetValue("x_unidade_medida_produto"));
		}
		if (!$this->unidades->FldIsDetailKey) {
			$this->unidades->setFormValue($objForm->GetValue("x_unidades"));
		}
		if (!$this->peso_produto->FldIsDetailKey) {
			$this->peso_produto->setFormValue($objForm->GetValue("x_peso_produto"));
		}
		if (!$this->data_adicionado->FldIsDetailKey) {
			$this->data_adicionado->setFormValue($objForm->GetValue("x_data_adicionado"));
			$this->data_adicionado->CurrentValue = ew_UnFormatDateTime($this->data_adicionado->CurrentValue, 0);
		}
		if (!$this->hora_adicionado->FldIsDetailKey) {
			$this->hora_adicionado->setFormValue($objForm->GetValue("x_hora_adicionado"));
			$this->hora_adicionado->CurrentValue = ew_UnFormatDateTime($this->hora_adicionado->CurrentValue, 4);
		}
		if (!$this->preco_produto->FldIsDetailKey) {
			$this->preco_produto->setFormValue($objForm->GetValue("x_preco_produto"));
		}
		if (!$this->descricao->FldIsDetailKey) {
			$this->descricao->setFormValue($objForm->GetValue("x_descricao"));
		}
		if (!$this->ipi->FldIsDetailKey) {
			$this->ipi->setFormValue($objForm->GetValue("x_ipi"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_produto->CurrentValue = $this->id_produto->FormValue;
		$this->codigo_produto->CurrentValue = $this->codigo_produto->FormValue;
		$this->nome_produto->CurrentValue = $this->nome_produto->FormValue;
		$this->modelo_produto->CurrentValue = $this->modelo_produto->FormValue;
		$this->id_departamento_produto->CurrentValue = $this->id_departamento_produto->FormValue;
		$this->id_marca_produto->CurrentValue = $this->id_marca_produto->FormValue;
		$this->status_produto->CurrentValue = $this->status_produto->FormValue;
		$this->unidade_medida_produto->CurrentValue = $this->unidade_medida_produto->FormValue;
		$this->unidades->CurrentValue = $this->unidades->FormValue;
		$this->peso_produto->CurrentValue = $this->peso_produto->FormValue;
		$this->data_adicionado->CurrentValue = $this->data_adicionado->FormValue;
		$this->data_adicionado->CurrentValue = ew_UnFormatDateTime($this->data_adicionado->CurrentValue, 0);
		$this->hora_adicionado->CurrentValue = $this->hora_adicionado->FormValue;
		$this->hora_adicionado->CurrentValue = ew_UnFormatDateTime($this->hora_adicionado->CurrentValue, 4);
		$this->preco_produto->CurrentValue = $this->preco_produto->FormValue;
		$this->descricao->CurrentValue = $this->descricao->FormValue;
		$this->ipi->CurrentValue = $this->ipi->FormValue;
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
		$this->id_produto->setDbValue($row['id_produto']);
		$this->codigo_produto->setDbValue($row['codigo_produto']);
		$this->nome_produto->setDbValue($row['nome_produto']);
		$this->modelo_produto->setDbValue($row['modelo_produto']);
		$this->id_departamento_produto->setDbValue($row['id_departamento_produto']);
		$this->id_marca_produto->setDbValue($row['id_marca_produto']);
		$this->status_produto->setDbValue($row['status_produto']);
		$this->unidade_medida_produto->setDbValue($row['unidade_medida_produto']);
		$this->unidades->setDbValue($row['unidades']);
		$this->peso_produto->setDbValue($row['peso_produto']);
		$this->data_adicionado->setDbValue($row['data_adicionado']);
		$this->hora_adicionado->setDbValue($row['hora_adicionado']);
		$this->preco_produto->setDbValue($row['preco_produto']);
		$this->descricao->setDbValue($row['descricao']);
		$this->ipi->setDbValue($row['ipi']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_produto'] = NULL;
		$row['codigo_produto'] = NULL;
		$row['nome_produto'] = NULL;
		$row['modelo_produto'] = NULL;
		$row['id_departamento_produto'] = NULL;
		$row['id_marca_produto'] = NULL;
		$row['status_produto'] = NULL;
		$row['unidade_medida_produto'] = NULL;
		$row['unidades'] = NULL;
		$row['peso_produto'] = NULL;
		$row['data_adicionado'] = NULL;
		$row['hora_adicionado'] = NULL;
		$row['preco_produto'] = NULL;
		$row['descricao'] = NULL;
		$row['ipi'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_produto->DbValue = $row['id_produto'];
		$this->codigo_produto->DbValue = $row['codigo_produto'];
		$this->nome_produto->DbValue = $row['nome_produto'];
		$this->modelo_produto->DbValue = $row['modelo_produto'];
		$this->id_departamento_produto->DbValue = $row['id_departamento_produto'];
		$this->id_marca_produto->DbValue = $row['id_marca_produto'];
		$this->status_produto->DbValue = $row['status_produto'];
		$this->unidade_medida_produto->DbValue = $row['unidade_medida_produto'];
		$this->unidades->DbValue = $row['unidades'];
		$this->peso_produto->DbValue = $row['peso_produto'];
		$this->data_adicionado->DbValue = $row['data_adicionado'];
		$this->hora_adicionado->DbValue = $row['hora_adicionado'];
		$this->preco_produto->DbValue = $row['preco_produto'];
		$this->descricao->DbValue = $row['descricao'];
		$this->ipi->DbValue = $row['ipi'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_produto")) <> "")
			$this->id_produto->CurrentValue = $this->getKey("id_produto"); // id_produto
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

		if ($this->preco_produto->FormValue == $this->preco_produto->CurrentValue && is_numeric(ew_StrToFloat($this->preco_produto->CurrentValue)))
			$this->preco_produto->CurrentValue = ew_StrToFloat($this->preco_produto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_produto
		// codigo_produto
		// nome_produto
		// modelo_produto
		// id_departamento_produto
		// id_marca_produto
		// status_produto
		// unidade_medida_produto
		// unidades
		// peso_produto
		// data_adicionado
		// hora_adicionado
		// preco_produto
		// descricao
		// ipi

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_produto
		$this->id_produto->ViewValue = $this->id_produto->CurrentValue;
		$this->id_produto->ViewCustomAttributes = "";

		// codigo_produto
		$this->codigo_produto->ViewValue = $this->codigo_produto->CurrentValue;
		$this->codigo_produto->ViewCustomAttributes = "";

		// nome_produto
		$this->nome_produto->ViewValue = $this->nome_produto->CurrentValue;
		$this->nome_produto->ViewCustomAttributes = "";

		// modelo_produto
		$this->modelo_produto->ViewValue = $this->modelo_produto->CurrentValue;
		$this->modelo_produto->ViewCustomAttributes = "";

		// id_departamento_produto
		if (strval($this->id_departamento_produto->CurrentValue) <> "") {
			$sFilterWrk = "`id_categoria`" . ew_SearchString("=", $this->id_departamento_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_categoria`, `categoria` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
		$sWhereWrk = "";
		$this->id_departamento_produto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_departamento_produto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_departamento_produto->ViewValue = $this->id_departamento_produto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_departamento_produto->ViewValue = $this->id_departamento_produto->CurrentValue;
			}
		} else {
			$this->id_departamento_produto->ViewValue = NULL;
		}
		$this->id_departamento_produto->ViewCustomAttributes = "";

		// id_marca_produto
		if (strval($this->id_marca_produto->CurrentValue) <> "") {
			$sFilterWrk = "`id_marca`" . ew_SearchString("=", $this->id_marca_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_marca`, `nome_marca` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
		$sWhereWrk = "";
		$this->id_marca_produto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_marca_produto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_marca_produto->ViewValue = $this->id_marca_produto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_marca_produto->ViewValue = $this->id_marca_produto->CurrentValue;
			}
		} else {
			$this->id_marca_produto->ViewValue = NULL;
		}
		$this->id_marca_produto->ViewCustomAttributes = "";

		// status_produto
		if (strval($this->status_produto->CurrentValue) <> "") {
			$this->status_produto->ViewValue = $this->status_produto->OptionCaption($this->status_produto->CurrentValue);
		} else {
			$this->status_produto->ViewValue = NULL;
		}
		$this->status_produto->ViewCustomAttributes = "";

		// unidade_medida_produto
		$this->unidade_medida_produto->ViewValue = $this->unidade_medida_produto->CurrentValue;
		$this->unidade_medida_produto->ViewCustomAttributes = "";

		// unidades
		$this->unidades->ViewValue = $this->unidades->CurrentValue;
		$this->unidades->ViewCustomAttributes = "";

		// peso_produto
		$this->peso_produto->ViewValue = $this->peso_produto->CurrentValue;
		$this->peso_produto->ViewCustomAttributes = "";

		// data_adicionado
		$this->data_adicionado->ViewValue = $this->data_adicionado->CurrentValue;
		$this->data_adicionado->ViewValue = ew_FormatDateTime($this->data_adicionado->ViewValue, 0);
		$this->data_adicionado->ViewCustomAttributes = "";

		// hora_adicionado
		$this->hora_adicionado->ViewValue = $this->hora_adicionado->CurrentValue;
		$this->hora_adicionado->ViewValue = ew_FormatDateTime($this->hora_adicionado->ViewValue, 4);
		$this->hora_adicionado->ViewCustomAttributes = "";

		// preco_produto
		$this->preco_produto->ViewValue = $this->preco_produto->CurrentValue;
		$this->preco_produto->ViewValue = ew_FormatCurrency($this->preco_produto->ViewValue, 2, -1, -1, -1);
		$this->preco_produto->ViewCustomAttributes = "";

		// descricao
		$this->descricao->ViewValue = $this->descricao->CurrentValue;
		$this->descricao->ViewCustomAttributes = "";

		// ipi
		$this->ipi->ViewValue = $this->ipi->CurrentValue;
		$this->ipi->ViewCustomAttributes = "";

			// id_produto
			$this->id_produto->LinkCustomAttributes = "";
			$this->id_produto->HrefValue = "";
			$this->id_produto->TooltipValue = "";

			// codigo_produto
			$this->codigo_produto->LinkCustomAttributes = "";
			$this->codigo_produto->HrefValue = "";
			$this->codigo_produto->TooltipValue = "";

			// nome_produto
			$this->nome_produto->LinkCustomAttributes = "";
			$this->nome_produto->HrefValue = "";
			$this->nome_produto->TooltipValue = "";

			// modelo_produto
			$this->modelo_produto->LinkCustomAttributes = "";
			$this->modelo_produto->HrefValue = "";
			$this->modelo_produto->TooltipValue = "";

			// id_departamento_produto
			$this->id_departamento_produto->LinkCustomAttributes = "";
			$this->id_departamento_produto->HrefValue = "";
			$this->id_departamento_produto->TooltipValue = "";

			// id_marca_produto
			$this->id_marca_produto->LinkCustomAttributes = "";
			$this->id_marca_produto->HrefValue = "";
			$this->id_marca_produto->TooltipValue = "";

			// status_produto
			$this->status_produto->LinkCustomAttributes = "";
			$this->status_produto->HrefValue = "";
			$this->status_produto->TooltipValue = "";

			// unidade_medida_produto
			$this->unidade_medida_produto->LinkCustomAttributes = "";
			$this->unidade_medida_produto->HrefValue = "";
			$this->unidade_medida_produto->TooltipValue = "";

			// unidades
			$this->unidades->LinkCustomAttributes = "";
			$this->unidades->HrefValue = "";
			$this->unidades->TooltipValue = "";

			// peso_produto
			$this->peso_produto->LinkCustomAttributes = "";
			$this->peso_produto->HrefValue = "";
			$this->peso_produto->TooltipValue = "";

			// data_adicionado
			$this->data_adicionado->LinkCustomAttributes = "";
			$this->data_adicionado->HrefValue = "";
			$this->data_adicionado->TooltipValue = "";

			// hora_adicionado
			$this->hora_adicionado->LinkCustomAttributes = "";
			$this->hora_adicionado->HrefValue = "";
			$this->hora_adicionado->TooltipValue = "";

			// preco_produto
			$this->preco_produto->LinkCustomAttributes = "";
			$this->preco_produto->HrefValue = "";
			$this->preco_produto->TooltipValue = "";

			// descricao
			$this->descricao->LinkCustomAttributes = "";
			$this->descricao->HrefValue = "";
			$this->descricao->TooltipValue = "";

			// ipi
			$this->ipi->LinkCustomAttributes = "";
			$this->ipi->HrefValue = "";
			$this->ipi->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_produto
			$this->id_produto->EditAttrs["class"] = "form-control";
			$this->id_produto->EditCustomAttributes = "";
			$this->id_produto->EditValue = $this->id_produto->CurrentValue;
			$this->id_produto->ViewCustomAttributes = "";

			// codigo_produto
			$this->codigo_produto->EditAttrs["class"] = "form-control";
			$this->codigo_produto->EditCustomAttributes = "";
			$this->codigo_produto->EditValue = ew_HtmlEncode($this->codigo_produto->CurrentValue);
			$this->codigo_produto->PlaceHolder = ew_RemoveHtml($this->codigo_produto->FldCaption());

			// nome_produto
			$this->nome_produto->EditAttrs["class"] = "form-control";
			$this->nome_produto->EditCustomAttributes = "";
			$this->nome_produto->EditValue = ew_HtmlEncode($this->nome_produto->CurrentValue);
			$this->nome_produto->PlaceHolder = ew_RemoveHtml($this->nome_produto->FldCaption());

			// modelo_produto
			$this->modelo_produto->EditAttrs["class"] = "form-control";
			$this->modelo_produto->EditCustomAttributes = "";
			$this->modelo_produto->EditValue = ew_HtmlEncode($this->modelo_produto->CurrentValue);
			$this->modelo_produto->PlaceHolder = ew_RemoveHtml($this->modelo_produto->FldCaption());

			// id_departamento_produto
			$this->id_departamento_produto->EditAttrs["class"] = "form-control";
			$this->id_departamento_produto->EditCustomAttributes = "";
			if (trim(strval($this->id_departamento_produto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_categoria`" . ew_SearchString("=", $this->id_departamento_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_categoria`, `categoria` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categoria`";
			$sWhereWrk = "";
			$this->id_departamento_produto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_departamento_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_departamento_produto->EditValue = $arwrk;

			// id_marca_produto
			$this->id_marca_produto->EditAttrs["class"] = "form-control";
			$this->id_marca_produto->EditCustomAttributes = "";
			if (trim(strval($this->id_marca_produto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_marca`" . ew_SearchString("=", $this->id_marca_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_marca`, `nome_marca` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `marcas`";
			$sWhereWrk = "";
			$this->id_marca_produto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_marca_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_marca_produto->EditValue = $arwrk;

			// status_produto
			$this->status_produto->EditCustomAttributes = "";
			$this->status_produto->EditValue = $this->status_produto->Options(FALSE);

			// unidade_medida_produto
			$this->unidade_medida_produto->EditAttrs["class"] = "form-control";
			$this->unidade_medida_produto->EditCustomAttributes = "";
			$this->unidade_medida_produto->EditValue = ew_HtmlEncode($this->unidade_medida_produto->CurrentValue);
			$this->unidade_medida_produto->PlaceHolder = ew_RemoveHtml($this->unidade_medida_produto->FldCaption());

			// unidades
			$this->unidades->EditAttrs["class"] = "form-control";
			$this->unidades->EditCustomAttributes = "";
			$this->unidades->EditValue = ew_HtmlEncode($this->unidades->CurrentValue);
			$this->unidades->PlaceHolder = ew_RemoveHtml($this->unidades->FldCaption());

			// peso_produto
			$this->peso_produto->EditAttrs["class"] = "form-control";
			$this->peso_produto->EditCustomAttributes = "";
			$this->peso_produto->EditValue = ew_HtmlEncode($this->peso_produto->CurrentValue);
			$this->peso_produto->PlaceHolder = ew_RemoveHtml($this->peso_produto->FldCaption());

			// data_adicionado
			// hora_adicionado
			// preco_produto

			$this->preco_produto->EditAttrs["class"] = "form-control";
			$this->preco_produto->EditCustomAttributes = "";
			$this->preco_produto->EditValue = ew_HtmlEncode($this->preco_produto->CurrentValue);
			$this->preco_produto->PlaceHolder = ew_RemoveHtml($this->preco_produto->FldCaption());
			if (strval($this->preco_produto->EditValue) <> "" && is_numeric($this->preco_produto->EditValue)) $this->preco_produto->EditValue = ew_FormatNumber($this->preco_produto->EditValue, -2, -1, -2, -1);

			// descricao
			$this->descricao->EditAttrs["class"] = "form-control";
			$this->descricao->EditCustomAttributes = "";
			$this->descricao->EditValue = ew_HtmlEncode($this->descricao->CurrentValue);
			$this->descricao->PlaceHolder = ew_RemoveHtml($this->descricao->FldCaption());

			// ipi
			$this->ipi->EditAttrs["class"] = "form-control";
			$this->ipi->EditCustomAttributes = "";
			$this->ipi->EditValue = ew_HtmlEncode($this->ipi->CurrentValue);
			$this->ipi->PlaceHolder = ew_RemoveHtml($this->ipi->FldCaption());

			// Edit refer script
			// id_produto

			$this->id_produto->LinkCustomAttributes = "";
			$this->id_produto->HrefValue = "";

			// codigo_produto
			$this->codigo_produto->LinkCustomAttributes = "";
			$this->codigo_produto->HrefValue = "";

			// nome_produto
			$this->nome_produto->LinkCustomAttributes = "";
			$this->nome_produto->HrefValue = "";

			// modelo_produto
			$this->modelo_produto->LinkCustomAttributes = "";
			$this->modelo_produto->HrefValue = "";

			// id_departamento_produto
			$this->id_departamento_produto->LinkCustomAttributes = "";
			$this->id_departamento_produto->HrefValue = "";

			// id_marca_produto
			$this->id_marca_produto->LinkCustomAttributes = "";
			$this->id_marca_produto->HrefValue = "";

			// status_produto
			$this->status_produto->LinkCustomAttributes = "";
			$this->status_produto->HrefValue = "";

			// unidade_medida_produto
			$this->unidade_medida_produto->LinkCustomAttributes = "";
			$this->unidade_medida_produto->HrefValue = "";

			// unidades
			$this->unidades->LinkCustomAttributes = "";
			$this->unidades->HrefValue = "";

			// peso_produto
			$this->peso_produto->LinkCustomAttributes = "";
			$this->peso_produto->HrefValue = "";

			// data_adicionado
			$this->data_adicionado->LinkCustomAttributes = "";
			$this->data_adicionado->HrefValue = "";

			// hora_adicionado
			$this->hora_adicionado->LinkCustomAttributes = "";
			$this->hora_adicionado->HrefValue = "";

			// preco_produto
			$this->preco_produto->LinkCustomAttributes = "";
			$this->preco_produto->HrefValue = "";

			// descricao
			$this->descricao->LinkCustomAttributes = "";
			$this->descricao->HrefValue = "";

			// ipi
			$this->ipi->LinkCustomAttributes = "";
			$this->ipi->HrefValue = "";
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
		if (!ew_CheckInteger($this->codigo_produto->FormValue)) {
			ew_AddMessage($gsFormError, $this->codigo_produto->FldErrMsg());
		}
		if (!$this->unidades->FldIsDetailKey && !is_null($this->unidades->FormValue) && $this->unidades->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unidades->FldCaption(), $this->unidades->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->unidades->FormValue)) {
			ew_AddMessage($gsFormError, $this->unidades->FldErrMsg());
		}
		if (!ew_CheckNumber($this->preco_produto->FormValue)) {
			ew_AddMessage($gsFormError, $this->preco_produto->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ipi->FormValue)) {
			ew_AddMessage($gsFormError, $this->ipi->FldErrMsg());
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

			// codigo_produto
			$this->codigo_produto->SetDbValueDef($rsnew, $this->codigo_produto->CurrentValue, NULL, $this->codigo_produto->ReadOnly);

			// nome_produto
			$this->nome_produto->SetDbValueDef($rsnew, $this->nome_produto->CurrentValue, NULL, $this->nome_produto->ReadOnly);

			// modelo_produto
			$this->modelo_produto->SetDbValueDef($rsnew, $this->modelo_produto->CurrentValue, NULL, $this->modelo_produto->ReadOnly);

			// id_departamento_produto
			$this->id_departamento_produto->SetDbValueDef($rsnew, $this->id_departamento_produto->CurrentValue, NULL, $this->id_departamento_produto->ReadOnly);

			// id_marca_produto
			$this->id_marca_produto->SetDbValueDef($rsnew, $this->id_marca_produto->CurrentValue, NULL, $this->id_marca_produto->ReadOnly);

			// status_produto
			$this->status_produto->SetDbValueDef($rsnew, $this->status_produto->CurrentValue, NULL, $this->status_produto->ReadOnly);

			// unidade_medida_produto
			$this->unidade_medida_produto->SetDbValueDef($rsnew, $this->unidade_medida_produto->CurrentValue, NULL, $this->unidade_medida_produto->ReadOnly);

			// unidades
			$this->unidades->SetDbValueDef($rsnew, $this->unidades->CurrentValue, 0, $this->unidades->ReadOnly);

			// peso_produto
			$this->peso_produto->SetDbValueDef($rsnew, $this->peso_produto->CurrentValue, NULL, $this->peso_produto->ReadOnly);

			// data_adicionado
			$this->data_adicionado->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
			$rsnew['data_adicionado'] = &$this->data_adicionado->DbValue;

			// hora_adicionado
			$this->hora_adicionado->SetDbValueDef($rsnew, ew_CurrentTime(), NULL);
			$rsnew['hora_adicionado'] = &$this->hora_adicionado->DbValue;

			// preco_produto
			$this->preco_produto->SetDbValueDef($rsnew, $this->preco_produto->CurrentValue, NULL, $this->preco_produto->ReadOnly);

			// descricao
			$this->descricao->SetDbValueDef($rsnew, $this->descricao->CurrentValue, NULL, $this->descricao->ReadOnly);

			// ipi
			$this->ipi->SetDbValueDef($rsnew, $this->ipi->CurrentValue, NULL, $this->ipi->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produtoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_departamento_produto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_categoria` AS `LinkFld`, `categoria` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_categoria` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_departamento_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_marca_produto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_marca` AS `LinkFld`, `nome_marca` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_marca` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_marca_produto, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($produtos_edit)) $produtos_edit = new cprodutos_edit();

// Page init
$produtos_edit->Page_Init();

// Page main
$produtos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produtos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fprodutosedit = new ew_Form("fprodutosedit", "edit");

// Validate form
fprodutosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codigo_produto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->codigo_produto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unidades");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $produtos->unidades->FldCaption(), $produtos->unidades->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unidades");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->unidades->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_preco_produto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->preco_produto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ipi");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->ipi->FldErrMsg()) ?>");

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
fprodutosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fprodutosedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fprodutosedit.Lists["x_id_departamento_produto"] = {"LinkField":"x_id_categoria","Ajax":true,"AutoFill":false,"DisplayFields":["x_categoria","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categoria"};
fprodutosedit.Lists["x_id_departamento_produto"].Data = "<?php echo $produtos_edit->id_departamento_produto->LookupFilterQuery(FALSE, "edit") ?>";
fprodutosedit.Lists["x_id_marca_produto"] = {"LinkField":"x_id_marca","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_marca","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
fprodutosedit.Lists["x_id_marca_produto"].Data = "<?php echo $produtos_edit->id_marca_produto->LookupFilterQuery(FALSE, "edit") ?>";
fprodutosedit.Lists["x_status_produto"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fprodutosedit.Lists["x_status_produto"].Options = <?php echo json_encode($produtos_edit->status_produto->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $produtos_edit->ShowPageHeader(); ?>
<?php
$produtos_edit->ShowMessage();
?>
<form name="fprodutosedit" id="fprodutosedit" class="<?php echo $produtos_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produtos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produtos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produtos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($produtos_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($produtos->id_produto->Visible) { // id_produto ?>
	<div id="r_id_produto" class="form-group">
		<label id="elh_produtos_id_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->id_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->id_produto->CellAttributes() ?>>
<span id="el_produtos_id_produto">
<span<?php echo $produtos->id_produto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $produtos->id_produto->EditValue ?></p></span>
</span>
<input type="hidden" data-table="produtos" data-field="x_id_produto" name="x_id_produto" id="x_id_produto" value="<?php echo ew_HtmlEncode($produtos->id_produto->CurrentValue) ?>">
<?php echo $produtos->id_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
	<div id="r_codigo_produto" class="form-group">
		<label id="elh_produtos_codigo_produto" for="x_codigo_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->codigo_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->codigo_produto->CellAttributes() ?>>
<span id="el_produtos_codigo_produto">
<input type="text" data-table="produtos" data-field="x_codigo_produto" name="x_codigo_produto" id="x_codigo_produto" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->codigo_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->codigo_produto->EditValue ?>"<?php echo $produtos->codigo_produto->EditAttributes() ?>>
</span>
<?php echo $produtos->codigo_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
	<div id="r_nome_produto" class="form-group">
		<label id="elh_produtos_nome_produto" for="x_nome_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->nome_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->nome_produto->CellAttributes() ?>>
<span id="el_produtos_nome_produto">
<input type="text" data-table="produtos" data-field="x_nome_produto" name="x_nome_produto" id="x_nome_produto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($produtos->nome_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->nome_produto->EditValue ?>"<?php echo $produtos->nome_produto->EditAttributes() ?>>
</span>
<?php echo $produtos->nome_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
	<div id="r_modelo_produto" class="form-group">
		<label id="elh_produtos_modelo_produto" for="x_modelo_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->modelo_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->modelo_produto->CellAttributes() ?>>
<span id="el_produtos_modelo_produto">
<input type="text" data-table="produtos" data-field="x_modelo_produto" name="x_modelo_produto" id="x_modelo_produto" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($produtos->modelo_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->modelo_produto->EditValue ?>"<?php echo $produtos->modelo_produto->EditAttributes() ?>>
</span>
<?php echo $produtos->modelo_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->id_departamento_produto->Visible) { // id_departamento_produto ?>
	<div id="r_id_departamento_produto" class="form-group">
		<label id="elh_produtos_id_departamento_produto" for="x_id_departamento_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->id_departamento_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->id_departamento_produto->CellAttributes() ?>>
<span id="el_produtos_id_departamento_produto">
<select data-table="produtos" data-field="x_id_departamento_produto" data-value-separator="<?php echo $produtos->id_departamento_produto->DisplayValueSeparatorAttribute() ?>" id="x_id_departamento_produto" name="x_id_departamento_produto"<?php echo $produtos->id_departamento_produto->EditAttributes() ?>>
<?php echo $produtos->id_departamento_produto->SelectOptionListHtml("x_id_departamento_produto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $produtos->id_departamento_produto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_departamento_produto',url:'categoriaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_departamento_produto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $produtos->id_departamento_produto->FldCaption() ?></span></button>
</span>
<?php echo $produtos->id_departamento_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
	<div id="r_id_marca_produto" class="form-group">
		<label id="elh_produtos_id_marca_produto" for="x_id_marca_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->id_marca_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->id_marca_produto->CellAttributes() ?>>
<span id="el_produtos_id_marca_produto">
<select data-table="produtos" data-field="x_id_marca_produto" data-value-separator="<?php echo $produtos->id_marca_produto->DisplayValueSeparatorAttribute() ?>" id="x_id_marca_produto" name="x_id_marca_produto"<?php echo $produtos->id_marca_produto->EditAttributes() ?>>
<?php echo $produtos->id_marca_produto->SelectOptionListHtml("x_id_marca_produto") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $produtos->id_marca_produto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_marca_produto',url:'marcasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_marca_produto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $produtos->id_marca_produto->FldCaption() ?></span></button>
</span>
<?php echo $produtos->id_marca_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->status_produto->Visible) { // status_produto ?>
	<div id="r_status_produto" class="form-group">
		<label id="elh_produtos_status_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->status_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->status_produto->CellAttributes() ?>>
<span id="el_produtos_status_produto">
<div id="tp_x_status_produto" class="ewTemplate"><input type="radio" data-table="produtos" data-field="x_status_produto" data-value-separator="<?php echo $produtos->status_produto->DisplayValueSeparatorAttribute() ?>" name="x_status_produto" id="x_status_produto" value="{value}"<?php echo $produtos->status_produto->EditAttributes() ?>></div>
<div id="dsl_x_status_produto" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $produtos->status_produto->RadioButtonListHtml(FALSE, "x_status_produto") ?>
</div></div>
</span>
<?php echo $produtos->status_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->unidade_medida_produto->Visible) { // unidade_medida_produto ?>
	<div id="r_unidade_medida_produto" class="form-group">
		<label id="elh_produtos_unidade_medida_produto" for="x_unidade_medida_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->unidade_medida_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->unidade_medida_produto->CellAttributes() ?>>
<span id="el_produtos_unidade_medida_produto">
<input type="text" data-table="produtos" data-field="x_unidade_medida_produto" name="x_unidade_medida_produto" id="x_unidade_medida_produto" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($produtos->unidade_medida_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->unidade_medida_produto->EditValue ?>"<?php echo $produtos->unidade_medida_produto->EditAttributes() ?>>
</span>
<?php echo $produtos->unidade_medida_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->unidades->Visible) { // unidades ?>
	<div id="r_unidades" class="form-group">
		<label id="elh_produtos_unidades" for="x_unidades" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->unidades->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->unidades->CellAttributes() ?>>
<span id="el_produtos_unidades">
<input type="text" data-table="produtos" data-field="x_unidades" name="x_unidades" id="x_unidades" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->unidades->getPlaceHolder()) ?>" value="<?php echo $produtos->unidades->EditValue ?>"<?php echo $produtos->unidades->EditAttributes() ?>>
</span>
<?php echo $produtos->unidades->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->peso_produto->Visible) { // peso_produto ?>
	<div id="r_peso_produto" class="form-group">
		<label id="elh_produtos_peso_produto" for="x_peso_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->peso_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->peso_produto->CellAttributes() ?>>
<span id="el_produtos_peso_produto">
<input type="text" data-table="produtos" data-field="x_peso_produto" name="x_peso_produto" id="x_peso_produto" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($produtos->peso_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->peso_produto->EditValue ?>"<?php echo $produtos->peso_produto->EditAttributes() ?>>
</span>
<?php echo $produtos->peso_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
	<div id="r_preco_produto" class="form-group">
		<label id="elh_produtos_preco_produto" for="x_preco_produto" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->preco_produto->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->preco_produto->CellAttributes() ?>>
<span id="el_produtos_preco_produto">
<input type="text" data-table="produtos" data-field="x_preco_produto" name="x_preco_produto" id="x_preco_produto" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->preco_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->preco_produto->EditValue ?>"<?php echo $produtos->preco_produto->EditAttributes() ?>>
</span>
<?php echo $produtos->preco_produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->descricao->Visible) { // descricao ?>
	<div id="r_descricao" class="form-group">
		<label id="elh_produtos_descricao" for="x_descricao" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->descricao->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->descricao->CellAttributes() ?>>
<span id="el_produtos_descricao">
<input type="text" data-table="produtos" data-field="x_descricao" name="x_descricao" id="x_descricao" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($produtos->descricao->getPlaceHolder()) ?>" value="<?php echo $produtos->descricao->EditValue ?>"<?php echo $produtos->descricao->EditAttributes() ?>>
</span>
<?php echo $produtos->descricao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->ipi->Visible) { // ipi ?>
	<div id="r_ipi" class="form-group">
		<label id="elh_produtos_ipi" for="x_ipi" class="<?php echo $produtos_edit->LeftColumnClass ?>"><?php echo $produtos->ipi->FldCaption() ?></label>
		<div class="<?php echo $produtos_edit->RightColumnClass ?>"><div<?php echo $produtos->ipi->CellAttributes() ?>>
<span id="el_produtos_ipi">
<input type="text" data-table="produtos" data-field="x_ipi" name="x_ipi" id="x_ipi" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->ipi->getPlaceHolder()) ?>" value="<?php echo $produtos->ipi->EditValue ?>"<?php echo $produtos->ipi->EditAttributes() ?>>
</span>
<?php echo $produtos->ipi->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$produtos_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $produtos_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $produtos_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fprodutosedit.Init();
</script>
<?php
$produtos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produtos_edit->Page_Terminate();
?>
