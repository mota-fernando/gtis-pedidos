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

$produtos_view = NULL; // Initialize page object first

class cprodutos_view extends cprodutos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'produtos';

	// Page object name
	var $PageObjName = 'produtos_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id_produto"] <> "") {
			$this->RecKey["id_produto"] = $_GET["id_produto"];
			$KeyUrl .= "&amp;id_produto=" . urlencode($this->RecKey["id_produto"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->codigo_produto->SetVisibility();
		$this->nome_produto->SetVisibility();
		$this->modelo_produto->SetVisibility();
		$this->id_marca_produto->SetVisibility();
		$this->status_produto->SetVisibility();
		$this->unidade_medida_produto->SetVisibility();
		$this->unidades->SetVisibility();
		$this->peso_produto->SetVisibility();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $gbSkipHeaderFooter, $EW_EXPORT;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id_produto"] <> "") {
				$this->id_produto->setQueryStringValue($_GET["id_produto"]);
				$this->RecKey["id_produto"] = $this->id_produto->QueryStringValue;
			} elseif (@$_POST["id_produto"] <> "") {
				$this->id_produto->setFormValue($_POST["id_produto"]);
				$this->RecKey["id_produto"] = $this->id_produto->FormValue;
			} else {
				$sReturnUrl = "produtoslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "produtoslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "produtoslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddQuery($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$this->preco_produto->ViewCustomAttributes = "";

		// descricao
		$this->descricao->ViewValue = $this->descricao->CurrentValue;
		$this->descricao->ViewCustomAttributes = "";

		// ipi
		$this->ipi->ViewValue = $this->ipi->CurrentValue;
		$this->ipi->ViewCustomAttributes = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produtoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($produtos_view)) $produtos_view = new cprodutos_view();

// Page init
$produtos_view->Page_Init();

// Page main
$produtos_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produtos_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fprodutosview = new ew_Form("fprodutosview", "view");

// Form_CustomValidate event
fprodutosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fprodutosview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fprodutosview.Lists["x_id_marca_produto"] = {"LinkField":"x_id_marca","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_marca","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
fprodutosview.Lists["x_id_marca_produto"].Data = "<?php echo $produtos_view->id_marca_produto->LookupFilterQuery(FALSE, "view") ?>";
fprodutosview.Lists["x_status_produto"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fprodutosview.Lists["x_status_produto"].Options = <?php echo json_encode($produtos_view->status_produto->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $produtos_view->ExportOptions->Render("body") ?>
<?php
	foreach ($produtos_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php $produtos_view->ShowPageHeader(); ?>
<?php
$produtos_view->ShowMessage();
?>
<form name="fprodutosview" id="fprodutosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produtos_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produtos_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produtos">
<input type="hidden" name="modal" value="<?php echo intval($produtos_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
	<tr id="r_codigo_produto">
		<td class="col-sm-2"><span id="elh_produtos_codigo_produto"><?php echo $produtos->codigo_produto->FldCaption() ?></span></td>
		<td data-name="codigo_produto"<?php echo $produtos->codigo_produto->CellAttributes() ?>>
<span id="el_produtos_codigo_produto">
<span<?php echo $produtos->codigo_produto->ViewAttributes() ?>>
<?php echo $produtos->codigo_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
	<tr id="r_nome_produto">
		<td class="col-sm-2"><span id="elh_produtos_nome_produto"><?php echo $produtos->nome_produto->FldCaption() ?></span></td>
		<td data-name="nome_produto"<?php echo $produtos->nome_produto->CellAttributes() ?>>
<span id="el_produtos_nome_produto">
<span<?php echo $produtos->nome_produto->ViewAttributes() ?>>
<?php echo $produtos->nome_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
	<tr id="r_modelo_produto">
		<td class="col-sm-2"><span id="elh_produtos_modelo_produto"><?php echo $produtos->modelo_produto->FldCaption() ?></span></td>
		<td data-name="modelo_produto"<?php echo $produtos->modelo_produto->CellAttributes() ?>>
<span id="el_produtos_modelo_produto">
<span<?php echo $produtos->modelo_produto->ViewAttributes() ?>>
<?php echo $produtos->modelo_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
	<tr id="r_id_marca_produto">
		<td class="col-sm-2"><span id="elh_produtos_id_marca_produto"><?php echo $produtos->id_marca_produto->FldCaption() ?></span></td>
		<td data-name="id_marca_produto"<?php echo $produtos->id_marca_produto->CellAttributes() ?>>
<span id="el_produtos_id_marca_produto">
<span<?php echo $produtos->id_marca_produto->ViewAttributes() ?>>
<?php echo $produtos->id_marca_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->status_produto->Visible) { // status_produto ?>
	<tr id="r_status_produto">
		<td class="col-sm-2"><span id="elh_produtos_status_produto"><?php echo $produtos->status_produto->FldCaption() ?></span></td>
		<td data-name="status_produto"<?php echo $produtos->status_produto->CellAttributes() ?>>
<span id="el_produtos_status_produto">
<span<?php echo $produtos->status_produto->ViewAttributes() ?>>
<?php echo $produtos->status_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->unidade_medida_produto->Visible) { // unidade_medida_produto ?>
	<tr id="r_unidade_medida_produto">
		<td class="col-sm-2"><span id="elh_produtos_unidade_medida_produto"><?php echo $produtos->unidade_medida_produto->FldCaption() ?></span></td>
		<td data-name="unidade_medida_produto"<?php echo $produtos->unidade_medida_produto->CellAttributes() ?>>
<span id="el_produtos_unidade_medida_produto">
<span<?php echo $produtos->unidade_medida_produto->ViewAttributes() ?>>
<?php echo $produtos->unidade_medida_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->unidades->Visible) { // unidades ?>
	<tr id="r_unidades">
		<td class="col-sm-2"><span id="elh_produtos_unidades"><?php echo $produtos->unidades->FldCaption() ?></span></td>
		<td data-name="unidades"<?php echo $produtos->unidades->CellAttributes() ?>>
<span id="el_produtos_unidades">
<span<?php echo $produtos->unidades->ViewAttributes() ?>>
<?php echo $produtos->unidades->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->peso_produto->Visible) { // peso_produto ?>
	<tr id="r_peso_produto">
		<td class="col-sm-2"><span id="elh_produtos_peso_produto"><?php echo $produtos->peso_produto->FldCaption() ?></span></td>
		<td data-name="peso_produto"<?php echo $produtos->peso_produto->CellAttributes() ?>>
<span id="el_produtos_peso_produto">
<span<?php echo $produtos->peso_produto->ViewAttributes() ?>>
<?php echo $produtos->peso_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
	<tr id="r_preco_produto">
		<td class="col-sm-2"><span id="elh_produtos_preco_produto"><?php echo $produtos->preco_produto->FldCaption() ?></span></td>
		<td data-name="preco_produto"<?php echo $produtos->preco_produto->CellAttributes() ?>>
<span id="el_produtos_preco_produto">
<span<?php echo $produtos->preco_produto->ViewAttributes() ?>>
<?php echo $produtos->preco_produto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->descricao->Visible) { // descricao ?>
	<tr id="r_descricao">
		<td class="col-sm-2"><span id="elh_produtos_descricao"><?php echo $produtos->descricao->FldCaption() ?></span></td>
		<td data-name="descricao"<?php echo $produtos->descricao->CellAttributes() ?>>
<span id="el_produtos_descricao">
<span<?php echo $produtos->descricao->ViewAttributes() ?>>
<?php echo $produtos->descricao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produtos->ipi->Visible) { // ipi ?>
	<tr id="r_ipi">
		<td class="col-sm-2"><span id="elh_produtos_ipi"><?php echo $produtos->ipi->FldCaption() ?></span></td>
		<td data-name="ipi"<?php echo $produtos->ipi->CellAttributes() ?>>
<span id="el_produtos_ipi">
<span<?php echo $produtos->ipi->ViewAttributes() ?>>
<?php echo $produtos->ipi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fprodutosview.Init();
</script>
<?php
$produtos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produtos_view->Page_Terminate();
?>
