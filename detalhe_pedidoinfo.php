<?php

// Global variable for table object
$detalhe_pedido = NULL;

//
// Table class for detalhe_pedido
//
class cdetalhe_pedido extends cTable {
	var $id_detalhe;
	var $numero_pedido;
	var $id_produto;
	var $desconto;
	var $preco;
	var $quantidade;
	var $subtotal;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'detalhe_pedido';
		$this->TableName = 'detalhe_pedido';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`detalhe_pedido`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_detalhe
		$this->id_detalhe = new cField('detalhe_pedido', 'detalhe_pedido', 'x_id_detalhe', 'id_detalhe', '`id_detalhe`', '`id_detalhe`', 3, -1, FALSE, '`id_detalhe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id_detalhe->Sortable = TRUE; // Allow sort
		$this->id_detalhe->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_detalhe'] = &$this->id_detalhe;

		// numero_pedido
		$this->numero_pedido = new cField('detalhe_pedido', 'detalhe_pedido', 'x_numero_pedido', 'numero_pedido', '`numero_pedido`', '`numero_pedido`', 3, -1, FALSE, '`numero_pedido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->numero_pedido->Sortable = TRUE; // Allow sort
		$this->numero_pedido->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero_pedido'] = &$this->numero_pedido;

		// id_produto
		$this->id_produto = new cField('detalhe_pedido', 'detalhe_pedido', 'x_id_produto', 'id_produto', '`id_produto`', '`id_produto`', 3, -1, FALSE, '`id_produto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_produto->Sortable = TRUE; // Allow sort
		$this->id_produto->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_produto->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_produto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_produto'] = &$this->id_produto;

		// desconto
		$this->desconto = new cField('detalhe_pedido', 'detalhe_pedido', 'x_desconto', 'desconto', '`desconto`', '`desconto`', 3, -1, FALSE, '`desconto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->desconto->Sortable = TRUE; // Allow sort
		$this->desconto->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->desconto->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->desconto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['desconto'] = &$this->desconto;

		// preco
		$this->preco = new cField('detalhe_pedido', 'detalhe_pedido', 'x_preco', 'preco', '`preco`', '`preco`', 4, -1, FALSE, '`preco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->preco->Sortable = TRUE; // Allow sort
		$this->preco->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->preco->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->preco->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['preco'] = &$this->preco;

		// quantidade
		$this->quantidade = new cField('detalhe_pedido', 'detalhe_pedido', 'x_quantidade', 'quantidade', '`quantidade`', '`quantidade`', 3, -1, FALSE, '`quantidade`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantidade->Sortable = TRUE; // Allow sort
		$this->quantidade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['quantidade'] = &$this->quantidade;

		// subtotal
		$this->subtotal = new cField('detalhe_pedido', 'detalhe_pedido', 'x_subtotal', 'subtotal', '`subtotal`', '`subtotal`', 4, -1, FALSE, '`subtotal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->subtotal->Sortable = TRUE; // Allow sort
		$this->subtotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['subtotal'] = &$this->subtotal;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "pedidos") {
			if ($this->numero_pedido->getSessionValue() <> "")
				$sMasterFilter .= "`numero`=" . ew_QuotedValue($this->numero_pedido->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "pedidos") {
			if ($this->numero_pedido->getSessionValue() <> "")
				$sDetailFilter .= "`numero_pedido`=" . ew_QuotedValue($this->numero_pedido->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_pedidos() {
		return "`numero`=@numero@";
	}

	// Detail filter
	function SqlDetailFilter_pedidos() {
		return "`numero_pedido`=@numero_pedido@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`detalhe_pedido`";
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
			$this->id_detalhe->setDbValue($conn->Insert_ID());
			$rs['id_detalhe'] = $this->id_detalhe->DbValue;
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
			if (array_key_exists('id_detalhe', $rs))
				ew_AddFilter($where, ew_QuotedName('id_detalhe', $this->DBID) . '=' . ew_QuotedValue($rs['id_detalhe'], $this->id_detalhe->FldDataType, $this->DBID));
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
		return "`id_detalhe` = @id_detalhe@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_detalhe->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_detalhe->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_detalhe@", ew_AdjustSql($this->id_detalhe->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "detalhe_pedidolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "detalhe_pedidoview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "detalhe_pedidoedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "detalhe_pedidoadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "detalhe_pedidolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("detalhe_pedidoview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("detalhe_pedidoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "detalhe_pedidoadd.php?" . $this->UrlParm($parm);
		else
			$url = "detalhe_pedidoadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("detalhe_pedidoedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("detalhe_pedidoadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("detalhe_pedidodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "pedidos" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_numero=" . urlencode($this->numero_pedido->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_detalhe:" . ew_VarToJson($this->id_detalhe->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_detalhe->CurrentValue)) {
			$sUrl .= "id_detalhe=" . urlencode($this->id_detalhe->CurrentValue);
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
			if ($isPost && isset($_POST["id_detalhe"]))
				$arKeys[] = $_POST["id_detalhe"];
			elseif (isset($_GET["id_detalhe"]))
				$arKeys[] = $_GET["id_detalhe"];
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
			$this->id_detalhe->CurrentValue = $key;
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
		$this->id_detalhe->setDbValue($rs->fields('id_detalhe'));
		$this->numero_pedido->setDbValue($rs->fields('numero_pedido'));
		$this->id_produto->setDbValue($rs->fields('id_produto'));
		$this->desconto->setDbValue($rs->fields('desconto'));
		$this->preco->setDbValue($rs->fields('preco'));
		$this->quantidade->setDbValue($rs->fields('quantidade'));
		$this->subtotal->setDbValue($rs->fields('subtotal'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id_detalhe
		// numero_pedido
		// id_produto
		// desconto
		// preco
		// quantidade
		// subtotal
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

		// id_detalhe
		$this->id_detalhe->EditAttrs["class"] = "form-control";
		$this->id_detalhe->EditCustomAttributes = "";
		$this->id_detalhe->EditValue = $this->id_detalhe->CurrentValue;
		$this->id_detalhe->ViewCustomAttributes = "";

		// numero_pedido
		$this->numero_pedido->EditAttrs["class"] = "form-control";
		$this->numero_pedido->EditCustomAttributes = "";
		if ($this->numero_pedido->getSessionValue() <> "") {
			$this->numero_pedido->CurrentValue = $this->numero_pedido->getSessionValue();
		$this->numero_pedido->ViewValue = $this->numero_pedido->CurrentValue;
		$this->numero_pedido->ViewCustomAttributes = "";
		} else {
		$this->numero_pedido->EditValue = $this->numero_pedido->CurrentValue;
		$this->numero_pedido->PlaceHolder = ew_RemoveHtml($this->numero_pedido->FldCaption());
		}

		// id_produto
		$this->id_produto->EditAttrs["class"] = "form-control";
		$this->id_produto->EditCustomAttributes = "";

		// desconto
		$this->desconto->EditAttrs["class"] = "form-control";
		$this->desconto->EditCustomAttributes = "";

		// preco
		$this->preco->EditAttrs["class"] = "form-control";
		$this->preco->EditCustomAttributes = "";

		// quantidade
		$this->quantidade->EditAttrs["class"] = "form-control";
		$this->quantidade->EditCustomAttributes = "";
		$this->quantidade->EditValue = $this->quantidade->CurrentValue;
		$this->quantidade->PlaceHolder = ew_RemoveHtml($this->quantidade->FldCaption());

		// subtotal
		$this->subtotal->EditAttrs["class"] = "form-control";
		$this->subtotal->EditCustomAttributes = "";
		$this->subtotal->EditValue = $this->subtotal->CurrentValue;
		$this->subtotal->PlaceHolder = ew_RemoveHtml($this->subtotal->FldCaption());
		if (strval($this->subtotal->EditValue) <> "" && is_numeric($this->subtotal->EditValue)) $this->subtotal->EditValue = ew_FormatNumber($this->subtotal->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->quantidade->CurrentValue))
				$this->quantidade->Total += $this->quantidade->CurrentValue; // Accumulate total
			if (is_numeric($this->subtotal->CurrentValue))
				$this->subtotal->Total += $this->subtotal->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->quantidade->CurrentValue = $this->quantidade->Total;
			$this->quantidade->ViewValue = $this->quantidade->CurrentValue;
			$this->quantidade->ViewCustomAttributes = "";
			$this->quantidade->HrefValue = ""; // Clear href value
			$this->subtotal->CurrentValue = $this->subtotal->Total;
			$this->subtotal->ViewValue = $this->subtotal->CurrentValue;
			$this->subtotal->ViewCustomAttributes = "";
			$this->subtotal->HrefValue = ""; // Clear href value

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
					if ($this->numero_pedido->Exportable) $Doc->ExportCaption($this->numero_pedido);
					if ($this->id_produto->Exportable) $Doc->ExportCaption($this->id_produto);
					if ($this->desconto->Exportable) $Doc->ExportCaption($this->desconto);
					if ($this->preco->Exportable) $Doc->ExportCaption($this->preco);
					if ($this->quantidade->Exportable) $Doc->ExportCaption($this->quantidade);
					if ($this->subtotal->Exportable) $Doc->ExportCaption($this->subtotal);
				} else {
					if ($this->id_detalhe->Exportable) $Doc->ExportCaption($this->id_detalhe);
					if ($this->numero_pedido->Exportable) $Doc->ExportCaption($this->numero_pedido);
					if ($this->id_produto->Exportable) $Doc->ExportCaption($this->id_produto);
					if ($this->desconto->Exportable) $Doc->ExportCaption($this->desconto);
					if ($this->preco->Exportable) $Doc->ExportCaption($this->preco);
					if ($this->quantidade->Exportable) $Doc->ExportCaption($this->quantidade);
					if ($this->subtotal->Exportable) $Doc->ExportCaption($this->subtotal);
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
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->numero_pedido->Exportable) $Doc->ExportField($this->numero_pedido);
						if ($this->id_produto->Exportable) $Doc->ExportField($this->id_produto);
						if ($this->desconto->Exportable) $Doc->ExportField($this->desconto);
						if ($this->preco->Exportable) $Doc->ExportField($this->preco);
						if ($this->quantidade->Exportable) $Doc->ExportField($this->quantidade);
						if ($this->subtotal->Exportable) $Doc->ExportField($this->subtotal);
					} else {
						if ($this->id_detalhe->Exportable) $Doc->ExportField($this->id_detalhe);
						if ($this->numero_pedido->Exportable) $Doc->ExportField($this->numero_pedido);
						if ($this->id_produto->Exportable) $Doc->ExportField($this->id_produto);
						if ($this->desconto->Exportable) $Doc->ExportField($this->desconto);
						if ($this->preco->Exportable) $Doc->ExportField($this->preco);
						if ($this->quantidade->Exportable) $Doc->ExportField($this->quantidade);
						if ($this->subtotal->Exportable) $Doc->ExportField($this->subtotal);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
				if ($this->id_detalhe->Exportable) $Doc->ExportAggregate($this->id_detalhe, '');
				if ($this->numero_pedido->Exportable) $Doc->ExportAggregate($this->numero_pedido, '');
				if ($this->id_produto->Exportable) $Doc->ExportAggregate($this->id_produto, '');
				if ($this->desconto->Exportable) $Doc->ExportAggregate($this->desconto, '');
				if ($this->preco->Exportable) $Doc->ExportAggregate($this->preco, '');
				if ($this->quantidade->Exportable) $Doc->ExportAggregate($this->quantidade, 'TOTAL');
				if ($this->subtotal->Exportable) $Doc->ExportAggregate($this->subtotal, 'TOTAL');
				$Doc->EndExportRow();
			}
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
