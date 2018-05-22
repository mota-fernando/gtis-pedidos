<?php

// Global variable for table object
$produtos = NULL;

//
// Table class for produtos
//
class cprodutos extends cTable {
	var $id_produto;
	var $codigo_produto;
	var $nome_produto;
	var $modelo_produto;
	var $id_departamento_produto;
	var $id_marca_produto;
	var $status_produto;
	var $unidade_medida_produto;
	var $peso_produto;
	var $data_adicionado;
	var $hora_adicionado;
	var $preco_produto;
	var $descricao;
	var $ipi;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'produtos';
		$this->TableName = 'produtos';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`produtos`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
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

		// id_produto
		$this->id_produto = new cField('produtos', 'produtos', 'x_id_produto', 'id_produto', '`id_produto`', '`id_produto`', 3, -1, FALSE, '`id_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id_produto->Sortable = TRUE; // Allow sort
		$this->id_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_produto'] = &$this->id_produto;

		// codigo_produto
		$this->codigo_produto = new cField('produtos', 'produtos', 'x_codigo_produto', 'codigo_produto', '`codigo_produto`', '`codigo_produto`', 3, -1, FALSE, '`codigo_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigo_produto->Sortable = TRUE; // Allow sort
		$this->codigo_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codigo_produto'] = &$this->codigo_produto;

		// nome_produto
		$this->nome_produto = new cField('produtos', 'produtos', 'x_nome_produto', 'nome_produto', '`nome_produto`', '`nome_produto`', 200, -1, FALSE, '`nome_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nome_produto->Sortable = TRUE; // Allow sort
		$this->fields['nome_produto'] = &$this->nome_produto;

		// modelo_produto
		$this->modelo_produto = new cField('produtos', 'produtos', 'x_modelo_produto', 'modelo_produto', '`modelo_produto`', '`modelo_produto`', 200, -1, FALSE, '`modelo_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modelo_produto->Sortable = TRUE; // Allow sort
		$this->fields['modelo_produto'] = &$this->modelo_produto;

		// id_departamento_produto
		$this->id_departamento_produto = new cField('produtos', 'produtos', 'x_id_departamento_produto', 'id_departamento_produto', '`id_departamento_produto`', '`id_departamento_produto`', 3, -1, FALSE, '`id_departamento_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_departamento_produto->Sortable = FALSE; // Allow sort
		$this->id_departamento_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_departamento_produto'] = &$this->id_departamento_produto;

		// id_marca_produto
		$this->id_marca_produto = new cField('produtos', 'produtos', 'x_id_marca_produto', 'id_marca_produto', '`id_marca_produto`', '`id_marca_produto`', 3, -1, FALSE, '`id_marca_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_marca_produto->Sortable = TRUE; // Allow sort
		$this->id_marca_produto->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_marca_produto->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_marca_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_marca_produto'] = &$this->id_marca_produto;

		// status_produto
		$this->status_produto = new cField('produtos', 'produtos', 'x_status_produto', 'status_produto', '`status_produto`', '`status_produto`', 3, -1, FALSE, '`status_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status_produto->Sortable = TRUE; // Allow sort
		$this->status_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status_produto'] = &$this->status_produto;

		// unidade_medida_produto
		$this->unidade_medida_produto = new cField('produtos', 'produtos', 'x_unidade_medida_produto', 'unidade_medida_produto', '`unidade_medida_produto`', '`unidade_medida_produto`', 200, -1, FALSE, '`unidade_medida_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unidade_medida_produto->Sortable = TRUE; // Allow sort
		$this->fields['unidade_medida_produto'] = &$this->unidade_medida_produto;

		// peso_produto
		$this->peso_produto = new cField('produtos', 'produtos', 'x_peso_produto', 'peso_produto', '`peso_produto`', '`peso_produto`', 200, -1, FALSE, '`peso_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->peso_produto->Sortable = TRUE; // Allow sort
		$this->fields['peso_produto'] = &$this->peso_produto;

		// data_adicionado
		$this->data_adicionado = new cField('produtos', 'produtos', 'x_data_adicionado', 'data_adicionado', '`data_adicionado`', ew_CastDateFieldForLike('`data_adicionado`', 0, "DB"), 133, 0, FALSE, '`data_adicionado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->data_adicionado->Sortable = TRUE; // Allow sort
		$this->data_adicionado->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['data_adicionado'] = &$this->data_adicionado;

		// hora_adicionado
		$this->hora_adicionado = new cField('produtos', 'produtos', 'x_hora_adicionado', 'hora_adicionado', '`hora_adicionado`', ew_CastDateFieldForLike('`hora_adicionado`', 4, "DB"), 134, 4, FALSE, '`hora_adicionado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hora_adicionado->Sortable = TRUE; // Allow sort
		$this->hora_adicionado->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['hora_adicionado'] = &$this->hora_adicionado;

		// preco_produto
		$this->preco_produto = new cField('produtos', 'produtos', 'x_preco_produto', 'preco_produto', '`preco_produto`', '`preco_produto`', 4, -1, FALSE, '`preco_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->preco_produto->Sortable = TRUE; // Allow sort
		$this->preco_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['preco_produto'] = &$this->preco_produto;

		// descricao
		$this->descricao = new cField('produtos', 'produtos', 'x_descricao', 'descricao', '`descricao`', '`descricao`', 200, -1, FALSE, '`descricao`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->descricao->Sortable = TRUE; // Allow sort
		$this->fields['descricao'] = &$this->descricao;

		// ipi
		$this->ipi = new cField('produtos', 'produtos', 'x_ipi', 'ipi', '`ipi`', '`ipi`', 3, -1, FALSE, '`ipi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ipi->Sortable = TRUE; // Allow sort
		$this->ipi->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ipi'] = &$this->ipi;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`produtos`";
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
			$this->id_produto->setDbValue($conn->Insert_ID());
			$rs['id_produto'] = $this->id_produto->DbValue;
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
			if (array_key_exists('id_produto', $rs))
				ew_AddFilter($where, ew_QuotedName('id_produto', $this->DBID) . '=' . ew_QuotedValue($rs['id_produto'], $this->id_produto->FldDataType, $this->DBID));
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
		return "`id_produto` = @id_produto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_produto->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_produto->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_produto@", ew_AdjustSql($this->id_produto->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "produtoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "produtosview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "produtosedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "produtosadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "produtoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("produtosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("produtosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "produtosadd.php?" . $this->UrlParm($parm);
		else
			$url = "produtosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("produtosedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("produtosadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("produtosdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_produto:" . ew_VarToJson($this->id_produto->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_produto->CurrentValue)) {
			$sUrl .= "id_produto=" . urlencode($this->id_produto->CurrentValue);
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
			if ($isPost && isset($_POST["id_produto"]))
				$arKeys[] = $_POST["id_produto"];
			elseif (isset($_GET["id_produto"]))
				$arKeys[] = $_GET["id_produto"];
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
			$this->id_produto->CurrentValue = $key;
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
		$this->id_produto->setDbValue($rs->fields('id_produto'));
		$this->codigo_produto->setDbValue($rs->fields('codigo_produto'));
		$this->nome_produto->setDbValue($rs->fields('nome_produto'));
		$this->modelo_produto->setDbValue($rs->fields('modelo_produto'));
		$this->id_departamento_produto->setDbValue($rs->fields('id_departamento_produto'));
		$this->id_marca_produto->setDbValue($rs->fields('id_marca_produto'));
		$this->status_produto->setDbValue($rs->fields('status_produto'));
		$this->unidade_medida_produto->setDbValue($rs->fields('unidade_medida_produto'));
		$this->peso_produto->setDbValue($rs->fields('peso_produto'));
		$this->data_adicionado->setDbValue($rs->fields('data_adicionado'));
		$this->hora_adicionado->setDbValue($rs->fields('hora_adicionado'));
		$this->preco_produto->setDbValue($rs->fields('preco_produto'));
		$this->descricao->setDbValue($rs->fields('descricao'));
		$this->ipi->setDbValue($rs->fields('ipi'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id_produto
		// codigo_produto
		// nome_produto
		// modelo_produto
		// id_departamento_produto

		$this->id_departamento_produto->CellCssStyle = "white-space: nowrap;";

		// id_marca_produto
		// status_produto
		// unidade_medida_produto
		// peso_produto
		// data_adicionado
		// hora_adicionado
		// preco_produto
		// descricao
		// ipi
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
		$this->id_departamento_produto->ViewValue = $this->id_departamento_produto->CurrentValue;
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
		$this->status_produto->ViewValue = $this->status_produto->CurrentValue;
		$this->status_produto->ViewCustomAttributes = "";

		// unidade_medida_produto
		$this->unidade_medida_produto->ViewValue = $this->unidade_medida_produto->CurrentValue;
		$this->unidade_medida_produto->ViewCustomAttributes = "";

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

		// status_produto
		$this->status_produto->LinkCustomAttributes = "";
		$this->status_produto->HrefValue = "";
		$this->status_produto->TooltipValue = "";

		// unidade_medida_produto
		$this->unidade_medida_produto->LinkCustomAttributes = "";
		$this->unidade_medida_produto->HrefValue = "";
		$this->unidade_medida_produto->TooltipValue = "";

		// peso_produto
		$this->peso_produto->LinkCustomAttributes = "";
		$this->peso_produto->HrefValue = "";
		$this->peso_produto->TooltipValue = "";

		// data_adicionado
		$this->data_adicionado->LinkCustomAttributes = "";
		$this->data_adicionado->HrefValue = "";
		$this->data_adicionado->TooltipValue = "";

		// hora_adicionado
		$this->hora_adicionado->LinkCustomAttributes = "";
		$this->hora_adicionado->HrefValue = "";
		$this->hora_adicionado->TooltipValue = "";

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

		// id_produto
		$this->id_produto->EditAttrs["class"] = "form-control";
		$this->id_produto->EditCustomAttributes = "";
		$this->id_produto->EditValue = $this->id_produto->CurrentValue;
		$this->id_produto->ViewCustomAttributes = "";

		// codigo_produto
		$this->codigo_produto->EditAttrs["class"] = "form-control";
		$this->codigo_produto->EditCustomAttributes = "";
		$this->codigo_produto->EditValue = $this->codigo_produto->CurrentValue;
		$this->codigo_produto->PlaceHolder = ew_RemoveHtml($this->codigo_produto->FldCaption());

		// nome_produto
		$this->nome_produto->EditAttrs["class"] = "form-control";
		$this->nome_produto->EditCustomAttributes = "";
		$this->nome_produto->EditValue = $this->nome_produto->CurrentValue;
		$this->nome_produto->PlaceHolder = ew_RemoveHtml($this->nome_produto->FldCaption());

		// modelo_produto
		$this->modelo_produto->EditAttrs["class"] = "form-control";
		$this->modelo_produto->EditCustomAttributes = "";
		$this->modelo_produto->EditValue = $this->modelo_produto->CurrentValue;
		$this->modelo_produto->PlaceHolder = ew_RemoveHtml($this->modelo_produto->FldCaption());

		// id_departamento_produto
		$this->id_departamento_produto->EditAttrs["class"] = "form-control";
		$this->id_departamento_produto->EditCustomAttributes = "";
		$this->id_departamento_produto->EditValue = $this->id_departamento_produto->CurrentValue;
		$this->id_departamento_produto->PlaceHolder = ew_RemoveHtml($this->id_departamento_produto->FldCaption());

		// id_marca_produto
		$this->id_marca_produto->EditAttrs["class"] = "form-control";
		$this->id_marca_produto->EditCustomAttributes = "";

		// status_produto
		$this->status_produto->EditAttrs["class"] = "form-control";
		$this->status_produto->EditCustomAttributes = "";
		$this->status_produto->EditValue = $this->status_produto->CurrentValue;
		$this->status_produto->PlaceHolder = ew_RemoveHtml($this->status_produto->FldCaption());

		// unidade_medida_produto
		$this->unidade_medida_produto->EditAttrs["class"] = "form-control";
		$this->unidade_medida_produto->EditCustomAttributes = "";
		$this->unidade_medida_produto->EditValue = $this->unidade_medida_produto->CurrentValue;
		$this->unidade_medida_produto->PlaceHolder = ew_RemoveHtml($this->unidade_medida_produto->FldCaption());

		// peso_produto
		$this->peso_produto->EditAttrs["class"] = "form-control";
		$this->peso_produto->EditCustomAttributes = "";
		$this->peso_produto->EditValue = $this->peso_produto->CurrentValue;
		$this->peso_produto->PlaceHolder = ew_RemoveHtml($this->peso_produto->FldCaption());

		// data_adicionado
		$this->data_adicionado->EditAttrs["class"] = "form-control";
		$this->data_adicionado->EditCustomAttributes = "";
		$this->data_adicionado->EditValue = ew_FormatDateTime($this->data_adicionado->CurrentValue, 8);
		$this->data_adicionado->PlaceHolder = ew_RemoveHtml($this->data_adicionado->FldCaption());

		// hora_adicionado
		$this->hora_adicionado->EditAttrs["class"] = "form-control";
		$this->hora_adicionado->EditCustomAttributes = "";
		$this->hora_adicionado->EditValue = $this->hora_adicionado->CurrentValue;
		$this->hora_adicionado->PlaceHolder = ew_RemoveHtml($this->hora_adicionado->FldCaption());

		// preco_produto
		$this->preco_produto->EditAttrs["class"] = "form-control";
		$this->preco_produto->EditCustomAttributes = "";
		$this->preco_produto->EditValue = $this->preco_produto->CurrentValue;
		$this->preco_produto->PlaceHolder = ew_RemoveHtml($this->preco_produto->FldCaption());
		if (strval($this->preco_produto->EditValue) <> "" && is_numeric($this->preco_produto->EditValue)) $this->preco_produto->EditValue = ew_FormatNumber($this->preco_produto->EditValue, -2, -1, -2, 0);

		// descricao
		$this->descricao->EditAttrs["class"] = "form-control";
		$this->descricao->EditCustomAttributes = "";
		$this->descricao->EditValue = $this->descricao->CurrentValue;
		$this->descricao->PlaceHolder = ew_RemoveHtml($this->descricao->FldCaption());

		// ipi
		$this->ipi->EditAttrs["class"] = "form-control";
		$this->ipi->EditCustomAttributes = "";
		$this->ipi->EditValue = $this->ipi->CurrentValue;
		$this->ipi->PlaceHolder = ew_RemoveHtml($this->ipi->FldCaption());

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
					if ($this->codigo_produto->Exportable) $Doc->ExportCaption($this->codigo_produto);
					if ($this->nome_produto->Exportable) $Doc->ExportCaption($this->nome_produto);
					if ($this->modelo_produto->Exportable) $Doc->ExportCaption($this->modelo_produto);
					if ($this->id_marca_produto->Exportable) $Doc->ExportCaption($this->id_marca_produto);
					if ($this->status_produto->Exportable) $Doc->ExportCaption($this->status_produto);
					if ($this->unidade_medida_produto->Exportable) $Doc->ExportCaption($this->unidade_medida_produto);
					if ($this->peso_produto->Exportable) $Doc->ExportCaption($this->peso_produto);
					if ($this->data_adicionado->Exportable) $Doc->ExportCaption($this->data_adicionado);
					if ($this->hora_adicionado->Exportable) $Doc->ExportCaption($this->hora_adicionado);
					if ($this->preco_produto->Exportable) $Doc->ExportCaption($this->preco_produto);
					if ($this->descricao->Exportable) $Doc->ExportCaption($this->descricao);
					if ($this->ipi->Exportable) $Doc->ExportCaption($this->ipi);
				} else {
					if ($this->id_produto->Exportable) $Doc->ExportCaption($this->id_produto);
					if ($this->codigo_produto->Exportable) $Doc->ExportCaption($this->codigo_produto);
					if ($this->nome_produto->Exportable) $Doc->ExportCaption($this->nome_produto);
					if ($this->modelo_produto->Exportable) $Doc->ExportCaption($this->modelo_produto);
					if ($this->id_marca_produto->Exportable) $Doc->ExportCaption($this->id_marca_produto);
					if ($this->status_produto->Exportable) $Doc->ExportCaption($this->status_produto);
					if ($this->unidade_medida_produto->Exportable) $Doc->ExportCaption($this->unidade_medida_produto);
					if ($this->peso_produto->Exportable) $Doc->ExportCaption($this->peso_produto);
					if ($this->data_adicionado->Exportable) $Doc->ExportCaption($this->data_adicionado);
					if ($this->hora_adicionado->Exportable) $Doc->ExportCaption($this->hora_adicionado);
					if ($this->preco_produto->Exportable) $Doc->ExportCaption($this->preco_produto);
					if ($this->descricao->Exportable) $Doc->ExportCaption($this->descricao);
					if ($this->ipi->Exportable) $Doc->ExportCaption($this->ipi);
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
						if ($this->codigo_produto->Exportable) $Doc->ExportField($this->codigo_produto);
						if ($this->nome_produto->Exportable) $Doc->ExportField($this->nome_produto);
						if ($this->modelo_produto->Exportable) $Doc->ExportField($this->modelo_produto);
						if ($this->id_marca_produto->Exportable) $Doc->ExportField($this->id_marca_produto);
						if ($this->status_produto->Exportable) $Doc->ExportField($this->status_produto);
						if ($this->unidade_medida_produto->Exportable) $Doc->ExportField($this->unidade_medida_produto);
						if ($this->peso_produto->Exportable) $Doc->ExportField($this->peso_produto);
						if ($this->data_adicionado->Exportable) $Doc->ExportField($this->data_adicionado);
						if ($this->hora_adicionado->Exportable) $Doc->ExportField($this->hora_adicionado);
						if ($this->preco_produto->Exportable) $Doc->ExportField($this->preco_produto);
						if ($this->descricao->Exportable) $Doc->ExportField($this->descricao);
						if ($this->ipi->Exportable) $Doc->ExportField($this->ipi);
					} else {
						if ($this->id_produto->Exportable) $Doc->ExportField($this->id_produto);
						if ($this->codigo_produto->Exportable) $Doc->ExportField($this->codigo_produto);
						if ($this->nome_produto->Exportable) $Doc->ExportField($this->nome_produto);
						if ($this->modelo_produto->Exportable) $Doc->ExportField($this->modelo_produto);
						if ($this->id_marca_produto->Exportable) $Doc->ExportField($this->id_marca_produto);
						if ($this->status_produto->Exportable) $Doc->ExportField($this->status_produto);
						if ($this->unidade_medida_produto->Exportable) $Doc->ExportField($this->unidade_medida_produto);
						if ($this->peso_produto->Exportable) $Doc->ExportField($this->peso_produto);
						if ($this->data_adicionado->Exportable) $Doc->ExportField($this->data_adicionado);
						if ($this->hora_adicionado->Exportable) $Doc->ExportField($this->hora_adicionado);
						if ($this->preco_produto->Exportable) $Doc->ExportField($this->preco_produto);
						if ($this->descricao->Exportable) $Doc->ExportField($this->descricao);
						if ($this->ipi->Exportable) $Doc->ExportField($this->ipi);
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
