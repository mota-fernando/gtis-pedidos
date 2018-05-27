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

$empresas_preview = NULL; // Initialize page object first

class cempresas_preview extends cempresas {

	// Page ID
	var $PageID = 'preview';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_preview';

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

		// Table object (tranportadora)
		if (!isset($GLOBALS['tranportadora'])) $GLOBALS['tranportadora'] = new ctranportadora();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'preview', TRUE);

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
		$useVirtual = FALSE;
		$sortField = $this->SortField;
		$sortOrder = $this->SortOrder;
		$sort = "";
		if (array_key_exists($sortField, $this->fields)) {
			$fld = $this->fields[$sortField];
			if ($fld->FldVirtualExpression <> "") {
				$sort = $fld->FldVirtualExpression;
				$useVirtual = TRUE;
			} else {
				$sort = $fld->FldExpression;
			}
			if ($sortOrder == "ASC" || $sortOrder == "DESC")
				$sort .= " " . $sortOrder;
		}
		if ($useVirtual) {
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $filter, $sort);
		} else {
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $filter, $sort);
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

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy dropdown-item\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl($masterkeyurl)) . "\">" . $Language->Phrase("CopyLink") . "</a>";
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
		if ($mastertblvar == "tranportadora") {
			$url = "" . EW_TABLE_SHOW_MASTER . "=tranportadora&fk_id_transportadora=" . urlencode(strval($this->id_perfil->QueryStringValue)) . "";
		}
		return $url;
	}

	// Set up foreign keys from filter
	function SetupForeignKeysFromFilter($f) {
		$mastertblvar = @$_GET["t"];
		if ($mastertblvar == "tranportadora") {
			$find = "`id_perfil`=";
			$x = strpos($f, $find);
			if ($x !== FALSE) {
				$x += strlen($find);
				$val = substr($f, $x);
				$val = $this->UnquoteValue($val, "DB");
 				$this->id_perfil->setQueryStringValue($val);
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
if (!isset($empresas_preview)) $empresas_preview = new cempresas_preview();

// Page init
$empresas_preview->Page_Init();

// Page main
$empresas_preview->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_preview->Page_Render();
?>
<?php $empresas_preview->ShowPageHeader(); ?>
<?php if ($empresas_preview->TotalRecs > 0) { ?>
<div class="card ewGrid empresas"><!-- .box -->
<div class="table table-sm <?php /* if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } */ ?>ewGridMiddlePanel"><!-- .table-responsive -->
<table class="table ewTable ewPreviewTable"><!-- .table -->
	<thead class="thead-light"><!-- Table header -->
		<tr class="ewTableHeader">
<?php

// Render list options
$empresas_preview->RenderListOptions();

// Render list options (header, left)
$empresas_preview->ListOptions->Render("header", "left");
?>
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
	<?php if ($empresas->SortUrl($empresas->id_perfil) == "") { ?>
		<th><?php echo $empresas->id_perfil->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->id_perfil->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->id_perfil->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->id_perfil->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->id_perfil->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
	<?php if ($empresas->SortUrl($empresas->razao_social) == "") { ?>
		<th><?php echo $empresas->razao_social->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->razao_social->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->razao_social->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->razao_social->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->razao_social->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
	<?php if ($empresas->SortUrl($empresas->proprietario) == "") { ?>
		<th><?php echo $empresas->proprietario->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->proprietario->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->proprietario->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->proprietario->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->proprietario->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
	<?php if ($empresas->SortUrl($empresas->telefone) == "") { ?>
		<th><?php echo $empresas->telefone->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->telefone->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->telefone->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->telefone->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->telefone->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
	<?php if ($empresas->SortUrl($empresas->direcao) == "") { ?>
		<th><?php echo $empresas->direcao->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->direcao->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->direcao->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->direcao->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->direcao->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
	<?php if ($empresas->SortUrl($empresas->_email) == "") { ?>
		<th><?php echo $empresas->_email->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->_email->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->_email->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->_email->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->_email->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
	<?php if ($empresas->SortUrl($empresas->id_endereco) == "") { ?>
		<th><?php echo $empresas->id_endereco->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->id_endereco->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->id_endereco->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->id_endereco->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->id_endereco->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
	<?php if ($empresas->SortUrl($empresas->endereco_numero) == "") { ?>
		<th><?php echo $empresas->endereco_numero->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->endereco_numero->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->endereco_numero->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->endereco_numero->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->endereco_numero->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
	<?php if ($empresas->SortUrl($empresas->nome_fantasia) == "") { ?>
		<th><?php echo $empresas->nome_fantasia->FldCaption() ?></th>
	<?php } else { ?>
		<th><div class="ewPointer" data-sort="<?php echo $empresas->nome_fantasia->FldName ?>" data-sort-order="<?php echo $empresas_preview->SortField == $empresas->nome_fantasia->FldName && $empresas_preview->SortOrder == "ASC" ? "DESC" : "ASC" ?>"><div class="ewTableHeaderBtn">
		<span class="ewTableHeaderCaption"><?php echo $empresas->nome_fantasia->FldCaption() ?></span>
		<span class="ewTableHeaderSort"><?php if ($empresas_preview->SortField == $empresas->nome_fantasia->FldName) { ?><?php if ($empresas_preview->SortOrder == "ASC") { ?><span class="fa fa-sort-amount-asc"></span><?php } elseif ($empresas_preview->SortOrder == "DESC") { ?><span class="fa fa-sort-amount-desc"></span><?php } ?><?php } ?></span>
	</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$empresas_preview->ListOptions->Render("header", "right");
?>
		</tr>
	</thead>
	<tbody><!-- Table body -->
<?php
$empresas_preview->RecCount = 0;
$empresas_preview->RowCnt = 0;
while ($empresas_preview->Recordset && !$empresas_preview->Recordset->EOF) {

	// Init row class and style
	$empresas_preview->RecCount++;
	$empresas_preview->RowCnt++;
	$empresas_preview->CssStyle = "";
	$empresas_preview->LoadListRowValues($empresas_preview->Recordset);

	// Render row
	$empresas_preview->RowType = EW_ROWTYPE_PREVIEW; // Preview record
	$empresas_preview->ResetAttrs();
	$empresas_preview->RenderListRow();

	// Render list options
	$empresas_preview->RenderListOptions();
?>
	<tr<?php echo $empresas_preview->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empresas_preview->ListOptions->Render("body", "left", $empresas_preview->RowCnt);
?>
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
		<!-- id_perfil -->
		<td<?php echo $empresas->id_perfil->CellAttributes() ?>>
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<?php echo $empresas->id_perfil->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
		<!-- razao_social -->
		<td<?php echo $empresas->razao_social->CellAttributes() ?>>
<span<?php echo $empresas->razao_social->ViewAttributes() ?>>
<?php echo $empresas->razao_social->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
		<!-- proprietario -->
		<td<?php echo $empresas->proprietario->CellAttributes() ?>>
<span<?php echo $empresas->proprietario->ViewAttributes() ?>>
<?php echo $empresas->proprietario->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
		<!-- telefone -->
		<td<?php echo $empresas->telefone->CellAttributes() ?>>
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<?php echo $empresas->telefone->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
		<!-- direcao -->
		<td<?php echo $empresas->direcao->CellAttributes() ?>>
<span<?php echo $empresas->direcao->ViewAttributes() ?>>
<?php echo $empresas->direcao->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
		<!-- email -->
		<td<?php echo $empresas->_email->CellAttributes() ?>>
<span<?php echo $empresas->_email->ViewAttributes() ?>>
<?php echo $empresas->_email->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
		<!-- id_endereco -->
		<td<?php echo $empresas->id_endereco->CellAttributes() ?>>
<span<?php echo $empresas->id_endereco->ViewAttributes() ?>>
<?php echo $empresas->id_endereco->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
		<!-- endereco_numero -->
		<td<?php echo $empresas->endereco_numero->CellAttributes() ?>>
<span<?php echo $empresas->endereco_numero->ViewAttributes() ?>>
<?php echo $empresas->endereco_numero->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
		<!-- nome_fantasia -->
		<td<?php echo $empresas->nome_fantasia->CellAttributes() ?>>
<span<?php echo $empresas->nome_fantasia->ViewAttributes() ?>>
<?php echo $empresas->nome_fantasia->ListViewValue() ?></span>
</td>
<?php } ?>
<?php

// Render list options (body, right)
$empresas_preview->ListOptions->Render("body", "right", $empresas_preview->RowCnt);
?>
	</tr>
<?php
	$empresas_preview->Recordset->MoveNext();
}
?>
	</tbody>
</table><!-- /.table -->
</div><!-- /.table-responsive -->
<?php } ?>
<div class="card-footer ewGridLowerPanel ewPreviewLowerPanel"><!-- .box-footer -->
<?php if ($empresas_preview->TotalRecs > 0) { ?>
<?php if (!isset($empresas_preview->Pager)) $empresas_preview->Pager = new cPrevNextPager($empresas_preview->StartRec, $empresas_preview->DisplayRecs, $empresas_preview->TotalRecs) ?>
<?php if ($empresas_preview->Pager->RecordCount > 0 && $empresas_preview->Pager->Visible) { ?>
<div class="ewPager"><div class="ewPrevNext"><div class="btn-group">
<!--first page button-->
	<?php if ($empresas_preview->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" data-start="<?php echo $empresas_preview->Pager->FirstButton->Start ?>"><span class="fa fa-angle-double-left ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="fa fa-angle-double-left ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empresas_preview->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" data-start="<?php echo $empresas_preview->Pager->PrevButton->Start ?>"><span class="fa-angle-left  ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="fa-angle-left ewIcon"></span></a>
	<?php } ?>
<!--next page button-->
	<?php if ($empresas_preview->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" data-start="<?php echo $empresas_preview->Pager->NextButton->Start ?>"><span class="fa-angle-right  ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="fa-angle-right ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empresas_preview->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" data-start="<?php echo $empresas_preview->Pager->LastButton->Start ?>"><span class="fa-angle-double-right  ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="fa-angle-double-right ewIcon"></span></a>
	<?php } ?>
</div></div></div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $empresas_preview->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $empresas_preview->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $empresas_preview->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php } else { ?>
<div class="ewDetailCount"><?php echo $Language->Phrase("NoRecord") ?></div>
<?php } ?>
<div class="ewPreviewOtherOptions">
<?php
	foreach ($empresas_preview->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div><!-- /.box-footer -->
</div><!-- /.box -->
<?php
$empresas_preview->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
if ($empresas_preview->Recordset)
	$empresas_preview->Recordset->Close();

// Output
$content = ob_get_contents();
ob_end_clean();
echo ew_ConvertToUtf8($content);
?>
<?php
$empresas_preview->Page_Terminate();
?>
