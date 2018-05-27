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

$detalhe_pedido_preview = NULL; // Initialize page object first

class cdetalhe_pedido_preview extends cdetalhe_pedido {

	// Page ID
	var $PageID = 'preview';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'detalhe_pedido';

	// Page object name
	var $PageObjName = 'detalhe_pedido_preview';

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
			define("EW_PAGE_ID", 'preview', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Set up list options
		$this->SetupListOptions();
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

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $Recordset;
	var $TotalRecs;
	var $RowCnt;
	var $RecCount;
	var $ListOptions; // List options
	var $OtherOptions; // Other options
	var $Pager;
	var $StartRec = 1;
	var $DisplayRecs = 0;
	var $SortField = "";
	var $SortOrder = "";

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load filter
		$filter = @$_GET["f"];
		$filter = ew_Decrypt($filter);
		if ($filter == "") $filter = "0=1";
		$this->StartRec = intval(@$_GET["start"]) ?: 1;
		$this->SortField = @$_GET["sort"];
		$this->SortOrder = @$_GET["sortorder"];

		// Set up foreign keys from filter
		$this->SetupForeignKeysFromFilter($filter);

		// Call Recordset Selecting event
		$this->Recordset_Selecting($filter);

		// Load recordset
		$filter = $this->ApplyUserIDFilters($filter);
		$this->TotalRecs = $this->LoadRecordCount($filter);
		$sSql = $this->PreviewSQL($filter);
		if ($this->DisplayRecs > 0)
			$this->Recordset = $this->Connection()->SelectLimit($sSql, $this->DisplayRecs, $this->StartRec - 1);
		if (!$this->Recordset)
			$this->Recordset = $this->Connection()->Execute($sSql);
		if ($this->Recordset) {

			// Call Recordset Selected event
			$this->Recordset_Selected($this->Recordset);
			$this->LoadListRowValues($this->Recordset);
		}
		$this->RenderOtherOptions();
	}

	// Get preview SQL
	function PreviewSQL($filter) {
		$sortField = $this->SortField;
		$sortOrder = $this->SortOrder;
		$sort = "";
		if (array_key_exists($sortField, $this->fields)) {
			$fld = $this->fields[$sortField];
			$sort = $fld->FldExpression;
			if ($sortOrder == "ASC" || $sortOrder == "DESC")
				$sort .= " " . $sortOrder;
		}
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $filter, $sort);
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

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();
		$masterkeyurl = $this->MasterKeyUrl();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewView dropdown-item\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl($masterkeyurl)) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit dropdown-item\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl($masterkeyurl)) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete dropdown-item\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->GetDeleteUrl()) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];
		$option->UseButtonGroup = FALSE;

		// Add group option item
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// Add
		$item = &$option->Add("add");
		$item->Visible = TRUE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->GetItem("add");
		$item->Body = "<a class=\"btn btn-default btn-sm ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl($this->MasterKeyUrl())) . "\">" . $Language->Phrase("AddLink") . "</a>";
	}

	// Get master foreign key url
	function MasterKeyUrl() {
		$mastertblvar = @$_GET["t"];
		$url = "";
		if ($mastertblvar == "pedidos") {
			$url = "" . EW_TABLE_SHOW_MASTER . "=pedidos&fk_numero=" . urlencode(strval($this->numero_pedido->QueryStringValue)) . "";
		}
		return $url;
	}

	// Set up foreign keys from filter
	function SetupForeignKeysFromFilter($f) {
		$mastertblvar = @$_GET["t"];
		if ($mastertblvar == "pedidos") {
			$find = "`numero_pedido`=";
			$x = strpos($f, $find);
			if ($x !== FALSE) {
				$x += strlen($find);
				$val = substr($f, $x);
				$val = $this->UnquoteValue($val, "DB");
 				$this->numero_pedido->setQueryStringValue($val);
			}
		}
	}

	// Unquote value
	function UnquoteValue($val, $dbid) {
		if (substr($val,0,1) == "'" && substr($val,strlen($val)-1) == "'") {
			if (ew_GetConnectionType($dbid) == "MYSQL")
				return stripslashes(substr($val, 1, strlen($val)-2));
			else
				return str_replace("''", "'", substr($val, 1, strlen($val)-2));
		} else {
			return $val;
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE, "utf-8") ?>
<?php

// Create page object
if (!isset($detalhe_pedido_preview)) $detalhe_pedido_preview = new cdetalhe_pedido_preview();

// Page init
$detalhe_pedido_preview->Page_Init();

// Page main
$detalhe_pedido_preview->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detalhe_pedido_preview->Page_Render();
?>
<?php $detalhe_pedido_preview->ShowPageHeader(); ?>
<?php if ($detalhe_pedido_preview->TotalRecs > 0) { ?>
<div class="card ewGrid detalhe_pedido"><!-- .box -->
<div class="table table-sm <?php /* if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } */ ?>ewGridMiddlePanel"><!-- .table-responsive -->
<table class="table ewTable ewPreviewTable"><!-- .table -->
	<thead class="thead-light"><!-- Table header -->
		<tr class="ewTableHeader">
<?php

// Render list options
$detalhe_pedido_preview->RenderListOptions();

// Render list options (header, left)
$detalhe_pedido_preview->ListOptions->Render("header", "left");
?>
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->id_detalhe) == "") { ?>
		<th><?php echo $detalhe_pedido->id_detalhe->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->id_detalhe->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->id_detalhe->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->id_detalhe->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->id_detalhe->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->numero_pedido) == "") { ?>
		<th><?php echo $detalhe_pedido->numero_pedido->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->numero_pedido->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->numero_pedido->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->numero_pedido->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->numero_pedido->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->id_produto) == "") { ?>
		<th><?php echo $detalhe_pedido->id_produto->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->id_produto->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->id_produto->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->id_produto->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->id_produto->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->desconto) == "") { ?>
		<th><?php echo $detalhe_pedido->desconto->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->desconto->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->desconto->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->desconto->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->desconto->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->preco) == "") { ?>
		<th><?php echo $detalhe_pedido->preco->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->preco->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->preco->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->preco->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->preco->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->quantidade) == "") { ?>
		<th><?php echo $detalhe_pedido->quantidade->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->quantidade->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->quantidade->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->quantidade->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->quantidade->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
	<?php if ($detalhe_pedido->SortUrl($detalhe_pedido->subtotal) == "") { ?>
		<th><?php echo $detalhe_pedido->subtotal->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $detalhe_pedido->subtotal->FldName ?>" data-sort-order="<?php echo $detalhe_pedido_preview->SortField == $detalhe_pedido->subtotal->FldName && $detalhe_pedido_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $detalhe_pedido->subtotal->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($detalhe_pedido_preview->SortField == $detalhe_pedido->subtotal->FldName) { ?><?php if ($detalhe_pedido_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($detalhe_pedido_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$detalhe_pedido_preview->ListOptions->Render("header", "right");
?>
		</tr>
	</thead>
	<tbody><!-- Table body -->
<?php
$detalhe_pedido_preview->RecCount = 0;
$detalhe_pedido_preview->RowCnt = 0;
while ($detalhe_pedido_preview->Recordset && !$detalhe_pedido_preview->Recordset->EOF) {

	// Init row class and style
	$detalhe_pedido_preview->RecCount++;
	$detalhe_pedido_preview->RowCnt++;
	$detalhe_pedido_preview->CssStyle = "";
	$detalhe_pedido_preview->LoadListRowValues($detalhe_pedido_preview->Recordset);
	$detalhe_pedido_preview->AggregateListRowValues(); // Aggregate row values

	// Render row
	$detalhe_pedido_preview->RowType = EW_ROWTYPE_PREVIEW; // Preview record
	$detalhe_pedido_preview->ResetAttrs();
	$detalhe_pedido_preview->RenderListRow();

	// Render list options
	$detalhe_pedido_preview->RenderListOptions();
?>
	<tr<?php echo $detalhe_pedido_preview->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detalhe_pedido_preview->ListOptions->Render("body", "left", $detalhe_pedido_preview->RowCnt);
?>
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
		<!-- id_detalhe -->
		<td<?php echo $detalhe_pedido->id_detalhe->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->id_detalhe->ViewAttributes() ?>>
<?php echo $detalhe_pedido->id_detalhe->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
		<!-- numero_pedido -->
		<td<?php echo $detalhe_pedido->numero_pedido->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<?php echo $detalhe_pedido->numero_pedido->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
		<!-- id_produto -->
		<td<?php echo $detalhe_pedido->id_produto->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->id_produto->ViewAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
		<!-- desconto -->
		<td<?php echo $detalhe_pedido->desconto->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->desconto->ViewAttributes() ?>>
<?php echo $detalhe_pedido->desconto->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
		<!-- preco -->
		<td<?php echo $detalhe_pedido->preco->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->preco->ViewAttributes() ?>>
<?php echo $detalhe_pedido->preco->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
		<!-- quantidade -->
		<td<?php echo $detalhe_pedido->quantidade->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->quantidade->ViewAttributes() ?>>
<?php echo $detalhe_pedido->quantidade->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
		<!-- subtotal -->
		<td<?php echo $detalhe_pedido->subtotal->CellAttributes() ?>>
<span<?php echo $detalhe_pedido->subtotal->ViewAttributes() ?>>
<?php echo $detalhe_pedido->subtotal->ListViewValue() ?></span>
</td>
<?php } ?>
<?php

// Render list options (body, right)
$detalhe_pedido_preview->ListOptions->Render("body", "right", $detalhe_pedido_preview->RowCnt);
?>
	</tr>
<?php
	$detalhe_pedido_preview->Recordset->MoveNext();
}
?>
	</tbody>
<?php

	// Render aggregate row
	$detalhe_pedido_preview->AggregateListRow(); // Prepare aggregate row

	// Render list options
	$detalhe_pedido_preview->RenderListOptions();
?>
	<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options (footer, left)
$detalhe_pedido_preview->ListOptions->Render("footer", "left");
?>
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
		<!-- id_detalhe -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
		<!-- numero_pedido -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
		<!-- id_produto -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
		<!-- desconto -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
		<!-- preco -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
		<!-- quantidade -->
		<td>
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span><span class="ewAggregateValue">
<?php echo $detalhe_pedido->quantidade->ViewValue ?></span>
		</td>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
		<!-- subtotal -->
		<td>
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span><span class="ewAggregateValue">
<?php echo $detalhe_pedido->subtotal->ViewValue ?></span>
		</td>
<?php } ?>
<?php

// Render list options (footer, right)
$detalhe_pedido_preview->ListOptions->Render("footer", "right");
?>
	</tr>
	</tfoot>
</table><!-- /.table -->
</div><!-- /.table-responsive -->
<?php } ?>
<div class="card-footer ewGridLowerPanel ewPreviewLowerPanel"><!-- .box-footer -->
<?php if ($detalhe_pedido_preview->TotalRecs > 0) { ?>
<?php if (!isset($detalhe_pedido_preview->Pager)) $detalhe_pedido_preview->Pager = new cPrevNextPager($detalhe_pedido_preview->StartRec, $detalhe_pedido_preview->DisplayRecs, $detalhe_pedido_preview->TotalRecs) ?>
<?php if ($detalhe_pedido_preview->Pager->RecordCount > 0 && $detalhe_pedido_preview->Pager->Visible) { ?>
<div class="ewPager"><div class="ewPrevNext"><div class="btn-group">
<!--first page button-->
	<?php if ($detalhe_pedido_preview->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" data-start="<?php echo $detalhe_pedido_preview->Pager->FirstButton->Start ?>"><span class="fa fa-angle-double-left ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="fa fa-angle-double-left ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($detalhe_pedido_preview->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" data-start="<?php echo $detalhe_pedido_preview->Pager->PrevButton->Start ?>"><span class="fa-angle-left  ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="fa-angle-left ewIcon"></span></a>
	<?php } ?>
<!--next page button-->
	<?php if ($detalhe_pedido_preview->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" data-start="<?php echo $detalhe_pedido_preview->Pager->NextButton->Start ?>"><span class="fa-angle-right  ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="fa-angle-right ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($detalhe_pedido_preview->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" data-start="<?php echo $detalhe_pedido_preview->Pager->LastButton->Start ?>"><span class="fa-angle-double-right  ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="fa-angle-double-right ewIcon"></span></a>
	<?php } ?>
</div></div></div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $detalhe_pedido_preview->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $detalhe_pedido_preview->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $detalhe_pedido_preview->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php } else { ?>
<div class="ewDetailCount"><?php echo $Language->Phrase("NoRecord") ?></div>
<?php } ?>
<div class="ewPreviewOtherOptions">
<?php
	foreach ($detalhe_pedido_preview->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div><!-- /.box-footer -->
</div><!-- /.box -->
<?php
$detalhe_pedido_preview->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
if ($detalhe_pedido_preview->Recordset)
	$detalhe_pedido_preview->Recordset->Close();

// Output
$content = ob_get_contents();
ob_end_clean();
echo ew_ConvertToUtf8($content);
?>
<?php
$detalhe_pedido_preview->Page_Terminate();
?>
