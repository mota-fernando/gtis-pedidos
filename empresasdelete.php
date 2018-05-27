<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "empresasinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$empresas_delete = NULL; // Initialize page object first

class cempresas_delete extends cempresas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_delete';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
			$this->Page_Terminate("empresaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in empresas class, empresasinfo.php

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
				$this->Page_Terminate("empresaslist.php"); // Return to list
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
				$sThisKey .= $row['id_perfil'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("empresaslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($empresas_delete)) $empresas_delete = new cempresas_delete();

// Page init
$empresas_delete->Page_Init();

// Page main
$empresas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fempresasdelete = new ew_Form("fempresasdelete", "delete");

// Form_CustomValidate event
fempresasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fempresasdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fempresasdelete.Lists["x_direcao"] = {"LinkField":"x_nome_pessoa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_pessoa","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pessoa_fisica"};
fempresasdelete.Lists["x_direcao"].Data = "<?php echo $empresas_delete->direcao->LookupFilterQuery(FALSE, "delete") ?>";
fempresasdelete.AutoSuggests["x_direcao"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $empresas_delete->direcao->LookupFilterQuery(TRUE, "delete"))) ?>;
fempresasdelete.Lists["x_id_endereco"] = {"LinkField":"x_id_endereco","Ajax":true,"AutoFill":false,"DisplayFields":["x_endereco","x_bairro","x_estado","x_cidade"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"endereco"};
fempresasdelete.Lists["x_id_endereco"].Data = "<?php echo $empresas_delete->id_endereco->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $empresas_delete->ShowPageHeader(); ?>
<?php
$empresas_delete->ShowMessage();
?>
<form name="fempresasdelete" id="fempresasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($empresas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
		<th class="<?php echo $empresas->id_perfil->HeaderCellClass() ?>"><span id="elh_empresas_id_perfil" class="empresas_id_perfil"><?php echo $empresas->id_perfil->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
		<th class="<?php echo $empresas->razao_social->HeaderCellClass() ?>"><span id="elh_empresas_razao_social" class="empresas_razao_social"><?php echo $empresas->razao_social->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
		<th class="<?php echo $empresas->proprietario->HeaderCellClass() ?>"><span id="elh_empresas_proprietario" class="empresas_proprietario"><?php echo $empresas->proprietario->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
		<th class="<?php echo $empresas->telefone->HeaderCellClass() ?>"><span id="elh_empresas_telefone" class="empresas_telefone"><?php echo $empresas->telefone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
		<th class="<?php echo $empresas->direcao->HeaderCellClass() ?>"><span id="elh_empresas_direcao" class="empresas_direcao"><?php echo $empresas->direcao->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
		<th class="<?php echo $empresas->_email->HeaderCellClass() ?>"><span id="elh_empresas__email" class="empresas__email"><?php echo $empresas->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
		<th class="<?php echo $empresas->id_endereco->HeaderCellClass() ?>"><span id="elh_empresas_id_endereco" class="empresas_id_endereco"><?php echo $empresas->id_endereco->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
		<th class="<?php echo $empresas->endereco_numero->HeaderCellClass() ?>"><span id="elh_empresas_endereco_numero" class="empresas_endereco_numero"><?php echo $empresas->endereco_numero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
		<th class="<?php echo $empresas->nome_fantasia->HeaderCellClass() ?>"><span id="elh_empresas_nome_fantasia" class="empresas_nome_fantasia"><?php echo $empresas->nome_fantasia->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$empresas_delete->RecCnt = 0;
$i = 0;
while (!$empresas_delete->Recordset->EOF) {
	$empresas_delete->RecCnt++;
	$empresas_delete->RowCnt++;

	// Set row properties
	$empresas->ResetAttrs();
	$empresas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$empresas_delete->LoadRowValues($empresas_delete->Recordset);

	// Render row
	$empresas_delete->RenderRow();
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php if ($empresas->id_perfil->Visible) { // id_perfil ?>
		<td<?php echo $empresas->id_perfil->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_id_perfil" class="empresas_id_perfil">
<span<?php echo $empresas->id_perfil->ViewAttributes() ?>>
<?php echo $empresas->id_perfil->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->razao_social->Visible) { // razao_social ?>
		<td<?php echo $empresas->razao_social->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_razao_social" class="empresas_razao_social">
<span<?php echo $empresas->razao_social->ViewAttributes() ?>>
<?php echo $empresas->razao_social->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->proprietario->Visible) { // proprietario ?>
		<td<?php echo $empresas->proprietario->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_proprietario" class="empresas_proprietario">
<span<?php echo $empresas->proprietario->ViewAttributes() ?>>
<?php echo $empresas->proprietario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->telefone->Visible) { // telefone ?>
		<td<?php echo $empresas->telefone->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_telefone" class="empresas_telefone">
<span<?php echo $empresas->telefone->ViewAttributes() ?>>
<?php echo $empresas->telefone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->direcao->Visible) { // direcao ?>
		<td<?php echo $empresas->direcao->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_direcao" class="empresas_direcao">
<span<?php echo $empresas->direcao->ViewAttributes() ?>>
<?php echo $empresas->direcao->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
		<td<?php echo $empresas->_email->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas__email" class="empresas__email">
<span<?php echo $empresas->_email->ViewAttributes() ?>>
<?php echo $empresas->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->id_endereco->Visible) { // id_endereco ?>
		<td<?php echo $empresas->id_endereco->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_id_endereco" class="empresas_id_endereco">
<span<?php echo $empresas->id_endereco->ViewAttributes() ?>>
<?php echo $empresas->id_endereco->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->endereco_numero->Visible) { // endereco_numero ?>
		<td<?php echo $empresas->endereco_numero->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_endereco_numero" class="empresas_endereco_numero">
<span<?php echo $empresas->endereco_numero->ViewAttributes() ?>>
<?php echo $empresas->endereco_numero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->nome_fantasia->Visible) { // nome_fantasia ?>
		<td<?php echo $empresas->nome_fantasia->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_nome_fantasia" class="empresas_nome_fantasia">
<span<?php echo $empresas->nome_fantasia->ViewAttributes() ?>>
<?php echo $empresas->nome_fantasia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$empresas_delete->Recordset->MoveNext();
}
$empresas_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $empresas_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fempresasdelete.Init();
</script>
<?php
$empresas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_delete->Page_Terminate();
?>
