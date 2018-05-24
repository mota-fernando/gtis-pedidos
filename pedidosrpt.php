<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "pedidosrptinfo.php" ?>
<?php

//
// Page class
//

$pedidos_rpt = NULL; // Initialize page object first

class crpedidos_rpt extends crpedidos {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{3346984C-52AD-427D-8358-EC1897ABFA49}";

	// Page object name
	var $PageObjName = 'pedidos_rpt';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $ReportLanguage;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $ReportLanguage;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $grToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$grToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (pedidos)
		if (!isset($GLOBALS["pedidos"])) {
			$GLOBALS["pedidos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pedidos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'rpt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'pedidos', TRUE);

		// Start timer
		if (!isset($GLOBALS["grTimer"]))
			$GLOBALS["grTimer"] = new crTimer();

		// Debug message
		ewr_LoadDebugMsg();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fpedidosrpt";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security, $UserProfile;
		global $gsCustomExport;

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		// Setup export options

		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a class=\"ewrExportLink ewPrint\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewrExportLink ewExcel\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewrExportLink ewWord\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = FALSE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_pedidos\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_pedidos',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpedidosrpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpedidosrpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fpedidosrpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = FALSE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = FALSE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_FullUrl($saveToFile, "genurl") : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		// Close connection if not in dashboard
		if (!$this->ShowReportInDashboard)
			ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ewr_SaveDebugMsg();
			header("Location: " . $url);
		}
		if (!$this->ShowReportInDashboard)
			exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();
	var $ShowReportInDashboard = FALSE;
	var $TopContentClass = "col-sm-12 ewTop";
	var $LeftContentClass = "ewLeft";
	var $CenterContentClass = "col-sm-12 ewCenter";
	var $RightContentClass = "ewRight";
	var $BottomContentClass = "col-sm-12 ewBottom";

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $grFormError;
		global $grDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;
		global $grDashboardReport;

		// Show report in dashboard
		$this->ShowReportInDashboard = $grDashboardReport;

		// Set field visibility for detail fields
		$this->id_pedidos->SetVisibility();
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
		$this->status->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 14;
		$nGrps = 1;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// No filter
		$this->FilterApplied = FALSE;
		$this->FilterOptions->GetItem("savecurrentfilter")->Visible = FALSE;
		$this->FilterOptions->GetItem("deletefilter")->Visible = FALSE;

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total count
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown || $this->ShowReportInDashboard) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = ($this->TotalGrps > 0);

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
		}

		// Hide export options if export/dashboard report
		if ($this->Export <> "" || $this->ShowReportInDashboard)
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown/dashboard report
		if ($this->Export <> "" || $this->DrillDown || $this->ShowReportInDashboard) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
		$this->SetupFieldCount();
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		$conn = &$this->Connection();
		$rscnt = $conn->Execute($sql);
		$cnt = ($rscnt) ? $rscnt->RecordCount() : 0;
		if ($rscnt) $rscnt->Close();
		return $cnt;
	}

	// Get recordset
	function GetRs($wrksql, $start, $grps) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
				$this->FirstRowData = array();
				$this->FirstRowData['id_pedidos'] = ewr_Conv($rs->fields('id_pedidos'), 3);
				$this->FirstRowData['numero'] = ewr_Conv($rs->fields('numero'), 3);
				$this->FirstRowData['fecha_data'] = ewr_Conv($rs->fields('fecha_data'), 133);
				$this->FirstRowData['fecha_hora'] = ewr_Conv($rs->fields('fecha_hora'), 134);
				$this->FirstRowData['id_fornecedor'] = ewr_Conv($rs->fields('id_fornecedor'), 3);
				$this->FirstRowData['id_transportadora'] = ewr_Conv($rs->fields('id_transportadora'), 3);
				$this->FirstRowData['id_prazos'] = ewr_Conv($rs->fields('id_prazos'), 200);
				$this->FirstRowData['comentarios'] = ewr_Conv($rs->fields('comentarios'), 200);
				$this->FirstRowData['id_representante'] = ewr_Conv($rs->fields('id_representante'), 3);
				$this->FirstRowData['comissao_representante'] = ewr_Conv($rs->fields('comissao_representante'), 200);
				$this->FirstRowData['tipo_pedido'] = ewr_Conv($rs->fields('tipo_pedido'), 200);
				$this->FirstRowData['id_cliente'] = ewr_Conv($rs->fields('id_cliente'), 3);
				$this->FirstRowData['status'] = ewr_Conv($rs->fields('status'), 3);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->id_pedidos->setDbValue($rs->fields('id_pedidos'));
			$this->numero->setDbValue($rs->fields('numero'));
			$this->fecha_data->setDbValue($rs->fields('fecha_data'));
			$this->fecha_hora->setDbValue($rs->fields('fecha_hora'));
			$this->id_fornecedor->setDbValue($rs->fields('id_fornecedor'));
			$this->id_transportadora->setDbValue($rs->fields('id_transportadora'));
			$this->id_prazos->setDbValue($rs->fields('id_prazos'));
			$this->comentarios->setDbValue($rs->fields('comentarios'));
			$this->id_representante->setDbValue($rs->fields('id_representante'));
			$this->comissao_representante->setDbValue($rs->fields('comissao_representante'));
			$this->tipo_pedido->setDbValue($rs->fields('tipo_pedido'));
			$this->id_cliente->setDbValue($rs->fields('id_cliente'));
			$this->status->setDbValue($rs->fields('status'));
			$this->Val[1] = $this->id_pedidos->CurrentValue;
			$this->Val[2] = $this->numero->CurrentValue;
			$this->Val[3] = $this->fecha_data->CurrentValue;
			$this->Val[4] = $this->fecha_hora->CurrentValue;
			$this->Val[5] = $this->id_fornecedor->CurrentValue;
			$this->Val[6] = $this->id_transportadora->CurrentValue;
			$this->Val[7] = $this->id_prazos->CurrentValue;
			$this->Val[8] = $this->comentarios->CurrentValue;
			$this->Val[9] = $this->id_representante->CurrentValue;
			$this->Val[10] = $this->comissao_representante->CurrentValue;
			$this->Val[11] = $this->tipo_pedido->CurrentValue;
			$this->Val[12] = $this->id_cliente->CurrentValue;
			$this->Val[13] = $this->status->CurrentValue;
		} else {
			$this->id_pedidos->setDbValue("");
			$this->numero->setDbValue("");
			$this->fecha_data->setDbValue("");
			$this->fecha_hora->setDbValue("");
			$this->id_fornecedor->setDbValue("");
			$this->id_transportadora->setDbValue("");
			$this->id_prazos->setDbValue("");
			$this->comentarios->setDbValue("");
			$this->id_representante->setDbValue("");
			$this->comissao_representante->setDbValue("");
			$this->tipo_pedido->setDbValue("");
			$this->id_cliente->setDbValue("");
			$this->status->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Output data as Json

			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = $_POST["sel_$sName"];
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = @$_POST["rf_$sName"];
					$_SESSION["rt_$sName"] = @$_POST["rt_$sName"];
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}
		$bGotSummary = TRUE;

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel); // Set up row class

			// id_pedidos
			$this->id_pedidos->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// fecha_data
			$this->fecha_data->HrefValue = "";

			// fecha_hora
			$this->fecha_hora->HrefValue = "";

			// id_fornecedor
			$this->id_fornecedor->HrefValue = "";

			// id_transportadora
			$this->id_transportadora->HrefValue = "";

			// id_prazos
			$this->id_prazos->HrefValue = "";

			// comentarios
			$this->comentarios->HrefValue = "";

			// id_representante
			$this->id_representante->HrefValue = "";

			// comissao_representante
			$this->comissao_representante->HrefValue = "";

			// tipo_pedido
			$this->tipo_pedido->HrefValue = "";

			// id_cliente
			$this->id_cliente->HrefValue = "";

			// status
			$this->status->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// id_pedidos
			$this->id_pedidos->ViewValue = $this->id_pedidos->CurrentValue;
			$this->id_pedidos->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// numero
			$this->numero->ViewValue = $this->numero->CurrentValue;
			$this->numero->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_data
			$this->fecha_data->ViewValue = $this->fecha_data->CurrentValue;
			$this->fecha_data->ViewValue = ewr_FormatDateTime($this->fecha_data->ViewValue, 0);
			$this->fecha_data->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fecha_hora
			$this->fecha_hora->ViewValue = $this->fecha_hora->CurrentValue;
			$this->fecha_hora->ViewValue = ewr_FormatDateTime($this->fecha_hora->ViewValue, 4);
			$this->fecha_hora->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_fornecedor
			$this->id_fornecedor->ViewValue = $this->id_fornecedor->CurrentValue;
			$this->id_fornecedor->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_transportadora
			$this->id_transportadora->ViewValue = $this->id_transportadora->CurrentValue;
			$this->id_transportadora->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_prazos
			$this->id_prazos->ViewValue = $this->id_prazos->CurrentValue;
			$this->id_prazos->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// comentarios
			$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
			$this->comentarios->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_representante
			$this->id_representante->ViewValue = $this->id_representante->CurrentValue;
			$this->id_representante->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// comissao_representante
			$this->comissao_representante->ViewValue = $this->comissao_representante->CurrentValue;
			$this->comissao_representante->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipo_pedido
			$this->tipo_pedido->ViewValue = $this->tipo_pedido->CurrentValue;
			$this->tipo_pedido->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_cliente
			$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
			$this->id_cliente->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id_pedidos
			$this->id_pedidos->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// fecha_data
			$this->fecha_data->HrefValue = "";

			// fecha_hora
			$this->fecha_hora->HrefValue = "";

			// id_fornecedor
			$this->id_fornecedor->HrefValue = "";

			// id_transportadora
			$this->id_transportadora->HrefValue = "";

			// id_prazos
			$this->id_prazos->HrefValue = "";

			// comentarios
			$this->comentarios->HrefValue = "";

			// id_representante
			$this->id_representante->HrefValue = "";

			// comissao_representante
			$this->comissao_representante->HrefValue = "";

			// tipo_pedido
			$this->tipo_pedido->HrefValue = "";

			// id_cliente
			$this->id_cliente->HrefValue = "";

			// status
			$this->status->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// id_pedidos
			$CurrentValue = $this->id_pedidos->CurrentValue;
			$ViewValue = &$this->id_pedidos->ViewValue;
			$ViewAttrs = &$this->id_pedidos->ViewAttrs;
			$CellAttrs = &$this->id_pedidos->CellAttrs;
			$HrefValue = &$this->id_pedidos->HrefValue;
			$LinkAttrs = &$this->id_pedidos->LinkAttrs;
			$this->Cell_Rendered($this->id_pedidos, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// numero
			$CurrentValue = $this->numero->CurrentValue;
			$ViewValue = &$this->numero->ViewValue;
			$ViewAttrs = &$this->numero->ViewAttrs;
			$CellAttrs = &$this->numero->CellAttrs;
			$HrefValue = &$this->numero->HrefValue;
			$LinkAttrs = &$this->numero->LinkAttrs;
			$this->Cell_Rendered($this->numero, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha_data
			$CurrentValue = $this->fecha_data->CurrentValue;
			$ViewValue = &$this->fecha_data->ViewValue;
			$ViewAttrs = &$this->fecha_data->ViewAttrs;
			$CellAttrs = &$this->fecha_data->CellAttrs;
			$HrefValue = &$this->fecha_data->HrefValue;
			$LinkAttrs = &$this->fecha_data->LinkAttrs;
			$this->Cell_Rendered($this->fecha_data, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fecha_hora
			$CurrentValue = $this->fecha_hora->CurrentValue;
			$ViewValue = &$this->fecha_hora->ViewValue;
			$ViewAttrs = &$this->fecha_hora->ViewAttrs;
			$CellAttrs = &$this->fecha_hora->CellAttrs;
			$HrefValue = &$this->fecha_hora->HrefValue;
			$LinkAttrs = &$this->fecha_hora->LinkAttrs;
			$this->Cell_Rendered($this->fecha_hora, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_fornecedor
			$CurrentValue = $this->id_fornecedor->CurrentValue;
			$ViewValue = &$this->id_fornecedor->ViewValue;
			$ViewAttrs = &$this->id_fornecedor->ViewAttrs;
			$CellAttrs = &$this->id_fornecedor->CellAttrs;
			$HrefValue = &$this->id_fornecedor->HrefValue;
			$LinkAttrs = &$this->id_fornecedor->LinkAttrs;
			$this->Cell_Rendered($this->id_fornecedor, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_transportadora
			$CurrentValue = $this->id_transportadora->CurrentValue;
			$ViewValue = &$this->id_transportadora->ViewValue;
			$ViewAttrs = &$this->id_transportadora->ViewAttrs;
			$CellAttrs = &$this->id_transportadora->CellAttrs;
			$HrefValue = &$this->id_transportadora->HrefValue;
			$LinkAttrs = &$this->id_transportadora->LinkAttrs;
			$this->Cell_Rendered($this->id_transportadora, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_prazos
			$CurrentValue = $this->id_prazos->CurrentValue;
			$ViewValue = &$this->id_prazos->ViewValue;
			$ViewAttrs = &$this->id_prazos->ViewAttrs;
			$CellAttrs = &$this->id_prazos->CellAttrs;
			$HrefValue = &$this->id_prazos->HrefValue;
			$LinkAttrs = &$this->id_prazos->LinkAttrs;
			$this->Cell_Rendered($this->id_prazos, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// comentarios
			$CurrentValue = $this->comentarios->CurrentValue;
			$ViewValue = &$this->comentarios->ViewValue;
			$ViewAttrs = &$this->comentarios->ViewAttrs;
			$CellAttrs = &$this->comentarios->CellAttrs;
			$HrefValue = &$this->comentarios->HrefValue;
			$LinkAttrs = &$this->comentarios->LinkAttrs;
			$this->Cell_Rendered($this->comentarios, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_representante
			$CurrentValue = $this->id_representante->CurrentValue;
			$ViewValue = &$this->id_representante->ViewValue;
			$ViewAttrs = &$this->id_representante->ViewAttrs;
			$CellAttrs = &$this->id_representante->CellAttrs;
			$HrefValue = &$this->id_representante->HrefValue;
			$LinkAttrs = &$this->id_representante->LinkAttrs;
			$this->Cell_Rendered($this->id_representante, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// comissao_representante
			$CurrentValue = $this->comissao_representante->CurrentValue;
			$ViewValue = &$this->comissao_representante->ViewValue;
			$ViewAttrs = &$this->comissao_representante->ViewAttrs;
			$CellAttrs = &$this->comissao_representante->CellAttrs;
			$HrefValue = &$this->comissao_representante->HrefValue;
			$LinkAttrs = &$this->comissao_representante->LinkAttrs;
			$this->Cell_Rendered($this->comissao_representante, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipo_pedido
			$CurrentValue = $this->tipo_pedido->CurrentValue;
			$ViewValue = &$this->tipo_pedido->ViewValue;
			$ViewAttrs = &$this->tipo_pedido->ViewAttrs;
			$CellAttrs = &$this->tipo_pedido->CellAttrs;
			$HrefValue = &$this->tipo_pedido->HrefValue;
			$LinkAttrs = &$this->tipo_pedido->LinkAttrs;
			$this->Cell_Rendered($this->tipo_pedido, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id_cliente
			$CurrentValue = $this->id_cliente->CurrentValue;
			$ViewValue = &$this->id_cliente->ViewValue;
			$ViewAttrs = &$this->id_cliente->ViewAttrs;
			$CellAttrs = &$this->id_cliente->CellAttrs;
			$HrefValue = &$this->id_cliente->HrefValue;
			$LinkAttrs = &$this->id_cliente->LinkAttrs;
			$this->Cell_Rendered($this->id_cliente, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// status
			$CurrentValue = $this->status->CurrentValue;
			$ViewValue = &$this->status->ViewValue;
			$ViewAttrs = &$this->status->ViewAttrs;
			$CellAttrs = &$this->status->CellAttrs;
			$HrefValue = &$this->status->HrefValue;
			$LinkAttrs = &$this->status->LinkAttrs;
			$this->Cell_Rendered($this->status, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->id_pedidos->Visible) $this->DtlColumnCount += 1;
		if ($this->numero->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_data->Visible) $this->DtlColumnCount += 1;
		if ($this->fecha_hora->Visible) $this->DtlColumnCount += 1;
		if ($this->id_fornecedor->Visible) $this->DtlColumnCount += 1;
		if ($this->id_transportadora->Visible) $this->DtlColumnCount += 1;
		if ($this->id_prazos->Visible) $this->DtlColumnCount += 1;
		if ($this->comentarios->Visible) $this->DtlColumnCount += 1;
		if ($this->id_representante->Visible) $this->DtlColumnCount += 1;
		if ($this->comissao_representante->Visible) $this->DtlColumnCount += 1;
		if ($this->tipo_pedido->Visible) $this->DtlColumnCount += 1;
		if ($this->id_cliente->Visible) $this->DtlColumnCount += 1;
		if ($this->status->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("rpt", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		return $sWrk;
	}

	// Get sort parameters based on sort links clicked
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : @$_GET["order"];
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : @$_GET["ordertype"];

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
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
			$this->status->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
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
<?php

// Create page object
if (!isset($pedidos_rpt)) $pedidos_rpt = new crpedidos_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$pedidos_rpt;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();
if (!$Page->ShowReportInDashboard)
	ewr_Header(FALSE);

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php if (!$Page->ShowReportInDashboard) { ?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<?php } ?>
<script type="text/javascript">

// Create page object
var pedidos_rpt = new ewr_Page("pedidos_rpt");

// Page properties
pedidos_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = pedidos_rpt.PageID;
</script>
<?php if (!$Page->DrillDown && !$Page->ShowReportInDashboard) { ?>
<?php } ?>
<?php if (!$Page->DrillDown && !$Page->ShowReportInDashboard) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<a id="top"></a>
<?php if ($Page->Export == "" && !$Page->ShowReportInDashboard) { ?>
<!-- Content Container -->
<div id="ewContainer" class="container-fluid ewContainer">
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<div class="ewToolbar">
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "" && !$Page->ShowReportInDashboard) { ?>
<div class="row">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->ShowReportInDashboard) { ?>
<!-- Center Container - Report -->
<div id="ewCenter" class="col-sm-12 ewCenter">
<?php } ?>
<!-- Summary Report begins -->
<div id="report_summary">
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray(2, -1);
$Page->GrpIdx[0] = -1;
$Page->GrpIdx[1] = $Page->StopGrp - $Page->StartGrp + 1;
while ($rs && !$rs->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_pedidos" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->id_pedidos->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_pedidos"><div class="pedidos_id_pedidos"><span class="ewTableHeaderCaption"><?php echo $Page->id_pedidos->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_pedidos">
<?php if ($Page->SortUrl($Page->id_pedidos) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_id_pedidos">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_pedidos->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_id_pedidos" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_pedidos) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_pedidos->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_pedidos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_pedidos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->numero->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="numero"><div class="pedidos_numero"><span class="ewTableHeaderCaption"><?php echo $Page->numero->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="numero">
<?php if ($Page->SortUrl($Page->numero) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_numero">
			<span class="ewTableHeaderCaption"><?php echo $Page->numero->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_numero" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->numero) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->numero->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_data->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_data"><div class="pedidos_fecha_data"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_data->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_data">
<?php if ($Page->SortUrl($Page->fecha_data) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_fecha_data">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_data->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_fecha_data" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_data) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_data->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_data->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_data->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fecha_hora->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fecha_hora"><div class="pedidos_fecha_hora"><span class="ewTableHeaderCaption"><?php echo $Page->fecha_hora->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fecha_hora">
<?php if ($Page->SortUrl($Page->fecha_hora) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_fecha_hora">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_hora->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_fecha_hora" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fecha_hora) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fecha_hora->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fecha_hora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fecha_hora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_fornecedor->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_fornecedor"><div class="pedidos_id_fornecedor"><span class="ewTableHeaderCaption"><?php echo $Page->id_fornecedor->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_fornecedor">
<?php if ($Page->SortUrl($Page->id_fornecedor) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_id_fornecedor">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_fornecedor->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_id_fornecedor" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_fornecedor) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_fornecedor->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_fornecedor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_fornecedor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_transportadora->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_transportadora"><div class="pedidos_id_transportadora"><span class="ewTableHeaderCaption"><?php echo $Page->id_transportadora->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_transportadora">
<?php if ($Page->SortUrl($Page->id_transportadora) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_id_transportadora">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_transportadora->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_id_transportadora" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_transportadora) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_transportadora->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_transportadora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_transportadora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_prazos->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_prazos"><div class="pedidos_id_prazos"><span class="ewTableHeaderCaption"><?php echo $Page->id_prazos->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_prazos">
<?php if ($Page->SortUrl($Page->id_prazos) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_id_prazos">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_prazos->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_id_prazos" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_prazos) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_prazos->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_prazos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_prazos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->comentarios->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="comentarios"><div class="pedidos_comentarios"><span class="ewTableHeaderCaption"><?php echo $Page->comentarios->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="comentarios">
<?php if ($Page->SortUrl($Page->comentarios) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_comentarios">
			<span class="ewTableHeaderCaption"><?php echo $Page->comentarios->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_comentarios" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->comentarios) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->comentarios->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->comentarios->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->comentarios->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_representante->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_representante"><div class="pedidos_id_representante"><span class="ewTableHeaderCaption"><?php echo $Page->id_representante->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_representante">
<?php if ($Page->SortUrl($Page->id_representante) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_id_representante">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_representante->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_id_representante" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_representante) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_representante->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_representante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_representante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->comissao_representante->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="comissao_representante"><div class="pedidos_comissao_representante"><span class="ewTableHeaderCaption"><?php echo $Page->comissao_representante->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="comissao_representante">
<?php if ($Page->SortUrl($Page->comissao_representante) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_comissao_representante">
			<span class="ewTableHeaderCaption"><?php echo $Page->comissao_representante->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_comissao_representante" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->comissao_representante) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->comissao_representante->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->comissao_representante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->comissao_representante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipo_pedido->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipo_pedido"><div class="pedidos_tipo_pedido"><span class="ewTableHeaderCaption"><?php echo $Page->tipo_pedido->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipo_pedido">
<?php if ($Page->SortUrl($Page->tipo_pedido) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_tipo_pedido">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo_pedido->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_tipo_pedido" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipo_pedido) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipo_pedido->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipo_pedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipo_pedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id_cliente->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id_cliente"><div class="pedidos_id_cliente"><span class="ewTableHeaderCaption"><?php echo $Page->id_cliente->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id_cliente">
<?php if ($Page->SortUrl($Page->id_cliente) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_id_cliente">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_cliente->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_id_cliente" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id_cliente) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id_cliente->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="status"><div class="pedidos_status"><span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="status">
<?php if ($Page->SortUrl($Page->status) == "") { ?>
		<div class="ewTableHeaderBtn pedidos_status">
			<span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer pedidos_status" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->status) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}
	$Page->RecCount++;
	$Page->RecIndex++;
?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->id_pedidos->Visible) { ?>
		<td data-field="id_pedidos"<?php echo $Page->id_pedidos->CellAttributes() ?>>
<span<?php echo $Page->id_pedidos->ViewAttributes() ?>><?php echo $Page->id_pedidos->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->numero->Visible) { ?>
		<td data-field="numero"<?php echo $Page->numero->CellAttributes() ?>>
<span<?php echo $Page->numero->ViewAttributes() ?>><?php echo $Page->numero->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_data->Visible) { ?>
		<td data-field="fecha_data"<?php echo $Page->fecha_data->CellAttributes() ?>>
<span<?php echo $Page->fecha_data->ViewAttributes() ?>><?php echo $Page->fecha_data->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fecha_hora->Visible) { ?>
		<td data-field="fecha_hora"<?php echo $Page->fecha_hora->CellAttributes() ?>>
<span<?php echo $Page->fecha_hora->ViewAttributes() ?>><?php echo $Page->fecha_hora->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_fornecedor->Visible) { ?>
		<td data-field="id_fornecedor"<?php echo $Page->id_fornecedor->CellAttributes() ?>>
<span<?php echo $Page->id_fornecedor->ViewAttributes() ?>><?php echo $Page->id_fornecedor->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_transportadora->Visible) { ?>
		<td data-field="id_transportadora"<?php echo $Page->id_transportadora->CellAttributes() ?>>
<span<?php echo $Page->id_transportadora->ViewAttributes() ?>><?php echo $Page->id_transportadora->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_prazos->Visible) { ?>
		<td data-field="id_prazos"<?php echo $Page->id_prazos->CellAttributes() ?>>
<span<?php echo $Page->id_prazos->ViewAttributes() ?>><?php echo $Page->id_prazos->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->comentarios->Visible) { ?>
		<td data-field="comentarios"<?php echo $Page->comentarios->CellAttributes() ?>>
<span<?php echo $Page->comentarios->ViewAttributes() ?>><?php echo $Page->comentarios->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_representante->Visible) { ?>
		<td data-field="id_representante"<?php echo $Page->id_representante->CellAttributes() ?>>
<span<?php echo $Page->id_representante->ViewAttributes() ?>><?php echo $Page->id_representante->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->comissao_representante->Visible) { ?>
		<td data-field="comissao_representante"<?php echo $Page->comissao_representante->CellAttributes() ?>>
<span<?php echo $Page->comissao_representante->ViewAttributes() ?>><?php echo $Page->comissao_representante->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipo_pedido->Visible) { ?>
		<td data-field="tipo_pedido"<?php echo $Page->tipo_pedido->CellAttributes() ?>>
<span<?php echo $Page->tipo_pedido->ViewAttributes() ?>><?php echo $Page->tipo_pedido->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id_cliente->Visible) { ?>
		<td data-field="id_cliente"<?php echo $Page->id_cliente->CellAttributes() ?>>
<span<?php echo $Page->id_cliente->ViewAttributes() ?>><?php echo $Page->id_cliente->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
		<td data-field="status"<?php echo $Page->status->CellAttributes() ?>>
<span<?php echo $Page->status->ViewAttributes() ?>><?php echo $Page->status->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
	$Page->GrpCount++;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_pedidos" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "pedidosrptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "" && !$Page->ShowReportInDashboard) { ?>
</div>
<!-- /#ewCenter -->
<?php } ?>
<?php if ($Page->Export == "" && !$Page->ShowReportInDashboard) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ($Page->Export == "" && !$Page->ShowReportInDashboard) { ?>
</div>
<!-- /.ewContainer -->
<?php } ?>
<?php
$Page->ShowPageFooter();
if (EWR_DEBUG_ENABLED)
	echo ewr_DebugMsg();
?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if (!$Page->DrillDown && !$Page->ShowReportInDashboard) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// console.log("page loaded");

</script>
<?php } ?>
<?php if (!$Page->ShowReportInDashboard) { ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php } ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
