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

$produtos_delete = NULL; // Initialize page object first

class cprodutos_delete extends cprodutos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'produtos';

	// Page object name
	var $PageObjName = 'produtos_delete';

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

		// Table object (produtos)
		if (!isset($GLOBALS["produtos"]) || get_class($GLOBALS["produtos"]) == "cprodutos") {
			$GLOBALS["produtos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["produtos"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_produto->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_produto->Visible = FALSE;
		$this->codigo_produto->SetVisibility();
		$this->nome_produto->SetVisibility();
		$this->modelo_produto->SetVisibility();
		$this->id_departamento_produto->SetVisibility();
		$this->id_marca_produto->SetVisibility();
		$this->preco_produto->SetVisibility();

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
			$this->Page_Terminate("produtoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in produtos class, produtosinfo.php

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
				$this->Page_Terminate("produtoslist.php"); // Return to list
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

		// id_departamento_produto
		if (strval($this->id_departamento_produto->CurrentValue) <> "") {
			$sFilterWrk = "`id_categoria`" . ew_SearchString("=", $this->id_departamento_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_categoria`, `categoria` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
		$sWhereWrk = "";
		$this->id_departamento_produto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_departamento_produto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_departamento_produto->ViewValue = $this->id_departamento_produto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_departamento_produto->ViewValue = $this->id_departamento_produto->CurrentValue;
			}
		} else {
			$this->id_departamento_produto->ViewValue = NULL;
		}
		$this->id_departamento_produto->ViewCustomAttributes = "";

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
		$this->preco_produto->ViewValue = ew_FormatCurrency($this->preco_produto->ViewValue, 2, -1, -1, -1);
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

			// id_departamento_produto
			$this->id_departamento_produto->LinkCustomAttributes = "";
			$this->id_departamento_produto->HrefValue = "";
			$this->id_departamento_produto->TooltipValue = "";

			// id_marca_produto
			$this->id_marca_produto->LinkCustomAttributes = "";
			$this->id_marca_produto->HrefValue = "";
			$this->id_marca_produto->TooltipValue = "";

			// preco_produto
			$this->preco_produto->LinkCustomAttributes = "";
			$this->preco_produto->HrefValue = "";
			$this->preco_produto->TooltipValue = "";
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
				$sThisKey .= $row['id_produto'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produtoslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($produtos_delete)) $produtos_delete = new cprodutos_delete();

// Page init
$produtos_delete->Page_Init();

// Page main
$produtos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produtos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fprodutosdelete = new ew_Form("fprodutosdelete", "delete");

// Form_CustomValidate event
fprodutosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fprodutosdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fprodutosdelete.Lists["x_id_departamento_produto"] = {"LinkField":"x_id_categoria","Ajax":true,"AutoFill":false,"DisplayFields":["x_categoria","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categoria"};
fprodutosdelete.Lists["x_id_departamento_produto"].Data = "<?php echo $produtos_delete->id_departamento_produto->LookupFilterQuery(FALSE, "delete") ?>";
fprodutosdelete.Lists["x_id_marca_produto"] = {"LinkField":"x_id_marca","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_marca","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
fprodutosdelete.Lists["x_id_marca_produto"].Data = "<?php echo $produtos_delete->id_marca_produto->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $produtos_delete->ShowPageHeader(); ?>
<?php
$produtos_delete->ShowMessage();
?>
<form name="fprodutosdelete" id="fprodutosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produtos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produtos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produtos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($produtos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($produtos->id_produto->Visible) { // id_produto ?>
		<th class="<?php echo $produtos->id_produto->HeaderCellClass() ?>"><span id="elh_produtos_id_produto" class="produtos_id_produto"><?php echo $produtos->id_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
		<th class="<?php echo $produtos->codigo_produto->HeaderCellClass() ?>"><span id="elh_produtos_codigo_produto" class="produtos_codigo_produto"><?php echo $produtos->codigo_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
		<th class="<?php echo $produtos->nome_produto->HeaderCellClass() ?>"><span id="elh_produtos_nome_produto" class="produtos_nome_produto"><?php echo $produtos->nome_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
		<th class="<?php echo $produtos->modelo_produto->HeaderCellClass() ?>"><span id="elh_produtos_modelo_produto" class="produtos_modelo_produto"><?php echo $produtos->modelo_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produtos->id_departamento_produto->Visible) { // id_departamento_produto ?>
		<th class="<?php echo $produtos->id_departamento_produto->HeaderCellClass() ?>"><span id="elh_produtos_id_departamento_produto" class="produtos_id_departamento_produto"><?php echo $produtos->id_departamento_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
		<th class="<?php echo $produtos->id_marca_produto->HeaderCellClass() ?>"><span id="elh_produtos_id_marca_produto" class="produtos_id_marca_produto"><?php echo $produtos->id_marca_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
		<th class="<?php echo $produtos->preco_produto->HeaderCellClass() ?>"><span id="elh_produtos_preco_produto" class="produtos_preco_produto"><?php echo $produtos->preco_produto->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$produtos_delete->RecCnt = 0;
$i = 0;
while (!$produtos_delete->Recordset->EOF) {
	$produtos_delete->RecCnt++;
	$produtos_delete->RowCnt++;

	// Set row properties
	$produtos->ResetAttrs();
	$produtos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$produtos_delete->LoadRowValues($produtos_delete->Recordset);

	// Render row
	$produtos_delete->RenderRow();
?>
	<tr<?php echo $produtos->RowAttributes() ?>>
<?php if ($produtos->id_produto->Visible) { // id_produto ?>
		<td<?php echo $produtos->id_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_id_produto" class="produtos_id_produto">
<span<?php echo $produtos->id_produto->ViewAttributes() ?>>
<?php echo $produtos->id_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
		<td<?php echo $produtos->codigo_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_codigo_produto" class="produtos_codigo_produto">
<span<?php echo $produtos->codigo_produto->ViewAttributes() ?>>
<?php echo $produtos->codigo_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
		<td<?php echo $produtos->nome_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_nome_produto" class="produtos_nome_produto">
<span<?php echo $produtos->nome_produto->ViewAttributes() ?>>
<?php echo $produtos->nome_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
		<td<?php echo $produtos->modelo_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_modelo_produto" class="produtos_modelo_produto">
<span<?php echo $produtos->modelo_produto->ViewAttributes() ?>>
<?php echo $produtos->modelo_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produtos->id_departamento_produto->Visible) { // id_departamento_produto ?>
		<td<?php echo $produtos->id_departamento_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_id_departamento_produto" class="produtos_id_departamento_produto">
<span<?php echo $produtos->id_departamento_produto->ViewAttributes() ?>>
<?php echo $produtos->id_departamento_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
		<td<?php echo $produtos->id_marca_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_id_marca_produto" class="produtos_id_marca_produto">
<span<?php echo $produtos->id_marca_produto->ViewAttributes() ?>>
<?php echo $produtos->id_marca_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
		<td<?php echo $produtos->preco_produto->CellAttributes() ?>>
<span id="el<?php echo $produtos_delete->RowCnt ?>_produtos_preco_produto" class="produtos_preco_produto">
<span<?php echo $produtos->preco_produto->ViewAttributes() ?>>
<?php echo $produtos->preco_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$produtos_delete->Recordset->MoveNext();
}
$produtos_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $produtos_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fprodutosdelete.Init();
</script>
<?php
$produtos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produtos_delete->Page_Terminate();
?>
