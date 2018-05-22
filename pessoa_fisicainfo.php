<?php

// Global variable for table object
$pessoa_fisica = NULL;

//
// Table class for pessoa_fisica
//
class cpessoa_fisica extends cTable {
	var $id_pessoa;
	var $nome_pessoa;
	var $sobrenome_pessoa;
	var $nascimento;
	var $telefone;
	var $_email;
	var $celular;
	var $CPF;
	var $RG;
	var $id_endereco;
	var $endereco_numero;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pessoa_fisica';
		$this->TableName = 'pessoa_fisica';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`pessoa_fisica`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_pessoa
		$this->id_pessoa = new cField('pessoa_fisica', 'pessoa_fisica', 'x_id_pessoa', 'id_pessoa', '`id_pessoa`', '`id_pessoa`', 3, -1, FALSE, '`id_pessoa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id_pessoa->Sortable = TRUE; // Allow sort
		$this->id_pessoa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_pessoa'] = &$this->id_pessoa;

		// nome_pessoa
		$this->nome_pessoa = new cField('pessoa_fisica', 'pessoa_fisica', 'x_nome_pessoa', 'nome_pessoa', '`nome_pessoa`', '`nome_pessoa`', 200, -1, FALSE, '`nome_pessoa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nome_pessoa->Sortable = TRUE; // Allow sort
		$this->fields['nome_pessoa'] = &$this->nome_pessoa;

		// sobrenome_pessoa
		$this->sobrenome_pessoa = new cField('pessoa_fisica', 'pessoa_fisica', 'x_sobrenome_pessoa', 'sobrenome_pessoa', '`sobrenome_pessoa`', '`sobrenome_pessoa`', 200, -1, FALSE, '`sobrenome_pessoa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sobrenome_pessoa->Sortable = TRUE; // Allow sort
		$this->fields['sobrenome_pessoa'] = &$this->sobrenome_pessoa;

		// nascimento
		$this->nascimento = new cField('pessoa_fisica', 'pessoa_fisica', 'x_nascimento', 'nascimento', '`nascimento`', ew_CastDateFieldForLike('`nascimento`', 0, "DB"), 133, 0, FALSE, '`nascimento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nascimento->Sortable = TRUE; // Allow sort
		$this->nascimento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['nascimento'] = &$this->nascimento;

		// telefone
		$this->telefone = new cField('pessoa_fisica', 'pessoa_fisica', 'x_telefone', 'telefone', '`telefone`', '`telefone`', 3, -1, FALSE, '`telefone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->telefone->Sortable = TRUE; // Allow sort
		$this->telefone->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['telefone'] = &$this->telefone;

		// email
		$this->_email = new cField('pessoa_fisica', 'pessoa_fisica', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// celular
		$this->celular = new cField('pessoa_fisica', 'pessoa_fisica', 'x_celular', 'celular', '`celular`', '`celular`', 3, -1, FALSE, '`celular`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->celular->Sortable = TRUE; // Allow sort
		$this->celular->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['celular'] = &$this->celular;

		// CPF
		$this->CPF = new cField('pessoa_fisica', 'pessoa_fisica', 'x_CPF', 'CPF', '`CPF`', '`CPF`', 3, -1, FALSE, '`CPF`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CPF->Sortable = TRUE; // Allow sort
		$this->CPF->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CPF'] = &$this->CPF;

		// RG
		$this->RG = new cField('pessoa_fisica', 'pessoa_fisica', 'x_RG', 'RG', '`RG`', '`RG`', 3, -1, FALSE, '`RG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->RG->Sortable = TRUE; // Allow sort
		$this->RG->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['RG'] = &$this->RG;

		// id_endereco
		$this->id_endereco = new cField('pessoa_fisica', 'pessoa_fisica', 'x_id_endereco', 'id_endereco', '`id_endereco`', '`id_endereco`', 3, -1, FALSE, '`id_endereco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_endereco->Sortable = TRUE; // Allow sort
		$this->id_endereco->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_endereco->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_endereco->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_endereco'] = &$this->id_endereco;

		// endereco_numero
		$this->endereco_numero = new cField('pessoa_fisica', 'pessoa_fisica', 'x_endereco_numero', 'endereco_numero', '`endereco_numero`', '`endereco_numero`', 200, -1, FALSE, '`endereco_numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->endereco_numero->Sortable = TRUE; // Allow sort
		$this->fields['endereco_numero'] = &$this->endereco_numero;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pessoa_fisica`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id_pessoa->setDbValue($conn->Insert_ID());
			$rs['id_pessoa'] = $this->id_pessoa->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id_pessoa', $rs))
				ew_AddFilter($where, ew_QuotedName('id_pessoa', $this->DBID) . '=' . ew_QuotedValue($rs['id_pessoa'], $this->id_pessoa->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_pessoa` = @id_pessoa@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_pessoa->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_pessoa->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_pessoa@", ew_AdjustSql($this->id_pessoa->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "pessoa_fisicalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "pessoa_fisicaview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "pessoa_fisicaedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "pessoa_fisicaadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "pessoa_fisicalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pessoa_fisicaview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pessoa_fisicaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "pessoa_fisicaadd.php?" . $this->UrlParm($parm);
		else
			$url = "pessoa_fisicaadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("pessoa_fisicaedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("pessoa_fisicaadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pessoa_fisicadelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_pessoa:" . ew_VarToJson($this->id_pessoa->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_pessoa->CurrentValue)) {
			$sUrl .= "id_pessoa=" . urlencode($this->id_pessoa->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["id_pessoa"]))
				$arKeys[] = $_POST["id_pessoa"];
			elseif (isset($_GET["id_pessoa"]))
				$arKeys[] = $_GET["id_pessoa"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_pessoa->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_pessoa->setDbValue($rs->fields('id_pessoa'));
		$this->nome_pessoa->setDbValue($rs->fields('nome_pessoa'));
		$this->sobrenome_pessoa->setDbValue($rs->fields('sobrenome_pessoa'));
		$this->nascimento->setDbValue($rs->fields('nascimento'));
		$this->telefone->setDbValue($rs->fields('telefone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->celular->setDbValue($rs->fields('celular'));
		$this->CPF->setDbValue($rs->fields('CPF'));
		$this->RG->setDbValue($rs->fields('RG'));
		$this->id_endereco->setDbValue($rs->fields('id_endereco'));
		$this->endereco_numero->setDbValue($rs->fields('endereco_numero'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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
		$this->nascimento->ViewValue = ew_FormatDateTime($this->nascimento->ViewValue, 0);
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

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_pessoa
		$this->id_pessoa->EditAttrs["class"] = "form-control";
		$this->id_pessoa->EditCustomAttributes = "";
		$this->id_pessoa->EditValue = $this->id_pessoa->CurrentValue;
		$this->id_pessoa->ViewCustomAttributes = "";

		// nome_pessoa
		$this->nome_pessoa->EditAttrs["class"] = "form-control";
		$this->nome_pessoa->EditCustomAttributes = "";
		$this->nome_pessoa->EditValue = $this->nome_pessoa->CurrentValue;
		$this->nome_pessoa->PlaceHolder = ew_RemoveHtml($this->nome_pessoa->FldCaption());

		// sobrenome_pessoa
		$this->sobrenome_pessoa->EditAttrs["class"] = "form-control";
		$this->sobrenome_pessoa->EditCustomAttributes = "";
		$this->sobrenome_pessoa->EditValue = $this->sobrenome_pessoa->CurrentValue;
		$this->sobrenome_pessoa->PlaceHolder = ew_RemoveHtml($this->sobrenome_pessoa->FldCaption());

		// nascimento
		$this->nascimento->EditAttrs["class"] = "form-control";
		$this->nascimento->EditCustomAttributes = "";
		$this->nascimento->EditValue = ew_FormatDateTime($this->nascimento->CurrentValue, 8);
		$this->nascimento->PlaceHolder = ew_RemoveHtml($this->nascimento->FldCaption());

		// telefone
		$this->telefone->EditAttrs["class"] = "form-control";
		$this->telefone->EditCustomAttributes = "";
		$this->telefone->EditValue = $this->telefone->CurrentValue;
		$this->telefone->PlaceHolder = ew_RemoveHtml($this->telefone->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// celular
		$this->celular->EditAttrs["class"] = "form-control";
		$this->celular->EditCustomAttributes = "";
		$this->celular->EditValue = $this->celular->CurrentValue;
		$this->celular->PlaceHolder = ew_RemoveHtml($this->celular->FldCaption());

		// CPF
		$this->CPF->EditAttrs["class"] = "form-control";
		$this->CPF->EditCustomAttributes = "";
		$this->CPF->EditValue = $this->CPF->CurrentValue;
		$this->CPF->PlaceHolder = ew_RemoveHtml($this->CPF->FldCaption());

		// RG
		$this->RG->EditAttrs["class"] = "form-control";
		$this->RG->EditCustomAttributes = "";
		$this->RG->EditValue = $this->RG->CurrentValue;
		$this->RG->PlaceHolder = ew_RemoveHtml($this->RG->FldCaption());

		// id_endereco
		$this->id_endereco->EditAttrs["class"] = "form-control";
		$this->id_endereco->EditCustomAttributes = "";

		// endereco_numero
		$this->endereco_numero->EditAttrs["class"] = "form-control";
		$this->endereco_numero->EditCustomAttributes = "";
		$this->endereco_numero->EditValue = $this->endereco_numero->CurrentValue;
		$this->endereco_numero->PlaceHolder = ew_RemoveHtml($this->endereco_numero->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->nome_pessoa->Exportable) $Doc->ExportCaption($this->nome_pessoa);
					if ($this->sobrenome_pessoa->Exportable) $Doc->ExportCaption($this->sobrenome_pessoa);
					if ($this->nascimento->Exportable) $Doc->ExportCaption($this->nascimento);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->celular->Exportable) $Doc->ExportCaption($this->celular);
					if ($this->CPF->Exportable) $Doc->ExportCaption($this->CPF);
					if ($this->RG->Exportable) $Doc->ExportCaption($this->RG);
					if ($this->id_endereco->Exportable) $Doc->ExportCaption($this->id_endereco);
					if ($this->endereco_numero->Exportable) $Doc->ExportCaption($this->endereco_numero);
				} else {
					if ($this->id_pessoa->Exportable) $Doc->ExportCaption($this->id_pessoa);
					if ($this->nome_pessoa->Exportable) $Doc->ExportCaption($this->nome_pessoa);
					if ($this->sobrenome_pessoa->Exportable) $Doc->ExportCaption($this->sobrenome_pessoa);
					if ($this->nascimento->Exportable) $Doc->ExportCaption($this->nascimento);
					if ($this->telefone->Exportable) $Doc->ExportCaption($this->telefone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->celular->Exportable) $Doc->ExportCaption($this->celular);
					if ($this->CPF->Exportable) $Doc->ExportCaption($this->CPF);
					if ($this->RG->Exportable) $Doc->ExportCaption($this->RG);
					if ($this->id_endereco->Exportable) $Doc->ExportCaption($this->id_endereco);
					if ($this->endereco_numero->Exportable) $Doc->ExportCaption($this->endereco_numero);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->nome_pessoa->Exportable) $Doc->ExportField($this->nome_pessoa);
						if ($this->sobrenome_pessoa->Exportable) $Doc->ExportField($this->sobrenome_pessoa);
						if ($this->nascimento->Exportable) $Doc->ExportField($this->nascimento);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->celular->Exportable) $Doc->ExportField($this->celular);
						if ($this->CPF->Exportable) $Doc->ExportField($this->CPF);
						if ($this->RG->Exportable) $Doc->ExportField($this->RG);
						if ($this->id_endereco->Exportable) $Doc->ExportField($this->id_endereco);
						if ($this->endereco_numero->Exportable) $Doc->ExportField($this->endereco_numero);
					} else {
						if ($this->id_pessoa->Exportable) $Doc->ExportField($this->id_pessoa);
						if ($this->nome_pessoa->Exportable) $Doc->ExportField($this->nome_pessoa);
						if ($this->sobrenome_pessoa->Exportable) $Doc->ExportField($this->sobrenome_pessoa);
						if ($this->nascimento->Exportable) $Doc->ExportField($this->nascimento);
						if ($this->telefone->Exportable) $Doc->ExportField($this->telefone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->celular->Exportable) $Doc->ExportField($this->celular);
						if ($this->CPF->Exportable) $Doc->ExportField($this->CPF);
						if ($this->RG->Exportable) $Doc->ExportField($this->RG);
						if ($this->id_endereco->Exportable) $Doc->ExportField($this->id_endereco);
						if ($this->endereco_numero->Exportable) $Doc->ExportField($this->endereco_numero);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
