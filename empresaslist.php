<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "empresasinfo.php" ?>
<?php include_once "tranportadorainfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$empresas_list = NULL; // Initialize page object first

class cempresas_list extends cempresas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_list';

	// Grid form hidden field names
	var $FormName = 'fempresaslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "empresasadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "empresasdelete.php";
		$this->MultiUpdateUrl = "empresasupdate.php";

		// Table object (tranportadora)
		if (!isset($GLOBALS['tranportadora'])) $GLOBALS['tranportadora'] = new ctranportadora();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fempresaslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->id_perfil->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_perfil->Visible = FALSE;
		$this->razao_social->SetVisibility();
		$this->proprietario->SetVisibility();
		$this->telefone->SetVisibility();
		$this->direcao->SetVisibility();
		$this->_email->SetVisibility();
		$this->id_endereco->SetVisibility();
		$this->endereco_numero->SetVisibility();
		$this->nome_fantasia->SetVisibility();

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

		// Set up master detail parameters
		$this->SetupMasterParms();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "tranportadora") {
			global $tranportadora;
			$rsmaster = $tranportadora->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("tranportadoralist.php"); // Return to master page
			} else {
				$tranportadora->LoadListRowValues($rsmaster);
				$tranportadora->RowType = EW_ROWTYPE_MASTER; // Master row
				$tranportadora->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export selected records
		if ($this->Export <> "")
			$this->CurrentFilter = $this->BuildExportSelectedFilter();

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id_perfil->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_perfil->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id_perfil->AdvancedSearch->ToJson(), ","); // Field id_perfil
		$sFilterList = ew_Concat($sFilterList, $this->razao_social->AdvancedSearch->ToJson(), ","); // Field razao_social
		$sFilterList = ew_Concat($sFilterList, $this->proprietario->AdvancedSearch->ToJson(), ","); // Field proprietario
		$sFilterList = ew_Concat($sFilterList, $this->telefone->AdvancedSearch->ToJson(), ","); // Field telefone
		$sFilterList = ew_Concat($sFilterList, $this->direcao->AdvancedSearch->ToJson(), ","); // Field direcao
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->id_endereco->AdvancedSearch->ToJson(), ","); // Field id_endereco
		$sFilterList = ew_Concat($sFilterList, $this->endereco_numero->AdvancedSearch->ToJson(), ","); // Field endereco_numero
		$sFilterList = ew_Concat($sFilterList, $this->nome_fantasia->AdvancedSearch->ToJson(), ","); // Field nome_fantasia
		$sFilterList = ew_Concat($sFilterList, $this->cnpj->AdvancedSearch->ToJson(), ","); // Field cnpj
		$sFilterList = ew_Concat($sFilterList, $this->ie->AdvancedSearch->ToJson(), ","); // Field ie
		$sFilterList = ew_Concat($sFilterList, $this->fonecedor->AdvancedSearch->ToJson(), ","); // Field fonecedor
		$sFilterList = ew_Concat($sFilterList, $this->celular->AdvancedSearch->ToJson(), ","); // Field celular
		$sFilterList = ew_Concat($sFilterList, $this->whatsapp->AdvancedSearch->ToJson(), ","); // Field whatsapp
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fempresaslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field id_perfil
		$this->id_perfil->AdvancedSearch->SearchValue = @$filter["x_id_perfil"];
		$this->id_perfil->AdvancedSearch->SearchOperator = @$filter["z_id_perfil"];
		$this->id_perfil->AdvancedSearch->SearchCondition = @$filter["v_id_perfil"];
		$this->id_perfil->AdvancedSearch->SearchValue2 = @$filter["y_id_perfil"];
		$this->id_perfil->AdvancedSearch->SearchOperator2 = @$filter["w_id_perfil"];
		$this->id_perfil->AdvancedSearch->Save();

		// Field razao_social
		$this->razao_social->AdvancedSearch->SearchValue = @$filter["x_razao_social"];
		$this->razao_social->AdvancedSearch->SearchOperator = @$filter["z_razao_social"];
		$this->razao_social->AdvancedSearch->SearchCondition = @$filter["v_razao_social"];
		$this->razao_social->AdvancedSearch->SearchValue2 = @$filter["y_razao_social"];
		$this->razao_social->AdvancedSearch->SearchOperator2 = @$filter["w_razao_social"];
		$this->razao_social->AdvancedSearch->Save();

		// Field proprietario
		$this->proprietario->AdvancedSearch->SearchValue = @$filter["x_proprietario"];
		$this->proprietario->AdvancedSearch->SearchOperator = @$filter["z_proprietario"];
		$this->proprietario->AdvancedSearch->SearchCondition = @$filter["v_proprietario"];
		$this->proprietario->AdvancedSearch->SearchValue2 = @$filter["y_proprietario"];
		$this->proprietario->AdvancedSearch->SearchOperator2 = @$filter["w_proprietario"];
		$this->proprietario->AdvancedSearch->Save();

		// Field telefone
		$this->telefone->AdvancedSearch->SearchValue = @$filter["x_telefone"];
		$this->telefone->AdvancedSearch->SearchOperator = @$filter["z_telefone"];
		$this->telefone->AdvancedSearch->SearchCondition = @$filter["v_telefone"];
		$this->telefone->AdvancedSearch->SearchValue2 = @$filter["y_telefone"];
		$this->telefone->AdvancedSearch->SearchOperator2 = @$filter["w_telefone"];
		$this->telefone->AdvancedSearch->Save();

		// Field direcao
		$this->direcao->AdvancedSearch->SearchValue = @$filter["x_direcao"];
		$this->direcao->AdvancedSearch->SearchOperator = @$filter["z_direcao"];
		$this->direcao->AdvancedSearch->SearchCondition = @$filter["v_direcao"];
		$this->direcao->AdvancedSearch->SearchValue2 = @$filter["y_direcao"];
		$this->direcao->AdvancedSearch->SearchOperator2 = @$filter["w_direcao"];
		$this->direcao->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field id_endereco
		$this->id_endereco->AdvancedSearch->SearchValue = @$filter["x_id_endereco"];
		$this->id_endereco->AdvancedSearch->SearchOperator = @$filter["z_id_endereco"];
		$this->id_endereco->AdvancedSearch->SearchCondition = @$filter["v_id_endereco"];
		$this->id_endereco->AdvancedSearch->SearchValue2 = @$filter["y_id_endereco"];
		$this->id_endereco->AdvancedSearch->SearchOperator2 = @$filter["w_id_endereco"];
		$this->id_endereco->AdvancedSearch->Save();

		// Field endereco_numero
		$this->endereco_numero->AdvancedSearch->SearchValue = @$filter["x_endereco_numero"];
		$this->endereco_numero->AdvancedSearch->SearchOperator = @$filter["z_endereco_numero"];
		$this->endereco_numero->AdvancedSearch->SearchCondition = @$filter["v_endereco_numero"];
		$this->endereco_numero->AdvancedSearch->SearchValue2 = @$filter["y_endereco_numero"];
		$this->endereco_numero->AdvancedSearch->SearchOperator2 = @$filter["w_endereco_numero"];
		$this->endereco_numero->AdvancedSearch->Save();

		// Field nome_fantasia
		$this->nome_fantasia->AdvancedSearch->SearchValue = @$filter["x_nome_fantasia"];
		$this->nome_fantasia->AdvancedSearch->SearchOperator = @$filter["z_nome_fantasia"];
		$this->nome_fantasia->AdvancedSearch->SearchCondition = @$filter["v_nome_fantasia"];
		$this->nome_fantasia->AdvancedSearch->SearchValue2 = @$filter["y_nome_fantasia"];
		$this->nome_fantasia->AdvancedSearch->SearchOperator2 = @$filter["w_nome_fantasia"];
		$this->nome_fantasia->AdvancedSearch->Save();

		// Field cnpj
		$this->cnpj->AdvancedSearch->SearchValue = @$filter["x_cnpj"];
		$this->cnpj->AdvancedSearch->SearchOperator = @$filter["z_cnpj"];
		$this->cnpj->AdvancedSearch->SearchCondition = @$filter["v_cnpj"];
		$this->cnpj->AdvancedSearch->SearchValue2 = @$filter["y_cnpj"];
		$this->cnpj->AdvancedSearch->SearchOperator2 = @$filter["w_cnpj"];
		$this->cnpj->AdvancedSearch->Save();

		// Field ie
		$this->ie->AdvancedSearch->SearchValue = @$filter["x_ie"];
		$this->ie->AdvancedSearch->SearchOperator = @$filter["z_ie"];
		$this->ie->AdvancedSearch->SearchCondition = @$filter["v_ie"];
		$this->ie->AdvancedSearch->SearchValue2 = @$filter["y_ie"];
		$this->ie->AdvancedSearch->SearchOperator2 = @$filter["w_ie"];
		$this->ie->AdvancedSearch->Save();

		// Field fonecedor
		$this->fonecedor->AdvancedSearch->SearchValue = @$filter["x_fonecedor"];
		$this->fonecedor->AdvancedSearch->SearchOperator = @$filter["z_fonecedor"];
		$this->fonecedor->AdvancedSearch->SearchCondition = @$filter["v_fonecedor"];
		$this->fonecedor->AdvancedSearch->SearchValue2 = @$filter["y_fonecedor"];
		$this->fonecedor->AdvancedSearch->SearchOperator2 = @$filter["w_fonecedor"];
		$this->fonecedor->AdvancedSearch->Save();

		// Field celular
		$this->celular->AdvancedSearch->SearchValue = @$filter["x_celular"];
		$this->celular->AdvancedSearch->SearchOperator = @$filter["z_celular"];
		$this->celular->AdvancedSearch->SearchCondition = @$filter["v_celular"];
		$this->celular->AdvancedSearch->SearchValue2 = @$filter["y_celular"];
		$this->celular->AdvancedSearch->SearchOperator2 = @$filter["w_celular"];
		$this->celular->AdvancedSearch->Save();

		// Field whatsapp
		$this->whatsapp->AdvancedSearch->SearchValue = @$filter["x_whatsapp"];
		$this->whatsapp->AdvancedSearch->SearchOperator = @$filter["z_whatsapp"];
		$this->whatsapp->AdvancedSearch->SearchCondition = @$filter["v_whatsapp"];
		$this->whatsapp->AdvancedSearch->SearchValue2 = @$filter["y_whatsapp"];
		$this->whatsapp->AdvancedSearch->SearchOperator2 = @$filter["w_whatsapp"];
		$this->whatsapp->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->razao_social, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->proprietario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telefone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direcao, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->endereco_numero, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nome_fantasia, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_perfil); // id_perfil
			$this->UpdateSort($this->razao_social); // razao_social
			$this->UpdateSort($this->proprietario); // proprietario
			$this->UpdateSort($this->telefone); // telefone
			$this->UpdateSort($this->direcao); // direcao
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->id_endereco); // id_endereco
			$this->UpdateSort($this->endereco_numero); // endereco_numero
			$this->UpdateSort($this->nome_fantasia); // nome_fantasia
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_perfil->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->id_perfil->setSort("");
				$this->razao_social->setSort("");
				$this->proprietario->setSort("");
				$this->telefone->setSort("");
				$this->direcao->setSort("");
				$this->_email->setSort("");
				$this->id_endereco->setSort("");
				$this->endereco_numero->setSort("");
				$this->nome_fantasia->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id_perfil->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fempresaslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = (TRUE);

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fempresaslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fempresaslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fempresaslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fempresaslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;

		// Hide detail items for dropdown if necessary
		$this->ListOptions->HideDetailItemsForDropDown();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		if (array_key_exists('EV__direcao', $rs->fields)) {
			$this->direcao->VirtualValue = $rs->fields('EV__direcao'); // Set up virtual field value
		} else {
			$this->direcao->VirtualValue = ""; // Clear value
		}
		$this->_email->setDbValue($row['email']);
		$this->id_endereco->setDbValue($row['id_endereco']);
		if (array_key_exists('EV__id_endereco', $rs->fields)) {
			$this->id_endereco->VirtualValue = $rs->fields('EV__id_endereco'); // Set up virtual field value
		} else {
			$this->id_endereco->VirtualValue = ""; // Clear value
		}
		$this->endereco_numero->setDbValue($row['endereco_numero']);
		$this->nome_fantasia->setDbValue($row['nome_fantasia']);
		$this->cnpj->setDbValue($row['cnpj']);
		$this->ie->setDbValue($row['ie']);
		$this->fonecedor->setDbValue($row['fonecedor']);
		$this->celular->setDbValue($row['celular']);
		$this->whatsapp->setDbValue($row['whatsapp']);
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
		$row['endereco_numero'] = NULL;
		$row['nome_fantasia'] = NULL;
		$row['cnpj'] = NULL;
		$row['ie'] = NULL;
		$row['fonecedor'] = NULL;
		$row['celular'] = NULL;
		$row['whatsapp'] = NULL;
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
		$this->endereco_numero->DbValue = $row['endereco_numero'];
		$this->nome_fantasia->DbValue = $row['nome_fantasia'];
		$this->cnpj->DbValue = $row['cnpj'];
		$this->ie->DbValue = $row['ie'];
		$this->fonecedor->DbValue = $row['fonecedor'];
		$this->celular->DbValue = $row['celular'];
		$this->whatsapp->DbValue = $row['whatsapp'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		// endereco_numero
		// nome_fantasia
		// cnpj
		// ie
		// fonecedor
		// celular
		// whatsapp

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
		if ($this->direcao->VirtualValue <> "") {
			$this->direcao->ViewValue = $this->direcao->VirtualValue;
		} else {
			$this->direcao->ViewValue = $this->direcao->CurrentValue;
		if (strval($this->direcao->CurrentValue) <> "") {
			$sFilterWrk = "`nome_pessoa`" . ew_SearchString("=", $this->direcao->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `nome_pessoa`, `nome_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pessoa_fisica`";
		$sWhereWrk = "";
		$this->direcao->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->direcao, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->direcao->ViewValue = $this->direcao->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->direcao->ViewValue = $this->direcao->CurrentValue;
			}
		} else {
			$this->direcao->ViewValue = NULL;
		}
		}
		$this->direcao->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// id_endereco
		if ($this->id_endereco->VirtualValue <> "") {
			$this->id_endereco->ViewValue = $this->id_endereco->VirtualValue;
		} else {
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
		}
		$this->id_endereco->ViewCustomAttributes = "";

		// endereco_numero
		$this->endereco_numero->ViewValue = $this->endereco_numero->CurrentValue;
		$this->endereco_numero->ViewCustomAttributes = "";

		// nome_fantasia
		$this->nome_fantasia->ViewValue = $this->nome_fantasia->CurrentValue;
		$this->nome_fantasia->ViewCustomAttributes = "";

		// cnpj
		$this->cnpj->ViewValue = $this->cnpj->CurrentValue;
		$this->cnpj->ViewCustomAttributes = "";

		// ie
		$this->ie->ViewValue = $this->ie->CurrentValue;
		$this->ie->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// whatsapp
		$this->whatsapp->ViewValue = $this->whatsapp->CurrentValue;
		$this->whatsapp->ViewCustomAttributes = "";

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

			// endereco_numero
			$this->endereco_numero->LinkCustomAttributes = "";
			$this->endereco_numero->HrefValue = "";
			$this->endereco_numero->TooltipValue = "";

			// nome_fantasia
			$this->nome_fantasia->LinkCustomAttributes = "";
			$this->nome_fantasia->HrefValue = "";
			$this->nome_fantasia->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Build export filter for selected records
	function BuildExportSelectedFilter() {
		global $Language;
		$sWrkFilter = "";
		if ($this->Export <> "") {
			$sWrkFilter = $this->GetKeyFilter();
		}
		return $sWrkFilter;
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','print',false,true);\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','excel',false,true);\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','word',false,true);\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','html',false,true);\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','xml',false,true);\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','csv',false,true);\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" onclick=\"ew_Export(document.fempresaslist,'" . ew_CurrentPage() . "','pdf',false,true);\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_empresas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_empresas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fempresaslist,sel:true" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "tranportadora") {
			global $tranportadora;
			if (!isset($tranportadora)) $tranportadora = new ctranportadora;
			$rsmaster = $tranportadora->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$tranportadora;
					$tranportadora->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
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
			if ($sMasterTblVar == "tranportadora") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id_transportadora"] <> "") {
					$GLOBALS["tranportadora"]->id_transportadora->setQueryStringValue($_GET["fk_id_transportadora"]);
					$this->id_perfil->setQueryStringValue($GLOBALS["tranportadora"]->id_transportadora->QueryStringValue);
					$this->id_perfil->setSessionValue($this->id_perfil->QueryStringValue);
					if (!is_numeric($GLOBALS["tranportadora"]->id_transportadora->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "tranportadora") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id_transportadora"] <> "") {
					$GLOBALS["tranportadora"]->id_transportadora->setFormValue($_POST["fk_id_transportadora"]);
					$this->id_perfil->setFormValue($GLOBALS["tranportadora"]->id_transportadora->FormValue);
					$this->id_perfil->setSessionValue($this->id_perfil->FormValue);
					if (!is_numeric($GLOBALS["tranportadora"]->id_transportadora->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			if (!$this->IsAddOrEdit()) {
				$this->StartRec = 1;
				$this->setStartRecordNumber($this->StartRec);
			}

			// Clear previous master key from Session
			if ($sMasterTblVar <> "tranportadora") {
				if ($this->id_perfil->CurrentValue == "") $this->id_perfil->setSessionValue("");
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($empresas_list)) $empresas_list = new cempresas_list();

// Page init
$empresas_list->Page_Init();

// Page main
$empresas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($empresas->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fempresaslist = new ew_Form("fempresaslist", "list");
fempresaslist.FormKeyCountName = '<?php echo $empresas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fempresaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fempresaslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fempresaslist.Lists["x_direcao"] = {"LinkField":"x_nome_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_pessoa","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pessoa_fisica"};
fempresaslist.Lists["x_direcao"].Data = "<?php echo $empresas_list->direcao->LookupFilterQuery(FALSE, "list") ?>";
fempresaslist.AutoSuggests["x_direcao"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $empresas_list->direcao->LookupFilterQuery(TRUE, "list"))) ?>;
fempresaslist.Lists["x_id_endereco"] = {"LinkField":"x_id_endereco","Ajax":true,"AutoFill":false,"DisplayFields":["x_endereco","x_bairro","x_estado","x_cidade"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"endereco"};
fempresaslist.Lists["x_id_endereco"].Data = "<?php echo $empresas_list->id_endereco->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fempresaslistsrch = new ew_Form("fempresaslistsrch");
</script>
<style type="text/css">
.ewTablePreviewRow { /* main table preview row color */
	background-color: #FFFFFF; /* preview row color */
}
.ewTablePreviewRow .ewGrid {
	display: table;
}
</style>
<div id="ewPreview" class="d-none"><!-- preview -->
	<div class="nav-tabs-custom"><!-- .nav-tabs-custom -->
		<ul class="nav nav-tabs" role="tablist"></ul>
		<div class="tab-content"><!-- .tab-content -->
			<div class="tab-pane fade"></div>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->
</div><!-- /preview -->
<script type="text/javascript" src="phpjs/ewpreview.js"></script>
<script type="text/javascript">
var EW_PREVIEW_PLACEMENT = EW_CSS_FLIP ? "left" : "right";
var EW_PREVIEW_SINGLE_ROW = false;
var EW_PREVIEW_OVERLAY = false;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($empresas->Export == "") { ?>
<div class="ewToolbar">
<?php if ($empresas_list->TotalRecs > 0 && $empresas_list->ExportOptions->Visible()) { ?>
<?php $empresas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($empresas_list->SearchOptions->Visible()) { ?>
<?php $empresas_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($empresas_list->FilterOptions->Visible()) { ?>
<?php $empresas_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($empresas->Export == "") || (EW_EXPORT_MASTER_RECORD && $empresas->Export == "print")) { ?>
<?php
if ($empresas_list->DbMasterFilter <> "" && $empresas->getCurrentMasterTable() == "tranportadora") {
	if ($empresas_list->MasterRecordExists) {
?>
<?php include_once "tranportadoramaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $empresas_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($empresas_list->TotalRecs <= 0)
			$empresas_list->TotalRecs = $empresas->ListRecordCount();
	} else {
		if (!$empresas_list->Recordset && ($empresas_list->Recordset = $empresas_list->LoadRecordset()))
			$empresas_list->TotalRecs = $empresas_list->Recordset->RecordCount();
	}
	$empresas_list->StartRec = 1;
	if ($empresas_list->DisplayRecs <= 0 || ($empresas->Export <> "" && $empresas->ExportAll)) // Display all records
		$empresas_list->DisplayRecs = $empresas_list->TotalRecs;
	if (!($empresas->Export <> "" && $empresas->ExportAll))
		$empresas_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$empresas_list->Recordset = $empresas_list->LoadRecordset($empresas_list->StartRec-1, $empresas_list->DisplayRecs);

	// Set no record found message
	if ($empresas->CurrentAction == "" && $empresas_list->TotalRecs == 0) {
		if ($empresas_list->SearchWhere == "0=101")
			$empresas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$empresas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$empresas_list->RenderOtherOptions();
?>
<?php if ($empresas->Export == "" && $empresas->CurrentAction == "") { ?>
<form name="fempresaslistsrch" id="fempresaslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($empresas_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fempresaslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="empresas">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($empresas_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($empresas_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $empresas_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($empresas_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($empresas_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($empresas_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($empresas_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $empresas_list->ShowPageHeader(); ?>
<?php
$empresas_list->ShowMessage();
?>
<?php if ($empresas_list->TotalRecs > 0 || $empresas->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($empresas_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> empresas">
<?php if ($empresas->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($empresas->CurrentAction <> "gridadd" && $empresas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($empresas_list->Pager)) $empresas_list->Pager = new cPrevNextPager($empresas_list->StartRec, $empresas_list->DisplayRecs, $empresas_list->TotalRecs, $empresas_list->AutoHidePager) ?>
<?php if ($empresas_list->Pager->RecordCount > 0 && $empresas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($empresas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empresas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empresas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($empresas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empresas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empresas_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($empresas_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $empresas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $empresas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $empresas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empresas_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fempresaslist" id="fempresaslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="exporttype" id="exporttype" value="">
<?php if ($empresas->getCurrentMasterTable() == "tranportadora" && $empresas->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="tranportadora">
<input type="hidden" name="fk_id_transportadora" value="<?php echo $empresas->id_perfil->getSessionValue() ?>">
<?php } ?>
<div id="gmp_empresas" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($empresas_list->TotalRecs > 0 || $empresas->CurrentAction == "gridedit") { ?>
<table id="tbl_empresaslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$empresas_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$empresas_list->RenderListOptions();

// Render list options (header, left)
$empresas_list->ListOptions->Render("header", "left");
?>
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
	<?php if ($empresas->SortUrl($empresas->id_perfil) == "") { ?>
		<th data-name="id_perfil" class="<?php echo $empresas->id_perfil->HeaderCellClass() ?>"><div id="elh_empresas_id_perfil" class="empresas_id_perfil"><div class="ewTableHeaderCaption"><?php echo $empresas->id_perfil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_perfil" class="<?php echo $empresas->id_perfil->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->id_perfil) ?>',1);"><div id="elh_empresas_id_perfil" class="empresas_id_perfil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->id_perfil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->id_perfil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->id_perfil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
	<?php if ($empresas->SortUrl($empresas->razao_social) == "") { ?>
		<th data-name="razao_social" class="<?php echo $empresas->razao_social->HeaderCellClass() ?>"><div id="elh_empresas_razao_social" class="empresas_razao_social"><div class="ewTableHeaderCaption"><?php echo $empresas->razao_social->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="razao_social" class="<?php echo $empresas->razao_social->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->razao_social) ?>',1);"><div id="elh_empresas_razao_social" class="empresas_razao_social">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->razao_social->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->razao_social->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->razao_social->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
	<?php if ($empresas->SortUrl($empresas->proprietario) == "") { ?>
		<th data-name="proprietario" class="<?php echo $empresas->proprietario->HeaderCellClass() ?>"><div id="elh_empresas_proprietario" class="empresas_proprietario"><div class="ewTableHeaderCaption"><?php echo $empresas->proprietario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="proprietario" class="<?php echo $empresas->proprietario->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->proprietario) ?>',1);"><div id="elh_empresas_proprietario" class="empresas_proprietario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->proprietario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->proprietario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->proprietario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
	<?php if ($empresas->SortUrl($empresas->telefone) == "") { ?>
		<th data-name="telefone" class="<?php echo $empresas->telefone->HeaderCellClass() ?>"><div id="elh_empresas_telefone" class="empresas_telefone"><div class="ewTableHeaderCaption"><?php echo $empresas->telefone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefone" class="<?php echo $empresas->telefone->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->telefone) ?>',1);"><div id="elh_empresas_telefone" class="empresas_telefone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->telefone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->telefone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->telefone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
	<?php if ($empresas->SortUrl($empresas->direcao) == "") { ?>
		<th data-name="direcao" class="<?php echo $empresas->direcao->HeaderCellClass() ?>"><div id="elh_empresas_direcao" class="empresas_direcao"><div class="ewTableHeaderCaption"><?php echo $empresas->direcao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direcao" class="<?php echo $empresas->direcao->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->direcao) ?>',1);"><div id="elh_empresas_direcao" class="empresas_direcao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->direcao->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->direcao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->direcao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
	<?php if ($empresas->SortUrl($empresas->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $empresas->_email->HeaderCellClass() ?>"><div id="elh_empresas__email" class="empresas__email"><div class="ewTableHeaderCaption"><?php echo $empresas->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $empresas->_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->_email) ?>',1);"><div id="elh_empresas__email" class="empresas__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
	<?php if ($empresas->SortUrl($empresas->id_endereco) == "") { ?>
		<th data-name="id_endereco" class="<?php echo $empresas->id_endereco->HeaderCellClass() ?>"><div id="elh_empresas_id_endereco" class="empresas_id_endereco"><div class="ewTableHeaderCaption"><?php echo $empresas->id_endereco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_endereco" class="<?php echo $empresas->id_endereco->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->id_endereco) ?>',1);"><div id="elh_empresas_id_endereco" class="empresas_id_endereco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->id_endereco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->id_endereco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->id_endereco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
	<?php if ($empresas->SortUrl($empresas->endereco_numero) == "") { ?>
		<th data-name="endereco_numero" class="<?php echo $empresas->endereco_numero->HeaderCellClass() ?>"><div id="elh_empresas_endereco_numero" class="empresas_endereco_numero"><div class="ewTableHeaderCaption"><?php echo $empresas->endereco_numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="endereco_numero" class="<?php echo $empresas->endereco_numero->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->endereco_numero) ?>',1);"><div id="elh_empresas_endereco_numero" class="empresas_endereco_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->endereco_numero->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->endereco_numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->endereco_numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
	<?php if ($empresas->SortUrl($empresas->nome_fantasia) == "") { ?>
		<th data-name="nome_fantasia" class="<?php echo $empresas->nome_fantasia->HeaderCellClass() ?>"><div id="elh_empresas_nome_fantasia" class="empresas_nome_fantasia"><div class="ewTableHeaderCaption"><?php echo $empresas->nome_fantasia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome_fantasia" class="<?php echo $empresas->nome_fantasia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->nome_fantasia) ?>',1);"><div id="elh_empresas_nome_fantasia" class="empresas_nome_fantasia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->nome_fantasia->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($empresas->nome_fantasia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->nome_fantasia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$empresas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($empresas->ExportAll && $empresas->Export <> "") {
	$empresas_list->StopRec = $empresas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($empresas_list->TotalRecs > $empresas_list->StartRec + $empresas_list->DisplayRecs - 1)
		$empresas_list->StopRec = $empresas_list->StartRec + $empresas_list->DisplayRecs - 1;
	else
		$empresas_list->StopRec = $empresas_list->TotalRecs;
}
$empresas_list->RecCnt = $empresas_list->StartRec - 1;
if ($empresas_list->Recordset && !$empresas_list->Recordset->EOF) {
	$empresas_list->Recordset->MoveFirst();
	$bSelectLimit = $empresas_list->UseSelectLimit;
	if (!$bSelectLimit && $empresas_list->StartRec > 1)
		$empresas_list->Recordset->Move($empresas_list->StartRec - 1);
} elseif (!$empresas->AllowAddDeleteRow && $empresas_list->StopRec == 0) {
	$empresas_list->StopRec = $empresas->GridAddRowCount;
}

// Initialize aggregate
$empresas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$empresas->ResetAttrs();
$empresas_list->RenderRow();
while ($empresas_list->RecCnt < $empresas_list->StopRec) {
	$empresas_list->RecCnt++;
	if (intval($empresas_list->RecCnt) >= intval($empresas_list->StartRec)) {
		$empresas_list->RowCnt++;

		// Set up key count
		$empresas_list->KeyCount = $empresas_list->RowIndex;

		// Init row class and style
		$empresas->ResetAttrs();
		$empresas->CssClass = "";
		if ($empresas->CurrentAction == "gridadd") {
		} else {
			$empresas_list->LoadRowValues($empresas_list->Recordset); // Load row values
		}
		$empresas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$empresas->RowAttrs = array_merge($empresas->RowAttrs, array('data-rowindex'=>$empresas_list->RowCnt, 'id'=>'r' . $empresas_list->RowCnt . '_empresas', 'data-rowtype'=>$empresas->RowType));

		// Render row
		$empresas_list->RenderRow();

		// Render list options
		$empresas_list->RenderListOptions();
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empresas_list->ListOptions->Render("body", "left", $empresas_list->RowCnt);
?>
	<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
		<td data-name="id_perfil"<?php echo $empresas->id_perfil->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_id_perfil" class="empresas_id_perfil">
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<?php echo $empresas->id_perfil->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->razao_social->Visible) { // razao_social ?>
		<td data-name="razao_social"<?php echo $empresas->razao_social->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_razao_social" class="empresas_razao_social">
<span<?php echo $empresas->razao_social->ViewAttributes() ?>>
<?php echo $empresas->razao_social->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->proprietario->Visible) { // proprietario ?>
		<td data-name="proprietario"<?php echo $empresas->proprietario->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_proprietario" class="empresas_proprietario">
<span<?php echo $empresas->proprietario->ViewAttributes() ?>>
<?php echo $empresas->proprietario->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->telefone->Visible) { // telefone ?>
		<td data-name="telefone"<?php echo $empresas->telefone->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_telefone" class="empresas_telefone">
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<?php echo $empresas->telefone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->direcao->Visible) { // direcao ?>
		<td data-name="direcao"<?php echo $empresas->direcao->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_direcao" class="empresas_direcao">
<span<?php echo $empresas->direcao->ViewAttributes() ?>>
<?php echo $empresas->direcao->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $empresas->_email->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas__email" class="empresas__email">
<span<?php echo $empresas->_email->ViewAttributes() ?>>
<?php echo $empresas->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
		<td data-name="id_endereco"<?php echo $empresas->id_endereco->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_id_endereco" class="empresas_id_endereco">
<span<?php echo $empresas->id_endereco->ViewAttributes() ?>>
<?php echo $empresas->id_endereco->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
		<td data-name="endereco_numero"<?php echo $empresas->endereco_numero->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_endereco_numero" class="empresas_endereco_numero">
<span<?php echo $empresas->endereco_numero->ViewAttributes() ?>>
<?php echo $empresas->endereco_numero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
		<td data-name="nome_fantasia"<?php echo $empresas->nome_fantasia->CellAttributes() ?>>
<span id="el<?php echo $empresas_list->RowCnt ?>_empresas_nome_fantasia" class="empresas_nome_fantasia">
<span<?php echo $empresas->nome_fantasia->ViewAttributes() ?>>
<?php echo $empresas->nome_fantasia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empresas_list->ListOptions->Render("body", "right", $empresas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($empresas->CurrentAction <> "gridadd")
		$empresas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($empresas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($empresas_list->Recordset)
	$empresas_list->Recordset->Close();
?>
<?php if ($empresas->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($empresas->CurrentAction <> "gridadd" && $empresas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($empresas_list->Pager)) $empresas_list->Pager = new cPrevNextPager($empresas_list->StartRec, $empresas_list->DisplayRecs, $empresas_list->TotalRecs, $empresas_list->AutoHidePager) ?>
<?php if ($empresas_list->Pager->RecordCount > 0 && $empresas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($empresas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empresas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empresas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($empresas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empresas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empresas_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($empresas_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $empresas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $empresas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $empresas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empresas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($empresas_list->TotalRecs == 0 && $empresas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empresas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($empresas->Export == "") { ?>
<script type="text/javascript">
fempresaslistsrch.FilterList = <?php echo $empresas_list->GetFilterList() ?>;
fempresaslistsrch.Init();
fempresaslist.Init();
</script>
<?php } ?>
<?php
$empresas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($empresas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$empresas_list->Page_Terminate();
?>
