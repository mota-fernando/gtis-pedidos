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

$detalhe_pedido_delete = NULL; // Initialize page object first

class cdetalhe_pedido_delete extends cdetalhe_pedido {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'detalhe_pedido';

	// Page object name
	var $PageObjName = 'detalhe_pedido_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Set up master/detail parameters
		$this->SetupMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("detalhe_pedidolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in detalhe_pedido class, detalhe_pedidoinfo.php

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
				$this->Page_Terminate("detalhe_pedidolist.php"); // Return to list
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
		$this->id_detalhe->setDbValue($row['id_detalhe']);
		$this->numero_pedido->setDbValue($row['numero_pedido']);
		$this->id_produto->setDbValue($row['id_produto']);
		$this->desconto->setDbValue($row['desconto']);
		$this->preco->setDbValue($row['preco']);
		$this->quantidade->setDbValue($row['quantidade']);
		$this->subtotal->setDbValue($row['subtotal']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_detalhe'] = NULL;
		$row['numero_pedido'] = NULL;
		$row['id_produto'] = NULL;
		$row['desconto'] = NULL;
		$row['preco'] = NULL;
		$row['quantidade'] = NULL;
		$row['subtotal'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_detalhe->DbValue = $row['id_detalhe'];
		$this->numero_pedido->DbValue = $row['numero_pedido'];
		$this->id_produto->DbValue = $row['id_produto'];
		$this->desconto->DbValue = $row['desconto'];
		$this->preco->DbValue = $row['preco'];
		$this->quantidade->DbValue = $row['quantidade'];
		$this->subtotal->DbValue = $row['subtotal'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->subtotal->FormValue == $this->subtotal->CurrentValue && is_numeric(ew_StrToFloat($this->subtotal->CurrentValue)))
			$this->subtotal->CurrentValue = ew_StrToFloat($this->subtotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_detalhe
		// numero_pedido
		// id_produto
		// desconto
		// preco
		// quantidade
		// subtotal

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_detalhe
		$this->id_detalhe->ViewValue = $this->id_detalhe->CurrentValue;
		$this->id_detalhe->ViewCustomAttributes = "";

		// numero_pedido
		$this->numero_pedido->ViewValue = $this->numero_pedido->CurrentValue;
		$this->numero_pedido->ViewCustomAttributes = "";

		// id_produto
		if (strval($this->id_produto->CurrentValue) <> "") {
			$sFilterWrk = "`id_produto`" . ew_SearchString("=", $this->id_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_produto`, `nome_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
		$sWhereWrk = "";
		$this->id_produto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_produto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_produto->ViewValue = $this->id_produto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_produto->ViewValue = $this->id_produto->CurrentValue;
			}
		} else {
			$this->id_produto->ViewValue = NULL;
		}
		$this->id_produto->ViewCustomAttributes = "";

		// desconto
		if (strval($this->desconto->CurrentValue) <> "") {
			$sFilterWrk = "`porcentagem`" . ew_SearchString("=", $this->desconto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `porcentagem`, `porcentagem` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `desconto`";
		$sWhereWrk = "";
		$this->desconto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->desconto, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->desconto->ViewValue = $this->desconto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->desconto->ViewValue = $this->desconto->CurrentValue;
			}
		} else {
			$this->desconto->ViewValue = NULL;
		}
		$this->desconto->ViewCustomAttributes = "";

		// preco
		if (strval($this->preco->CurrentValue) <> "") {
			$sFilterWrk = "`preco_produto`" . ew_SearchString("=", $this->preco->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `preco_produto`, `preco_produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
		$sWhereWrk = "";
		$this->preco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->preco, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_FormatCurrency($rswrk->fields('DispFld'), 2, -1, -1, -1);
				$this->preco->ViewValue = $this->preco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->preco->ViewValue = $this->preco->CurrentValue;
			}
		} else {
			$this->preco->ViewValue = NULL;
		}
		$this->preco->ViewCustomAttributes = "";

		// quantidade
		$this->quantidade->ViewValue = $this->quantidade->CurrentValue;
		$this->quantidade->ViewCustomAttributes = "";

		// subtotal
		$this->subtotal->ViewValue = $this->subtotal->CurrentValue;
		$this->subtotal->ViewCustomAttributes = "";

			// id_detalhe
			$this->id_detalhe->LinkCustomAttributes = "";
			$this->id_detalhe->HrefValue = "";
			$this->id_detalhe->TooltipValue = "";

			// numero_pedido
			$this->numero_pedido->LinkCustomAttributes = "";
			$this->numero_pedido->HrefValue = "";
			$this->numero_pedido->TooltipValue = "";

			// id_produto
			$this->id_produto->LinkCustomAttributes = "";
			$this->id_produto->HrefValue = "";
			$this->id_produto->TooltipValue = "";

			// desconto
			$this->desconto->LinkCustomAttributes = "";
			$this->desconto->HrefValue = "";
			$this->desconto->TooltipValue = "";

			// preco
			$this->preco->LinkCustomAttributes = "";
			$this->preco->HrefValue = "";
			$this->preco->TooltipValue = "";

			// quantidade
			$this->quantidade->LinkCustomAttributes = "";
			$this->quantidade->HrefValue = "";
			$this->quantidade->TooltipValue = "";

			// subtotal
			$this->subtotal->LinkCustomAttributes = "";
			$this->subtotal->HrefValue = "";
			$this->subtotal->TooltipValue = "";
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
				$sThisKey .= $row['id_detalhe'];
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
			if ($sMasterTblVar == "pedidos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_numero"] <> "") {
					$GLOBALS["pedidos"]->numero->setQueryStringValue($_GET["fk_numero"]);
					$this->numero_pedido->setQueryStringValue($GLOBALS["pedidos"]->numero->QueryStringValue);
					$this->numero_pedido->setSessionValue($this->numero_pedido->QueryStringValue);
					if (!is_numeric($GLOBALS["pedidos"]->numero->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "pedidos") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_numero"] <> "") {
					$GLOBALS["pedidos"]->numero->setFormValue($_POST["fk_numero"]);
					$this->numero_pedido->setFormValue($GLOBALS["pedidos"]->numero->FormValue);
					$this->numero_pedido->setSessionValue($this->numero_pedido->FormValue);
					if (!is_numeric($GLOBALS["pedidos"]->numero->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			if (!$this->IsAddOrEdit()) {
				$this->StartRec = 1;
				$this->setStartRecordNumber($this->StartRec);
			}

			// Clear previous master key from Session
			if ($sMasterTblVar <> "pedidos") {
				if ($this->numero_pedido->CurrentValue == "") $this->numero_pedido->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("detalhe_pedidolist.php"), "", $this->TableVar, TRUE);
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
if (!isset($detalhe_pedido_delete)) $detalhe_pedido_delete = new cdetalhe_pedido_delete();

// Page init
$detalhe_pedido_delete->Page_Init();

// Page main
$detalhe_pedido_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detalhe_pedido_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdetalhe_pedidodelete = new ew_Form("fdetalhe_pedidodelete", "delete");

// Form_CustomValidate event
fdetalhe_pedidodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdetalhe_pedidodelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdetalhe_pedidodelete.Lists["x_id_produto"] = {"LinkField":"x_id_produto","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_produto","","",""],"ParentFields":[],"ChildFields":["x_preco"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"produtos"};
fdetalhe_pedidodelete.Lists["x_id_produto"].Data = "<?php echo $detalhe_pedido_delete->id_produto->LookupFilterQuery(FALSE, "delete") ?>";
fdetalhe_pedidodelete.Lists["x_desconto"] = {"LinkField":"x_porcentagem","Ajax":true,"AutoFill":false,"DisplayFields":["x_porcentagem","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"desconto"};
fdetalhe_pedidodelete.Lists["x_desconto"].Data = "<?php echo $detalhe_pedido_delete->desconto->LookupFilterQuery(FALSE, "delete") ?>";
fdetalhe_pedidodelete.Lists["x_preco"] = {"LinkField":"x_preco_produto","Ajax":true,"AutoFill":false,"DisplayFields":["x_preco_produto","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"produtos"};
fdetalhe_pedidodelete.Lists["x_preco"].Data = "<?php echo $detalhe_pedido_delete->preco->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $detalhe_pedido_delete->ShowPageHeader(); ?>
<?php
$detalhe_pedido_delete->ShowMessage();
?>
<form name="fdetalhe_pedidodelete" id="fdetalhe_pedidodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detalhe_pedido_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detalhe_pedido_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detalhe_pedido">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($detalhe_pedido_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
		<th class="<?php echo $detalhe_pedido->id_detalhe->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_id_detalhe" class="detalhe_pedido_id_detalhe"><?php echo $detalhe_pedido->id_detalhe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
		<th class="<?php echo $detalhe_pedido->numero_pedido->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_numero_pedido" class="detalhe_pedido_numero_pedido"><?php echo $detalhe_pedido->numero_pedido->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
		<th class="<?php echo $detalhe_pedido->id_produto->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_id_produto" class="detalhe_pedido_id_produto"><?php echo $detalhe_pedido->id_produto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
		<th class="<?php echo $detalhe_pedido->desconto->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_desconto" class="detalhe_pedido_desconto"><?php echo $detalhe_pedido->desconto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
		<th class="<?php echo $detalhe_pedido->preco->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_preco" class="detalhe_pedido_preco"><?php echo $detalhe_pedido->preco->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
		<th class="<?php echo $detalhe_pedido->quantidade->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_quantidade" class="detalhe_pedido_quantidade"><?php echo $detalhe_pedido->quantidade->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
		<th class="<?php echo $detalhe_pedido->subtotal->HeaderCellClass() ?>"><span id="elh_detalhe_pedido_subtotal" class="detalhe_pedido_subtotal"><?php echo $detalhe_pedido->subtotal->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$detalhe_pedido_delete->RecCnt = 0;
$i = 0;
while (!$detalhe_pedido_delete->Recordset->EOF) {
	$detalhe_pedido_delete->RecCnt++;
	$detalhe_pedido_delete->RowCnt++;

	// Set row properties
	$detalhe_pedido->ResetAttrs();
	$detalhe_pedido->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$detalhe_pedido_delete->LoadRowValues($detalhe_pedido_delete->Recordset);

	// Render row
	$detalhe_pedido_delete->RenderRow();
?>
	<tr<?php echo $detalhe_pedido->RowAttributes() ?>>
<?php if ($detalhe_pedido->id_detalhe->Visible) { // id_detalhe ?>
		<td<?php echo $detalhe_pedido->id_detalhe->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_id_detalhe" class="detalhe_pedido_id_detalhe">
<span<?php echo $detalhe_pedido->id_detalhe->ViewAttributes() ?>>
<?php echo $detalhe_pedido->id_detalhe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->numero_pedido->Visible) { // numero_pedido ?>
		<td<?php echo $detalhe_pedido->numero_pedido->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_numero_pedido" class="detalhe_pedido_numero_pedido">
<span<?php echo $detalhe_pedido->numero_pedido->ViewAttributes() ?>>
<?php echo $detalhe_pedido->numero_pedido->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->id_produto->Visible) { // id_produto ?>
		<td<?php echo $detalhe_pedido->id_produto->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_id_produto" class="detalhe_pedido_id_produto">
<span<?php echo $detalhe_pedido->id_produto->ViewAttributes() ?>>
<?php echo $detalhe_pedido->id_produto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->desconto->Visible) { // desconto ?>
		<td<?php echo $detalhe_pedido->desconto->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_desconto" class="detalhe_pedido_desconto">
<span<?php echo $detalhe_pedido->desconto->ViewAttributes() ?>>
<?php echo $detalhe_pedido->desconto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->preco->Visible) { // preco ?>
		<td<?php echo $detalhe_pedido->preco->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_preco" class="detalhe_pedido_preco">
<span<?php echo $detalhe_pedido->preco->ViewAttributes() ?>>
<?php echo $detalhe_pedido->preco->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->quantidade->Visible) { // quantidade ?>
		<td<?php echo $detalhe_pedido->quantidade->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_quantidade" class="detalhe_pedido_quantidade">
<span<?php echo $detalhe_pedido->quantidade->ViewAttributes() ?>>
<?php echo $detalhe_pedido->quantidade->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detalhe_pedido->subtotal->Visible) { // subtotal ?>
		<td<?php echo $detalhe_pedido->subtotal->CellAttributes() ?>>
<span id="el<?php echo $detalhe_pedido_delete->RowCnt ?>_detalhe_pedido_subtotal" class="detalhe_pedido_subtotal">
<span<?php echo $detalhe_pedido->subtotal->ViewAttributes() ?>>
<?php echo $detalhe_pedido->subtotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$detalhe_pedido_delete->Recordset->MoveNext();
}
$detalhe_pedido_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $detalhe_pedido_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdetalhe_pedidodelete.Init();
</script>
<?php
$detalhe_pedido_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$detalhe_pedido_delete->Page_Terminate();
?>
