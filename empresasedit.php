<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "empresasinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$empresas_edit = NULL; // Initialize page object first

class cempresas_edit extends cempresas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_edit';

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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empresas', TRUE);

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
		$this->id_perfil->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_perfil->Visible = FALSE;
		$this->razao_social->SetVisibility();
		$this->proprietario->SetVisibility();
		$this->telefone->SetVisibility();
		$this->direcao->SetVisibility();
		$this->_email->SetVisibility();
		$this->id_endereco->SetVisibility();
		$this->nome_fantasia->SetVisibility();
		$this->cnpj->SetVisibility();
		$this->ie->SetVisibility();
		$this->fonecedor->SetVisibility();
		$this->celular->SetVisibility();
		$this->whatsapp->SetVisibility();
		$this->endereco_numero->SetVisibility();

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
		global $EW_EXPORT, $empresas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empresas);
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
					if ($pageName == "empresasview.php")
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
			if ($objForm->HasValue("x_id_perfil")) {
				$this->id_perfil->setFormValue($objForm->GetValue("x_id_perfil"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_perfil"])) {
				$this->id_perfil->setQueryStringValue($_GET["id_perfil"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_perfil->CurrentValue = NULL;
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
					$this->Page_Terminate("empresaslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "empresaslist.php")
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
		if (!$this->id_perfil->FldIsDetailKey)
			$this->id_perfil->setFormValue($objForm->GetValue("x_id_perfil"));
		if (!$this->razao_social->FldIsDetailKey) {
			$this->razao_social->setFormValue($objForm->GetValue("x_razao_social"));
		}
		if (!$this->proprietario->FldIsDetailKey) {
			$this->proprietario->setFormValue($objForm->GetValue("x_proprietario"));
		}
		if (!$this->telefone->FldIsDetailKey) {
			$this->telefone->setFormValue($objForm->GetValue("x_telefone"));
		}
		if (!$this->direcao->FldIsDetailKey) {
			$this->direcao->setFormValue($objForm->GetValue("x_direcao"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->id_endereco->FldIsDetailKey) {
			$this->id_endereco->setFormValue($objForm->GetValue("x_id_endereco"));
		}
		if (!$this->nome_fantasia->FldIsDetailKey) {
			$this->nome_fantasia->setFormValue($objForm->GetValue("x_nome_fantasia"));
		}
		if (!$this->cnpj->FldIsDetailKey) {
			$this->cnpj->setFormValue($objForm->GetValue("x_cnpj"));
		}
		if (!$this->ie->FldIsDetailKey) {
			$this->ie->setFormValue($objForm->GetValue("x_ie"));
		}
		if (!$this->fonecedor->FldIsDetailKey) {
			$this->fonecedor->setFormValue($objForm->GetValue("x_fonecedor"));
		}
		if (!$this->celular->FldIsDetailKey) {
			$this->celular->setFormValue($objForm->GetValue("x_celular"));
		}
		if (!$this->whatsapp->FldIsDetailKey) {
			$this->whatsapp->setFormValue($objForm->GetValue("x_whatsapp"));
		}
		if (!$this->endereco_numero->FldIsDetailKey) {
			$this->endereco_numero->setFormValue($objForm->GetValue("x_endereco_numero"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_perfil->CurrentValue = $this->id_perfil->FormValue;
		$this->razao_social->CurrentValue = $this->razao_social->FormValue;
		$this->proprietario->CurrentValue = $this->proprietario->FormValue;
		$this->telefone->CurrentValue = $this->telefone->FormValue;
		$this->direcao->CurrentValue = $this->direcao->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->id_endereco->CurrentValue = $this->id_endereco->FormValue;
		$this->nome_fantasia->CurrentValue = $this->nome_fantasia->FormValue;
		$this->cnpj->CurrentValue = $this->cnpj->FormValue;
		$this->ie->CurrentValue = $this->ie->FormValue;
		$this->fonecedor->CurrentValue = $this->fonecedor->FormValue;
		$this->celular->CurrentValue = $this->celular->FormValue;
		$this->whatsapp->CurrentValue = $this->whatsapp->FormValue;
		$this->endereco_numero->CurrentValue = $this->endereco_numero->FormValue;
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
		$this->id_perfil->setDbValue($row['id_perfil']);
		$this->razao_social->setDbValue($row['razao_social']);
		$this->proprietario->setDbValue($row['proprietario']);
		$this->telefone->setDbValue($row['telefone']);
		$this->direcao->setDbValue($row['direcao']);
		$this->_email->setDbValue($row['email']);
		$this->id_endereco->setDbValue($row['id_endereco']);
		$this->nome_fantasia->setDbValue($row['nome_fantasia']);
		$this->cnpj->setDbValue($row['cnpj']);
		$this->ie->setDbValue($row['ie']);
		$this->fonecedor->setDbValue($row['fonecedor']);
		$this->celular->setDbValue($row['celular']);
		$this->whatsapp->setDbValue($row['whatsapp']);
		$this->endereco_numero->setDbValue($row['endereco_numero']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_perfil'] = NULL;
		$row['razao_social'] = NULL;
		$row['proprietario'] = NULL;
		$row['telefone'] = NULL;
		$row['direcao'] = NULL;
		$row['email'] = NULL;
		$row['id_endereco'] = NULL;
		$row['nome_fantasia'] = NULL;
		$row['cnpj'] = NULL;
		$row['ie'] = NULL;
		$row['fonecedor'] = NULL;
		$row['celular'] = NULL;
		$row['whatsapp'] = NULL;
		$row['endereco_numero'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_perfil->DbValue = $row['id_perfil'];
		$this->razao_social->DbValue = $row['razao_social'];
		$this->proprietario->DbValue = $row['proprietario'];
		$this->telefone->DbValue = $row['telefone'];
		$this->direcao->DbValue = $row['direcao'];
		$this->_email->DbValue = $row['email'];
		$this->id_endereco->DbValue = $row['id_endereco'];
		$this->nome_fantasia->DbValue = $row['nome_fantasia'];
		$this->cnpj->DbValue = $row['cnpj'];
		$this->ie->DbValue = $row['ie'];
		$this->fonecedor->DbValue = $row['fonecedor'];
		$this->celular->DbValue = $row['celular'];
		$this->whatsapp->DbValue = $row['whatsapp'];
		$this->endereco_numero->DbValue = $row['endereco_numero'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_perfil")) <> "")
			$this->id_perfil->CurrentValue = $this->getKey("id_perfil"); // id_perfil
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_perfil
		// razao_social
		// proprietario
		// telefone
		// direcao
		// email
		// id_endereco
		// nome_fantasia
		// cnpj
		// ie
		// fonecedor
		// celular
		// whatsapp
		// endereco_numero

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_perfil
		$this->id_perfil->ViewValue = $this->id_perfil->CurrentValue;
		$this->id_perfil->ViewCustomAttributes = "";

		// razao_social
		$this->razao_social->ViewValue = $this->razao_social->CurrentValue;
		$this->razao_social->ViewCustomAttributes = "";

		// proprietario
		$this->proprietario->ViewValue = $this->proprietario->CurrentValue;
		$this->proprietario->ViewCustomAttributes = "";

		// telefone
		$this->telefone->ViewValue = $this->telefone->CurrentValue;
		$this->telefone->ViewCustomAttributes = "";

		// direcao
		$this->direcao->ViewValue = $this->direcao->CurrentValue;
		$this->direcao->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// id_endereco
		if (strval($this->id_endereco->CurrentValue) <> "") {
			$sFilterWrk = "`id_endereco`" . ew_SearchString("=", $this->id_endereco->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_endereco`, `endereco` AS `DispFld`, `bairro` AS `Disp2Fld`, `estado` AS `Disp3Fld`, `cidade` AS `Disp4Fld` FROM `endereco`";
		$sWhereWrk = "";
		$this->id_endereco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_endereco, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->id_endereco->ViewValue = $this->id_endereco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_endereco->ViewValue = $this->id_endereco->CurrentValue;
			}
		} else {
			$this->id_endereco->ViewValue = NULL;
		}
		$this->id_endereco->ViewCustomAttributes = "";

		// nome_fantasia
		$this->nome_fantasia->ViewValue = $this->nome_fantasia->CurrentValue;
		$this->nome_fantasia->ViewCustomAttributes = "";

		// cnpj
		$this->cnpj->ViewValue = $this->cnpj->CurrentValue;
		$this->cnpj->ViewCustomAttributes = "";

		// ie
		$this->ie->ViewValue = $this->ie->CurrentValue;
		$this->ie->ViewCustomAttributes = "";

		// fonecedor
		$this->fonecedor->ViewValue = $this->fonecedor->CurrentValue;
		$this->fonecedor->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// whatsapp
		$this->whatsapp->ViewValue = $this->whatsapp->CurrentValue;
		$this->whatsapp->ViewCustomAttributes = "";

		// endereco_numero
		$this->endereco_numero->ViewValue = $this->endereco_numero->CurrentValue;
		$this->endereco_numero->ViewCustomAttributes = "";

			// id_perfil
			$this->id_perfil->LinkCustomAttributes = "";
			$this->id_perfil->HrefValue = "";
			$this->id_perfil->TooltipValue = "";

			// razao_social
			$this->razao_social->LinkCustomAttributes = "";
			$this->razao_social->HrefValue = "";
			$this->razao_social->TooltipValue = "";

			// proprietario
			$this->proprietario->LinkCustomAttributes = "";
			$this->proprietario->HrefValue = "";
			$this->proprietario->TooltipValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";
			$this->telefone->TooltipValue = "";

			// direcao
			$this->direcao->LinkCustomAttributes = "";
			$this->direcao->HrefValue = "";
			$this->direcao->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// id_endereco
			$this->id_endereco->LinkCustomAttributes = "";
			$this->id_endereco->HrefValue = "";
			$this->id_endereco->TooltipValue = "";

			// nome_fantasia
			$this->nome_fantasia->LinkCustomAttributes = "";
			$this->nome_fantasia->HrefValue = "";
			$this->nome_fantasia->TooltipValue = "";

			// cnpj
			$this->cnpj->LinkCustomAttributes = "";
			$this->cnpj->HrefValue = "";
			$this->cnpj->TooltipValue = "";

			// ie
			$this->ie->LinkCustomAttributes = "";
			$this->ie->HrefValue = "";
			$this->ie->TooltipValue = "";

			// fonecedor
			$this->fonecedor->LinkCustomAttributes = "";
			$this->fonecedor->HrefValue = "";
			$this->fonecedor->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// whatsapp
			$this->whatsapp->LinkCustomAttributes = "";
			$this->whatsapp->HrefValue = "";
			$this->whatsapp->TooltipValue = "";

			// endereco_numero
			$this->endereco_numero->LinkCustomAttributes = "";
			$this->endereco_numero->HrefValue = "";
			$this->endereco_numero->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_perfil
			$this->id_perfil->EditAttrs["class"] = "form-control";
			$this->id_perfil->EditCustomAttributes = "";
			$this->id_perfil->EditValue = $this->id_perfil->CurrentValue;
			$this->id_perfil->ViewCustomAttributes = "";

			// razao_social
			$this->razao_social->EditAttrs["class"] = "form-control";
			$this->razao_social->EditCustomAttributes = "";
			$this->razao_social->EditValue = ew_HtmlEncode($this->razao_social->CurrentValue);
			$this->razao_social->PlaceHolder = ew_RemoveHtml($this->razao_social->FldCaption());

			// proprietario
			$this->proprietario->EditAttrs["class"] = "form-control";
			$this->proprietario->EditCustomAttributes = "";
			$this->proprietario->EditValue = ew_HtmlEncode($this->proprietario->CurrentValue);
			$this->proprietario->PlaceHolder = ew_RemoveHtml($this->proprietario->FldCaption());

			// telefone
			$this->telefone->EditAttrs["class"] = "form-control";
			$this->telefone->EditCustomAttributes = "";
			$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
			$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

			// direcao
			$this->direcao->EditAttrs["class"] = "form-control";
			$this->direcao->EditCustomAttributes = "";
			$this->direcao->EditValue = ew_HtmlEncode($this->direcao->CurrentValue);
			$this->direcao->PlaceHolder = ew_RemoveHtml($this->direcao->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// id_endereco
			$this->id_endereco->EditAttrs["class"] = "form-control";
			$this->id_endereco->EditCustomAttributes = "";
			if (trim(strval($this->id_endereco->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_endereco`" . ew_SearchString("=", $this->id_endereco->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_endereco`, `endereco` AS `DispFld`, `bairro` AS `Disp2Fld`, `estado` AS `Disp3Fld`, `cidade` AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `endereco`";
			$sWhereWrk = "";
			$this->id_endereco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_endereco, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_endereco->EditValue = $arwrk;

			// nome_fantasia
			$this->nome_fantasia->EditAttrs["class"] = "form-control";
			$this->nome_fantasia->EditCustomAttributes = "";
			$this->nome_fantasia->EditValue = ew_HtmlEncode($this->nome_fantasia->CurrentValue);
			$this->nome_fantasia->PlaceHolder = ew_RemoveHtml($this->nome_fantasia->FldCaption());

			// cnpj
			$this->cnpj->EditAttrs["class"] = "form-control";
			$this->cnpj->EditCustomAttributes = "";
			$this->cnpj->EditValue = ew_HtmlEncode($this->cnpj->CurrentValue);
			$this->cnpj->PlaceHolder = ew_RemoveHtml($this->cnpj->FldCaption());

			// ie
			$this->ie->EditAttrs["class"] = "form-control";
			$this->ie->EditCustomAttributes = "";
			$this->ie->EditValue = ew_HtmlEncode($this->ie->CurrentValue);
			$this->ie->PlaceHolder = ew_RemoveHtml($this->ie->FldCaption());

			// fonecedor
			$this->fonecedor->EditAttrs["class"] = "form-control";
			$this->fonecedor->EditCustomAttributes = "";
			$this->fonecedor->EditValue = ew_HtmlEncode($this->fonecedor->CurrentValue);
			$this->fonecedor->PlaceHolder = ew_RemoveHtml($this->fonecedor->FldCaption());

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->CurrentValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// whatsapp
			$this->whatsapp->EditAttrs["class"] = "form-control";
			$this->whatsapp->EditCustomAttributes = "";
			$this->whatsapp->EditValue = ew_HtmlEncode($this->whatsapp->CurrentValue);
			$this->whatsapp->PlaceHolder = ew_RemoveHtml($this->whatsapp->FldCaption());

			// endereco_numero
			$this->endereco_numero->EditAttrs["class"] = "form-control";
			$this->endereco_numero->EditCustomAttributes = "";
			$this->endereco_numero->EditValue = ew_HtmlEncode($this->endereco_numero->CurrentValue);
			$this->endereco_numero->PlaceHolder = ew_RemoveHtml($this->endereco_numero->FldCaption());

			// Edit refer script
			// id_perfil

			$this->id_perfil->LinkCustomAttributes = "";
			$this->id_perfil->HrefValue = "";

			// razao_social
			$this->razao_social->LinkCustomAttributes = "";
			$this->razao_social->HrefValue = "";

			// proprietario
			$this->proprietario->LinkCustomAttributes = "";
			$this->proprietario->HrefValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";

			// direcao
			$this->direcao->LinkCustomAttributes = "";
			$this->direcao->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// id_endereco
			$this->id_endereco->LinkCustomAttributes = "";
			$this->id_endereco->HrefValue = "";

			// nome_fantasia
			$this->nome_fantasia->LinkCustomAttributes = "";
			$this->nome_fantasia->HrefValue = "";

			// cnpj
			$this->cnpj->LinkCustomAttributes = "";
			$this->cnpj->HrefValue = "";

			// ie
			$this->ie->LinkCustomAttributes = "";
			$this->ie->HrefValue = "";

			// fonecedor
			$this->fonecedor->LinkCustomAttributes = "";
			$this->fonecedor->HrefValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";

			// whatsapp
			$this->whatsapp->LinkCustomAttributes = "";
			$this->whatsapp->HrefValue = "";

			// endereco_numero
			$this->endereco_numero->LinkCustomAttributes = "";
			$this->endereco_numero->HrefValue = "";
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
		if (!ew_CheckInteger($this->cnpj->FormValue)) {
			ew_AddMessage($gsFormError, $this->cnpj->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ie->FormValue)) {
			ew_AddMessage($gsFormError, $this->ie->FldErrMsg());
		}
		if (!ew_CheckInteger($this->fonecedor->FormValue)) {
			ew_AddMessage($gsFormError, $this->fonecedor->FldErrMsg());
		}
		if (!ew_CheckInteger($this->celular->FormValue)) {
			ew_AddMessage($gsFormError, $this->celular->FldErrMsg());
		}
		if (!ew_CheckInteger($this->whatsapp->FormValue)) {
			ew_AddMessage($gsFormError, $this->whatsapp->FldErrMsg());
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

			// razao_social
			$this->razao_social->SetDbValueDef($rsnew, $this->razao_social->CurrentValue, NULL, $this->razao_social->ReadOnly);

			// proprietario
			$this->proprietario->SetDbValueDef($rsnew, $this->proprietario->CurrentValue, NULL, $this->proprietario->ReadOnly);

			// telefone
			$this->telefone->SetDbValueDef($rsnew, $this->telefone->CurrentValue, NULL, $this->telefone->ReadOnly);

			// direcao
			$this->direcao->SetDbValueDef($rsnew, $this->direcao->CurrentValue, NULL, $this->direcao->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// id_endereco
			$this->id_endereco->SetDbValueDef($rsnew, $this->id_endereco->CurrentValue, NULL, $this->id_endereco->ReadOnly);

			// nome_fantasia
			$this->nome_fantasia->SetDbValueDef($rsnew, $this->nome_fantasia->CurrentValue, NULL, $this->nome_fantasia->ReadOnly);

			// cnpj
			$this->cnpj->SetDbValueDef($rsnew, $this->cnpj->CurrentValue, NULL, $this->cnpj->ReadOnly);

			// ie
			$this->ie->SetDbValueDef($rsnew, $this->ie->CurrentValue, NULL, $this->ie->ReadOnly);

			// fonecedor
			$this->fonecedor->SetDbValueDef($rsnew, $this->fonecedor->CurrentValue, NULL, $this->fonecedor->ReadOnly);

			// celular
			$this->celular->SetDbValueDef($rsnew, $this->celular->CurrentValue, NULL, $this->celular->ReadOnly);

			// whatsapp
			$this->whatsapp->SetDbValueDef($rsnew, $this->whatsapp->CurrentValue, NULL, $this->whatsapp->ReadOnly);

			// endereco_numero
			$this->endereco_numero->SetDbValueDef($rsnew, $this->endereco_numero->CurrentValue, NULL, $this->endereco_numero->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("empresaslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_endereco":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_endereco` AS `LinkFld`, `endereco` AS `DispFld`, `bairro` AS `Disp2Fld`, `estado` AS `Disp3Fld`, `cidade` AS `Disp4Fld` FROM `endereco`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_endereco` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_endereco, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($empresas_edit)) $empresas_edit = new cempresas_edit();

// Page init
$empresas_edit->Page_Init();

// Page main
$empresas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fempresasedit = new ew_Form("fempresasedit", "edit");

// Validate form
fempresasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cnpj");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->cnpj->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ie");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->ie->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fonecedor");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->fonecedor->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_celular");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->celular->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_whatsapp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas->whatsapp->FldErrMsg()) ?>");

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
fempresasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fempresasedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fempresasedit.Lists["x_id_endereco"] = {"LinkField":"x_id_endereco","Ajax":true,"AutoFill":false,"DisplayFields":["x_endereco","x_bairro","x_estado","x_cidade"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"endereco"};
fempresasedit.Lists["x_id_endereco"].Data = "<?php echo $empresas_edit->id_endereco->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $empresas_edit->ShowPageHeader(); ?>
<?php
$empresas_edit->ShowMessage();
?>
<form name="fempresasedit" id="fempresasedit" class="<?php echo $empresas_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($empresas_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
	<div id="r_id_perfil" class="form-group">
		<label id="elh_empresas_id_perfil" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->id_perfil->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->id_perfil->CellAttributes() ?>>
<span id="el_empresas_id_perfil">
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empresas->id_perfil->EditValue ?></p></span>
</span>
<input type="hidden" data-table="empresas" data-field="x_id_perfil" name="x_id_perfil" id="x_id_perfil" value="<?php echo ew_HtmlEncode($empresas->id_perfil->CurrentValue) ?>">
<?php echo $empresas->id_perfil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
	<div id="r_razao_social" class="form-group">
		<label id="elh_empresas_razao_social" for="x_razao_social" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->razao_social->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->razao_social->CellAttributes() ?>>
<span id="el_empresas_razao_social">
<input type="text" data-table="empresas" data-field="x_razao_social" name="x_razao_social" id="x_razao_social" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->razao_social->getPlaceHolder()) ?>" value="<?php echo $empresas->razao_social->EditValue ?>"<?php echo $empresas->razao_social->EditAttributes() ?>>
</span>
<?php echo $empresas->razao_social->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
	<div id="r_proprietario" class="form-group">
		<label id="elh_empresas_proprietario" for="x_proprietario" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->proprietario->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->proprietario->CellAttributes() ?>>
<span id="el_empresas_proprietario">
<input type="text" data-table="empresas" data-field="x_proprietario" name="x_proprietario" id="x_proprietario" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->proprietario->getPlaceHolder()) ?>" value="<?php echo $empresas->proprietario->EditValue ?>"<?php echo $empresas->proprietario->EditAttributes() ?>>
</span>
<?php echo $empresas->proprietario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
	<div id="r_telefone" class="form-group">
		<label id="elh_empresas_telefone" for="x_telefone" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->telefone->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->telefone->CellAttributes() ?>>
<span id="el_empresas_telefone">
<input type="text" data-table="empresas" data-field="x_telefone" name="x_telefone" id="x_telefone" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($empresas->telefone->getPlaceHolder()) ?>" value="<?php echo $empresas->telefone->EditValue ?>"<?php echo $empresas->telefone->EditAttributes() ?>>
</span>
<?php echo $empresas->telefone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
	<div id="r_direcao" class="form-group">
		<label id="elh_empresas_direcao" for="x_direcao" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->direcao->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->direcao->CellAttributes() ?>>
<span id="el_empresas_direcao">
<input type="text" data-table="empresas" data-field="x_direcao" name="x_direcao" id="x_direcao" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->direcao->getPlaceHolder()) ?>" value="<?php echo $empresas->direcao->EditValue ?>"<?php echo $empresas->direcao->EditAttributes() ?>>
</span>
<?php echo $empresas->direcao->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_empresas__email" for="x__email" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->_email->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->_email->CellAttributes() ?>>
<span id="el_empresas__email">
<input type="text" data-table="empresas" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="64" placeholder="<?php echo ew_HtmlEncode($empresas->_email->getPlaceHolder()) ?>" value="<?php echo $empresas->_email->EditValue ?>"<?php echo $empresas->_email->EditAttributes() ?>>
</span>
<?php echo $empresas->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
	<div id="r_id_endereco" class="form-group">
		<label id="elh_empresas_id_endereco" for="x_id_endereco" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->id_endereco->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->id_endereco->CellAttributes() ?>>
<span id="el_empresas_id_endereco">
<select data-table="empresas" data-field="x_id_endereco" data-value-separator="<?php echo $empresas->id_endereco->DisplayValueSeparatorAttribute() ?>" id="x_id_endereco" name="x_id_endereco"<?php echo $empresas->id_endereco->EditAttributes() ?>>
<?php echo $empresas->id_endereco->SelectOptionListHtml("x_id_endereco") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $empresas->id_endereco->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_endereco',url:'enderecoaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_endereco"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $empresas->id_endereco->FldCaption() ?></span></button>
</span>
<?php echo $empresas->id_endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
	<div id="r_nome_fantasia" class="form-group">
		<label id="elh_empresas_nome_fantasia" for="x_nome_fantasia" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->nome_fantasia->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->nome_fantasia->CellAttributes() ?>>
<span id="el_empresas_nome_fantasia">
<input type="text" data-table="empresas" data-field="x_nome_fantasia" name="x_nome_fantasia" id="x_nome_fantasia" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($empresas->nome_fantasia->getPlaceHolder()) ?>" value="<?php echo $empresas->nome_fantasia->EditValue ?>"<?php echo $empresas->nome_fantasia->EditAttributes() ?>>
</span>
<?php echo $empresas->nome_fantasia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->cnpj->Visible) { // cnpj ?>
	<div id="r_cnpj" class="form-group">
		<label id="elh_empresas_cnpj" for="x_cnpj" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->cnpj->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->cnpj->CellAttributes() ?>>
<span id="el_empresas_cnpj">
<input type="text" data-table="empresas" data-field="x_cnpj" name="x_cnpj" id="x_cnpj" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->cnpj->getPlaceHolder()) ?>" value="<?php echo $empresas->cnpj->EditValue ?>"<?php echo $empresas->cnpj->EditAttributes() ?>>
</span>
<?php echo $empresas->cnpj->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->ie->Visible) { // ie ?>
	<div id="r_ie" class="form-group">
		<label id="elh_empresas_ie" for="x_ie" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->ie->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->ie->CellAttributes() ?>>
<span id="el_empresas_ie">
<input type="text" data-table="empresas" data-field="x_ie" name="x_ie" id="x_ie" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->ie->getPlaceHolder()) ?>" value="<?php echo $empresas->ie->EditValue ?>"<?php echo $empresas->ie->EditAttributes() ?>>
</span>
<?php echo $empresas->ie->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->fonecedor->Visible) { // fonecedor ?>
	<div id="r_fonecedor" class="form-group">
		<label id="elh_empresas_fonecedor" for="x_fonecedor" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->fonecedor->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->fonecedor->CellAttributes() ?>>
<span id="el_empresas_fonecedor">
<input type="text" data-table="empresas" data-field="x_fonecedor" name="x_fonecedor" id="x_fonecedor" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->fonecedor->getPlaceHolder()) ?>" value="<?php echo $empresas->fonecedor->EditValue ?>"<?php echo $empresas->fonecedor->EditAttributes() ?>>
</span>
<?php echo $empresas->fonecedor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->celular->Visible) { // celular ?>
	<div id="r_celular" class="form-group">
		<label id="elh_empresas_celular" for="x_celular" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->celular->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->celular->CellAttributes() ?>>
<span id="el_empresas_celular">
<input type="text" data-table="empresas" data-field="x_celular" name="x_celular" id="x_celular" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->celular->getPlaceHolder()) ?>" value="<?php echo $empresas->celular->EditValue ?>"<?php echo $empresas->celular->EditAttributes() ?>>
</span>
<?php echo $empresas->celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->whatsapp->Visible) { // whatsapp ?>
	<div id="r_whatsapp" class="form-group">
		<label id="elh_empresas_whatsapp" for="x_whatsapp" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->whatsapp->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->whatsapp->CellAttributes() ?>>
<span id="el_empresas_whatsapp">
<input type="text" data-table="empresas" data-field="x_whatsapp" name="x_whatsapp" id="x_whatsapp" size="30" placeholder="<?php echo ew_HtmlEncode($empresas->whatsapp->getPlaceHolder()) ?>" value="<?php echo $empresas->whatsapp->EditValue ?>"<?php echo $empresas->whatsapp->EditAttributes() ?>>
</span>
<?php echo $empresas->whatsapp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
	<div id="r_endereco_numero" class="form-group">
		<label id="elh_empresas_endereco_numero" for="x_endereco_numero" class="<?php echo $empresas_edit->LeftColumnClass ?>"><?php echo $empresas->endereco_numero->FldCaption() ?></label>
		<div class="<?php echo $empresas_edit->RightColumnClass ?>"><div<?php echo $empresas->endereco_numero->CellAttributes() ?>>
<span id="el_empresas_endereco_numero">
<input type="text" data-table="empresas" data-field="x_endereco_numero" name="x_endereco_numero" id="x_endereco_numero" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($empresas->endereco_numero->getPlaceHolder()) ?>" value="<?php echo $empresas->endereco_numero->EditValue ?>"<?php echo $empresas->endereco_numero->EditAttributes() ?>>
</span>
<?php echo $empresas->endereco_numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$empresas_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $empresas_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $empresas_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fempresasedit.Init();
</script>
<?php
$empresas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_edit->Page_Terminate();
?>
