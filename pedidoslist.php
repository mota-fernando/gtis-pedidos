<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pedidosinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pedidos_list = NULL; // Initialize page object first

class cpedidos_list extends cpedidos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{D83B9BB1-2CD4-4540-9A5B-B0E890360FB3}';

	// Table name
	var $TableName = 'pedidos';

	// Page object name
	var $PageObjName = 'pedidos_list';

	// Grid form hidden field names
	var $FormName = 'fpedidoslist';
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

		// Table object (pedidos)
		if (!isset($GLOBALS["pedidos"]) || get_class($GLOBALS["pedidos"]) == "cpedidos") {
			$GLOBALS["pedidos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pedidos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pedidosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pedidosdelete.php";
		$this->MultiUpdateUrl = "pedidosupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pedidos', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fpedidoslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->id_pedidos->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_pedidos->Visible = FALSE;
		$this->numero->SetVisibility();
		$this->fecha_data->SetVisibility();
		$this->fecha_hora->SetVisibility();
		$this->id_fornecedor->SetVisibility();
		$this->id_transportadora->SetVisibility();
		$this->id_prazos->SetVisibility();
		$this->comentarios->SetVisibility();
		$this->id_representante->SetVisibility();
		$this->comissao_representante->SetVisibility();
		$this->tipo_pedido->SetVisibility();
		$this->id_cliente->SetVisibility();

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
		global $EW_EXPORT, $pedidos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pedidos);
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
			$this->id_pedidos->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_pedidos->FormValue))
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fpedidoslistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id_pedidos->AdvancedSearch->ToJson(), ","); // Field id_pedidos
		$sFilterList = ew_Concat($sFilterList, $this->numero->AdvancedSearch->ToJson(), ","); // Field numero
		$sFilterList = ew_Concat($sFilterList, $this->fecha_data->AdvancedSearch->ToJson(), ","); // Field fecha_data
		$sFilterList = ew_Concat($sFilterList, $this->fecha_hora->AdvancedSearch->ToJson(), ","); // Field fecha_hora
		$sFilterList = ew_Concat($sFilterList, $this->id_fornecedor->AdvancedSearch->ToJson(), ","); // Field id_fornecedor
		$sFilterList = ew_Concat($sFilterList, $this->id_transportadora->AdvancedSearch->ToJson(), ","); // Field id_transportadora
		$sFilterList = ew_Concat($sFilterList, $this->id_prazos->AdvancedSearch->ToJson(), ","); // Field id_prazos
		$sFilterList = ew_Concat($sFilterList, $this->comentarios->AdvancedSearch->ToJson(), ","); // Field comentarios
		$sFilterList = ew_Concat($sFilterList, $this->id_representante->AdvancedSearch->ToJson(), ","); // Field id_representante
		$sFilterList = ew_Concat($sFilterList, $this->comissao_representante->AdvancedSearch->ToJson(), ","); // Field comissao_representante
		$sFilterList = ew_Concat($sFilterList, $this->tipo_pedido->AdvancedSearch->ToJson(), ","); // Field tipo_pedido
		$sFilterList = ew_Concat($sFilterList, $this->id_cliente->AdvancedSearch->ToJson(), ","); // Field id_cliente
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fpedidoslistsrch", $filters);

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

		// Field id_pedidos
		$this->id_pedidos->AdvancedSearch->SearchValue = @$filter["x_id_pedidos"];
		$this->id_pedidos->AdvancedSearch->SearchOperator = @$filter["z_id_pedidos"];
		$this->id_pedidos->AdvancedSearch->SearchCondition = @$filter["v_id_pedidos"];
		$this->id_pedidos->AdvancedSearch->SearchValue2 = @$filter["y_id_pedidos"];
		$this->id_pedidos->AdvancedSearch->SearchOperator2 = @$filter["w_id_pedidos"];
		$this->id_pedidos->AdvancedSearch->Save();

		// Field numero
		$this->numero->AdvancedSearch->SearchValue = @$filter["x_numero"];
		$this->numero->AdvancedSearch->SearchOperator = @$filter["z_numero"];
		$this->numero->AdvancedSearch->SearchCondition = @$filter["v_numero"];
		$this->numero->AdvancedSearch->SearchValue2 = @$filter["y_numero"];
		$this->numero->AdvancedSearch->SearchOperator2 = @$filter["w_numero"];
		$this->numero->AdvancedSearch->Save();

		// Field fecha_data
		$this->fecha_data->AdvancedSearch->SearchValue = @$filter["x_fecha_data"];
		$this->fecha_data->AdvancedSearch->SearchOperator = @$filter["z_fecha_data"];
		$this->fecha_data->AdvancedSearch->SearchCondition = @$filter["v_fecha_data"];
		$this->fecha_data->AdvancedSearch->SearchValue2 = @$filter["y_fecha_data"];
		$this->fecha_data->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_data"];
		$this->fecha_data->AdvancedSearch->Save();

		// Field fecha_hora
		$this->fecha_hora->AdvancedSearch->SearchValue = @$filter["x_fecha_hora"];
		$this->fecha_hora->AdvancedSearch->SearchOperator = @$filter["z_fecha_hora"];
		$this->fecha_hora->AdvancedSearch->SearchCondition = @$filter["v_fecha_hora"];
		$this->fecha_hora->AdvancedSearch->SearchValue2 = @$filter["y_fecha_hora"];
		$this->fecha_hora->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_hora"];
		$this->fecha_hora->AdvancedSearch->Save();

		// Field id_fornecedor
		$this->id_fornecedor->AdvancedSearch->SearchValue = @$filter["x_id_fornecedor"];
		$this->id_fornecedor->AdvancedSearch->SearchOperator = @$filter["z_id_fornecedor"];
		$this->id_fornecedor->AdvancedSearch->SearchCondition = @$filter["v_id_fornecedor"];
		$this->id_fornecedor->AdvancedSearch->SearchValue2 = @$filter["y_id_fornecedor"];
		$this->id_fornecedor->AdvancedSearch->SearchOperator2 = @$filter["w_id_fornecedor"];
		$this->id_fornecedor->AdvancedSearch->Save();

		// Field id_transportadora
		$this->id_transportadora->AdvancedSearch->SearchValue = @$filter["x_id_transportadora"];
		$this->id_transportadora->AdvancedSearch->SearchOperator = @$filter["z_id_transportadora"];
		$this->id_transportadora->AdvancedSearch->SearchCondition = @$filter["v_id_transportadora"];
		$this->id_transportadora->AdvancedSearch->SearchValue2 = @$filter["y_id_transportadora"];
		$this->id_transportadora->AdvancedSearch->SearchOperator2 = @$filter["w_id_transportadora"];
		$this->id_transportadora->AdvancedSearch->Save();

		// Field id_prazos
		$this->id_prazos->AdvancedSearch->SearchValue = @$filter["x_id_prazos"];
		$this->id_prazos->AdvancedSearch->SearchOperator = @$filter["z_id_prazos"];
		$this->id_prazos->AdvancedSearch->SearchCondition = @$filter["v_id_prazos"];
		$this->id_prazos->AdvancedSearch->SearchValue2 = @$filter["y_id_prazos"];
		$this->id_prazos->AdvancedSearch->SearchOperator2 = @$filter["w_id_prazos"];
		$this->id_prazos->AdvancedSearch->Save();

		// Field comentarios
		$this->comentarios->AdvancedSearch->SearchValue = @$filter["x_comentarios"];
		$this->comentarios->AdvancedSearch->SearchOperator = @$filter["z_comentarios"];
		$this->comentarios->AdvancedSearch->SearchCondition = @$filter["v_comentarios"];
		$this->comentarios->AdvancedSearch->SearchValue2 = @$filter["y_comentarios"];
		$this->comentarios->AdvancedSearch->SearchOperator2 = @$filter["w_comentarios"];
		$this->comentarios->AdvancedSearch->Save();

		// Field id_representante
		$this->id_representante->AdvancedSearch->SearchValue = @$filter["x_id_representante"];
		$this->id_representante->AdvancedSearch->SearchOperator = @$filter["z_id_representante"];
		$this->id_representante->AdvancedSearch->SearchCondition = @$filter["v_id_representante"];
		$this->id_representante->AdvancedSearch->SearchValue2 = @$filter["y_id_representante"];
		$this->id_representante->AdvancedSearch->SearchOperator2 = @$filter["w_id_representante"];
		$this->id_representante->AdvancedSearch->Save();

		// Field comissao_representante
		$this->comissao_representante->AdvancedSearch->SearchValue = @$filter["x_comissao_representante"];
		$this->comissao_representante->AdvancedSearch->SearchOperator = @$filter["z_comissao_representante"];
		$this->comissao_representante->AdvancedSearch->SearchCondition = @$filter["v_comissao_representante"];
		$this->comissao_representante->AdvancedSearch->SearchValue2 = @$filter["y_comissao_representante"];
		$this->comissao_representante->AdvancedSearch->SearchOperator2 = @$filter["w_comissao_representante"];
		$this->comissao_representante->AdvancedSearch->Save();

		// Field tipo_pedido
		$this->tipo_pedido->AdvancedSearch->SearchValue = @$filter["x_tipo_pedido"];
		$this->tipo_pedido->AdvancedSearch->SearchOperator = @$filter["z_tipo_pedido"];
		$this->tipo_pedido->AdvancedSearch->SearchCondition = @$filter["v_tipo_pedido"];
		$this->tipo_pedido->AdvancedSearch->SearchValue2 = @$filter["y_tipo_pedido"];
		$this->tipo_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_pedido"];
		$this->tipo_pedido->AdvancedSearch->Save();

		// Field id_cliente
		$this->id_cliente->AdvancedSearch->SearchValue = @$filter["x_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchOperator = @$filter["z_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchCondition = @$filter["v_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchValue2 = @$filter["y_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchOperator2 = @$filter["w_id_cliente"];
		$this->id_cliente->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->id_transportadora, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id_prazos, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->comentarios, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->comissao_representante, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipo_pedido, $arKeywords, $type);
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
			$this->UpdateSort($this->id_pedidos); // id_pedidos
			$this->UpdateSort($this->numero); // numero
			$this->UpdateSort($this->fecha_data); // fecha_data
			$this->UpdateSort($this->fecha_hora); // fecha_hora
			$this->UpdateSort($this->id_fornecedor); // id_fornecedor
			$this->UpdateSort($this->id_transportadora); // id_transportadora
			$this->UpdateSort($this->id_prazos); // id_prazos
			$this->UpdateSort($this->comentarios); // comentarios
			$this->UpdateSort($this->id_representante); // id_representante
			$this->UpdateSort($this->comissao_representante); // comissao_representante
			$this->UpdateSort($this->tipo_pedido); // tipo_pedido
			$this->UpdateSort($this->id_cliente); // id_cliente
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
				$this->id_pedidos->setSort("");
				$this->numero->setSort("");
				$this->fecha_data->setSort("");
				$this->fecha_hora->setSort("");
				$this->id_fornecedor->setSort("");
				$this->id_transportadora->setSort("");
				$this->id_prazos->setSort("");
				$this->comentarios->setSort("");
				$this->id_representante->setSort("");
				$this->comissao_representante->setSort("");
				$this->tipo_pedido->setSort("");
				$this->id_cliente->setSort("");
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
		$item->Visible = FALSE;
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id_pedidos->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpedidoslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpedidoslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpedidoslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpedidoslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		$this->id_pedidos->setDbValue($row['id_pedidos']);
		$this->numero->setDbValue($row['numero']);
		$this->fecha_data->setDbValue($row['fecha_data']);
		$this->fecha_hora->setDbValue($row['fecha_hora']);
		$this->id_fornecedor->setDbValue($row['id_fornecedor']);
		$this->id_transportadora->setDbValue($row['id_transportadora']);
		$this->id_prazos->setDbValue($row['id_prazos']);
		$this->comentarios->setDbValue($row['comentarios']);
		$this->id_representante->setDbValue($row['id_representante']);
		$this->comissao_representante->setDbValue($row['comissao_representante']);
		$this->tipo_pedido->setDbValue($row['tipo_pedido']);
		$this->id_cliente->setDbValue($row['id_cliente']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_pedidos'] = NULL;
		$row['numero'] = NULL;
		$row['fecha_data'] = NULL;
		$row['fecha_hora'] = NULL;
		$row['id_fornecedor'] = NULL;
		$row['id_transportadora'] = NULL;
		$row['id_prazos'] = NULL;
		$row['comentarios'] = NULL;
		$row['id_representante'] = NULL;
		$row['comissao_representante'] = NULL;
		$row['tipo_pedido'] = NULL;
		$row['id_cliente'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_pedidos->DbValue = $row['id_pedidos'];
		$this->numero->DbValue = $row['numero'];
		$this->fecha_data->DbValue = $row['fecha_data'];
		$this->fecha_hora->DbValue = $row['fecha_hora'];
		$this->id_fornecedor->DbValue = $row['id_fornecedor'];
		$this->id_transportadora->DbValue = $row['id_transportadora'];
		$this->id_prazos->DbValue = $row['id_prazos'];
		$this->comentarios->DbValue = $row['comentarios'];
		$this->id_representante->DbValue = $row['id_representante'];
		$this->comissao_representante->DbValue = $row['comissao_representante'];
		$this->tipo_pedido->DbValue = $row['tipo_pedido'];
		$this->id_cliente->DbValue = $row['id_cliente'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_pedidos")) <> "")
			$this->id_pedidos->CurrentValue = $this->getKey("id_pedidos"); // id_pedidos
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
		// id_pedidos
		// numero
		// fecha_data
		// fecha_hora
		// id_fornecedor
		// id_transportadora
		// id_prazos
		// comentarios
		// id_representante
		// comissao_representante
		// tipo_pedido
		// id_cliente

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_pedidos
		$this->id_pedidos->ViewValue = $this->id_pedidos->CurrentValue;
		$this->id_pedidos->ViewCustomAttributes = "";

		// numero
		$this->numero->ViewValue = $this->numero->CurrentValue;
		$this->numero->ViewCustomAttributes = "";

		// fecha_data
		$this->fecha_data->ViewValue = $this->fecha_data->CurrentValue;
		$this->fecha_data->ViewValue = ew_FormatDateTime($this->fecha_data->ViewValue, 0);
		$this->fecha_data->ViewCustomAttributes = "";

		// fecha_hora
		$this->fecha_hora->ViewValue = $this->fecha_hora->CurrentValue;
		$this->fecha_hora->ViewValue = ew_FormatDateTime($this->fecha_hora->ViewValue, 4);
		$this->fecha_hora->ViewCustomAttributes = "";

		// id_fornecedor
		$this->id_fornecedor->ViewValue = $this->id_fornecedor->CurrentValue;
		$this->id_fornecedor->ViewCustomAttributes = "";

		// id_transportadora
		$this->id_transportadora->ViewValue = $this->id_transportadora->CurrentValue;
		$this->id_transportadora->ViewCustomAttributes = "";

		// id_prazos
		$this->id_prazos->ViewValue = $this->id_prazos->CurrentValue;
		$this->id_prazos->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

		// id_representante
		$this->id_representante->ViewValue = $this->id_representante->CurrentValue;
		$this->id_representante->ViewCustomAttributes = "";

		// comissao_representante
		$this->comissao_representante->ViewValue = $this->comissao_representante->CurrentValue;
		$this->comissao_representante->ViewCustomAttributes = "";

		// tipo_pedido
		$this->tipo_pedido->ViewValue = $this->tipo_pedido->CurrentValue;
		$this->tipo_pedido->ViewCustomAttributes = "";

		// id_cliente
		$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
		$this->id_cliente->ViewCustomAttributes = "";

			// id_pedidos
			$this->id_pedidos->LinkCustomAttributes = "";
			$this->id_pedidos->HrefValue = "";
			$this->id_pedidos->TooltipValue = "";

			// numero
			$this->numero->LinkCustomAttributes = "";
			$this->numero->HrefValue = "";
			$this->numero->TooltipValue = "";

			// fecha_data
			$this->fecha_data->LinkCustomAttributes = "";
			$this->fecha_data->HrefValue = "";
			$this->fecha_data->TooltipValue = "";

			// fecha_hora
			$this->fecha_hora->LinkCustomAttributes = "";
			$this->fecha_hora->HrefValue = "";
			$this->fecha_hora->TooltipValue = "";

			// id_fornecedor
			$this->id_fornecedor->LinkCustomAttributes = "";
			$this->id_fornecedor->HrefValue = "";
			$this->id_fornecedor->TooltipValue = "";

			// id_transportadora
			$this->id_transportadora->LinkCustomAttributes = "";
			$this->id_transportadora->HrefValue = "";
			$this->id_transportadora->TooltipValue = "";

			// id_prazos
			$this->id_prazos->LinkCustomAttributes = "";
			$this->id_prazos->HrefValue = "";
			$this->id_prazos->TooltipValue = "";

			// comentarios
			$this->comentarios->LinkCustomAttributes = "";
			$this->comentarios->HrefValue = "";
			$this->comentarios->TooltipValue = "";

			// id_representante
			$this->id_representante->LinkCustomAttributes = "";
			$this->id_representante->HrefValue = "";
			$this->id_representante->TooltipValue = "";

			// comissao_representante
			$this->comissao_representante->LinkCustomAttributes = "";
			$this->comissao_representante->HrefValue = "";
			$this->comissao_representante->TooltipValue = "";

			// tipo_pedido
			$this->tipo_pedido->LinkCustomAttributes = "";
			$this->tipo_pedido->HrefValue = "";
			$this->tipo_pedido->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";
			$this->id_cliente->TooltipValue = "";
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
if (!isset($pedidos_list)) $pedidos_list = new cpedidos_list();

// Page init
$pedidos_list->Page_Init();

// Page main
$pedidos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pedidos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpedidoslist = new ew_Form("fpedidoslist", "list");
fpedidoslist.FormKeyCountName = '<?php echo $pedidos_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpedidoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpedidoslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fpedidoslistsrch = new ew_Form("fpedidoslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($pedidos_list->TotalRecs > 0 && $pedidos_list->ExportOptions->Visible()) { ?>
<?php $pedidos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($pedidos_list->SearchOptions->Visible()) { ?>
<?php $pedidos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($pedidos_list->FilterOptions->Visible()) { ?>
<?php $pedidos_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $pedidos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pedidos_list->TotalRecs <= 0)
			$pedidos_list->TotalRecs = $pedidos->ListRecordCount();
	} else {
		if (!$pedidos_list->Recordset && ($pedidos_list->Recordset = $pedidos_list->LoadRecordset()))
			$pedidos_list->TotalRecs = $pedidos_list->Recordset->RecordCount();
	}
	$pedidos_list->StartRec = 1;
	if ($pedidos_list->DisplayRecs <= 0 || ($pedidos->Export <> "" && $pedidos->ExportAll)) // Display all records
		$pedidos_list->DisplayRecs = $pedidos_list->TotalRecs;
	if (!($pedidos->Export <> "" && $pedidos->ExportAll))
		$pedidos_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pedidos_list->Recordset = $pedidos_list->LoadRecordset($pedidos_list->StartRec-1, $pedidos_list->DisplayRecs);

	// Set no record found message
	if ($pedidos->CurrentAction == "" && $pedidos_list->TotalRecs == 0) {
		if ($pedidos_list->SearchWhere == "0=101")
			$pedidos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pedidos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$pedidos_list->RenderOtherOptions();
?>
<?php if ($pedidos->Export == "" && $pedidos->CurrentAction == "") { ?>
<form name="fpedidoslistsrch" id="fpedidoslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($pedidos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fpedidoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pedidos">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($pedidos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($pedidos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $pedidos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($pedidos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($pedidos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($pedidos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($pedidos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $pedidos_list->ShowPageHeader(); ?>
<?php
$pedidos_list->ShowMessage();
?>
<?php if ($pedidos_list->TotalRecs > 0 || $pedidos->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($pedidos_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> pedidos">
<form name="fpedidoslist" id="fpedidoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pedidos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pedidos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pedidos">
<div id="gmp_pedidos" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($pedidos_list->TotalRecs > 0 || $pedidos->CurrentAction == "gridedit") { ?>
<table id="tbl_pedidoslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$pedidos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pedidos_list->RenderListOptions();

// Render list options (header, left)
$pedidos_list->ListOptions->Render("header", "left");
?>
<?php if ($pedidos->id_pedidos->Visible) { // id_pedidos ?>
	<?php if ($pedidos->SortUrl($pedidos->id_pedidos) == "") { ?>
		<th data-name="id_pedidos" class="<?php echo $pedidos->id_pedidos->HeaderCellClass() ?>"><div id="elh_pedidos_id_pedidos" class="pedidos_id_pedidos"><div class="ewTableHeaderCaption"><?php echo $pedidos->id_pedidos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_pedidos" class="<?php echo $pedidos->id_pedidos->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->id_pedidos) ?>',1);"><div id="elh_pedidos_id_pedidos" class="pedidos_id_pedidos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->id_pedidos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->id_pedidos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->id_pedidos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->numero->Visible) { // numero ?>
	<?php if ($pedidos->SortUrl($pedidos->numero) == "") { ?>
		<th data-name="numero" class="<?php echo $pedidos->numero->HeaderCellClass() ?>"><div id="elh_pedidos_numero" class="pedidos_numero"><div class="ewTableHeaderCaption"><?php echo $pedidos->numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numero" class="<?php echo $pedidos->numero->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->numero) ?>',1);"><div id="elh_pedidos_numero" class="pedidos_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->numero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->fecha_data->Visible) { // fecha_data ?>
	<?php if ($pedidos->SortUrl($pedidos->fecha_data) == "") { ?>
		<th data-name="fecha_data" class="<?php echo $pedidos->fecha_data->HeaderCellClass() ?>"><div id="elh_pedidos_fecha_data" class="pedidos_fecha_data"><div class="ewTableHeaderCaption"><?php echo $pedidos->fecha_data->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_data" class="<?php echo $pedidos->fecha_data->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->fecha_data) ?>',1);"><div id="elh_pedidos_fecha_data" class="pedidos_fecha_data">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->fecha_data->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->fecha_data->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->fecha_data->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->fecha_hora->Visible) { // fecha_hora ?>
	<?php if ($pedidos->SortUrl($pedidos->fecha_hora) == "") { ?>
		<th data-name="fecha_hora" class="<?php echo $pedidos->fecha_hora->HeaderCellClass() ?>"><div id="elh_pedidos_fecha_hora" class="pedidos_fecha_hora"><div class="ewTableHeaderCaption"><?php echo $pedidos->fecha_hora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_hora" class="<?php echo $pedidos->fecha_hora->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->fecha_hora) ?>',1);"><div id="elh_pedidos_fecha_hora" class="pedidos_fecha_hora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->fecha_hora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->fecha_hora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->fecha_hora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
	<?php if ($pedidos->SortUrl($pedidos->id_fornecedor) == "") { ?>
		<th data-name="id_fornecedor" class="<?php echo $pedidos->id_fornecedor->HeaderCellClass() ?>"><div id="elh_pedidos_id_fornecedor" class="pedidos_id_fornecedor"><div class="ewTableHeaderCaption"><?php echo $pedidos->id_fornecedor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_fornecedor" class="<?php echo $pedidos->id_fornecedor->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->id_fornecedor) ?>',1);"><div id="elh_pedidos_id_fornecedor" class="pedidos_id_fornecedor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->id_fornecedor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->id_fornecedor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->id_fornecedor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->id_transportadora->Visible) { // id_transportadora ?>
	<?php if ($pedidos->SortUrl($pedidos->id_transportadora) == "") { ?>
		<th data-name="id_transportadora" class="<?php echo $pedidos->id_transportadora->HeaderCellClass() ?>"><div id="elh_pedidos_id_transportadora" class="pedidos_id_transportadora"><div class="ewTableHeaderCaption"><?php echo $pedidos->id_transportadora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_transportadora" class="<?php echo $pedidos->id_transportadora->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->id_transportadora) ?>',1);"><div id="elh_pedidos_id_transportadora" class="pedidos_id_transportadora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->id_transportadora->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->id_transportadora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->id_transportadora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->id_prazos->Visible) { // id_prazos ?>
	<?php if ($pedidos->SortUrl($pedidos->id_prazos) == "") { ?>
		<th data-name="id_prazos" class="<?php echo $pedidos->id_prazos->HeaderCellClass() ?>"><div id="elh_pedidos_id_prazos" class="pedidos_id_prazos"><div class="ewTableHeaderCaption"><?php echo $pedidos->id_prazos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_prazos" class="<?php echo $pedidos->id_prazos->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->id_prazos) ?>',1);"><div id="elh_pedidos_id_prazos" class="pedidos_id_prazos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->id_prazos->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->id_prazos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->id_prazos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->comentarios->Visible) { // comentarios ?>
	<?php if ($pedidos->SortUrl($pedidos->comentarios) == "") { ?>
		<th data-name="comentarios" class="<?php echo $pedidos->comentarios->HeaderCellClass() ?>"><div id="elh_pedidos_comentarios" class="pedidos_comentarios"><div class="ewTableHeaderCaption"><?php echo $pedidos->comentarios->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comentarios" class="<?php echo $pedidos->comentarios->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->comentarios) ?>',1);"><div id="elh_pedidos_comentarios" class="pedidos_comentarios">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->comentarios->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->comentarios->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->comentarios->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->id_representante->Visible) { // id_representante ?>
	<?php if ($pedidos->SortUrl($pedidos->id_representante) == "") { ?>
		<th data-name="id_representante" class="<?php echo $pedidos->id_representante->HeaderCellClass() ?>"><div id="elh_pedidos_id_representante" class="pedidos_id_representante"><div class="ewTableHeaderCaption"><?php echo $pedidos->id_representante->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_representante" class="<?php echo $pedidos->id_representante->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->id_representante) ?>',1);"><div id="elh_pedidos_id_representante" class="pedidos_id_representante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->id_representante->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->id_representante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->id_representante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->comissao_representante->Visible) { // comissao_representante ?>
	<?php if ($pedidos->SortUrl($pedidos->comissao_representante) == "") { ?>
		<th data-name="comissao_representante" class="<?php echo $pedidos->comissao_representante->HeaderCellClass() ?>"><div id="elh_pedidos_comissao_representante" class="pedidos_comissao_representante"><div class="ewTableHeaderCaption"><?php echo $pedidos->comissao_representante->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comissao_representante" class="<?php echo $pedidos->comissao_representante->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->comissao_representante) ?>',1);"><div id="elh_pedidos_comissao_representante" class="pedidos_comissao_representante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->comissao_representante->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->comissao_representante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->comissao_representante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
	<?php if ($pedidos->SortUrl($pedidos->tipo_pedido) == "") { ?>
		<th data-name="tipo_pedido" class="<?php echo $pedidos->tipo_pedido->HeaderCellClass() ?>"><div id="elh_pedidos_tipo_pedido" class="pedidos_tipo_pedido"><div class="ewTableHeaderCaption"><?php echo $pedidos->tipo_pedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_pedido" class="<?php echo $pedidos->tipo_pedido->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->tipo_pedido) ?>',1);"><div id="elh_pedidos_tipo_pedido" class="pedidos_tipo_pedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->tipo_pedido->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->tipo_pedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->tipo_pedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
	<?php if ($pedidos->SortUrl($pedidos->id_cliente) == "") { ?>
		<th data-name="id_cliente" class="<?php echo $pedidos->id_cliente->HeaderCellClass() ?>"><div id="elh_pedidos_id_cliente" class="pedidos_id_cliente"><div class="ewTableHeaderCaption"><?php echo $pedidos->id_cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_cliente" class="<?php echo $pedidos->id_cliente->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pedidos->SortUrl($pedidos->id_cliente) ?>',1);"><div id="elh_pedidos_id_cliente" class="pedidos_id_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedidos->id_cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedidos->id_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedidos->id_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$pedidos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($pedidos->ExportAll && $pedidos->Export <> "") {
	$pedidos_list->StopRec = $pedidos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pedidos_list->TotalRecs > $pedidos_list->StartRec + $pedidos_list->DisplayRecs - 1)
		$pedidos_list->StopRec = $pedidos_list->StartRec + $pedidos_list->DisplayRecs - 1;
	else
		$pedidos_list->StopRec = $pedidos_list->TotalRecs;
}
$pedidos_list->RecCnt = $pedidos_list->StartRec - 1;
if ($pedidos_list->Recordset && !$pedidos_list->Recordset->EOF) {
	$pedidos_list->Recordset->MoveFirst();
	$bSelectLimit = $pedidos_list->UseSelectLimit;
	if (!$bSelectLimit && $pedidos_list->StartRec > 1)
		$pedidos_list->Recordset->Move($pedidos_list->StartRec - 1);
} elseif (!$pedidos->AllowAddDeleteRow && $pedidos_list->StopRec == 0) {
	$pedidos_list->StopRec = $pedidos->GridAddRowCount;
}

// Initialize aggregate
$pedidos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pedidos->ResetAttrs();
$pedidos_list->RenderRow();
while ($pedidos_list->RecCnt < $pedidos_list->StopRec) {
	$pedidos_list->RecCnt++;
	if (intval($pedidos_list->RecCnt) >= intval($pedidos_list->StartRec)) {
		$pedidos_list->RowCnt++;

		// Set up key count
		$pedidos_list->KeyCount = $pedidos_list->RowIndex;

		// Init row class and style
		$pedidos->ResetAttrs();
		$pedidos->CssClass = "";
		if ($pedidos->CurrentAction == "gridadd") {
		} else {
			$pedidos_list->LoadRowValues($pedidos_list->Recordset); // Load row values
		}
		$pedidos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$pedidos->RowAttrs = array_merge($pedidos->RowAttrs, array('data-rowindex'=>$pedidos_list->RowCnt, 'id'=>'r' . $pedidos_list->RowCnt . '_pedidos', 'data-rowtype'=>$pedidos->RowType));

		// Render row
		$pedidos_list->RenderRow();

		// Render list options
		$pedidos_list->RenderListOptions();
?>
	<tr<?php echo $pedidos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pedidos_list->ListOptions->Render("body", "left", $pedidos_list->RowCnt);
?>
	<?php if ($pedidos->id_pedidos->Visible) { // id_pedidos ?>
		<td data-name="id_pedidos"<?php echo $pedidos->id_pedidos->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_id_pedidos" class="pedidos_id_pedidos">
<span<?php echo $pedidos->id_pedidos->ViewAttributes() ?>>
<?php echo $pedidos->id_pedidos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->numero->Visible) { // numero ?>
		<td data-name="numero"<?php echo $pedidos->numero->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_numero" class="pedidos_numero">
<span<?php echo $pedidos->numero->ViewAttributes() ?>>
<?php echo $pedidos->numero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->fecha_data->Visible) { // fecha_data ?>
		<td data-name="fecha_data"<?php echo $pedidos->fecha_data->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_fecha_data" class="pedidos_fecha_data">
<span<?php echo $pedidos->fecha_data->ViewAttributes() ?>>
<?php echo $pedidos->fecha_data->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->fecha_hora->Visible) { // fecha_hora ?>
		<td data-name="fecha_hora"<?php echo $pedidos->fecha_hora->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_fecha_hora" class="pedidos_fecha_hora">
<span<?php echo $pedidos->fecha_hora->ViewAttributes() ?>>
<?php echo $pedidos->fecha_hora->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
		<td data-name="id_fornecedor"<?php echo $pedidos->id_fornecedor->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_id_fornecedor" class="pedidos_id_fornecedor">
<span<?php echo $pedidos->id_fornecedor->ViewAttributes() ?>>
<?php echo $pedidos->id_fornecedor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->id_transportadora->Visible) { // id_transportadora ?>
		<td data-name="id_transportadora"<?php echo $pedidos->id_transportadora->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_id_transportadora" class="pedidos_id_transportadora">
<span<?php echo $pedidos->id_transportadora->ViewAttributes() ?>>
<?php echo $pedidos->id_transportadora->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->id_prazos->Visible) { // id_prazos ?>
		<td data-name="id_prazos"<?php echo $pedidos->id_prazos->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_id_prazos" class="pedidos_id_prazos">
<span<?php echo $pedidos->id_prazos->ViewAttributes() ?>>
<?php echo $pedidos->id_prazos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->comentarios->Visible) { // comentarios ?>
		<td data-name="comentarios"<?php echo $pedidos->comentarios->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_comentarios" class="pedidos_comentarios">
<span<?php echo $pedidos->comentarios->ViewAttributes() ?>>
<?php echo $pedidos->comentarios->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->id_representante->Visible) { // id_representante ?>
		<td data-name="id_representante"<?php echo $pedidos->id_representante->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_id_representante" class="pedidos_id_representante">
<span<?php echo $pedidos->id_representante->ViewAttributes() ?>>
<?php echo $pedidos->id_representante->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->comissao_representante->Visible) { // comissao_representante ?>
		<td data-name="comissao_representante"<?php echo $pedidos->comissao_representante->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_comissao_representante" class="pedidos_comissao_representante">
<span<?php echo $pedidos->comissao_representante->ViewAttributes() ?>>
<?php echo $pedidos->comissao_representante->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
		<td data-name="tipo_pedido"<?php echo $pedidos->tipo_pedido->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_tipo_pedido" class="pedidos_tipo_pedido">
<span<?php echo $pedidos->tipo_pedido->ViewAttributes() ?>>
<?php echo $pedidos->tipo_pedido->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
		<td data-name="id_cliente"<?php echo $pedidos->id_cliente->CellAttributes() ?>>
<span id="el<?php echo $pedidos_list->RowCnt ?>_pedidos_id_cliente" class="pedidos_id_cliente">
<span<?php echo $pedidos->id_cliente->ViewAttributes() ?>>
<?php echo $pedidos->id_cliente->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pedidos_list->ListOptions->Render("body", "right", $pedidos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($pedidos->CurrentAction <> "gridadd")
		$pedidos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pedidos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pedidos_list->Recordset)
	$pedidos_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($pedidos->CurrentAction <> "gridadd" && $pedidos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pedidos_list->Pager)) $pedidos_list->Pager = new cPrevNextPager($pedidos_list->StartRec, $pedidos_list->DisplayRecs, $pedidos_list->TotalRecs, $pedidos_list->AutoHidePager) ?>
<?php if ($pedidos_list->Pager->RecordCount > 0 && $pedidos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pedidos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pedidos_list->PageUrl() ?>start=<?php echo $pedidos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pedidos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pedidos_list->PageUrl() ?>start=<?php echo $pedidos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pedidos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pedidos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pedidos_list->PageUrl() ?>start=<?php echo $pedidos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pedidos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pedidos_list->PageUrl() ?>start=<?php echo $pedidos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pedidos_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($pedidos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pedidos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pedidos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pedidos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pedidos_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($pedidos_list->TotalRecs == 0 && $pedidos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pedidos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fpedidoslistsrch.FilterList = <?php echo $pedidos_list->GetFilterList() ?>;
fpedidoslistsrch.Init();
fpedidoslist.Init();
</script>
<?php
$pedidos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pedidos_list->Page_Terminate();
?>
