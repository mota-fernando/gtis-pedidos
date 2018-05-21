<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pessoa_fisicainfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pessoa_fisica_edit = NULL; // Initialize page object first

class cpessoa_fisica_edit extends cpessoa_fisica {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{D83B9BB1-2CD4-4540-9A5B-B0E890360FB3}';

	// Table name
	var $TableName = 'pessoa_fisica';

	// Page object name
	var $PageObjName = 'pessoa_fisica_edit';

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

		// Table object (pessoa_fisica)
		if (!isset($GLOBALS["pessoa_fisica"]) || get_class($GLOBALS["pessoa_fisica"]) == "cpessoa_fisica") {
			$GLOBALS["pessoa_fisica"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoa_fisica"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pessoa_fisica', TRUE);

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
		$this->id_pessoa->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_pessoa->Visible = FALSE;
		$this->nome_pessoa->SetVisibility();
		$this->sobrenome_pessoa->SetVisibility();
		$this->nascimento->SetVisibility();
		$this->telefone->SetVisibility();
		$this->_email->SetVisibility();
		$this->celular->SetVisibility();
		$this->RG->SetVisibility();
		$this->CPF->SetVisibility();
		$this->endereco_numero->SetVisibility();
		$this->id_endereco->SetVisibility();

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
		global $EW_EXPORT, $pessoa_fisica;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pessoa_fisica);
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
					if ($pageName == "pessoa_fisicaview.php")
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
			if ($objForm->HasValue("x_id_pessoa")) {
				$this->id_pessoa->setFormValue($objForm->GetValue("x_id_pessoa"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_pessoa"])) {
				$this->id_pessoa->setQueryStringValue($_GET["id_pessoa"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_pessoa->CurrentValue = NULL;
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
					$this->Page_Terminate("pessoa_fisicalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pessoa_fisicalist.php")
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
		if (!$this->id_pessoa->FldIsDetailKey)
			$this->id_pessoa->setFormValue($objForm->GetValue("x_id_pessoa"));
		if (!$this->nome_pessoa->FldIsDetailKey) {
			$this->nome_pessoa->setFormValue($objForm->GetValue("x_nome_pessoa"));
		}
		if (!$this->sobrenome_pessoa->FldIsDetailKey) {
			$this->sobrenome_pessoa->setFormValue($objForm->GetValue("x_sobrenome_pessoa"));
		}
		if (!$this->nascimento->FldIsDetailKey) {
			$this->nascimento->setFormValue($objForm->GetValue("x_nascimento"));
			$this->nascimento->CurrentValue = ew_UnFormatDateTime($this->nascimento->CurrentValue, 0);
		}
		if (!$this->telefone->FldIsDetailKey) {
			$this->telefone->setFormValue($objForm->GetValue("x_telefone"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->celular->FldIsDetailKey) {
			$this->celular->setFormValue($objForm->GetValue("x_celular"));
		}
		if (!$this->RG->FldIsDetailKey) {
			$this->RG->setFormValue($objForm->GetValue("x_RG"));
		}
		if (!$this->CPF->FldIsDetailKey) {
			$this->CPF->setFormValue($objForm->GetValue("x_CPF"));
		}
		if (!$this->endereco_numero->FldIsDetailKey) {
			$this->endereco_numero->setFormValue($objForm->GetValue("x_endereco_numero"));
		}
		if (!$this->id_endereco->FldIsDetailKey) {
			$this->id_endereco->setFormValue($objForm->GetValue("x_id_endereco"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_pessoa->CurrentValue = $this->id_pessoa->FormValue;
		$this->nome_pessoa->CurrentValue = $this->nome_pessoa->FormValue;
		$this->sobrenome_pessoa->CurrentValue = $this->sobrenome_pessoa->FormValue;
		$this->nascimento->CurrentValue = $this->nascimento->FormValue;
		$this->nascimento->CurrentValue = ew_UnFormatDateTime($this->nascimento->CurrentValue, 0);
		$this->telefone->CurrentValue = $this->telefone->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->celular->CurrentValue = $this->celular->FormValue;
		$this->RG->CurrentValue = $this->RG->FormValue;
		$this->CPF->CurrentValue = $this->CPF->FormValue;
		$this->endereco_numero->CurrentValue = $this->endereco_numero->FormValue;
		$this->id_endereco->CurrentValue = $this->id_endereco->FormValue;
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
		$this->nome_pessoa->setDbValue($row['nome_pessoa']);
		$this->sobrenome_pessoa->setDbValue($row['sobrenome_pessoa']);
		$this->nascimento->setDbValue($row['nascimento']);
		$this->telefone->setDbValue($row['telefone']);
		$this->_email->setDbValue($row['email']);
		$this->celular->setDbValue($row['celular']);
		$this->RG->setDbValue($row['RG']);
		$this->CPF->setDbValue($row['CPF']);
		$this->endereco_numero->setDbValue($row['endereco_numero']);
		$this->id_endereco->setDbValue($row['id_endereco']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_pessoa'] = NULL;
		$row['nome_pessoa'] = NULL;
		$row['sobrenome_pessoa'] = NULL;
		$row['nascimento'] = NULL;
		$row['telefone'] = NULL;
		$row['email'] = NULL;
		$row['celular'] = NULL;
		$row['RG'] = NULL;
		$row['CPF'] = NULL;
		$row['endereco_numero'] = NULL;
		$row['id_endereco'] = NULL;
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
		$this->nascimento->DbValue = $row['nascimento'];
		$this->telefone->DbValue = $row['telefone'];
		$this->_email->DbValue = $row['email'];
		$this->celular->DbValue = $row['celular'];
		$this->RG->DbValue = $row['RG'];
		$this->CPF->DbValue = $row['CPF'];
		$this->endereco_numero->DbValue = $row['endereco_numero'];
		$this->id_endereco->DbValue = $row['id_endereco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_pessoa")) <> "")
			$this->id_pessoa->CurrentValue = $this->getKey("id_pessoa"); // id_pessoa
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
		// id_pessoa
		// nome_pessoa
		// sobrenome_pessoa
		// nascimento
		// telefone
		// email
		// celular
		// RG
		// CPF
		// endereco_numero
		// id_endereco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_pessoa
		$this->id_pessoa->ViewValue = $this->id_pessoa->CurrentValue;
		$this->id_pessoa->ViewCustomAttributes = "";

		// nome_pessoa
		$this->nome_pessoa->ViewValue = $this->nome_pessoa->CurrentValue;
		$this->nome_pessoa->ViewCustomAttributes = "";

		// sobrenome_pessoa
		$this->sobrenome_pessoa->ViewValue = $this->sobrenome_pessoa->CurrentValue;
		$this->sobrenome_pessoa->ViewCustomAttributes = "";

		// nascimento
		$this->nascimento->ViewValue = $this->nascimento->CurrentValue;
		$this->nascimento->ViewValue = ew_FormatDateTime($this->nascimento->ViewValue, 0);
		$this->nascimento->ViewCustomAttributes = "";

		// telefone
		$this->telefone->ViewValue = $this->telefone->CurrentValue;
		$this->telefone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// RG
		$this->RG->ViewValue = $this->RG->CurrentValue;
		$this->RG->ViewCustomAttributes = "";

		// CPF
		$this->CPF->ViewValue = $this->CPF->CurrentValue;
		$this->CPF->ViewCustomAttributes = "";

		// endereco_numero
		$this->endereco_numero->ViewValue = $this->endereco_numero->CurrentValue;
		$this->endereco_numero->ViewCustomAttributes = "";

		// id_endereco
		$this->id_endereco->ViewValue = $this->id_endereco->CurrentValue;
		$this->id_endereco->ViewCustomAttributes = "";

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

			// nascimento
			$this->nascimento->LinkCustomAttributes = "";
			$this->nascimento->HrefValue = "";
			$this->nascimento->TooltipValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";
			$this->telefone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// RG
			$this->RG->LinkCustomAttributes = "";
			$this->RG->HrefValue = "";
			$this->RG->TooltipValue = "";

			// CPF
			$this->CPF->LinkCustomAttributes = "";
			$this->CPF->HrefValue = "";
			$this->CPF->TooltipValue = "";

			// endereco_numero
			$this->endereco_numero->LinkCustomAttributes = "";
			$this->endereco_numero->HrefValue = "";
			$this->endereco_numero->TooltipValue = "";

			// id_endereco
			$this->id_endereco->LinkCustomAttributes = "";
			$this->id_endereco->HrefValue = "";
			$this->id_endereco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_pessoa
			$this->id_pessoa->EditAttrs["class"] = "form-control";
			$this->id_pessoa->EditCustomAttributes = "";
			$this->id_pessoa->EditValue = $this->id_pessoa->CurrentValue;
			$this->id_pessoa->ViewCustomAttributes = "";

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

			// nascimento
			$this->nascimento->EditAttrs["class"] = "form-control";
			$this->nascimento->EditCustomAttributes = "";
			$this->nascimento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->nascimento->CurrentValue, 8));
			$this->nascimento->PlaceHolder = ew_RemoveHtml($this->nascimento->FldCaption());

			// telefone
			$this->telefone->EditAttrs["class"] = "form-control";
			$this->telefone->EditCustomAttributes = "";
			$this->telefone->EditValue = ew_HtmlEncode($this->telefone->CurrentValue);
			$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// celular
			$this->celular->EditAttrs["class"] = "form-control";
			$this->celular->EditCustomAttributes = "";
			$this->celular->EditValue = ew_HtmlEncode($this->celular->CurrentValue);
			$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

			// RG
			$this->RG->EditAttrs["class"] = "form-control";
			$this->RG->EditCustomAttributes = "";
			$this->RG->EditValue = ew_HtmlEncode($this->RG->CurrentValue);
			$this->RG->PlaceHolder = ew_RemoveHtml($this->RG->FldCaption());

			// CPF
			$this->CPF->EditAttrs["class"] = "form-control";
			$this->CPF->EditCustomAttributes = "";
			$this->CPF->EditValue = ew_HtmlEncode($this->CPF->CurrentValue);
			$this->CPF->PlaceHolder = ew_RemoveHtml($this->CPF->FldCaption());

			// endereco_numero
			$this->endereco_numero->EditAttrs["class"] = "form-control";
			$this->endereco_numero->EditCustomAttributes = "";
			$this->endereco_numero->EditValue = ew_HtmlEncode($this->endereco_numero->CurrentValue);
			$this->endereco_numero->PlaceHolder = ew_RemoveHtml($this->endereco_numero->FldCaption());

			// id_endereco
			$this->id_endereco->EditAttrs["class"] = "form-control";
			$this->id_endereco->EditCustomAttributes = "";
			$this->id_endereco->EditValue = ew_HtmlEncode($this->id_endereco->CurrentValue);
			$this->id_endereco->PlaceHolder = ew_RemoveHtml($this->id_endereco->FldCaption());

			// Edit refer script
			// id_pessoa

			$this->id_pessoa->LinkCustomAttributes = "";
			$this->id_pessoa->HrefValue = "";

			// nome_pessoa
			$this->nome_pessoa->LinkCustomAttributes = "";
			$this->nome_pessoa->HrefValue = "";

			// sobrenome_pessoa
			$this->sobrenome_pessoa->LinkCustomAttributes = "";
			$this->sobrenome_pessoa->HrefValue = "";

			// nascimento
			$this->nascimento->LinkCustomAttributes = "";
			$this->nascimento->HrefValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";

			// RG
			$this->RG->LinkCustomAttributes = "";
			$this->RG->HrefValue = "";

			// CPF
			$this->CPF->LinkCustomAttributes = "";
			$this->CPF->HrefValue = "";

			// endereco_numero
			$this->endereco_numero->LinkCustomAttributes = "";
			$this->endereco_numero->HrefValue = "";

			// id_endereco
			$this->id_endereco->LinkCustomAttributes = "";
			$this->id_endereco->HrefValue = "";
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
		if (!ew_CheckDateDef($this->nascimento->FormValue)) {
			ew_AddMessage($gsFormError, $this->nascimento->FldErrMsg());
		}
		if (!ew_CheckInteger($this->telefone->FormValue)) {
			ew_AddMessage($gsFormError, $this->telefone->FldErrMsg());
		}
		if (!ew_CheckInteger($this->celular->FormValue)) {
			ew_AddMessage($gsFormError, $this->celular->FldErrMsg());
		}
		if (!ew_CheckInteger($this->RG->FormValue)) {
			ew_AddMessage($gsFormError, $this->RG->FldErrMsg());
		}
		if (!ew_CheckInteger($this->CPF->FormValue)) {
			ew_AddMessage($gsFormError, $this->CPF->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_endereco->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_endereco->FldErrMsg());
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

			// nome_pessoa
			$this->nome_pessoa->SetDbValueDef($rsnew, $this->nome_pessoa->CurrentValue, NULL, $this->nome_pessoa->ReadOnly);

			// sobrenome_pessoa
			$this->sobrenome_pessoa->SetDbValueDef($rsnew, $this->sobrenome_pessoa->CurrentValue, NULL, $this->sobrenome_pessoa->ReadOnly);

			// nascimento
			$this->nascimento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->nascimento->CurrentValue, 0), NULL, $this->nascimento->ReadOnly);

			// telefone
			$this->telefone->SetDbValueDef($rsnew, $this->telefone->CurrentValue, NULL, $this->telefone->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// celular
			$this->celular->SetDbValueDef($rsnew, $this->celular->CurrentValue, NULL, $this->celular->ReadOnly);

			// RG
			$this->RG->SetDbValueDef($rsnew, $this->RG->CurrentValue, NULL, $this->RG->ReadOnly);

			// CPF
			$this->CPF->SetDbValueDef($rsnew, $this->CPF->CurrentValue, NULL, $this->CPF->ReadOnly);

			// endereco_numero
			$this->endereco_numero->SetDbValueDef($rsnew, $this->endereco_numero->CurrentValue, NULL, $this->endereco_numero->ReadOnly);

			// id_endereco
			$this->id_endereco->SetDbValueDef($rsnew, $this->id_endereco->CurrentValue, NULL, $this->id_endereco->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pessoa_fisicalist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
if (!isset($pessoa_fisica_edit)) $pessoa_fisica_edit = new cpessoa_fisica_edit();

// Page init
$pessoa_fisica_edit->Page_Init();

// Page main
$pessoa_fisica_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoa_fisica_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpessoa_fisicaedit = new ew_Form("fpessoa_fisicaedit", "edit");

// Validate form
fpessoa_fisicaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nascimento");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->nascimento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_telefone");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->telefone->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_celular");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->celular->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_RG");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->RG->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CPF");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->CPF->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_endereco");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa_fisica->id_endereco->FldErrMsg()) ?>");

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
fpessoa_fisicaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpessoa_fisicaedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pessoa_fisica_edit->ShowPageHeader(); ?>
<?php
$pessoa_fisica_edit->ShowMessage();
?>
<form name="fpessoa_fisicaedit" id="fpessoa_fisicaedit" class="<?php echo $pessoa_fisica_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pessoa_fisica_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pessoa_fisica_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pessoa_fisica">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($pessoa_fisica_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($pessoa_fisica->id_pessoa->Visible) { // id_pessoa ?>
	<div id="r_id_pessoa" class="form-group">
		<label id="elh_pessoa_fisica_id_pessoa" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->id_pessoa->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->id_pessoa->CellAttributes() ?>>
<span id="el_pessoa_fisica_id_pessoa">
<span<?php echo $pessoa_fisica->id_pessoa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pessoa_fisica->id_pessoa->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pessoa_fisica" data-field="x_id_pessoa" name="x_id_pessoa" id="x_id_pessoa" value="<?php echo ew_HtmlEncode($pessoa_fisica->id_pessoa->CurrentValue) ?>">
<?php echo $pessoa_fisica->id_pessoa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->nome_pessoa->Visible) { // nome_pessoa ?>
	<div id="r_nome_pessoa" class="form-group">
		<label id="elh_pessoa_fisica_nome_pessoa" for="x_nome_pessoa" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->nome_pessoa->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->nome_pessoa->CellAttributes() ?>>
<span id="el_pessoa_fisica_nome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_nome_pessoa" name="x_nome_pessoa" id="x_nome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->nome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->nome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->nome_pessoa->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->nome_pessoa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
	<div id="r_sobrenome_pessoa" class="form-group">
		<label id="elh_pessoa_fisica_sobrenome_pessoa" for="x_sobrenome_pessoa" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->sobrenome_pessoa->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->sobrenome_pessoa->CellAttributes() ?>>
<span id="el_pessoa_fisica_sobrenome_pessoa">
<input type="text" data-table="pessoa_fisica" data-field="x_sobrenome_pessoa" name="x_sobrenome_pessoa" id="x_sobrenome_pessoa" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->sobrenome_pessoa->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->sobrenome_pessoa->EditValue ?>"<?php echo $pessoa_fisica->sobrenome_pessoa->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->sobrenome_pessoa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->nascimento->Visible) { // nascimento ?>
	<div id="r_nascimento" class="form-group">
		<label id="elh_pessoa_fisica_nascimento" for="x_nascimento" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->nascimento->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->nascimento->CellAttributes() ?>>
<span id="el_pessoa_fisica_nascimento">
<input type="text" data-table="pessoa_fisica" data-field="x_nascimento" name="x_nascimento" id="x_nascimento" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->nascimento->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->nascimento->EditValue ?>"<?php echo $pessoa_fisica->nascimento->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->nascimento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->telefone->Visible) { // telefone ?>
	<div id="r_telefone" class="form-group">
		<label id="elh_pessoa_fisica_telefone" for="x_telefone" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->telefone->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->telefone->CellAttributes() ?>>
<span id="el_pessoa_fisica_telefone">
<input type="text" data-table="pessoa_fisica" data-field="x_telefone" name="x_telefone" id="x_telefone" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->telefone->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->telefone->EditValue ?>"<?php echo $pessoa_fisica->telefone->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->telefone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_pessoa_fisica__email" for="x__email" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->_email->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->_email->CellAttributes() ?>>
<span id="el_pessoa_fisica__email">
<input type="text" data-table="pessoa_fisica" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->_email->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->_email->EditValue ?>"<?php echo $pessoa_fisica->_email->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->celular->Visible) { // celular ?>
	<div id="r_celular" class="form-group">
		<label id="elh_pessoa_fisica_celular" for="x_celular" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->celular->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->celular->CellAttributes() ?>>
<span id="el_pessoa_fisica_celular">
<input type="text" data-table="pessoa_fisica" data-field="x_celular" name="x_celular" id="x_celular" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->celular->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->celular->EditValue ?>"<?php echo $pessoa_fisica->celular->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->RG->Visible) { // RG ?>
	<div id="r_RG" class="form-group">
		<label id="elh_pessoa_fisica_RG" for="x_RG" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->RG->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->RG->CellAttributes() ?>>
<span id="el_pessoa_fisica_RG">
<input type="text" data-table="pessoa_fisica" data-field="x_RG" name="x_RG" id="x_RG" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->RG->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->RG->EditValue ?>"<?php echo $pessoa_fisica->RG->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->RG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->CPF->Visible) { // CPF ?>
	<div id="r_CPF" class="form-group">
		<label id="elh_pessoa_fisica_CPF" for="x_CPF" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->CPF->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->CPF->CellAttributes() ?>>
<span id="el_pessoa_fisica_CPF">
<input type="text" data-table="pessoa_fisica" data-field="x_CPF" name="x_CPF" id="x_CPF" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->CPF->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->CPF->EditValue ?>"<?php echo $pessoa_fisica->CPF->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->CPF->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->endereco_numero->Visible) { // endereco_numero ?>
	<div id="r_endereco_numero" class="form-group">
		<label id="elh_pessoa_fisica_endereco_numero" for="x_endereco_numero" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->endereco_numero->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->endereco_numero->CellAttributes() ?>>
<span id="el_pessoa_fisica_endereco_numero">
<input type="text" data-table="pessoa_fisica" data-field="x_endereco_numero" name="x_endereco_numero" id="x_endereco_numero" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->endereco_numero->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->endereco_numero->EditValue ?>"<?php echo $pessoa_fisica->endereco_numero->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->endereco_numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pessoa_fisica->id_endereco->Visible) { // id_endereco ?>
	<div id="r_id_endereco" class="form-group">
		<label id="elh_pessoa_fisica_id_endereco" for="x_id_endereco" class="<?php echo $pessoa_fisica_edit->LeftColumnClass ?>"><?php echo $pessoa_fisica->id_endereco->FldCaption() ?></label>
		<div class="<?php echo $pessoa_fisica_edit->RightColumnClass ?>"><div<?php echo $pessoa_fisica->id_endereco->CellAttributes() ?>>
<span id="el_pessoa_fisica_id_endereco">
<input type="text" data-table="pessoa_fisica" data-field="x_id_endereco" name="x_id_endereco" id="x_id_endereco" size="30" placeholder="<?php echo ew_HtmlEncode($pessoa_fisica->id_endereco->getPlaceHolder()) ?>" value="<?php echo $pessoa_fisica->id_endereco->EditValue ?>"<?php echo $pessoa_fisica->id_endereco->EditAttributes() ?>>
</span>
<?php echo $pessoa_fisica->id_endereco->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$pessoa_fisica_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $pessoa_fisica_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pessoa_fisica_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fpessoa_fisicaedit.Init();
</script>
<?php
$pessoa_fisica_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pessoa_fisica_edit->Page_Terminate();
?>
