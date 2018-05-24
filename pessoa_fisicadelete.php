<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pessoa_fisicainfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pessoa_fisica_delete = NULL; // Initialize page object first

class cpessoa_fisica_delete extends cpessoa_fisica {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'pessoa_fisica';

	// Page object name
	var $PageObjName = 'pessoa_fisica_delete';

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

		// Table object (pessoa_fisica)
		if (!isset($GLOBALS["pessoa_fisica"]) || get_class($GLOBALS["pessoa_fisica"]) == "cpessoa_fisica") {
			$GLOBALS["pessoa_fisica"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoa_fisica"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pessoa_fisica', TRUE);

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
		$this->id_pessoa->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id_pessoa->Visible = FALSE;
		$this->nome_pessoa->SetVisibility();
		$this->sobrenome_pessoa->SetVisibility();
		$this->nascimento->SetVisibility();
		$this->telefone->SetVisibility();
		$this->_email->SetVisibility();
		$this->celular->SetVisibility();
		$this->CPF->SetVisibility();
		$this->RG->SetVisibility();
		$this->id_endereco->SetVisibility();
		$this->endereco_numero->SetVisibility();
		$this->id_empresa->SetVisibility();

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
		global $EW_EXPORT, $pessoa_fisica;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pessoa_fisica);
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
			$this->Page_Terminate("pessoa_fisicalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pessoa_fisica class, pessoa_fisicainfo.php

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
				$this->Page_Terminate("pessoa_fisicalist.php"); // Return to list
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
		$this->id_pessoa->setDbValue($row['id_pessoa']);
		$this->nome_pessoa->setDbValue($row['nome_pessoa']);
		$this->sobrenome_pessoa->setDbValue($row['sobrenome_pessoa']);
		$this->nascimento->setDbValue($row['nascimento']);
		$this->telefone->setDbValue($row['telefone']);
		$this->_email->setDbValue($row['email']);
		$this->celular->setDbValue($row['celular']);
		$this->CPF->setDbValue($row['CPF']);
		$this->RG->setDbValue($row['RG']);
		$this->id_endereco->setDbValue($row['id_endereco']);
		$this->endereco_numero->setDbValue($row['endereco_numero']);
		$this->id_empresa->setDbValue($row['id_empresa']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_pessoa'] = NULL;
		$row['nome_pessoa'] = NULL;
		$row['sobrenome_pessoa'] = NULL;
		$row['nascimento'] = NULL;
		$row['telefone'] = NULL;
		$row['email'] = NULL;
		$row['celular'] = NULL;
		$row['CPF'] = NULL;
		$row['RG'] = NULL;
		$row['id_endereco'] = NULL;
		$row['endereco_numero'] = NULL;
		$row['id_empresa'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_pessoa->DbValue = $row['id_pessoa'];
		$this->nome_pessoa->DbValue = $row['nome_pessoa'];
		$this->sobrenome_pessoa->DbValue = $row['sobrenome_pessoa'];
		$this->nascimento->DbValue = $row['nascimento'];
		$this->telefone->DbValue = $row['telefone'];
		$this->_email->DbValue = $row['email'];
		$this->celular->DbValue = $row['celular'];
		$this->CPF->DbValue = $row['CPF'];
		$this->RG->DbValue = $row['RG'];
		$this->id_endereco->DbValue = $row['id_endereco'];
		$this->endereco_numero->DbValue = $row['endereco_numero'];
		$this->id_empresa->DbValue = $row['id_empresa'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_pessoa
		// nome_pessoa
		// sobrenome_pessoa
		// nascimento
		// telefone
		// email
		// celular
		// CPF
		// RG
		// id_endereco
		// endereco_numero
		// id_empresa

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_pessoa
		$this->id_pessoa->ViewValue = $this->id_pessoa->CurrentValue;
		$this->id_pessoa->ViewCustomAttributes = "";

		// nome_pessoa
		$this->nome_pessoa->ViewValue = $this->nome_pessoa->CurrentValue;
		$this->nome_pessoa->ViewCustomAttributes = "";

		// sobrenome_pessoa
		$this->sobrenome_pessoa->ViewValue = $this->sobrenome_pessoa->CurrentValue;
		$this->sobrenome_pessoa->ViewCustomAttributes = "";

		// nascimento
		$this->nascimento->ViewValue = $this->nascimento->CurrentValue;
		$this->nascimento->ViewValue = ew_FormatDateTime($this->nascimento->ViewValue, 2);
		$this->nascimento->ViewCustomAttributes = "";

		// telefone
		$this->telefone->ViewValue = $this->telefone->CurrentValue;
		$this->telefone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// celular
		$this->celular->ViewValue = $this->celular->CurrentValue;
		$this->celular->ViewCustomAttributes = "";

		// CPF
		$this->CPF->ViewValue = $this->CPF->CurrentValue;
		$this->CPF->ViewCustomAttributes = "";

		// RG
		$this->RG->ViewValue = $this->RG->CurrentValue;
		$this->RG->ViewCustomAttributes = "";

		// id_endereco
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
		$this->id_endereco->ViewCustomAttributes = "";

		// endereco_numero
		$this->endereco_numero->ViewValue = $this->endereco_numero->CurrentValue;
		$this->endereco_numero->ViewCustomAttributes = "";

		// id_empresa
		$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
		if (strval($this->id_empresa->CurrentValue) <> "") {
			$sFilterWrk = "`id_perfil`" . ew_SearchString("=", $this->id_empresa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_perfil`, `razao_social` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		$this->id_empresa->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_empresa, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_empresa->ViewValue = $this->id_empresa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
			}
		} else {
			$this->id_empresa->ViewValue = NULL;
		}
		$this->id_empresa->ViewCustomAttributes = "";

			// id_pessoa
			$this->id_pessoa->LinkCustomAttributes = "";
			$this->id_pessoa->HrefValue = "";
			$this->id_pessoa->TooltipValue = "";

			// nome_pessoa
			$this->nome_pessoa->LinkCustomAttributes = "";
			$this->nome_pessoa->HrefValue = "";
			$this->nome_pessoa->TooltipValue = "";

			// sobrenome_pessoa
			$this->sobrenome_pessoa->LinkCustomAttributes = "";
			$this->sobrenome_pessoa->HrefValue = "";
			$this->sobrenome_pessoa->TooltipValue = "";

			// nascimento
			$this->nascimento->LinkCustomAttributes = "";
			$this->nascimento->HrefValue = "";
			$this->nascimento->TooltipValue = "";

			// telefone
			$this->telefone->LinkCustomAttributes = "";
			$this->telefone->HrefValue = "";
			$this->telefone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// celular
			$this->celular->LinkCustomAttributes = "";
			$this->celular->HrefValue = "";
			$this->celular->TooltipValue = "";

			// CPF
			$this->CPF->LinkCustomAttributes = "";
			$this->CPF->HrefValue = "";
			$this->CPF->TooltipValue = "";

			// RG
			$this->RG->LinkCustomAttributes = "";
			$this->RG->HrefValue = "";
			$this->RG->TooltipValue = "";

			// id_endereco
			$this->id_endereco->LinkCustomAttributes = "";
			$this->id_endereco->HrefValue = "";
			$this->id_endereco->TooltipValue = "";

			// endereco_numero
			$this->endereco_numero->LinkCustomAttributes = "";
			$this->endereco_numero->HrefValue = "";
			$this->endereco_numero->TooltipValue = "";

			// id_empresa
			$this->id_empresa->LinkCustomAttributes = "";
			$this->id_empresa->HrefValue = "";
			$this->id_empresa->TooltipValue = "";
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
				$sThisKey .= $row['id_pessoa'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pessoa_fisicalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($pessoa_fisica_delete)) $pessoa_fisica_delete = new cpessoa_fisica_delete();

// Page init
$pessoa_fisica_delete->Page_Init();

// Page main
$pessoa_fisica_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoa_fisica_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpessoa_fisicadelete = new ew_Form("fpessoa_fisicadelete", "delete");

// Form_CustomValidate event
fpessoa_fisicadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpessoa_fisicadelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpessoa_fisicadelete.Lists["x_id_endereco"] = {"LinkField":"x_id_endereco","Ajax":true,"AutoFill":false,"DisplayFields":["x_endereco","x_bairro","x_estado","x_cidade"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"endereco"};
fpessoa_fisicadelete.Lists["x_id_endereco"].Data = "<?php echo $pessoa_fisica_delete->id_endereco->LookupFilterQuery(FALSE, "delete") ?>";
fpessoa_fisicadelete.Lists["x_id_empresa"] = {"LinkField":"x_id_perfil","Ajax":true,"AutoFill":false,"DisplayFields":["x_razao_social","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"empresas"};
fpessoa_fisicadelete.Lists["x_id_empresa"].Data = "<?php echo $pessoa_fisica_delete->id_empresa->LookupFilterQuery(FALSE, "delete") ?>";
fpessoa_fisicadelete.AutoSuggests["x_id_empresa"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $pessoa_fisica_delete->id_empresa->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pessoa_fisica_delete->ShowPageHeader(); ?>
<?php
$pessoa_fisica_delete->ShowMessage();
?>
<form name="fpessoa_fisicadelete" id="fpessoa_fisicadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pessoa_fisica_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pessoa_fisica_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pessoa_fisica">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pessoa_fisica_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($pessoa_fisica->id_pessoa->Visible) { // id_pessoa ?>
		<th class="<?php echo $pessoa_fisica->id_pessoa->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_id_pessoa" class="pessoa_fisica_id_pessoa"><?php echo $pessoa_fisica->id_pessoa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->nome_pessoa->Visible) { // nome_pessoa ?>
		<th class="<?php echo $pessoa_fisica->nome_pessoa->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_nome_pessoa" class="pessoa_fisica_nome_pessoa"><?php echo $pessoa_fisica->nome_pessoa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
		<th class="<?php echo $pessoa_fisica->sobrenome_pessoa->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_sobrenome_pessoa" class="pessoa_fisica_sobrenome_pessoa"><?php echo $pessoa_fisica->sobrenome_pessoa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->nascimento->Visible) { // nascimento ?>
		<th class="<?php echo $pessoa_fisica->nascimento->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_nascimento" class="pessoa_fisica_nascimento"><?php echo $pessoa_fisica->nascimento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->telefone->Visible) { // telefone ?>
		<th class="<?php echo $pessoa_fisica->telefone->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_telefone" class="pessoa_fisica_telefone"><?php echo $pessoa_fisica->telefone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->_email->Visible) { // email ?>
		<th class="<?php echo $pessoa_fisica->_email->HeaderCellClass() ?>"><span id="elh_pessoa_fisica__email" class="pessoa_fisica__email"><?php echo $pessoa_fisica->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->celular->Visible) { // celular ?>
		<th class="<?php echo $pessoa_fisica->celular->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_celular" class="pessoa_fisica_celular"><?php echo $pessoa_fisica->celular->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->CPF->Visible) { // CPF ?>
		<th class="<?php echo $pessoa_fisica->CPF->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_CPF" class="pessoa_fisica_CPF"><?php echo $pessoa_fisica->CPF->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->RG->Visible) { // RG ?>
		<th class="<?php echo $pessoa_fisica->RG->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_RG" class="pessoa_fisica_RG"><?php echo $pessoa_fisica->RG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->id_endereco->Visible) { // id_endereco ?>
		<th class="<?php echo $pessoa_fisica->id_endereco->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_id_endereco" class="pessoa_fisica_id_endereco"><?php echo $pessoa_fisica->id_endereco->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->endereco_numero->Visible) { // endereco_numero ?>
		<th class="<?php echo $pessoa_fisica->endereco_numero->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_endereco_numero" class="pessoa_fisica_endereco_numero"><?php echo $pessoa_fisica->endereco_numero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pessoa_fisica->id_empresa->Visible) { // id_empresa ?>
		<th class="<?php echo $pessoa_fisica->id_empresa->HeaderCellClass() ?>"><span id="elh_pessoa_fisica_id_empresa" class="pessoa_fisica_id_empresa"><?php echo $pessoa_fisica->id_empresa->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pessoa_fisica_delete->RecCnt = 0;
$i = 0;
while (!$pessoa_fisica_delete->Recordset->EOF) {
	$pessoa_fisica_delete->RecCnt++;
	$pessoa_fisica_delete->RowCnt++;

	// Set row properties
	$pessoa_fisica->ResetAttrs();
	$pessoa_fisica->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pessoa_fisica_delete->LoadRowValues($pessoa_fisica_delete->Recordset);

	// Render row
	$pessoa_fisica_delete->RenderRow();
?>
	<tr<?php echo $pessoa_fisica->RowAttributes() ?>>
<?php if ($pessoa_fisica->id_pessoa->Visible) { // id_pessoa ?>
		<td<?php echo $pessoa_fisica->id_pessoa->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_id_pessoa" class="pessoa_fisica_id_pessoa">
<span<?php echo $pessoa_fisica->id_pessoa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->id_pessoa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->nome_pessoa->Visible) { // nome_pessoa ?>
		<td<?php echo $pessoa_fisica->nome_pessoa->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_nome_pessoa" class="pessoa_fisica_nome_pessoa">
<span<?php echo $pessoa_fisica->nome_pessoa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->nome_pessoa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->sobrenome_pessoa->Visible) { // sobrenome_pessoa ?>
		<td<?php echo $pessoa_fisica->sobrenome_pessoa->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_sobrenome_pessoa" class="pessoa_fisica_sobrenome_pessoa">
<span<?php echo $pessoa_fisica->sobrenome_pessoa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->sobrenome_pessoa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->nascimento->Visible) { // nascimento ?>
		<td<?php echo $pessoa_fisica->nascimento->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_nascimento" class="pessoa_fisica_nascimento">
<span<?php echo $pessoa_fisica->nascimento->ViewAttributes() ?>>
<?php echo $pessoa_fisica->nascimento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->telefone->Visible) { // telefone ?>
		<td<?php echo $pessoa_fisica->telefone->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_telefone" class="pessoa_fisica_telefone">
<span<?php echo $pessoa_fisica->telefone->ViewAttributes() ?>>
<?php echo $pessoa_fisica->telefone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->_email->Visible) { // email ?>
		<td<?php echo $pessoa_fisica->_email->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica__email" class="pessoa_fisica__email">
<span<?php echo $pessoa_fisica->_email->ViewAttributes() ?>>
<?php echo $pessoa_fisica->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->celular->Visible) { // celular ?>
		<td<?php echo $pessoa_fisica->celular->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_celular" class="pessoa_fisica_celular">
<span<?php echo $pessoa_fisica->celular->ViewAttributes() ?>>
<?php echo $pessoa_fisica->celular->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->CPF->Visible) { // CPF ?>
		<td<?php echo $pessoa_fisica->CPF->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_CPF" class="pessoa_fisica_CPF">
<span<?php echo $pessoa_fisica->CPF->ViewAttributes() ?>>
<?php echo $pessoa_fisica->CPF->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->RG->Visible) { // RG ?>
		<td<?php echo $pessoa_fisica->RG->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_RG" class="pessoa_fisica_RG">
<span<?php echo $pessoa_fisica->RG->ViewAttributes() ?>>
<?php echo $pessoa_fisica->RG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->id_endereco->Visible) { // id_endereco ?>
		<td<?php echo $pessoa_fisica->id_endereco->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_id_endereco" class="pessoa_fisica_id_endereco">
<span<?php echo $pessoa_fisica->id_endereco->ViewAttributes() ?>>
<?php echo $pessoa_fisica->id_endereco->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->endereco_numero->Visible) { // endereco_numero ?>
		<td<?php echo $pessoa_fisica->endereco_numero->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_endereco_numero" class="pessoa_fisica_endereco_numero">
<span<?php echo $pessoa_fisica->endereco_numero->ViewAttributes() ?>>
<?php echo $pessoa_fisica->endereco_numero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pessoa_fisica->id_empresa->Visible) { // id_empresa ?>
		<td<?php echo $pessoa_fisica->id_empresa->CellAttributes() ?>>
<span id="el<?php echo $pessoa_fisica_delete->RowCnt ?>_pessoa_fisica_id_empresa" class="pessoa_fisica_id_empresa">
<span<?php echo $pessoa_fisica->id_empresa->ViewAttributes() ?>>
<?php echo $pessoa_fisica->id_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pessoa_fisica_delete->Recordset->MoveNext();
}
$pessoa_fisica_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pessoa_fisica_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpessoa_fisicadelete.Init();
</script>
<?php
$pessoa_fisica_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pessoa_fisica_delete->Page_Terminate();
?>
