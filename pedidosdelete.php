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

$pedidos_delete = NULL; // Initialize page object first

class cpedidos_delete extends cpedidos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'pedidos';

	// Page object name
	var $PageObjName = 'pedidos_delete';

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

		// Table object (pedidos)
		if (!isset($GLOBALS["pedidos"]) || get_class($GLOBALS["pedidos"]) == "cpedidos") {
			$GLOBALS["pedidos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pedidos"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_pedidos->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_pedidos->Visible = FALSE;
		$this->tipo_pedido->SetVisibility();
		$this->numero->SetVisibility();
		$this->fecha_data->SetVisibility();
		$this->fecha_hora->SetVisibility();
		$this->id_fornecedor->SetVisibility();
		$this->id_transportadora->SetVisibility();
		$this->id_prazos->SetVisibility();
		$this->comentarios->SetVisibility();
		$this->id_representante->SetVisibility();
		$this->comissao_representante->SetVisibility();
		$this->id_cliente->SetVisibility();
		$this->status->SetVisibility();

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("pedidoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pedidos class, pedidosinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("pedidoslist.php"); // Return to list
			}
		}
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
		$this->tipo_pedido->setDbValue($row['tipo_pedido']);
		$this->numero->setDbValue($row['numero']);
		$this->fecha_data->setDbValue($row['fecha_data']);
		$this->fecha_hora->setDbValue($row['fecha_hora']);
		$this->id_fornecedor->setDbValue($row['id_fornecedor']);
		$this->id_transportadora->setDbValue($row['id_transportadora']);
		$this->id_prazos->setDbValue($row['id_prazos']);
		$this->comentarios->setDbValue($row['comentarios']);
		$this->id_representante->setDbValue($row['id_representante']);
		$this->comissao_representante->setDbValue($row['comissao_representante']);
		$this->id_cliente->setDbValue($row['id_cliente']);
		$this->status->setDbValue($row['status']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_pedidos'] = NULL;
		$row['tipo_pedido'] = NULL;
		$row['numero'] = NULL;
		$row['fecha_data'] = NULL;
		$row['fecha_hora'] = NULL;
		$row['id_fornecedor'] = NULL;
		$row['id_transportadora'] = NULL;
		$row['id_prazos'] = NULL;
		$row['comentarios'] = NULL;
		$row['id_representante'] = NULL;
		$row['comissao_representante'] = NULL;
		$row['id_cliente'] = NULL;
		$row['status'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_pedidos->DbValue = $row['id_pedidos'];
		$this->tipo_pedido->DbValue = $row['tipo_pedido'];
		$this->numero->DbValue = $row['numero'];
		$this->fecha_data->DbValue = $row['fecha_data'];
		$this->fecha_hora->DbValue = $row['fecha_hora'];
		$this->id_fornecedor->DbValue = $row['id_fornecedor'];
		$this->id_transportadora->DbValue = $row['id_transportadora'];
		$this->id_prazos->DbValue = $row['id_prazos'];
		$this->comentarios->DbValue = $row['comentarios'];
		$this->id_representante->DbValue = $row['id_representante'];
		$this->comissao_representante->DbValue = $row['comissao_representante'];
		$this->id_cliente->DbValue = $row['id_cliente'];
		$this->status->DbValue = $row['status'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_pedidos
		// tipo_pedido
		// numero
		// fecha_data
		// fecha_hora
		// id_fornecedor
		// id_transportadora
		// id_prazos
		// comentarios
		// id_representante
		// comissao_representante
		// id_cliente
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_pedidos
		$this->id_pedidos->ViewValue = $this->id_pedidos->CurrentValue;
		$this->id_pedidos->ViewCustomAttributes = "";

		// tipo_pedido
		if (strval($this->tipo_pedido->CurrentValue) <> "") {
			$this->tipo_pedido->ViewValue = $this->tipo_pedido->OptionCaption($this->tipo_pedido->CurrentValue);
		} else {
			$this->tipo_pedido->ViewValue = NULL;
		}
		$this->tipo_pedido->ViewCustomAttributes = "";

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
		if (strval($this->id_fornecedor->CurrentValue) <> "") {
			$sFilterWrk = "`id_perfil`" . ew_SearchString("=", $this->id_fornecedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_perfil`, `razao_social` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		$this->id_fornecedor->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_fornecedor, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_fornecedor->ViewValue = $this->id_fornecedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_fornecedor->ViewValue = $this->id_fornecedor->CurrentValue;
			}
		} else {
			$this->id_fornecedor->ViewValue = NULL;
		}
		$this->id_fornecedor->ViewCustomAttributes = "";

		// id_transportadora
		if (strval($this->id_transportadora->CurrentValue) <> "") {
			$sFilterWrk = "`id_transportadora`" . ew_SearchString("=", $this->id_transportadora->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_transportadora`, `transportadora` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tranportadora`";
		$sWhereWrk = "";
		$this->id_transportadora->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_transportadora, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_transportadora->ViewValue = $this->id_transportadora->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_transportadora->ViewValue = $this->id_transportadora->CurrentValue;
			}
		} else {
			$this->id_transportadora->ViewValue = NULL;
		}
		$this->id_transportadora->ViewCustomAttributes = "";

		// id_prazos
		if (strval($this->id_prazos->CurrentValue) <> "") {
			$sFilterWrk = "`id_prazos`" . ew_SearchString("=", $this->id_prazos->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_prazos`, `prazo_em_dias` AS `DispFld`, `parcelas` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `prazos`";
		$sWhereWrk = "";
		$this->id_prazos->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_prazos, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->id_prazos->ViewValue = $this->id_prazos->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_prazos->ViewValue = $this->id_prazos->CurrentValue;
			}
		} else {
			$this->id_prazos->ViewValue = NULL;
		}
		$this->id_prazos->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

		// id_representante
		if (strval($this->id_representante->CurrentValue) <> "") {
			$sFilterWrk = "`id_representantes`" . ew_SearchString("=", $this->id_representante->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_representantes`, `id_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `representantes`";
		$sWhereWrk = "";
		$this->id_representante->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_representante, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_representante->ViewValue = $this->id_representante->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_representante->ViewValue = $this->id_representante->CurrentValue;
			}
		} else {
			$this->id_representante->ViewValue = NULL;
		}
		$this->id_representante->ViewCustomAttributes = "";

		// comissao_representante
		if (strval($this->comissao_representante->CurrentValue) <> "") {
			$this->comissao_representante->ViewValue = $this->comissao_representante->OptionCaption($this->comissao_representante->CurrentValue);
		} else {
			$this->comissao_representante->ViewValue = NULL;
		}
		$this->comissao_representante->ViewCustomAttributes = "";

		// id_cliente
		$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
		if (strval($this->id_cliente->CurrentValue) <> "") {
			$sFilterWrk = "`id_perfil`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_perfil`, `razao_social` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		$this->id_cliente->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_cliente->ViewValue = $this->id_cliente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
			}
		} else {
			$this->id_cliente->ViewValue = NULL;
		}
		$this->id_cliente->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

			// id_pedidos
			$this->id_pedidos->LinkCustomAttributes = "";
			$this->id_pedidos->HrefValue = "";
			$this->id_pedidos->TooltipValue = "";

			// tipo_pedido
			$this->tipo_pedido->LinkCustomAttributes = "";
			$this->tipo_pedido->HrefValue = "";
			$this->tipo_pedido->TooltipValue = "";

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

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";
			$this->id_cliente->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_pedidos'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pedidoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pedidos_delete)) $pedidos_delete = new cpedidos_delete();

// Page init
$pedidos_delete->Page_Init();

// Page main
$pedidos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pedidos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpedidosdelete = new ew_Form("fpedidosdelete", "delete");

// Form_CustomValidate event
fpedidosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpedidosdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpedidosdelete.Lists["x_tipo_pedido"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpedidosdelete.Lists["x_tipo_pedido"].Options = <?php echo json_encode($pedidos_delete->tipo_pedido->Options()) ?>;
fpedidosdelete.Lists["x_id_fornecedor"] = {"LinkField":"x_id_perfil","Ajax":true,"AutoFill":false,"DisplayFields":["x_razao_social","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"empresas"};
fpedidosdelete.Lists["x_id_fornecedor"].Data = "<?php echo $pedidos_delete->id_fornecedor->LookupFilterQuery(FALSE, "delete") ?>";
fpedidosdelete.Lists["x_id_transportadora"] = {"LinkField":"x_id_transportadora","Ajax":true,"AutoFill":false,"DisplayFields":["x_transportadora","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tranportadora"};
fpedidosdelete.Lists["x_id_transportadora"].Data = "<?php echo $pedidos_delete->id_transportadora->LookupFilterQuery(FALSE, "delete") ?>";
fpedidosdelete.Lists["x_id_prazos"] = {"LinkField":"x_id_prazos","Ajax":true,"AutoFill":false,"DisplayFields":["x_prazo_em_dias","x_parcelas","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"prazos"};
fpedidosdelete.Lists["x_id_prazos"].Data = "<?php echo $pedidos_delete->id_prazos->LookupFilterQuery(FALSE, "delete") ?>";
fpedidosdelete.Lists["x_id_representante"] = {"LinkField":"x_id_representantes","Ajax":true,"AutoFill":false,"DisplayFields":["x_id_pessoa","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"representantes"};
fpedidosdelete.Lists["x_id_representante"].Data = "<?php echo $pedidos_delete->id_representante->LookupFilterQuery(FALSE, "delete") ?>";
fpedidosdelete.Lists["x_comissao_representante"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpedidosdelete.Lists["x_comissao_representante"].Options = <?php echo json_encode($pedidos_delete->comissao_representante->Options()) ?>;
fpedidosdelete.Lists["x_id_cliente"] = {"LinkField":"x_id_perfil","Ajax":true,"AutoFill":false,"DisplayFields":["x_razao_social","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"empresas"};
fpedidosdelete.Lists["x_id_cliente"].Data = "<?php echo $pedidos_delete->id_cliente->LookupFilterQuery(FALSE, "delete") ?>";
fpedidosdelete.AutoSuggests["x_id_cliente"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $pedidos_delete->id_cliente->LookupFilterQuery(TRUE, "delete"))) ?>;
fpedidosdelete.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpedidosdelete.Lists["x_status"].Options = <?php echo json_encode($pedidos_delete->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pedidos_delete->ShowPageHeader(); ?>
<?php
$pedidos_delete->ShowMessage();
?>
<form name="fpedidosdelete" id="fpedidosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pedidos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pedidos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pedidos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pedidos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($pedidos->id_pedidos->Visible) { // id_pedidos ?>
		<th class="<?php echo $pedidos->id_pedidos->HeaderCellClass() ?>"><span id="elh_pedidos_id_pedidos" class="pedidos_id_pedidos"><?php echo $pedidos->id_pedidos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
		<th class="<?php echo $pedidos->tipo_pedido->HeaderCellClass() ?>"><span id="elh_pedidos_tipo_pedido" class="pedidos_tipo_pedido"><?php echo $pedidos->tipo_pedido->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->numero->Visible) { // numero ?>
		<th class="<?php echo $pedidos->numero->HeaderCellClass() ?>"><span id="elh_pedidos_numero" class="pedidos_numero"><?php echo $pedidos->numero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->fecha_data->Visible) { // fecha_data ?>
		<th class="<?php echo $pedidos->fecha_data->HeaderCellClass() ?>"><span id="elh_pedidos_fecha_data" class="pedidos_fecha_data"><?php echo $pedidos->fecha_data->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->fecha_hora->Visible) { // fecha_hora ?>
		<th class="<?php echo $pedidos->fecha_hora->HeaderCellClass() ?>"><span id="elh_pedidos_fecha_hora" class="pedidos_fecha_hora"><?php echo $pedidos->fecha_hora->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
		<th class="<?php echo $pedidos->id_fornecedor->HeaderCellClass() ?>"><span id="elh_pedidos_id_fornecedor" class="pedidos_id_fornecedor"><?php echo $pedidos->id_fornecedor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->id_transportadora->Visible) { // id_transportadora ?>
		<th class="<?php echo $pedidos->id_transportadora->HeaderCellClass() ?>"><span id="elh_pedidos_id_transportadora" class="pedidos_id_transportadora"><?php echo $pedidos->id_transportadora->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->id_prazos->Visible) { // id_prazos ?>
		<th class="<?php echo $pedidos->id_prazos->HeaderCellClass() ?>"><span id="elh_pedidos_id_prazos" class="pedidos_id_prazos"><?php echo $pedidos->id_prazos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->comentarios->Visible) { // comentarios ?>
		<th class="<?php echo $pedidos->comentarios->HeaderCellClass() ?>"><span id="elh_pedidos_comentarios" class="pedidos_comentarios"><?php echo $pedidos->comentarios->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->id_representante->Visible) { // id_representante ?>
		<th class="<?php echo $pedidos->id_representante->HeaderCellClass() ?>"><span id="elh_pedidos_id_representante" class="pedidos_id_representante"><?php echo $pedidos->id_representante->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->comissao_representante->Visible) { // comissao_representante ?>
		<th class="<?php echo $pedidos->comissao_representante->HeaderCellClass() ?>"><span id="elh_pedidos_comissao_representante" class="pedidos_comissao_representante"><?php echo $pedidos->comissao_representante->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
		<th class="<?php echo $pedidos->id_cliente->HeaderCellClass() ?>"><span id="elh_pedidos_id_cliente" class="pedidos_id_cliente"><?php echo $pedidos->id_cliente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pedidos->status->Visible) { // status ?>
		<th class="<?php echo $pedidos->status->HeaderCellClass() ?>"><span id="elh_pedidos_status" class="pedidos_status"><?php echo $pedidos->status->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pedidos_delete->RecCnt = 0;
$i = 0;
while (!$pedidos_delete->Recordset->EOF) {
	$pedidos_delete->RecCnt++;
	$pedidos_delete->RowCnt++;

	// Set row properties
	$pedidos->ResetAttrs();
	$pedidos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pedidos_delete->LoadRowValues($pedidos_delete->Recordset);

	// Render row
	$pedidos_delete->RenderRow();
?>
	<tr<?php echo $pedidos->RowAttributes() ?>>
<?php if ($pedidos->id_pedidos->Visible) { // id_pedidos ?>
		<td<?php echo $pedidos->id_pedidos->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_id_pedidos" class="pedidos_id_pedidos">
<span<?php echo $pedidos->id_pedidos->ViewAttributes() ?>>
<?php echo $pedidos->id_pedidos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
		<td<?php echo $pedidos->tipo_pedido->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_tipo_pedido" class="pedidos_tipo_pedido">
<span<?php echo $pedidos->tipo_pedido->ViewAttributes() ?>>
<?php echo $pedidos->tipo_pedido->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->numero->Visible) { // numero ?>
		<td<?php echo $pedidos->numero->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_numero" class="pedidos_numero">
<span<?php echo $pedidos->numero->ViewAttributes() ?>>
<?php echo $pedidos->numero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->fecha_data->Visible) { // fecha_data ?>
		<td<?php echo $pedidos->fecha_data->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_fecha_data" class="pedidos_fecha_data">
<span<?php echo $pedidos->fecha_data->ViewAttributes() ?>>
<?php echo $pedidos->fecha_data->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->fecha_hora->Visible) { // fecha_hora ?>
		<td<?php echo $pedidos->fecha_hora->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_fecha_hora" class="pedidos_fecha_hora">
<span<?php echo $pedidos->fecha_hora->ViewAttributes() ?>>
<?php echo $pedidos->fecha_hora->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
		<td<?php echo $pedidos->id_fornecedor->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_id_fornecedor" class="pedidos_id_fornecedor">
<span<?php echo $pedidos->id_fornecedor->ViewAttributes() ?>>
<?php echo $pedidos->id_fornecedor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->id_transportadora->Visible) { // id_transportadora ?>
		<td<?php echo $pedidos->id_transportadora->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_id_transportadora" class="pedidos_id_transportadora">
<span<?php echo $pedidos->id_transportadora->ViewAttributes() ?>>
<?php echo $pedidos->id_transportadora->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->id_prazos->Visible) { // id_prazos ?>
		<td<?php echo $pedidos->id_prazos->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_id_prazos" class="pedidos_id_prazos">
<span<?php echo $pedidos->id_prazos->ViewAttributes() ?>>
<?php echo $pedidos->id_prazos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->comentarios->Visible) { // comentarios ?>
		<td<?php echo $pedidos->comentarios->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_comentarios" class="pedidos_comentarios">
<span<?php echo $pedidos->comentarios->ViewAttributes() ?>>
<?php echo $pedidos->comentarios->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->id_representante->Visible) { // id_representante ?>
		<td<?php echo $pedidos->id_representante->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_id_representante" class="pedidos_id_representante">
<span<?php echo $pedidos->id_representante->ViewAttributes() ?>>
<?php echo $pedidos->id_representante->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->comissao_representante->Visible) { // comissao_representante ?>
		<td<?php echo $pedidos->comissao_representante->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_comissao_representante" class="pedidos_comissao_representante">
<span<?php echo $pedidos->comissao_representante->ViewAttributes() ?>>
<?php echo $pedidos->comissao_representante->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
		<td<?php echo $pedidos->id_cliente->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_id_cliente" class="pedidos_id_cliente">
<span<?php echo $pedidos->id_cliente->ViewAttributes() ?>>
<?php echo $pedidos->id_cliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pedidos->status->Visible) { // status ?>
		<td<?php echo $pedidos->status->CellAttributes() ?>>
<span id="el<?php echo $pedidos_delete->RowCnt ?>_pedidos_status" class="pedidos_status">
<span<?php echo $pedidos->status->ViewAttributes() ?>>
<?php echo $pedidos->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pedidos_delete->Recordset->MoveNext();
}
$pedidos_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pedidos_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpedidosdelete.Init();
</script>
<?php
$pedidos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pedidos_delete->Page_Terminate();
?>
