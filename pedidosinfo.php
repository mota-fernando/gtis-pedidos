<?php

// Global variable for table object
$pedidos = NULL;

//
// Table class for pedidos
//
class cpedidos extends cTable {
	var $id_pedidos;
	var $tipo_pedido;
	var $numero;
	var $fecha_data;
	var $fecha_hora;
	var $id_fornecedor;
	var $id_transportadora;
	var $id_prazos;
	var $comentarios;
	var $id_representante;
	var $comissao_representante;
	var $id_cliente;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pedidos';
		$this->TableName = 'pedidos';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`pedidos`";
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

		// id_pedidos
		$this->id_pedidos = new cField('pedidos', 'pedidos', 'x_id_pedidos', 'id_pedidos', '`id_pedidos`', '`id_pedidos`', 3, -1, FALSE, '`id_pedidos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id_pedidos->Sortable = TRUE; // Allow sort
		$this->id_pedidos->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_pedidos'] = &$this->id_pedidos;

		// tipo_pedido
		$this->tipo_pedido = new cField('pedidos', 'pedidos', 'x_tipo_pedido', 'tipo_pedido', '`tipo_pedido`', '`tipo_pedido`', 200, -1, FALSE, '`tipo_pedido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipo_pedido->Sortable = TRUE; // Allow sort
		$this->tipo_pedido->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipo_pedido->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->tipo_pedido->OptionCount = 2;
		$this->fields['tipo_pedido'] = &$this->tipo_pedido;

		// numero
		$this->numero = new cField('pedidos', 'pedidos', 'x_numero', 'numero', '`numero`', '`numero`', 3, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->numero->Sortable = TRUE; // Allow sort
		$this->numero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['numero'] = &$this->numero;

		// fecha_data
		$this->fecha_data = new cField('pedidos', 'pedidos', 'x_fecha_data', 'fecha_data', '`fecha_data`', ew_CastDateFieldForLike('`fecha_data`', 0, "DB"), 133, 0, FALSE, '`fecha_data`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_data->Sortable = TRUE; // Allow sort
		$this->fecha_data->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_data'] = &$this->fecha_data;

		// fecha_hora
		$this->fecha_hora = new cField('pedidos', 'pedidos', 'x_fecha_hora', 'fecha_hora', '`fecha_hora`', ew_CastDateFieldForLike('`fecha_hora`', 4, "DB"), 134, 4, FALSE, '`fecha_hora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_hora->Sortable = TRUE; // Allow sort
		$this->fecha_hora->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['fecha_hora'] = &$this->fecha_hora;

		// id_fornecedor
		$this->id_fornecedor = new cField('pedidos', 'pedidos', 'x_id_fornecedor', 'id_fornecedor', '`id_fornecedor`', '`id_fornecedor`', 3, -1, FALSE, '`id_fornecedor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_fornecedor->Sortable = TRUE; // Allow sort
		$this->id_fornecedor->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_fornecedor->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_fornecedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_fornecedor'] = &$this->id_fornecedor;

		// id_transportadora
		$this->id_transportadora = new cField('pedidos', 'pedidos', 'x_id_transportadora', 'id_transportadora', '`id_transportadora`', '`id_transportadora`', 3, -1, FALSE, '`id_transportadora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_transportadora->Sortable = TRUE; // Allow sort
		$this->id_transportadora->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_transportadora->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_transportadora'] = &$this->id_transportadora;

		// id_prazos
		$this->id_prazos = new cField('pedidos', 'pedidos', 'x_id_prazos', 'id_prazos', '`id_prazos`', '`id_prazos`', 200, -1, FALSE, '`id_prazos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_prazos->Sortable = TRUE; // Allow sort
		$this->id_prazos->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_prazos->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_prazos'] = &$this->id_prazos;

		// comentarios
		$this->comentarios = new cField('pedidos', 'pedidos', 'x_comentarios', 'comentarios', '`comentarios`', '`comentarios`', 200, -1, FALSE, '`comentarios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->comentarios->Sortable = TRUE; // Allow sort
		$this->fields['comentarios'] = &$this->comentarios;

		// id_representante
		$this->id_representante = new cField('pedidos', 'pedidos', 'x_id_representante', 'id_representante', '`id_representante`', '`id_representante`', 3, -1, FALSE, '`id_representante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_representante->Sortable = TRUE; // Allow sort
		$this->id_representante->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_representante->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_representante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_representante'] = &$this->id_representante;

		// comissao_representante
		$this->comissao_representante = new cField('pedidos', 'pedidos', 'x_comissao_representante', 'comissao_representante', '`comissao_representante`', '`comissao_representante`', 200, -1, FALSE, '`comissao_representante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->comissao_representante->Sortable = TRUE; // Allow sort
		$this->comissao_representante->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->comissao_representante->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->comissao_representante->OptionCount = 2;
		$this->fields['comissao_representante'] = &$this->comissao_representante;

		// id_cliente
		$this->id_cliente = new cField('pedidos', 'pedidos', 'x_id_cliente', 'id_cliente', '`id_cliente`', '`id_cliente`', 3, -1, FALSE, '`id_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_cliente->Sortable = TRUE; // Allow sort
		$this->id_cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_cliente'] = &$this->id_cliente;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "detalhe_pedido") {
			$sDetailUrl = $GLOBALS["detalhe_pedido"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_numero=" . urlencode($this->numero->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "pedidoslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pedidos`";
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
			$this->id_pedidos->setDbValue($conn->Insert_ID());
			$rs['id_pedidos'] = $this->id_pedidos->DbValue;
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

		// Cascade Update detail table 'detalhe_pedido'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['numero']) && $rsold['numero'] <> $rs['numero'])) { // Update detail field 'numero_pedido'
			$bCascadeUpdate = TRUE;
			$rscascade['numero_pedido'] = $rs['numero']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["detalhe_pedido"])) $GLOBALS["detalhe_pedido"] = new cdetalhe_pedido();
			$rswrk = $GLOBALS["detalhe_pedido"]->LoadRs("`numero_pedido` = " . ew_QuotedValue($rsold['numero'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$rskey = array();
				$fldname = 'id_detalhe';
				$rskey[$fldname] = $rswrk->fields[$fldname];
				$rsdtlold = &$rswrk->fields;
				$rsdtlnew = array_merge($rsdtlold, $rscascade);

				// Call Row_Updating event
				$bUpdate = $GLOBALS["detalhe_pedido"]->Row_Updating($rsdtlold, $rsdtlnew);
				if ($bUpdate)
					$bUpdate = $GLOBALS["detalhe_pedido"]->Update($rscascade, $rskey, $rswrk->fields);
				if (!$bUpdate) return FALSE;

				// Call Row_Updated event
				$GLOBALS["detalhe_pedido"]->Row_Updated($rsdtlold, $rsdtlnew);
				$rswrk->MoveNext();
			}
		}
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id_pedidos', $rs))
				ew_AddFilter($where, ew_QuotedName('id_pedidos', $this->DBID) . '=' . ew_QuotedValue($rs['id_pedidos'], $this->id_pedidos->FldDataType, $this->DBID));
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

		// Cascade delete detail table 'detalhe_pedido'
		if (!isset($GLOBALS["detalhe_pedido"])) $GLOBALS["detalhe_pedido"] = new cdetalhe_pedido();
		$rscascade = $GLOBALS["detalhe_pedido"]->LoadRs("`numero_pedido` = " . ew_QuotedValue($rs['numero'], EW_DATATYPE_NUMBER, "DB")); 
		$dtlrows = ($rscascade) ? $rscascade->GetRows() : array();

		// Call Row Deleting event
		foreach ($dtlrows as $dtlrow) {
			$bDelete = $GLOBALS["detalhe_pedido"]->Row_Deleting($dtlrow);
			if (!$bDelete) break;
		}
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$bDelete = $GLOBALS["detalhe_pedido"]->Delete($dtlrow); // Delete
				if ($bDelete === FALSE)
					break;
			}
		}

		// Call Row Deleted event
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$GLOBALS["detalhe_pedido"]->Row_Deleted($dtlrow);
			}
		}
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_pedidos` = @id_pedidos@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_pedidos->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_pedidos->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_pedidos@", ew_AdjustSql($this->id_pedidos->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "pedidoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "pedidosview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "pedidosedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "pedidosadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "pedidoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pedidosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pedidosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "pedidosadd.php?" . $this->UrlParm($parm);
		else
			$url = "pedidosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pedidosedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pedidosedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pedidosadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pedidosadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pedidosdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_pedidos:" . ew_VarToJson($this->id_pedidos->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_pedidos->CurrentValue)) {
			$sUrl .= "id_pedidos=" . urlencode($this->id_pedidos->CurrentValue);
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
			if ($isPost && isset($_POST["id_pedidos"]))
				$arKeys[] = $_POST["id_pedidos"];
			elseif (isset($_GET["id_pedidos"]))
				$arKeys[] = $_GET["id_pedidos"];
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
			$this->id_pedidos->CurrentValue = $key;
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
		$this->id_pedidos->setDbValue($rs->fields('id_pedidos'));
		$this->tipo_pedido->setDbValue($rs->fields('tipo_pedido'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->fecha_data->setDbValue($rs->fields('fecha_data'));
		$this->fecha_hora->setDbValue($rs->fields('fecha_hora'));
		$this->id_fornecedor->setDbValue($rs->fields('id_fornecedor'));
		$this->id_transportadora->setDbValue($rs->fields('id_transportadora'));
		$this->id_prazos->setDbValue($rs->fields('id_prazos'));
		$this->comentarios->setDbValue($rs->fields('comentarios'));
		$this->id_representante->setDbValue($rs->fields('id_representante'));
		$this->comissao_representante->setDbValue($rs->fields('comissao_representante'));
		$this->id_cliente->setDbValue($rs->fields('id_cliente'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		// id_pedidos
		$this->id_pedidos->EditAttrs["class"] = "form-control";
		$this->id_pedidos->EditCustomAttributes = "";
		$this->id_pedidos->EditValue = $this->id_pedidos->CurrentValue;
		$this->id_pedidos->ViewCustomAttributes = "";

		// tipo_pedido
		$this->tipo_pedido->EditAttrs["class"] = "form-control";
		$this->tipo_pedido->EditCustomAttributes = "";
		$this->tipo_pedido->EditValue = $this->tipo_pedido->Options(TRUE);

		// numero
		$this->numero->EditAttrs["class"] = "form-control";
		$this->numero->EditCustomAttributes = "";
		$this->numero->EditValue = $this->numero->CurrentValue;
		$this->numero->PlaceHolder = ew_RemoveHtml($this->numero->FldCaption());

		// fecha_data
		// fecha_hora
		// id_fornecedor

		$this->id_fornecedor->EditAttrs["class"] = "form-control";
		$this->id_fornecedor->EditCustomAttributes = "";

		// id_transportadora
		$this->id_transportadora->EditAttrs["class"] = "form-control";
		$this->id_transportadora->EditCustomAttributes = "";

		// id_prazos
		$this->id_prazos->EditAttrs["class"] = "form-control";
		$this->id_prazos->EditCustomAttributes = "";

		// comentarios
		$this->comentarios->EditAttrs["class"] = "form-control";
		$this->comentarios->EditCustomAttributes = "";
		$this->comentarios->EditValue = $this->comentarios->CurrentValue;
		$this->comentarios->PlaceHolder = ew_RemoveHtml($this->comentarios->FldCaption());

		// id_representante
		$this->id_representante->EditAttrs["class"] = "form-control";
		$this->id_representante->EditCustomAttributes = "";

		// comissao_representante
		$this->comissao_representante->EditAttrs["class"] = "form-control";
		$this->comissao_representante->EditCustomAttributes = "";
		$this->comissao_representante->EditValue = $this->comissao_representante->Options(TRUE);

		// id_cliente
		$this->id_cliente->EditAttrs["class"] = "form-control";
		$this->id_cliente->EditCustomAttributes = "";
		$this->id_cliente->EditValue = $this->id_cliente->CurrentValue;
		$this->id_cliente->PlaceHolder = ew_RemoveHtml($this->id_cliente->FldCaption());

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
					if ($this->tipo_pedido->Exportable) $Doc->ExportCaption($this->tipo_pedido);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->id_fornecedor->Exportable) $Doc->ExportCaption($this->id_fornecedor);
					if ($this->id_transportadora->Exportable) $Doc->ExportCaption($this->id_transportadora);
					if ($this->id_prazos->Exportable) $Doc->ExportCaption($this->id_prazos);
					if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
					if ($this->id_representante->Exportable) $Doc->ExportCaption($this->id_representante);
					if ($this->comissao_representante->Exportable) $Doc->ExportCaption($this->comissao_representante);
					if ($this->id_cliente->Exportable) $Doc->ExportCaption($this->id_cliente);
				} else {
					if ($this->id_pedidos->Exportable) $Doc->ExportCaption($this->id_pedidos);
					if ($this->tipo_pedido->Exportable) $Doc->ExportCaption($this->tipo_pedido);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->fecha_data->Exportable) $Doc->ExportCaption($this->fecha_data);
					if ($this->fecha_hora->Exportable) $Doc->ExportCaption($this->fecha_hora);
					if ($this->id_fornecedor->Exportable) $Doc->ExportCaption($this->id_fornecedor);
					if ($this->id_transportadora->Exportable) $Doc->ExportCaption($this->id_transportadora);
					if ($this->id_prazos->Exportable) $Doc->ExportCaption($this->id_prazos);
					if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
					if ($this->id_representante->Exportable) $Doc->ExportCaption($this->id_representante);
					if ($this->comissao_representante->Exportable) $Doc->ExportCaption($this->comissao_representante);
					if ($this->id_cliente->Exportable) $Doc->ExportCaption($this->id_cliente);
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
						if ($this->tipo_pedido->Exportable) $Doc->ExportField($this->tipo_pedido);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->id_fornecedor->Exportable) $Doc->ExportField($this->id_fornecedor);
						if ($this->id_transportadora->Exportable) $Doc->ExportField($this->id_transportadora);
						if ($this->id_prazos->Exportable) $Doc->ExportField($this->id_prazos);
						if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
						if ($this->id_representante->Exportable) $Doc->ExportField($this->id_representante);
						if ($this->comissao_representante->Exportable) $Doc->ExportField($this->comissao_representante);
						if ($this->id_cliente->Exportable) $Doc->ExportField($this->id_cliente);
					} else {
						if ($this->id_pedidos->Exportable) $Doc->ExportField($this->id_pedidos);
						if ($this->tipo_pedido->Exportable) $Doc->ExportField($this->tipo_pedido);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->fecha_data->Exportable) $Doc->ExportField($this->fecha_data);
						if ($this->fecha_hora->Exportable) $Doc->ExportField($this->fecha_hora);
						if ($this->id_fornecedor->Exportable) $Doc->ExportField($this->id_fornecedor);
						if ($this->id_transportadora->Exportable) $Doc->ExportField($this->id_transportadora);
						if ($this->id_prazos->Exportable) $Doc->ExportField($this->id_prazos);
						if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
						if ($this->id_representante->Exportable) $Doc->ExportField($this->id_representante);
						if ($this->comissao_representante->Exportable) $Doc->ExportField($this->comissao_representante);
						if ($this->id_cliente->Exportable) $Doc->ExportField($this->id_cliente);
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
