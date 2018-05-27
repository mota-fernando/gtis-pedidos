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

$produtos_addopt = NULL; // Initialize page object first

class cprodutos_addopt extends cprodutos {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'produtos';

	// Page object name
	var $PageObjName = 'produtos_addopt';

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
			define("EW_PAGE_ID", 'addopt', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->codigo_produto->SetVisibility();
		$this->nome_produto->SetVisibility();
		$this->modelo_produto->SetVisibility();
		$this->id_departamento_produto->SetVisibility();
		$this->id_marca_produto->SetVisibility();
		$this->status_produto->SetVisibility();
		$this->unidade_medida_produto->SetVisibility();
		$this->unidades->SetVisibility();
		$this->peso_produto->SetVisibility();
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used

		$this->LoadRowValues(); // Load default values

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_id_produto"] = $this->id_produto->DbValue;
					$row["x_codigo_produto"] = $this->codigo_produto->DbValue;
					$row["x_nome_produto"] = ew_ConvertToUtf8($this->nome_produto->DbValue);
					$row["x_modelo_produto"] = ew_ConvertToUtf8($this->modelo_produto->DbValue);
					$row["x_id_departamento_produto"] = $this->id_departamento_produto->DbValue;
					$row["x_id_marca_produto"] = $this->id_marca_produto->DbValue;
					$row["x_status_produto"] = $this->status_produto->DbValue;
					$row["x_unidade_medida_produto"] = ew_ConvertToUtf8($this->unidade_medida_produto->DbValue);
					$row["x_unidades"] = $this->unidades->DbValue;
					$row["x_peso_produto"] = ew_ConvertToUtf8($this->peso_produto->DbValue);
					$row["x_data_adicionado"] = $this->data_adicionado->DbValue;
					$row["x_hora_adicionado"] = $this->hora_adicionado->DbValue;
					$row["x_preco_produto"] = $this->preco_produto->DbValue;
					$row["x_descricao"] = ew_ConvertToUtf8($this->descricao->DbValue);
					$row["x_ipi"] = $this->ipi->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					ew_Header(FALSE, "utf-8", TRUE);
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_produto->CurrentValue = NULL;
		$this->id_produto->OldValue = $this->id_produto->CurrentValue;
		$this->codigo_produto->CurrentValue = NULL;
		$this->codigo_produto->OldValue = $this->codigo_produto->CurrentValue;
		$this->nome_produto->CurrentValue = NULL;
		$this->nome_produto->OldValue = $this->nome_produto->CurrentValue;
		$this->modelo_produto->CurrentValue = NULL;
		$this->modelo_produto->OldValue = $this->modelo_produto->CurrentValue;
		$this->id_departamento_produto->CurrentValue = NULL;
		$this->id_departamento_produto->OldValue = $this->id_departamento_produto->CurrentValue;
		$this->id_marca_produto->CurrentValue = NULL;
		$this->id_marca_produto->OldValue = $this->id_marca_produto->CurrentValue;
		$this->status_produto->CurrentValue = 1;
		$this->unidade_medida_produto->CurrentValue = NULL;
		$this->unidade_medida_produto->OldValue = $this->unidade_medida_produto->CurrentValue;
		$this->unidades->CurrentValue = 1;
		$this->peso_produto->CurrentValue = NULL;
		$this->peso_produto->OldValue = $this->peso_produto->CurrentValue;
		$this->data_adicionado->CurrentValue = NULL;
		$this->data_adicionado->OldValue = $this->data_adicionado->CurrentValue;
		$this->hora_adicionado->CurrentValue = NULL;
		$this->hora_adicionado->OldValue = $this->hora_adicionado->CurrentValue;
		$this->preco_produto->CurrentValue = NULL;
		$this->preco_produto->OldValue = $this->preco_produto->CurrentValue;
		$this->descricao->CurrentValue = NULL;
		$this->descricao->OldValue = $this->descricao->CurrentValue;
		$this->ipi->CurrentValue = NULL;
		$this->ipi->OldValue = $this->ipi->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codigo_produto->FldIsDetailKey) {
			$this->codigo_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_codigo_produto")));
		}
		if (!$this->nome_produto->FldIsDetailKey) {
			$this->nome_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nome_produto")));
		}
		if (!$this->modelo_produto->FldIsDetailKey) {
			$this->modelo_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_modelo_produto")));
		}
		if (!$this->id_departamento_produto->FldIsDetailKey) {
			$this->id_departamento_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_departamento_produto")));
		}
		if (!$this->id_marca_produto->FldIsDetailKey) {
			$this->id_marca_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_marca_produto")));
		}
		if (!$this->status_produto->FldIsDetailKey) {
			$this->status_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_status_produto")));
		}
		if (!$this->unidade_medida_produto->FldIsDetailKey) {
			$this->unidade_medida_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_unidade_medida_produto")));
		}
		if (!$this->unidades->FldIsDetailKey) {
			$this->unidades->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_unidades")));
		}
		if (!$this->peso_produto->FldIsDetailKey) {
			$this->peso_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_peso_produto")));
		}
		if (!$this->preco_produto->FldIsDetailKey) {
			$this->preco_produto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_preco_produto")));
		}
		if (!$this->descricao->FldIsDetailKey) {
			$this->descricao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_descricao")));
		}
		if (!$this->ipi->FldIsDetailKey) {
			$this->ipi->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ipi")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->codigo_produto->CurrentValue = ew_ConvertToUtf8($this->codigo_produto->FormValue);
		$this->nome_produto->CurrentValue = ew_ConvertToUtf8($this->nome_produto->FormValue);
		$this->modelo_produto->CurrentValue = ew_ConvertToUtf8($this->modelo_produto->FormValue);
		$this->id_departamento_produto->CurrentValue = ew_ConvertToUtf8($this->id_departamento_produto->FormValue);
		$this->id_marca_produto->CurrentValue = ew_ConvertToUtf8($this->id_marca_produto->FormValue);
		$this->status_produto->CurrentValue = ew_ConvertToUtf8($this->status_produto->FormValue);
		$this->unidade_medida_produto->CurrentValue = ew_ConvertToUtf8($this->unidade_medida_produto->FormValue);
		$this->unidades->CurrentValue = ew_ConvertToUtf8($this->unidades->FormValue);
		$this->peso_produto->CurrentValue = ew_ConvertToUtf8($this->peso_produto->FormValue);
		$this->preco_produto->CurrentValue = ew_ConvertToUtf8($this->preco_produto->FormValue);
		$this->descricao->CurrentValue = ew_ConvertToUtf8($this->descricao->FormValue);
		$this->ipi->CurrentValue = ew_ConvertToUtf8($this->ipi->FormValue);
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id_produto'] = $this->id_produto->CurrentValue;
		$row['codigo_produto'] = $this->codigo_produto->CurrentValue;
		$row['nome_produto'] = $this->nome_produto->CurrentValue;
		$row['modelo_produto'] = $this->modelo_produto->CurrentValue;
		$row['id_departamento_produto'] = $this->id_departamento_produto->CurrentValue;
		$row['id_marca_produto'] = $this->id_marca_produto->CurrentValue;
		$row['status_produto'] = $this->status_produto->CurrentValue;
		$row['unidade_medida_produto'] = $this->unidade_medida_produto->CurrentValue;
		$row['unidades'] = $this->unidades->CurrentValue;
		$row['peso_produto'] = $this->peso_produto->CurrentValue;
		$row['data_adicionado'] = $this->data_adicionado->CurrentValue;
		$row['hora_adicionado'] = $this->hora_adicionado->CurrentValue;
		$row['preco_produto'] = $this->preco_produto->CurrentValue;
		$row['descricao'] = $this->descricao->CurrentValue;
		$row['ipi'] = $this->ipi->CurrentValue;
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

			// status_produto
			$this->status_produto->LinkCustomAttributes = "";
			$this->status_produto->HrefValue = "";
			$this->status_produto->TooltipValue = "";

			// unidade_medida_produto
			$this->unidade_medida_produto->LinkCustomAttributes = "";
			$this->unidade_medida_produto->HrefValue = "";
			$this->unidade_medida_produto->TooltipValue = "";

			// unidades
			$this->unidades->LinkCustomAttributes = "";
			$this->unidades->HrefValue = "";
			$this->unidades->TooltipValue = "";

			// peso_produto
			$this->peso_produto->LinkCustomAttributes = "";
			$this->peso_produto->HrefValue = "";
			$this->peso_produto->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codigo_produto
			$this->codigo_produto->EditAttrs["class"] = "form-control";
			$this->codigo_produto->EditCustomAttributes = "";
			$this->codigo_produto->EditValue = ew_HtmlEncode($this->codigo_produto->CurrentValue);
			$this->codigo_produto->PlaceHolder = ew_RemoveHtml($this->codigo_produto->FldCaption());

			// nome_produto
			$this->nome_produto->EditAttrs["class"] = "form-control";
			$this->nome_produto->EditCustomAttributes = "";
			$this->nome_produto->EditValue = ew_HtmlEncode($this->nome_produto->CurrentValue);
			$this->nome_produto->PlaceHolder = ew_RemoveHtml($this->nome_produto->FldCaption());

			// modelo_produto
			$this->modelo_produto->EditAttrs["class"] = "form-control";
			$this->modelo_produto->EditCustomAttributes = "";
			$this->modelo_produto->EditValue = ew_HtmlEncode($this->modelo_produto->CurrentValue);
			$this->modelo_produto->PlaceHolder = ew_RemoveHtml($this->modelo_produto->FldCaption());

			// id_departamento_produto
			$this->id_departamento_produto->EditAttrs["class"] = "form-control";
			$this->id_departamento_produto->EditCustomAttributes = "";
			if (trim(strval($this->id_departamento_produto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_categoria`" . ew_SearchString("=", $this->id_departamento_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_categoria`, `categoria` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categoria`";
			$sWhereWrk = "";
			$this->id_departamento_produto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_departamento_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_departamento_produto->EditValue = $arwrk;

			// id_marca_produto
			$this->id_marca_produto->EditAttrs["class"] = "form-control";
			$this->id_marca_produto->EditCustomAttributes = "";
			if (trim(strval($this->id_marca_produto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_marca`" . ew_SearchString("=", $this->id_marca_produto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_marca`, `nome_marca` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `marcas`";
			$sWhereWrk = "";
			$this->id_marca_produto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_marca_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_marca_produto->EditValue = $arwrk;

			// status_produto
			$this->status_produto->EditCustomAttributes = "";
			$this->status_produto->EditValue = $this->status_produto->Options(FALSE);

			// unidade_medida_produto
			$this->unidade_medida_produto->EditAttrs["class"] = "form-control";
			$this->unidade_medida_produto->EditCustomAttributes = "";
			$this->unidade_medida_produto->EditValue = ew_HtmlEncode($this->unidade_medida_produto->CurrentValue);
			$this->unidade_medida_produto->PlaceHolder = ew_RemoveHtml($this->unidade_medida_produto->FldCaption());

			// unidades
			$this->unidades->EditAttrs["class"] = "form-control";
			$this->unidades->EditCustomAttributes = "";
			$this->unidades->EditValue = ew_HtmlEncode($this->unidades->CurrentValue);
			$this->unidades->PlaceHolder = ew_RemoveHtml($this->unidades->FldCaption());

			// peso_produto
			$this->peso_produto->EditAttrs["class"] = "form-control";
			$this->peso_produto->EditCustomAttributes = "";
			$this->peso_produto->EditValue = ew_HtmlEncode($this->peso_produto->CurrentValue);
			$this->peso_produto->PlaceHolder = ew_RemoveHtml($this->peso_produto->FldCaption());

			// preco_produto
			$this->preco_produto->EditAttrs["class"] = "form-control";
			$this->preco_produto->EditCustomAttributes = "";
			$this->preco_produto->EditValue = ew_HtmlEncode($this->preco_produto->CurrentValue);
			$this->preco_produto->PlaceHolder = ew_RemoveHtml($this->preco_produto->FldCaption());
			if (strval($this->preco_produto->EditValue) <> "" && is_numeric($this->preco_produto->EditValue)) $this->preco_produto->EditValue = ew_FormatNumber($this->preco_produto->EditValue, -2, -1, -2, -1);

			// descricao
			$this->descricao->EditAttrs["class"] = "form-control";
			$this->descricao->EditCustomAttributes = "";
			$this->descricao->EditValue = ew_HtmlEncode($this->descricao->CurrentValue);
			$this->descricao->PlaceHolder = ew_RemoveHtml($this->descricao->FldCaption());

			// ipi
			$this->ipi->EditAttrs["class"] = "form-control";
			$this->ipi->EditCustomAttributes = "";
			$this->ipi->EditValue = ew_HtmlEncode($this->ipi->CurrentValue);
			$this->ipi->PlaceHolder = ew_RemoveHtml($this->ipi->FldCaption());

			// Add refer script
			// codigo_produto

			$this->codigo_produto->LinkCustomAttributes = "";
			$this->codigo_produto->HrefValue = "";

			// nome_produto
			$this->nome_produto->LinkCustomAttributes = "";
			$this->nome_produto->HrefValue = "";

			// modelo_produto
			$this->modelo_produto->LinkCustomAttributes = "";
			$this->modelo_produto->HrefValue = "";

			// id_departamento_produto
			$this->id_departamento_produto->LinkCustomAttributes = "";
			$this->id_departamento_produto->HrefValue = "";

			// id_marca_produto
			$this->id_marca_produto->LinkCustomAttributes = "";
			$this->id_marca_produto->HrefValue = "";

			// status_produto
			$this->status_produto->LinkCustomAttributes = "";
			$this->status_produto->HrefValue = "";

			// unidade_medida_produto
			$this->unidade_medida_produto->LinkCustomAttributes = "";
			$this->unidade_medida_produto->HrefValue = "";

			// unidades
			$this->unidades->LinkCustomAttributes = "";
			$this->unidades->HrefValue = "";

			// peso_produto
			$this->peso_produto->LinkCustomAttributes = "";
			$this->peso_produto->HrefValue = "";

			// preco_produto
			$this->preco_produto->LinkCustomAttributes = "";
			$this->preco_produto->HrefValue = "";

			// descricao
			$this->descricao->LinkCustomAttributes = "";
			$this->descricao->HrefValue = "";

			// ipi
			$this->ipi->LinkCustomAttributes = "";
			$this->ipi->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->codigo_produto->FormValue)) {
			ew_AddMessage($gsFormError, $this->codigo_produto->FldErrMsg());
		}
		if (!$this->unidades->FldIsDetailKey && !is_null($this->unidades->FormValue) && $this->unidades->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unidades->FldCaption(), $this->unidades->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->unidades->FormValue)) {
			ew_AddMessage($gsFormError, $this->unidades->FldErrMsg());
		}
		if (!ew_CheckNumber($this->preco_produto->FormValue)) {
			ew_AddMessage($gsFormError, $this->preco_produto->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ipi->FormValue)) {
			ew_AddMessage($gsFormError, $this->ipi->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// codigo_produto
		$this->codigo_produto->SetDbValueDef($rsnew, $this->codigo_produto->CurrentValue, NULL, FALSE);

		// nome_produto
		$this->nome_produto->SetDbValueDef($rsnew, $this->nome_produto->CurrentValue, NULL, FALSE);

		// modelo_produto
		$this->modelo_produto->SetDbValueDef($rsnew, $this->modelo_produto->CurrentValue, NULL, FALSE);

		// id_departamento_produto
		$this->id_departamento_produto->SetDbValueDef($rsnew, $this->id_departamento_produto->CurrentValue, NULL, FALSE);

		// id_marca_produto
		$this->id_marca_produto->SetDbValueDef($rsnew, $this->id_marca_produto->CurrentValue, NULL, FALSE);

		// status_produto
		$this->status_produto->SetDbValueDef($rsnew, $this->status_produto->CurrentValue, NULL, FALSE);

		// unidade_medida_produto
		$this->unidade_medida_produto->SetDbValueDef($rsnew, $this->unidade_medida_produto->CurrentValue, NULL, FALSE);

		// unidades
		$this->unidades->SetDbValueDef($rsnew, $this->unidades->CurrentValue, 0, FALSE);

		// peso_produto
		$this->peso_produto->SetDbValueDef($rsnew, $this->peso_produto->CurrentValue, NULL, FALSE);

		// preco_produto
		$this->preco_produto->SetDbValueDef($rsnew, $this->preco_produto->CurrentValue, NULL, FALSE);

		// descricao
		$this->descricao->SetDbValueDef($rsnew, $this->descricao->CurrentValue, NULL, FALSE);

		// ipi
		$this->ipi->SetDbValueDef($rsnew, $this->ipi->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produtoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_departamento_produto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_categoria` AS `LinkFld`, `categoria` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_categoria` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_departamento_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_marca_produto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_marca` AS `LinkFld`, `nome_marca` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_marca` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_marca_produto, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Custom validate event
	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($produtos_addopt)) $produtos_addopt = new cprodutos_addopt();

// Page init
$produtos_addopt->Page_Init();

// Page main
$produtos_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produtos_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fprodutosaddopt = new ew_Form("fprodutosaddopt", "addopt");

// Validate form
fprodutosaddopt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_codigo_produto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->codigo_produto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unidades");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $produtos->unidades->FldCaption(), $produtos->unidades->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unidades");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->unidades->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_preco_produto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->preco_produto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ipi");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produtos->ipi->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fprodutosaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fprodutosaddopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fprodutosaddopt.Lists["x_id_departamento_produto"] = {"LinkField":"x_id_categoria","Ajax":true,"AutoFill":false,"DisplayFields":["x_categoria","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categoria"};
fprodutosaddopt.Lists["x_id_departamento_produto"].Data = "<?php echo $produtos_addopt->id_departamento_produto->LookupFilterQuery(FALSE, "addopt") ?>";
fprodutosaddopt.Lists["x_id_marca_produto"] = {"LinkField":"x_id_marca","Ajax":true,"AutoFill":false,"DisplayFields":["x_nome_marca","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
fprodutosaddopt.Lists["x_id_marca_produto"].Data = "<?php echo $produtos_addopt->id_marca_produto->LookupFilterQuery(FALSE, "addopt") ?>";
fprodutosaddopt.Lists["x_status_produto"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fprodutosaddopt.Lists["x_status_produto"].Options = <?php echo json_encode($produtos_addopt->status_produto->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$produtos_addopt->ShowMessage();
?>
<form name="fprodutosaddopt" id="fprodutosaddopt" class="ewForm form-horizontal" action="produtosaddopt.php" method="post">
<?php if ($produtos_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produtos_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produtos">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($produtos->codigo_produto->Visible) { // codigo_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_codigo_produto"><?php echo $produtos->codigo_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_codigo_produto" name="x_codigo_produto" id="x_codigo_produto" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->codigo_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->codigo_produto->EditValue ?>"<?php echo $produtos->codigo_produto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->nome_produto->Visible) { // nome_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_nome_produto"><?php echo $produtos->nome_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_nome_produto" name="x_nome_produto" id="x_nome_produto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($produtos->nome_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->nome_produto->EditValue ?>"<?php echo $produtos->nome_produto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->modelo_produto->Visible) { // modelo_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_modelo_produto"><?php echo $produtos->modelo_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_modelo_produto" name="x_modelo_produto" id="x_modelo_produto" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($produtos->modelo_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->modelo_produto->EditValue ?>"<?php echo $produtos->modelo_produto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->id_departamento_produto->Visible) { // id_departamento_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_departamento_produto"><?php echo $produtos->id_departamento_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<select data-table="produtos" data-field="x_id_departamento_produto" data-value-separator="<?php echo $produtos->id_departamento_produto->DisplayValueSeparatorAttribute() ?>" id="x_id_departamento_produto" name="x_id_departamento_produto"<?php echo $produtos->id_departamento_produto->EditAttributes() ?>>
<?php echo $produtos->id_departamento_produto->SelectOptionListHtml("x_id_departamento_produto") ?>
</select>
</div>
	</div>
<?php } ?>
<?php if ($produtos->id_marca_produto->Visible) { // id_marca_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_id_marca_produto"><?php echo $produtos->id_marca_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<select data-table="produtos" data-field="x_id_marca_produto" data-value-separator="<?php echo $produtos->id_marca_produto->DisplayValueSeparatorAttribute() ?>" id="x_id_marca_produto" name="x_id_marca_produto"<?php echo $produtos->id_marca_produto->EditAttributes() ?>>
<?php echo $produtos->id_marca_produto->SelectOptionListHtml("x_id_marca_produto") ?>
</select>
</div>
	</div>
<?php } ?>
<?php if ($produtos->status_produto->Visible) { // status_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $produtos->status_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<div id="tp_x_status_produto" class="ewTemplate"><input type="radio" data-table="produtos" data-field="x_status_produto" data-value-separator="<?php echo $produtos->status_produto->DisplayValueSeparatorAttribute() ?>" name="x_status_produto" id="x_status_produto" value="{value}"<?php echo $produtos->status_produto->EditAttributes() ?>></div>
<div id="dsl_x_status_produto" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $produtos->status_produto->RadioButtonListHtml(FALSE, "x_status_produto") ?>
</div></div>
</div>
	</div>
<?php } ?>
<?php if ($produtos->unidade_medida_produto->Visible) { // unidade_medida_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_unidade_medida_produto"><?php echo $produtos->unidade_medida_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_unidade_medida_produto" name="x_unidade_medida_produto" id="x_unidade_medida_produto" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($produtos->unidade_medida_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->unidade_medida_produto->EditValue ?>"<?php echo $produtos->unidade_medida_produto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->unidades->Visible) { // unidades ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_unidades"><?php echo $produtos->unidades->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_unidades" name="x_unidades" id="x_unidades" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->unidades->getPlaceHolder()) ?>" value="<?php echo $produtos->unidades->EditValue ?>"<?php echo $produtos->unidades->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->peso_produto->Visible) { // peso_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_peso_produto"><?php echo $produtos->peso_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_peso_produto" name="x_peso_produto" id="x_peso_produto" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($produtos->peso_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->peso_produto->EditValue ?>"<?php echo $produtos->peso_produto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->preco_produto->Visible) { // preco_produto ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_preco_produto"><?php echo $produtos->preco_produto->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_preco_produto" name="x_preco_produto" id="x_preco_produto" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->preco_produto->getPlaceHolder()) ?>" value="<?php echo $produtos->preco_produto->EditValue ?>"<?php echo $produtos->preco_produto->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->descricao->Visible) { // descricao ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_descricao"><?php echo $produtos->descricao->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_descricao" name="x_descricao" id="x_descricao" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($produtos->descricao->getPlaceHolder()) ?>" value="<?php echo $produtos->descricao->EditValue ?>"<?php echo $produtos->descricao->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($produtos->ipi->Visible) { // ipi ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_ipi"><?php echo $produtos->ipi->FldCaption() ?></label>
		<div class="col-sm-10">
<input type="text" data-table="produtos" data-field="x_ipi" name="x_ipi" id="x_ipi" size="30" placeholder="<?php echo ew_HtmlEncode($produtos->ipi->getPlaceHolder()) ?>" value="<?php echo $produtos->ipi->EditValue ?>"<?php echo $produtos->ipi->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
fprodutosaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$produtos_addopt->Page_Terminate();
?>
