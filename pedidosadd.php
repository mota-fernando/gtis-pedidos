<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pedidosinfo.php" ?>
<?php include_once "detalhe_pedidogridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pedidos_add = NULL; // Initialize page object first

class cpedidos_add extends cpedidos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{A4E38B50-67B8-459F-992C-3B232135A6E3}';

	// Table name
	var $TableName = 'pedidos';

	// Page object name
	var $PageObjName = 'pedidos_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Get the keys for master table
			$sDetailTblVar = $this->getCurrentDetailTable();
			if ($sDetailTblVar <> "") {
				$DetailTblVar = explode(",", $sDetailTblVar);
				if (in_array("detalhe_pedido", $DetailTblVar)) {

					// Process auto fill for detail table 'detalhe_pedido'
					if (preg_match('/^fdetalhe_pedido(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["detalhe_pedido_grid"])) $GLOBALS["detalhe_pedido_grid"] = new cdetalhe_pedido_grid;
						$GLOBALS["detalhe_pedido_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
			}
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "pedidosview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_pedidos"] != "") {
				$this->id_pedidos->setQueryStringValue($_GET["id_pedidos"]);
				$this->setKey("id_pedidos", $this->id_pedidos->CurrentValue); // Set up key
			} else {
				$this->setKey("id_pedidos", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Set up detail parameters
		$this->SetupDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("pedidoslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = "pedidoslist.php";
					if (ew_GetPageName($sReturnUrl) == "pedidoslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "pedidosview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetupDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
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
		$this->id_pedidos->CurrentValue = NULL;
		$this->id_pedidos->OldValue = $this->id_pedidos->CurrentValue;
		$this->tipo_pedido->CurrentValue = "C";
		$this->numero->CurrentValue = NULL;
		$this->numero->OldValue = $this->numero->CurrentValue;
		$this->fecha_data->CurrentValue = NULL;
		$this->fecha_data->OldValue = $this->fecha_data->CurrentValue;
		$this->fecha_hora->CurrentValue = NULL;
		$this->fecha_hora->OldValue = $this->fecha_hora->CurrentValue;
		$this->id_fornecedor->CurrentValue = NULL;
		$this->id_fornecedor->OldValue = $this->id_fornecedor->CurrentValue;
		$this->id_transportadora->CurrentValue = NULL;
		$this->id_transportadora->OldValue = $this->id_transportadora->CurrentValue;
		$this->id_prazos->CurrentValue = NULL;
		$this->id_prazos->OldValue = $this->id_prazos->CurrentValue;
		$this->comentarios->CurrentValue = NULL;
		$this->comentarios->OldValue = $this->comentarios->CurrentValue;
		$this->id_representante->CurrentValue = NULL;
		$this->id_representante->OldValue = $this->id_representante->CurrentValue;
		$this->comissao_representante->CurrentValue = "N";
		$this->id_cliente->CurrentValue = 0;
		$this->status->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->tipo_pedido->FldIsDetailKey) {
			$this->tipo_pedido->setFormValue($objForm->GetValue("x_tipo_pedido"));
		}
		if (!$this->numero->FldIsDetailKey) {
			$this->numero->setFormValue($objForm->GetValue("x_numero"));
		}
		if (!$this->fecha_data->FldIsDetailKey) {
			$this->fecha_data->setFormValue($objForm->GetValue("x_fecha_data"));
			$this->fecha_data->CurrentValue = ew_UnFormatDateTime($this->fecha_data->CurrentValue, 0);
		}
		if (!$this->fecha_hora->FldIsDetailKey) {
			$this->fecha_hora->setFormValue($objForm->GetValue("x_fecha_hora"));
			$this->fecha_hora->CurrentValue = ew_UnFormatDateTime($this->fecha_hora->CurrentValue, 4);
		}
		if (!$this->id_fornecedor->FldIsDetailKey) {
			$this->id_fornecedor->setFormValue($objForm->GetValue("x_id_fornecedor"));
		}
		if (!$this->id_transportadora->FldIsDetailKey) {
			$this->id_transportadora->setFormValue($objForm->GetValue("x_id_transportadora"));
		}
		if (!$this->id_prazos->FldIsDetailKey) {
			$this->id_prazos->setFormValue($objForm->GetValue("x_id_prazos"));
		}
		if (!$this->comentarios->FldIsDetailKey) {
			$this->comentarios->setFormValue($objForm->GetValue("x_comentarios"));
		}
		if (!$this->id_representante->FldIsDetailKey) {
			$this->id_representante->setFormValue($objForm->GetValue("x_id_representante"));
		}
		if (!$this->comissao_representante->FldIsDetailKey) {
			$this->comissao_representante->setFormValue($objForm->GetValue("x_comissao_representante"));
		}
		if (!$this->id_cliente->FldIsDetailKey) {
			$this->id_cliente->setFormValue($objForm->GetValue("x_id_cliente"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->tipo_pedido->CurrentValue = $this->tipo_pedido->FormValue;
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->fecha_data->CurrentValue = $this->fecha_data->FormValue;
		$this->fecha_data->CurrentValue = ew_UnFormatDateTime($this->fecha_data->CurrentValue, 0);
		$this->fecha_hora->CurrentValue = $this->fecha_hora->FormValue;
		$this->fecha_hora->CurrentValue = ew_UnFormatDateTime($this->fecha_hora->CurrentValue, 4);
		$this->id_fornecedor->CurrentValue = $this->id_fornecedor->FormValue;
		$this->id_transportadora->CurrentValue = $this->id_transportadora->FormValue;
		$this->id_prazos->CurrentValue = $this->id_prazos->FormValue;
		$this->comentarios->CurrentValue = $this->comentarios->FormValue;
		$this->id_representante->CurrentValue = $this->id_representante->FormValue;
		$this->comissao_representante->CurrentValue = $this->comissao_representante->FormValue;
		$this->id_cliente->CurrentValue = $this->id_cliente->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id_pedidos'] = $this->id_pedidos->CurrentValue;
		$row['tipo_pedido'] = $this->tipo_pedido->CurrentValue;
		$row['numero'] = $this->numero->CurrentValue;
		$row['fecha_data'] = $this->fecha_data->CurrentValue;
		$row['fecha_hora'] = $this->fecha_hora->CurrentValue;
		$row['id_fornecedor'] = $this->id_fornecedor->CurrentValue;
		$row['id_transportadora'] = $this->id_transportadora->CurrentValue;
		$row['id_prazos'] = $this->id_prazos->CurrentValue;
		$row['comentarios'] = $this->comentarios->CurrentValue;
		$row['id_representante'] = $this->id_representante->CurrentValue;
		$row['comissao_representante'] = $this->comissao_representante->CurrentValue;
		$row['id_cliente'] = $this->id_cliente->CurrentValue;
		$row['status'] = $this->status->CurrentValue;
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
		$this->id_cliente->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// tipo_pedido
			$this->tipo_pedido->EditAttrs["class"] = "form-control";
			$this->tipo_pedido->EditCustomAttributes = "";
			$this->tipo_pedido->EditValue = $this->tipo_pedido->Options(TRUE);

			// numero
			$this->numero->EditAttrs["class"] = "form-control";
			$this->numero->EditCustomAttributes = "";
			$this->numero->EditValue = ew_HtmlEncode($this->numero->CurrentValue);
			$this->numero->PlaceHolder = ew_RemoveHtml($this->numero->FldCaption());

			// fecha_data
			// fecha_hora
			// id_fornecedor

			$this->id_fornecedor->EditAttrs["class"] = "form-control";
			$this->id_fornecedor->EditCustomAttributes = "";
			if (trim(strval($this->id_fornecedor->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_perfil`" . ew_SearchString("=", $this->id_fornecedor->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_perfil`, `razao_social` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresas`";
			$sWhereWrk = "";
			$this->id_fornecedor->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_fornecedor, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_fornecedor->EditValue = $arwrk;

			// id_transportadora
			$this->id_transportadora->EditAttrs["class"] = "form-control";
			$this->id_transportadora->EditCustomAttributes = "";
			if (trim(strval($this->id_transportadora->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_transportadora`" . ew_SearchString("=", $this->id_transportadora->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_transportadora`, `transportadora` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tranportadora`";
			$sWhereWrk = "";
			$this->id_transportadora->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_transportadora, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_transportadora->EditValue = $arwrk;

			// id_prazos
			$this->id_prazos->EditAttrs["class"] = "form-control";
			$this->id_prazos->EditCustomAttributes = "";
			if (trim(strval($this->id_prazos->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_prazos`" . ew_SearchString("=", $this->id_prazos->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_prazos`, `prazo_em_dias` AS `DispFld`, `parcelas` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `prazos`";
			$sWhereWrk = "";
			$this->id_prazos->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_prazos, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_prazos->EditValue = $arwrk;

			// comentarios
			$this->comentarios->EditAttrs["class"] = "form-control";
			$this->comentarios->EditCustomAttributes = "";
			$this->comentarios->EditValue = ew_HtmlEncode($this->comentarios->CurrentValue);
			$this->comentarios->PlaceHolder = ew_RemoveHtml($this->comentarios->FldCaption());

			// id_representante
			$this->id_representante->EditAttrs["class"] = "form-control";
			$this->id_representante->EditCustomAttributes = "";
			if (trim(strval($this->id_representante->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_representantes`" . ew_SearchString("=", $this->id_representante->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_representantes`, `id_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `representantes`";
			$sWhereWrk = "";
			$this->id_representante->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_representante, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_representante->EditValue = $arwrk;

			// comissao_representante
			$this->comissao_representante->EditAttrs["class"] = "form-control";
			$this->comissao_representante->EditCustomAttributes = "";
			$this->comissao_representante->EditValue = $this->comissao_representante->Options(TRUE);

			// id_cliente
			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			$this->id_cliente->EditValue = ew_HtmlEncode($this->id_cliente->CurrentValue);
			$this->id_cliente->PlaceHolder = ew_RemoveHtml($this->id_cliente->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// tipo_pedido

			$this->tipo_pedido->LinkCustomAttributes = "";
			$this->tipo_pedido->HrefValue = "";

			// numero
			$this->numero->LinkCustomAttributes = "";
			$this->numero->HrefValue = "";

			// fecha_data
			$this->fecha_data->LinkCustomAttributes = "";
			$this->fecha_data->HrefValue = "";

			// fecha_hora
			$this->fecha_hora->LinkCustomAttributes = "";
			$this->fecha_hora->HrefValue = "";

			// id_fornecedor
			$this->id_fornecedor->LinkCustomAttributes = "";
			$this->id_fornecedor->HrefValue = "";

			// id_transportadora
			$this->id_transportadora->LinkCustomAttributes = "";
			$this->id_transportadora->HrefValue = "";

			// id_prazos
			$this->id_prazos->LinkCustomAttributes = "";
			$this->id_prazos->HrefValue = "";

			// comentarios
			$this->comentarios->LinkCustomAttributes = "";
			$this->comentarios->HrefValue = "";

			// id_representante
			$this->id_representante->LinkCustomAttributes = "";
			$this->id_representante->HrefValue = "";

			// comissao_representante
			$this->comissao_representante->LinkCustomAttributes = "";
			$this->comissao_representante->HrefValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			$this->id_cliente->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
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
		if (!$this->tipo_pedido->FldIsDetailKey && !is_null($this->tipo_pedido->FormValue) && $this->tipo_pedido->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipo_pedido->FldCaption(), $this->tipo_pedido->ReqErrMsg));
		}
		if (!$this->numero->FldIsDetailKey && !is_null($this->numero->FormValue) && $this->numero->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->numero->FldCaption(), $this->numero->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->numero->FormValue)) {
			ew_AddMessage($gsFormError, $this->numero->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_cliente->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_cliente->FldErrMsg());
		}
		if ($this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("detalhe_pedido", $DetailTblVar) && $GLOBALS["detalhe_pedido"]->DetailAdd) {
			if (!isset($GLOBALS["detalhe_pedido_grid"])) $GLOBALS["detalhe_pedido_grid"] = new cdetalhe_pedido_grid(); // get detail page object
			$GLOBALS["detalhe_pedido_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// tipo_pedido
		$this->tipo_pedido->SetDbValueDef($rsnew, $this->tipo_pedido->CurrentValue, NULL, strval($this->tipo_pedido->CurrentValue) == "");

		// numero
		$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, FALSE);

		// fecha_data
		$this->fecha_data->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['fecha_data'] = &$this->fecha_data->DbValue;

		// fecha_hora
		$this->fecha_hora->SetDbValueDef($rsnew, ew_CurrentTime(), NULL);
		$rsnew['fecha_hora'] = &$this->fecha_hora->DbValue;

		// id_fornecedor
		$this->id_fornecedor->SetDbValueDef($rsnew, $this->id_fornecedor->CurrentValue, NULL, FALSE);

		// id_transportadora
		$this->id_transportadora->SetDbValueDef($rsnew, $this->id_transportadora->CurrentValue, NULL, FALSE);

		// id_prazos
		$this->id_prazos->SetDbValueDef($rsnew, $this->id_prazos->CurrentValue, NULL, FALSE);

		// comentarios
		$this->comentarios->SetDbValueDef($rsnew, $this->comentarios->CurrentValue, NULL, FALSE);

		// id_representante
		$this->id_representante->SetDbValueDef($rsnew, $this->id_representante->CurrentValue, NULL, FALSE);

		// comissao_representante
		$this->comissao_representante->SetDbValueDef($rsnew, $this->comissao_representante->CurrentValue, NULL, strval($this->comissao_representante->CurrentValue) == "");

		// id_cliente
		$this->id_cliente->SetDbValueDef($rsnew, $this->id_cliente->CurrentValue, NULL, strval($this->id_cliente->CurrentValue) == "");

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, 0, FALSE);

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("detalhe_pedido", $DetailTblVar) && $GLOBALS["detalhe_pedido"]->DetailAdd) {
				$GLOBALS["detalhe_pedido"]->numero_pedido->setSessionValue($this->numero->CurrentValue); // Set master key
				if (!isset($GLOBALS["detalhe_pedido_grid"])) $GLOBALS["detalhe_pedido_grid"] = new cdetalhe_pedido_grid(); // Get detail page object
				$AddRow = $GLOBALS["detalhe_pedido_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["detalhe_pedido"]->numero_pedido->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("detalhe_pedido", $DetailTblVar)) {
				if (!isset($GLOBALS["detalhe_pedido_grid"]))
					$GLOBALS["detalhe_pedido_grid"] = new cdetalhe_pedido_grid;
				if ($GLOBALS["detalhe_pedido_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["detalhe_pedido_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["detalhe_pedido_grid"]->CurrentMode = "add";
					$GLOBALS["detalhe_pedido_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["detalhe_pedido_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["detalhe_pedido_grid"]->setStartRecordNumber(1);
					$GLOBALS["detalhe_pedido_grid"]->numero_pedido->FldIsDetailKey = TRUE;
					$GLOBALS["detalhe_pedido_grid"]->numero_pedido->CurrentValue = $this->numero->CurrentValue;
					$GLOBALS["detalhe_pedido_grid"]->numero_pedido->setSessionValue($GLOBALS["detalhe_pedido_grid"]->numero_pedido->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pedidoslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_fornecedor":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_perfil` AS `LinkFld`, `razao_social` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_perfil` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_fornecedor, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_transportadora":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_transportadora` AS `LinkFld`, `transportadora` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tranportadora`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_transportadora` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_transportadora, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_prazos":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_prazos` AS `LinkFld`, `prazo_em_dias` AS `DispFld`, `parcelas` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `prazos`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_prazos` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_prazos, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_representante":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_representantes` AS `LinkFld`, `id_pessoa` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `representantes`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_representantes` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_representante, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($pedidos_add)) $pedidos_add = new cpedidos_add();

// Page init
$pedidos_add->Page_Init();

// Page main
$pedidos_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pedidos_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fpedidosadd = new ew_Form("fpedidosadd", "add");

// Validate form
fpedidosadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tipo_pedido");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pedidos->tipo_pedido->FldCaption(), $pedidos->tipo_pedido->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pedidos->numero->FldCaption(), $pedidos->numero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pedidos->numero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_cliente");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pedidos->id_cliente->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pedidos->status->FldCaption(), $pedidos->status->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpedidosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpedidosadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpedidosadd.Lists["x_tipo_pedido"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpedidosadd.Lists["x_tipo_pedido"].Options = <?php echo json_encode($pedidos_add->tipo_pedido->Options()) ?>;
fpedidosadd.Lists["x_id_fornecedor"] = {"LinkField":"x_id_perfil","Ajax":true,"AutoFill":false,"DisplayFields":["x_razao_social","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"empresas"};
fpedidosadd.Lists["x_id_fornecedor"].Data = "<?php echo $pedidos_add->id_fornecedor->LookupFilterQuery(FALSE, "add") ?>";
fpedidosadd.Lists["x_id_transportadora"] = {"LinkField":"x_id_transportadora","Ajax":true,"AutoFill":false,"DisplayFields":["x_transportadora","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tranportadora"};
fpedidosadd.Lists["x_id_transportadora"].Data = "<?php echo $pedidos_add->id_transportadora->LookupFilterQuery(FALSE, "add") ?>";
fpedidosadd.Lists["x_id_prazos"] = {"LinkField":"x_id_prazos","Ajax":true,"AutoFill":false,"DisplayFields":["x_prazo_em_dias","x_parcelas","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"prazos"};
fpedidosadd.Lists["x_id_prazos"].Data = "<?php echo $pedidos_add->id_prazos->LookupFilterQuery(FALSE, "add") ?>";
fpedidosadd.Lists["x_id_representante"] = {"LinkField":"x_id_representantes","Ajax":true,"AutoFill":false,"DisplayFields":["x_id_pessoa","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"representantes"};
fpedidosadd.Lists["x_id_representante"].Data = "<?php echo $pedidos_add->id_representante->LookupFilterQuery(FALSE, "add") ?>";
fpedidosadd.Lists["x_comissao_representante"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpedidosadd.Lists["x_comissao_representante"].Options = <?php echo json_encode($pedidos_add->comissao_representante->Options()) ?>;
fpedidosadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpedidosadd.Lists["x_status"].Options = <?php echo json_encode($pedidos_add->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pedidos_add->ShowPageHeader(); ?>
<?php
$pedidos_add->ShowMessage();
?>
<form name="fpedidosadd" id="fpedidosadd" class="<?php echo $pedidos_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pedidos_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pedidos_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pedidos">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($pedidos_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($pedidos->tipo_pedido->Visible) { // tipo_pedido ?>
	<div id="r_tipo_pedido" class="form-group">
		<label id="elh_pedidos_tipo_pedido" for="x_tipo_pedido" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->tipo_pedido->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->tipo_pedido->CellAttributes() ?>>
<span id="el_pedidos_tipo_pedido">
<select data-table="pedidos" data-field="x_tipo_pedido" data-value-separator="<?php echo $pedidos->tipo_pedido->DisplayValueSeparatorAttribute() ?>" id="x_tipo_pedido" name="x_tipo_pedido"<?php echo $pedidos->tipo_pedido->EditAttributes() ?>>
<?php echo $pedidos->tipo_pedido->SelectOptionListHtml("x_tipo_pedido") ?>
</select>
</span>
<?php echo $pedidos->tipo_pedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_pedidos_numero" for="x_numero" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->numero->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->numero->CellAttributes() ?>>
<span id="el_pedidos_numero">
<input type="text" data-table="pedidos" data-field="x_numero" name="x_numero" id="x_numero" size="30" placeholder="<?php echo ew_HtmlEncode($pedidos->numero->getPlaceHolder()) ?>" value="<?php echo $pedidos->numero->EditValue ?>"<?php echo $pedidos->numero->EditAttributes() ?>>
</span>
<?php echo $pedidos->numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->id_fornecedor->Visible) { // id_fornecedor ?>
	<div id="r_id_fornecedor" class="form-group">
		<label id="elh_pedidos_id_fornecedor" for="x_id_fornecedor" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->id_fornecedor->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->id_fornecedor->CellAttributes() ?>>
<span id="el_pedidos_id_fornecedor">
<select data-table="pedidos" data-field="x_id_fornecedor" data-value-separator="<?php echo $pedidos->id_fornecedor->DisplayValueSeparatorAttribute() ?>" id="x_id_fornecedor" name="x_id_fornecedor"<?php echo $pedidos->id_fornecedor->EditAttributes() ?>>
<?php echo $pedidos->id_fornecedor->SelectOptionListHtml("x_id_fornecedor") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pedidos->id_fornecedor->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_fornecedor',url:'empresasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_fornecedor"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pedidos->id_fornecedor->FldCaption() ?></span></button>
</span>
<?php echo $pedidos->id_fornecedor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->id_transportadora->Visible) { // id_transportadora ?>
	<div id="r_id_transportadora" class="form-group">
		<label id="elh_pedidos_id_transportadora" for="x_id_transportadora" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->id_transportadora->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->id_transportadora->CellAttributes() ?>>
<span id="el_pedidos_id_transportadora">
<select data-table="pedidos" data-field="x_id_transportadora" data-value-separator="<?php echo $pedidos->id_transportadora->DisplayValueSeparatorAttribute() ?>" id="x_id_transportadora" name="x_id_transportadora"<?php echo $pedidos->id_transportadora->EditAttributes() ?>>
<?php echo $pedidos->id_transportadora->SelectOptionListHtml("x_id_transportadora") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pedidos->id_transportadora->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_transportadora',url:'tranportadoraaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_transportadora"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pedidos->id_transportadora->FldCaption() ?></span></button>
</span>
<?php echo $pedidos->id_transportadora->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->id_prazos->Visible) { // id_prazos ?>
	<div id="r_id_prazos" class="form-group">
		<label id="elh_pedidos_id_prazos" for="x_id_prazos" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->id_prazos->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->id_prazos->CellAttributes() ?>>
<span id="el_pedidos_id_prazos">
<select data-table="pedidos" data-field="x_id_prazos" data-value-separator="<?php echo $pedidos->id_prazos->DisplayValueSeparatorAttribute() ?>" id="x_id_prazos" name="x_id_prazos"<?php echo $pedidos->id_prazos->EditAttributes() ?>>
<?php echo $pedidos->id_prazos->SelectOptionListHtml("x_id_prazos") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pedidos->id_prazos->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_prazos',url:'prazosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_prazos"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pedidos->id_prazos->FldCaption() ?></span></button>
</span>
<?php echo $pedidos->id_prazos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->comentarios->Visible) { // comentarios ?>
	<div id="r_comentarios" class="form-group">
		<label id="elh_pedidos_comentarios" for="x_comentarios" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->comentarios->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->comentarios->CellAttributes() ?>>
<span id="el_pedidos_comentarios">
<input type="text" data-table="pedidos" data-field="x_comentarios" name="x_comentarios" id="x_comentarios" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pedidos->comentarios->getPlaceHolder()) ?>" value="<?php echo $pedidos->comentarios->EditValue ?>"<?php echo $pedidos->comentarios->EditAttributes() ?>>
</span>
<?php echo $pedidos->comentarios->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->id_representante->Visible) { // id_representante ?>
	<div id="r_id_representante" class="form-group">
		<label id="elh_pedidos_id_representante" for="x_id_representante" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->id_representante->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->id_representante->CellAttributes() ?>>
<span id="el_pedidos_id_representante">
<select data-table="pedidos" data-field="x_id_representante" data-value-separator="<?php echo $pedidos->id_representante->DisplayValueSeparatorAttribute() ?>" id="x_id_representante" name="x_id_representante"<?php echo $pedidos->id_representante->EditAttributes() ?>>
<?php echo $pedidos->id_representante->SelectOptionListHtml("x_id_representante") ?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pedidos->id_representante->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_representante',url:'representantesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_representante"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pedidos->id_representante->FldCaption() ?></span></button>
</span>
<?php echo $pedidos->id_representante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->comissao_representante->Visible) { // comissao_representante ?>
	<div id="r_comissao_representante" class="form-group">
		<label id="elh_pedidos_comissao_representante" for="x_comissao_representante" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->comissao_representante->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->comissao_representante->CellAttributes() ?>>
<span id="el_pedidos_comissao_representante">
<select data-table="pedidos" data-field="x_comissao_representante" data-value-separator="<?php echo $pedidos->comissao_representante->DisplayValueSeparatorAttribute() ?>" id="x_comissao_representante" name="x_comissao_representante"<?php echo $pedidos->comissao_representante->EditAttributes() ?>>
<?php echo $pedidos->comissao_representante->SelectOptionListHtml("x_comissao_representante") ?>
</select>
</span>
<?php echo $pedidos->comissao_representante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->id_cliente->Visible) { // id_cliente ?>
	<div id="r_id_cliente" class="form-group">
		<label id="elh_pedidos_id_cliente" for="x_id_cliente" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->id_cliente->FldCaption() ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->id_cliente->CellAttributes() ?>>
<span id="el_pedidos_id_cliente">
<input type="text" data-table="pedidos" data-field="x_id_cliente" name="x_id_cliente" id="x_id_cliente" size="30" placeholder="<?php echo ew_HtmlEncode($pedidos->id_cliente->getPlaceHolder()) ?>" value="<?php echo $pedidos->id_cliente->EditValue ?>"<?php echo $pedidos->id_cliente->EditAttributes() ?>>
</span>
<?php echo $pedidos->id_cliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pedidos->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_pedidos_status" class="<?php echo $pedidos_add->LeftColumnClass ?>"><?php echo $pedidos->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $pedidos_add->RightColumnClass ?>"><div<?php echo $pedidos->status->CellAttributes() ?>>
<span id="el_pedidos_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="pedidos" data-field="x_status" data-value-separator="<?php echo $pedidos->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="{value}"<?php echo $pedidos->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pedidos->status->RadioButtonListHtml(FALSE, "x_status") ?>
</div></div>
</span>
<?php echo $pedidos->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("detalhe_pedido", explode(",", $pedidos->getCurrentDetailTable())) && $detalhe_pedido->DetailAdd) {
?>
<?php if ($pedidos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("detalhe_pedido", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "detalhe_pedidogrid.php" ?>
<?php } ?>
<?php if (!$pedidos_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $pedidos_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pedidos_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fpedidosadd.Init();
</script>
<?php
$pedidos_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pedidos_add->Page_Terminate();
?>
