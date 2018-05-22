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

$produtos_list = NULL; // Initialize page object first

class cprodutos_list extends cprodutos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'produtos';

	// Page object name
	var $PageObjName = 'produtos_list';

	// Grid form hidden field names
	var $FormName = 'fprodutoslist';
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

		// Table object (produtos)
		if (!isset($GLOBALS["produtos"]) || get_class($GLOBALS["produtos"]) == "cprodutos") {
			$GLOBALS["produtos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["produtos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "produtosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "produtosdelete.php";
		$this->MultiUpdateUrl = "produtosupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fprodutoslistsrch";

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
		$this->id_produto->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_produto->Visible = FALSE;
		$this->codigo_produto->SetVisibility();
		$this->nome_produto->SetVisibility();
		$this->modelo_produto->SetVisibility();
		$this->id_marca_produto->SetVisibility();
		$this->status_produto->SetVisibility();
		$this->unidade_medida_produto->SetVisibility();
		$this->peso_produto->SetVisibility();
		$this->data_adicionado->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->data_adicionado->Visible = FALSE;
		$this->hora_adicionado->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->hora_adicionado->Visible = FALSE;
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

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
			$this->id_produto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_produto->FormValue))
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

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server" && isset($UserProfile))
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fprodutoslistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id_produto->AdvancedSearch->ToJson(), ","); // Field id_produto
		$sFilterList = ew_Concat($sFilterList, $this->codigo_produto->AdvancedSearch->ToJson(), ","); // Field codigo_produto
		$sFilterList = ew_Concat($sFilterList, $this->nome_produto->AdvancedSearch->ToJson(), ","); // Field nome_produto
		$sFilterList = ew_Concat($sFilterList, $this->modelo_produto->AdvancedSearch->ToJson(), ","); // Field modelo_produto
		$sFilterList = ew_Concat($sFilterList, $this->id_marca_produto->AdvancedSearch->ToJson(), ","); // Field id_marca_produto
		$sFilterList = ew_Concat($sFilterList, $this->status_produto->AdvancedSearch->ToJson(), ","); // Field status_produto
		$sFilterList = ew_Concat($sFilterList, $this->unidade_medida_produto->AdvancedSearch->ToJson(), ","); // Field unidade_medida_produto
		$sFilterList = ew_Concat($sFilterList, $this->peso_produto->AdvancedSearch->ToJson(), ","); // Field peso_produto
		$sFilterList = ew_Concat($sFilterList, $this->data_adicionado->AdvancedSearch->ToJson(), ","); // Field data_adicionado
		$sFilterList = ew_Concat($sFilterList, $this->hora_adicionado->AdvancedSearch->ToJson(), ","); // Field hora_adicionado
		$sFilterList = ew_Concat($sFilterList, $this->preco_produto->AdvancedSearch->ToJson(), ","); // Field preco_produto
		$sFilterList = ew_Concat($sFilterList, $this->descricao->AdvancedSearch->ToJson(), ","); // Field descricao
		$sFilterList = ew_Concat($sFilterList, $this->ipi->AdvancedSearch->ToJson(), ","); // Field ipi
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fprodutoslistsrch", $filters);

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

		// Field id_produto
		$this->id_produto->AdvancedSearch->SearchValue = @$filter["x_id_produto"];
		$this->id_produto->AdvancedSearch->SearchOperator = @$filter["z_id_produto"];
		$this->id_produto->AdvancedSearch->SearchCondition = @$filter["v_id_produto"];
		$this->id_produto->AdvancedSearch->SearchValue2 = @$filter["y_id_produto"];
		$this->id_produto->AdvancedSearch->SearchOperator2 = @$filter["w_id_produto"];
		$this->id_produto->AdvancedSearch->Save();

		// Field codigo_produto
		$this->codigo_produto->AdvancedSearch->SearchValue = @$filter["x_codigo_produto"];
		$this->codigo_produto->AdvancedSearch->SearchOperator = @$filter["z_codigo_produto"];
		$this->codigo_produto->AdvancedSearch->SearchCondition = @$filter["v_codigo_produto"];
		$this->codigo_produto->AdvancedSearch->SearchValue2 = @$filter["y_codigo_produto"];
		$this->codigo_produto->AdvancedSearch->SearchOperator2 = @$filter["w_codigo_produto"];
		$this->codigo_produto->AdvancedSearch->Save();

		// Field nome_produto
		$this->nome_produto->AdvancedSearch->SearchValue = @$filter["x_nome_produto"];
		$this->nome_produto->AdvancedSearch->SearchOperator = @$filter["z_nome_produto"];
		$this->nome_produto->AdvancedSearch->SearchCondition = @$filter["v_nome_produto"];
		$this->nome_produto->AdvancedSearch->SearchValue2 = @$filter["y_nome_produto"];
		$this->nome_produto->AdvancedSearch->SearchOperator2 = @$filter["w_nome_produto"];
		$this->nome_produto->AdvancedSearch->Save();

		// Field modelo_produto
		$this->modelo_produto->AdvancedSearch->SearchValue = @$filter["x_modelo_produto"];
		$this->modelo_produto->AdvancedSearch->SearchOperator = @$filter["z_modelo_produto"];
		$this->modelo_produto->AdvancedSearch->SearchCondition = @$filter["v_modelo_produto"];
		$this->modelo_produto->AdvancedSearch->SearchValue2 = @$filter["y_modelo_produto"];
		$this->modelo_produto->AdvancedSearch->SearchOperator2 = @$filter["w_modelo_produto"];
		$this->modelo_produto->AdvancedSearch->Save();

		// Field id_marca_produto
		$this->id_marca_produto->AdvancedSearch->SearchValue = @$filter["x_id_marca_produto"];
		$this->id_marca_produto->AdvancedSearch->SearchOperator = @$filter["z_id_marca_produto"];
		$this->id_marca_produto->AdvancedSearch->SearchCondition = @$filter["v_id_marca_produto"];
		$this->id_marca_produto->AdvancedSearch->SearchValue2 = @$filter["y_id_marca_produto"];
		$this->id_marca_produto->AdvancedSearch->SearchOperator2 = @$filter["w_id_marca_produto"];
		$this->id_marca_produto->AdvancedSearch->Save();

		// Field status_produto
		$this->status_produto->AdvancedSearch->SearchValue = @$filter["x_status_produto"];
		$this->status_produto->AdvancedSearch->SearchOperator = @$filter["z_status_produto"];
		$this->status_produto->AdvancedSearch->SearchCondition = @$filter["v_status_produto"];
		$this->status_produto->AdvancedSearch->SearchValue2 = @$filter["y_status_produto"];
		$this->status_produto->AdvancedSearch->SearchOperator2 = @$filter["w_status_produto"];
		$this->status_produto->AdvancedSearch->Save();

		// Field unidade_medida_produto
		$this->unidade_medida_produto->AdvancedSearch->SearchValue = @$filter["x_unidade_medida_produto"];
		$this->unidade_medida_produto->AdvancedSearch->SearchOperator = @$filter["z_unidade_medida_produto"];
		$this->unidade_medida_produto->AdvancedSearch->SearchCondition = @$filter["v_unidade_medida_produto"];
		$this->unidade_medida_produto->AdvancedSearch->SearchValue2 = @$filter["y_unidade_medida_produto"];
		$this->unidade_medida_produto->AdvancedSearch->SearchOperator2 = @$filter["w_unidade_medida_produto"];
		$this->unidade_medida_produto->AdvancedSearch->Save();

		// Field peso_produto
		$this->peso_produto->AdvancedSearch->SearchValue = @$filter["x_peso_produto"];
		$this->peso_produto->AdvancedSearch->SearchOperator = @$filter["z_peso_produto"];
		$this->peso_produto->AdvancedSearch->SearchCondition = @$filter["v_peso_produto"];
		$this->peso_produto->AdvancedSearch->SearchValue2 = @$filter["y_peso_produto"];
		$this->peso_produto->AdvancedSearch->SearchOperator2 = @$filter["w_peso_produto"];
		$this->peso_produto->AdvancedSearch->Save();

		// Field data_adicionado
		$this->data_adicionado->AdvancedSearch->SearchValue = @$filter["x_data_adicionado"];
		$this->data_adicionado->AdvancedSearch->SearchOperator = @$filter["z_data_adicionado"];
		$this->data_adicionado->AdvancedSearch->SearchCondition = @$filter["v_data_adicionado"];
		$this->data_adicionado->AdvancedSearch->SearchValue2 = @$filter["y_data_adicionado"];
		$this->data_adicionado->AdvancedSearch->SearchOperator2 = @$filter["w_data_adicionado"];
		$this->data_adicionado->AdvancedSearch->Save();

		// Field hora_adicionado
		$this->hora_adicionado->AdvancedSearch->SearchValue = @$filter["x_hora_adicionado"];
		$this->hora_adicionado->AdvancedSearch->SearchOperator = @$filter["z_hora_adicionado"];
		$this->hora_adicionado->AdvancedSearch->SearchCondition = @$filter["v_hora_adicionado"];
		$this->hora_adicionado->AdvancedSearch->SearchValue2 = @$filter["y_hora_adicionado"];
		$this->hora_adicionado->AdvancedSearch->SearchOperator2 = @$filter["w_hora_adicionado"];
		$this->hora_adicionado->AdvancedSearch->Save();

		// Field preco_produto
		$this->preco_produto->AdvancedSearch->SearchValue = @$filter["x_preco_produto"];
		$this->preco_produto->AdvancedSearch->SearchOperator = @$filter["z_preco_produto"];
		$this->preco_produto->AdvancedSearch->SearchCondition = @$filter["v_preco_produto"];
		$this->preco_produto->AdvancedSearch->SearchValue2 = @$filter["y_preco_produto"];
		$this->preco_produto->AdvancedSearch->SearchOperator2 = @$filter["w_preco_produto"];
		$this->preco_produto->AdvancedSearch->Save();

		// Field descricao
		$this->descricao->AdvancedSearch->SearchValue = @$filter["x_descricao"];
		$this->descricao->AdvancedSearch->SearchOperator = @$filter["z_descricao"];
		$this->descricao->AdvancedSearch->SearchCondition = @$filter["v_descricao"];
		$this->descricao->AdvancedSearch->SearchValue2 = @$filter["y_descricao"];
		$this->descricao->AdvancedSearch->SearchOperator2 = @$filter["w_descricao"];
		$this->descricao->AdvancedSearch->Save();

		// Field ipi
		$this->ipi->AdvancedSearch->SearchValue = @$filter["x_ipi"];
		$this->ipi->AdvancedSearch->SearchOperator = @$filter["z_ipi"];
		$this->ipi->AdvancedSearch->SearchCondition = @$filter["v_ipi"];
		$this->ipi->AdvancedSearch->SearchValue2 = @$filter["y_ipi"];
		$this->ipi->AdvancedSearch->SearchOperator2 = @$filter["w_ipi"];
		$this->ipi->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nome_produto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->modelo_produto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->unidade_medida_produto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->peso_produto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->descricao, $arKeywords, $type);
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
			$this->UpdateSort($this->id_produto); // id_produto
			$this->UpdateSort($this->codigo_produto); // codigo_produto
			$this->UpdateSort($this->nome_produto); // nome_produto
			$this->UpdateSort($this->modelo_produto); // modelo_produto
			$this->UpdateSort($this->id_marca_produto); // id_marca_produto
			$this->UpdateSort($this->status_produto); // status_produto
			$this->UpdateSort($this->unidade_medida_produto); // unidade_medida_produto
			$this->UpdateSort($this->peso_produto); // peso_produto
			$this->UpdateSort($this->data_adicionado); // data_adicionado
			$this->UpdateSort($this->hora_adicionado); // hora_adicionado
			$this->UpdateSort($this->preco_produto); // preco_produto
			$this->UpdateSort($this->descricao); // descricao
			$this->UpdateSort($this->ipi); // ipi
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_produto->setSort("");
				$this->codigo_produto->setSort("");
				$this->nome_produto->setSort("");
				$this->modelo_produto->setSort("");
				$this->id_marca_produto->setSort("");
				$this->status_produto->setSort("");
				$this->unidade_medida_produto->setSort("");
				$this->peso_produto->setSort("");
				$this->data_adicionado->setSort("");
				$this->hora_adicionado->setSort("");
				$this->preco_produto->setSort("");
				$this->descricao->setSort("");
				$this->ipi->setSort("");
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
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id_produto->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fprodutoslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fprodutoslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fprodutoslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fprodutoslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->id_produto->setDbValue($row['id_produto']);
		$this->codigo_produto->setDbValue($row['codigo_produto']);
		$this->nome_produto->setDbValue($row['nome_produto']);
		$this->modelo_produto->setDbValue($row['modelo_produto']);
		$this->id_departamento_produto->setDbValue($row['id_departamento_produto']);
		$this->id_marca_produto->setDbValue($row['id_marca_produto']);
		$this->status_produto->setDbValue($row['status_produto']);
		$this->unidade_medida_produto->setDbValue($row['unidade_medida_produto']);
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

		$this->id_departamento_produto->CellCssStyle = "white-space: nowrap;";

		// id_marca_produto
		// status_produto
		// unidade_medida_produto
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
		$this->status_produto->ViewValue = $this->status_produto->CurrentValue;
		$this->status_produto->ViewCustomAttributes = "";

		// unidade_medida_produto
		$this->unidade_medida_produto->ViewValue = $this->unidade_medida_produto->CurrentValue;
		$this->unidade_medida_produto->ViewCustomAttributes = "";

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
		$item->Body = "<a class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','print',false,true);\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','excel',false,true);\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','word',false,true);\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','html',false,true);\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','xml',false,true);\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','csv',false,true);\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" onclick=\"ew_Export(document.fprodutoslist,'" . ew_CurrentPage() . "','pdf',false,true);\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_produtos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_produtos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fprodutoslist,sel:true" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($produtos_list)) $produtos_list = new cprodutos_list();

// Page init
$produtos_list->Page_Init();

// Page main
$produtos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produtos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($produtos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fprodutoslist = new ew_Form("fprodutoslist", "list");
fprodutoslist.FormKeyCountName = '<?php echo $produtos_list->FormKeyCountName ?>';

// Form_CustomValidate event
fprodutoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fprodutoslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fprodutoslist.Lists["x_id_marca_produto"] = {"LinkField":"x_id_marca","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_marca","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
fprodutoslist.Lists["x_id_marca_produto"].Data = "<?php echo $produtos_list->id_marca_produto->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fprodutoslistsrch = new ew_Form("fprodutoslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($produtos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($produtos_list->TotalRecs > 0 && $produtos_list->ExportOptions->Visible()) { ?>
<?php $produtos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($produtos_list->SearchOptions->Visible()) { ?>
<?php $produtos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($produtos_list->FilterOptions->Visible()) { ?>
<?php $produtos_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $produtos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($produtos_list->TotalRecs <= 0)
			$produtos_list->TotalRecs = $produtos->ListRecordCount();
	} else {
		if (!$produtos_list->Recordset && ($produtos_list->Recordset = $produtos_list->LoadRecordset()))
			$produtos_list->TotalRecs = $produtos_list->Recordset->RecordCount();
	}
	$produtos_list->StartRec = 1;
	if ($produtos_list->DisplayRecs <= 0 || ($produtos->Export <> "" && $produtos->ExportAll)) // Display all records
		$produtos_list->DisplayRecs = $produtos_list->TotalRecs;
	if (!($produtos->Export <> "" && $produtos->ExportAll))
		$produtos_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$produtos_list->Recordset = $produtos_list->LoadRecordset($produtos_list->StartRec-1, $produtos_list->DisplayRecs);

	// Set no record found message
	if ($produtos->CurrentAction == "" && $produtos_list->TotalRecs == 0) {
		if ($produtos_list->SearchWhere == "0=101")
			$produtos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$produtos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$produtos_list->RenderOtherOptions();
?>
<?php if ($produtos->Export == "" && $produtos->CurrentAction == "") { ?>
<form name="fprodutoslistsrch" id="fprodutoslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($produtos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fprodutoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="produtos">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($produtos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($produtos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $produtos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($produtos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($produtos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($produtos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($produtos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $produtos_list->ShowPageHeader(); ?>
<?php
$produtos_list->ShowMessage();
?>
<?php if ($produtos_list->TotalRecs > 0 || $produtos->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($produtos_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> produtos">
<form name="fprodutoslist" id="fprodutoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produtos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produtos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produtos">
<input type="hidden" name="exporttype" id="exporttype" value="">
<div id="gmp_produtos" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($produtos_list->TotalRecs > 0 || $produtos->CurrentAction == "gridedit") { ?>
<table id="tbl_produtoslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$produtos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$produtos_list->RenderListOptions();

// Render list options (header, left)
$produtos_list->ListOptions->Render("header", "left");
?>
<?php if ($produtos->id_produto->Visible) { // id_produto ?>
	<?php if ($produtos->SortUrl($produtos->id_produto) == "") { ?>
		<th data-name="id_produto" class="<?php echo $produtos->id_produto->HeaderCellClass() ?>"><div id="elh_produtos_id_produto" class="produtos_id_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->id_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_produto" class="<?php echo $produtos->id_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->id_produto) ?>',1);"><div id="elh_produtos_id_produto" class="produtos_id_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->id_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->id_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->id_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
	<?php if ($produtos->SortUrl($produtos->codigo_produto) == "") { ?>
		<th data-name="codigo_produto" class="<?php echo $produtos->codigo_produto->HeaderCellClass() ?>"><div id="elh_produtos_codigo_produto" class="produtos_codigo_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->codigo_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo_produto" class="<?php echo $produtos->codigo_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->codigo_produto) ?>',1);"><div id="elh_produtos_codigo_produto" class="produtos_codigo_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->codigo_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->codigo_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->codigo_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
	<?php if ($produtos->SortUrl($produtos->nome_produto) == "") { ?>
		<th data-name="nome_produto" class="<?php echo $produtos->nome_produto->HeaderCellClass() ?>"><div id="elh_produtos_nome_produto" class="produtos_nome_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->nome_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nome_produto" class="<?php echo $produtos->nome_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->nome_produto) ?>',1);"><div id="elh_produtos_nome_produto" class="produtos_nome_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->nome_produto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produtos->nome_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->nome_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
	<?php if ($produtos->SortUrl($produtos->modelo_produto) == "") { ?>
		<th data-name="modelo_produto" class="<?php echo $produtos->modelo_produto->HeaderCellClass() ?>"><div id="elh_produtos_modelo_produto" class="produtos_modelo_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->modelo_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modelo_produto" class="<?php echo $produtos->modelo_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->modelo_produto) ?>',1);"><div id="elh_produtos_modelo_produto" class="produtos_modelo_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->modelo_produto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produtos->modelo_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->modelo_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
	<?php if ($produtos->SortUrl($produtos->id_marca_produto) == "") { ?>
		<th data-name="id_marca_produto" class="<?php echo $produtos->id_marca_produto->HeaderCellClass() ?>"><div id="elh_produtos_id_marca_produto" class="produtos_id_marca_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->id_marca_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_marca_produto" class="<?php echo $produtos->id_marca_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->id_marca_produto) ?>',1);"><div id="elh_produtos_id_marca_produto" class="produtos_id_marca_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->id_marca_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->id_marca_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->id_marca_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->status_produto->Visible) { // status_produto ?>
	<?php if ($produtos->SortUrl($produtos->status_produto) == "") { ?>
		<th data-name="status_produto" class="<?php echo $produtos->status_produto->HeaderCellClass() ?>"><div id="elh_produtos_status_produto" class="produtos_status_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->status_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_produto" class="<?php echo $produtos->status_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->status_produto) ?>',1);"><div id="elh_produtos_status_produto" class="produtos_status_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->status_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->status_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->status_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->unidade_medida_produto->Visible) { // unidade_medida_produto ?>
	<?php if ($produtos->SortUrl($produtos->unidade_medida_produto) == "") { ?>
		<th data-name="unidade_medida_produto" class="<?php echo $produtos->unidade_medida_produto->HeaderCellClass() ?>"><div id="elh_produtos_unidade_medida_produto" class="produtos_unidade_medida_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->unidade_medida_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unidade_medida_produto" class="<?php echo $produtos->unidade_medida_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->unidade_medida_produto) ?>',1);"><div id="elh_produtos_unidade_medida_produto" class="produtos_unidade_medida_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->unidade_medida_produto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produtos->unidade_medida_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->unidade_medida_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->peso_produto->Visible) { // peso_produto ?>
	<?php if ($produtos->SortUrl($produtos->peso_produto) == "") { ?>
		<th data-name="peso_produto" class="<?php echo $produtos->peso_produto->HeaderCellClass() ?>"><div id="elh_produtos_peso_produto" class="produtos_peso_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->peso_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="peso_produto" class="<?php echo $produtos->peso_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->peso_produto) ?>',1);"><div id="elh_produtos_peso_produto" class="produtos_peso_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->peso_produto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produtos->peso_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->peso_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->data_adicionado->Visible) { // data_adicionado ?>
	<?php if ($produtos->SortUrl($produtos->data_adicionado) == "") { ?>
		<th data-name="data_adicionado" class="<?php echo $produtos->data_adicionado->HeaderCellClass() ?>"><div id="elh_produtos_data_adicionado" class="produtos_data_adicionado"><div class="ewTableHeaderCaption"><?php echo $produtos->data_adicionado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="data_adicionado" class="<?php echo $produtos->data_adicionado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->data_adicionado) ?>',1);"><div id="elh_produtos_data_adicionado" class="produtos_data_adicionado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->data_adicionado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->data_adicionado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->data_adicionado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->hora_adicionado->Visible) { // hora_adicionado ?>
	<?php if ($produtos->SortUrl($produtos->hora_adicionado) == "") { ?>
		<th data-name="hora_adicionado" class="<?php echo $produtos->hora_adicionado->HeaderCellClass() ?>"><div id="elh_produtos_hora_adicionado" class="produtos_hora_adicionado"><div class="ewTableHeaderCaption"><?php echo $produtos->hora_adicionado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hora_adicionado" class="<?php echo $produtos->hora_adicionado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->hora_adicionado) ?>',1);"><div id="elh_produtos_hora_adicionado" class="produtos_hora_adicionado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->hora_adicionado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->hora_adicionado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->hora_adicionado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
	<?php if ($produtos->SortUrl($produtos->preco_produto) == "") { ?>
		<th data-name="preco_produto" class="<?php echo $produtos->preco_produto->HeaderCellClass() ?>"><div id="elh_produtos_preco_produto" class="produtos_preco_produto"><div class="ewTableHeaderCaption"><?php echo $produtos->preco_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="preco_produto" class="<?php echo $produtos->preco_produto->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->preco_produto) ?>',1);"><div id="elh_produtos_preco_produto" class="produtos_preco_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->preco_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->preco_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->preco_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->descricao->Visible) { // descricao ?>
	<?php if ($produtos->SortUrl($produtos->descricao) == "") { ?>
		<th data-name="descricao" class="<?php echo $produtos->descricao->HeaderCellClass() ?>"><div id="elh_produtos_descricao" class="produtos_descricao"><div class="ewTableHeaderCaption"><?php echo $produtos->descricao->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descricao" class="<?php echo $produtos->descricao->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->descricao) ?>',1);"><div id="elh_produtos_descricao" class="produtos_descricao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->descricao->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produtos->descricao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->descricao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($produtos->ipi->Visible) { // ipi ?>
	<?php if ($produtos->SortUrl($produtos->ipi) == "") { ?>
		<th data-name="ipi" class="<?php echo $produtos->ipi->HeaderCellClass() ?>"><div id="elh_produtos_ipi" class="produtos_ipi"><div class="ewTableHeaderCaption"><?php echo $produtos->ipi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ipi" class="<?php echo $produtos->ipi->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produtos->SortUrl($produtos->ipi) ?>',1);"><div id="elh_produtos_ipi" class="produtos_ipi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produtos->ipi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produtos->ipi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produtos->ipi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$produtos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($produtos->ExportAll && $produtos->Export <> "") {
	$produtos_list->StopRec = $produtos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($produtos_list->TotalRecs > $produtos_list->StartRec + $produtos_list->DisplayRecs - 1)
		$produtos_list->StopRec = $produtos_list->StartRec + $produtos_list->DisplayRecs - 1;
	else
		$produtos_list->StopRec = $produtos_list->TotalRecs;
}
$produtos_list->RecCnt = $produtos_list->StartRec - 1;
if ($produtos_list->Recordset && !$produtos_list->Recordset->EOF) {
	$produtos_list->Recordset->MoveFirst();
	$bSelectLimit = $produtos_list->UseSelectLimit;
	if (!$bSelectLimit && $produtos_list->StartRec > 1)
		$produtos_list->Recordset->Move($produtos_list->StartRec - 1);
} elseif (!$produtos->AllowAddDeleteRow && $produtos_list->StopRec == 0) {
	$produtos_list->StopRec = $produtos->GridAddRowCount;
}

// Initialize aggregate
$produtos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$produtos->ResetAttrs();
$produtos_list->RenderRow();
while ($produtos_list->RecCnt < $produtos_list->StopRec) {
	$produtos_list->RecCnt++;
	if (intval($produtos_list->RecCnt) >= intval($produtos_list->StartRec)) {
		$produtos_list->RowCnt++;

		// Set up key count
		$produtos_list->KeyCount = $produtos_list->RowIndex;

		// Init row class and style
		$produtos->ResetAttrs();
		$produtos->CssClass = "";
		if ($produtos->CurrentAction == "gridadd") {
		} else {
			$produtos_list->LoadRowValues($produtos_list->Recordset); // Load row values
		}
		$produtos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$produtos->RowAttrs = array_merge($produtos->RowAttrs, array('data-rowindex'=>$produtos_list->RowCnt, 'id'=>'r' . $produtos_list->RowCnt . '_produtos', 'data-rowtype'=>$produtos->RowType));

		// Render row
		$produtos_list->RenderRow();

		// Render list options
		$produtos_list->RenderListOptions();
?>
	<tr<?php echo $produtos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$produtos_list->ListOptions->Render("body", "left", $produtos_list->RowCnt);
?>
	<?php if ($produtos->id_produto->Visible) { // id_produto ?>
		<td data-name="id_produto"<?php echo $produtos->id_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_id_produto" class="produtos_id_produto">
<span<?php echo $produtos->id_produto->ViewAttributes() ?>>
<?php echo $produtos->id_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
		<td data-name="codigo_produto"<?php echo $produtos->codigo_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_codigo_produto" class="produtos_codigo_produto">
<span<?php echo $produtos->codigo_produto->ViewAttributes() ?>>
<?php echo $produtos->codigo_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
		<td data-name="nome_produto"<?php echo $produtos->nome_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_nome_produto" class="produtos_nome_produto">
<span<?php echo $produtos->nome_produto->ViewAttributes() ?>>
<?php echo $produtos->nome_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
		<td data-name="modelo_produto"<?php echo $produtos->modelo_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_modelo_produto" class="produtos_modelo_produto">
<span<?php echo $produtos->modelo_produto->ViewAttributes() ?>>
<?php echo $produtos->modelo_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
		<td data-name="id_marca_produto"<?php echo $produtos->id_marca_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_id_marca_produto" class="produtos_id_marca_produto">
<span<?php echo $produtos->id_marca_produto->ViewAttributes() ?>>
<?php echo $produtos->id_marca_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->status_produto->Visible) { // status_produto ?>
		<td data-name="status_produto"<?php echo $produtos->status_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_status_produto" class="produtos_status_produto">
<span<?php echo $produtos->status_produto->ViewAttributes() ?>>
<?php echo $produtos->status_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->unidade_medida_produto->Visible) { // unidade_medida_produto ?>
		<td data-name="unidade_medida_produto"<?php echo $produtos->unidade_medida_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_unidade_medida_produto" class="produtos_unidade_medida_produto">
<span<?php echo $produtos->unidade_medida_produto->ViewAttributes() ?>>
<?php echo $produtos->unidade_medida_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->peso_produto->Visible) { // peso_produto ?>
		<td data-name="peso_produto"<?php echo $produtos->peso_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_peso_produto" class="produtos_peso_produto">
<span<?php echo $produtos->peso_produto->ViewAttributes() ?>>
<?php echo $produtos->peso_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->data_adicionado->Visible) { // data_adicionado ?>
		<td data-name="data_adicionado"<?php echo $produtos->data_adicionado->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_data_adicionado" class="produtos_data_adicionado">
<span<?php echo $produtos->data_adicionado->ViewAttributes() ?>>
<?php echo $produtos->data_adicionado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->hora_adicionado->Visible) { // hora_adicionado ?>
		<td data-name="hora_adicionado"<?php echo $produtos->hora_adicionado->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_hora_adicionado" class="produtos_hora_adicionado">
<span<?php echo $produtos->hora_adicionado->ViewAttributes() ?>>
<?php echo $produtos->hora_adicionado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
		<td data-name="preco_produto"<?php echo $produtos->preco_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_preco_produto" class="produtos_preco_produto">
<span<?php echo $produtos->preco_produto->ViewAttributes() ?>>
<?php echo $produtos->preco_produto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->descricao->Visible) { // descricao ?>
		<td data-name="descricao"<?php echo $produtos->descricao->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_descricao" class="produtos_descricao">
<span<?php echo $produtos->descricao->ViewAttributes() ?>>
<?php echo $produtos->descricao->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produtos->ipi->Visible) { // ipi ?>
		<td data-name="ipi"<?php echo $produtos->ipi->CellAttributes() ?>>
<span id="el<?php echo $produtos_list->RowCnt ?>_produtos_ipi" class="produtos_ipi">
<span<?php echo $produtos->ipi->ViewAttributes() ?>>
<?php echo $produtos->ipi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$produtos_list->ListOptions->Render("body", "right", $produtos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($produtos->CurrentAction <> "gridadd")
		$produtos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($produtos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($produtos_list->Recordset)
	$produtos_list->Recordset->Close();
?>
<?php if ($produtos->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($produtos->CurrentAction <> "gridadd" && $produtos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($produtos_list->Pager)) $produtos_list->Pager = new cPrevNextPager($produtos_list->StartRec, $produtos_list->DisplayRecs, $produtos_list->TotalRecs, $produtos_list->AutoHidePager) ?>
<?php if ($produtos_list->Pager->RecordCount > 0 && $produtos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($produtos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $produtos_list->PageUrl() ?>start=<?php echo $produtos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($produtos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $produtos_list->PageUrl() ?>start=<?php echo $produtos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $produtos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($produtos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $produtos_list->PageUrl() ?>start=<?php echo $produtos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($produtos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $produtos_list->PageUrl() ?>start=<?php echo $produtos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $produtos_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($produtos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $produtos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $produtos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $produtos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($produtos_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($produtos_list->TotalRecs == 0 && $produtos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($produtos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($produtos->Export == "") { ?>
<script type="text/javascript">
fprodutoslistsrch.FilterList = <?php echo $produtos_list->GetFilterList() ?>;
fprodutoslistsrch.Init();
fprodutoslist.Init();
</script>
<?php } ?>
<?php
$produtos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($produtos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$produtos_list->Page_Terminate();
?>
