<?php

// Global variable for table object
$pedidos = NULL;

//
// Table class for pedidos
//
class crpedidos extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id_pedidos;
	var $numero;
	var $fecha_data;
	var $fecha_hora;
	var $id_fornecedor;
	var $id_transportadora;
	var $id_prazos;
	var $comentarios;
	var $id_representante;
	var $comissao_representante;
	var $tipo_pedido;
	var $id_cliente;
	var $status;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $grLanguage;
		$this->TableVar = 'pedidos';
		$this->TableName = 'pedidos';
		$this->TableType = 'TABLE';
		$this->TableReportType = 'rpt';
		$this->SourcTableIsCustomView = FALSE;
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// id_pedidos
		$this->id_pedidos = new crField('pedidos', 'pedidos', 'x_id_pedidos', 'id_pedidos', '`id_pedidos`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_pedidos->Sortable = TRUE; // Allow sort
		$this->id_pedidos->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_pedidos->DateFilter = "";
		$this->id_pedidos->SqlSelect = "";
		$this->id_pedidos->SqlOrderBy = "";
		$this->fields['id_pedidos'] = &$this->id_pedidos;

		// numero
		$this->numero = new crField('pedidos', 'pedidos', 'x_numero', 'numero', '`numero`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->numero->Sortable = TRUE; // Allow sort
		$this->numero->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->numero->DateFilter = "";
		$this->numero->SqlSelect = "";
		$this->numero->SqlOrderBy = "";
		$this->fields['numero'] = &$this->numero;

		// fecha_data
		$this->fecha_data = new crField('pedidos', 'pedidos', 'x_fecha_data', 'fecha_data', '`fecha_data`', 133, EWR_DATATYPE_DATE, 0);
		$this->fecha_data->Sortable = TRUE; // Allow sort
		$this->fecha_data->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fecha_data->DateFilter = "";
		$this->fecha_data->SqlSelect = "";
		$this->fecha_data->SqlOrderBy = "";
		$this->fields['fecha_data'] = &$this->fecha_data;

		// fecha_hora
		$this->fecha_hora = new crField('pedidos', 'pedidos', 'x_fecha_hora', 'fecha_hora', '`fecha_hora`', 134, EWR_DATATYPE_TIME, 4);
		$this->fecha_hora->Sortable = TRUE; // Allow sort
		$this->fecha_hora->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectTime");
		$this->fecha_hora->DateFilter = "";
		$this->fecha_hora->SqlSelect = "";
		$this->fecha_hora->SqlOrderBy = "";
		$this->fields['fecha_hora'] = &$this->fecha_hora;

		// id_fornecedor
		$this->id_fornecedor = new crField('pedidos', 'pedidos', 'x_id_fornecedor', 'id_fornecedor', '`id_fornecedor`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_fornecedor->Sortable = TRUE; // Allow sort
		$this->id_fornecedor->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_fornecedor->DateFilter = "";
		$this->id_fornecedor->SqlSelect = "";
		$this->id_fornecedor->SqlOrderBy = "";
		$this->fields['id_fornecedor'] = &$this->id_fornecedor;

		// id_transportadora
		$this->id_transportadora = new crField('pedidos', 'pedidos', 'x_id_transportadora', 'id_transportadora', '`id_transportadora`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_transportadora->Sortable = TRUE; // Allow sort
		$this->id_transportadora->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_transportadora->DateFilter = "";
		$this->id_transportadora->SqlSelect = "";
		$this->id_transportadora->SqlOrderBy = "";
		$this->fields['id_transportadora'] = &$this->id_transportadora;

		// id_prazos
		$this->id_prazos = new crField('pedidos', 'pedidos', 'x_id_prazos', 'id_prazos', '`id_prazos`', 200, EWR_DATATYPE_STRING, -1);
		$this->id_prazos->Sortable = TRUE; // Allow sort
		$this->id_prazos->DateFilter = "";
		$this->id_prazos->SqlSelect = "";
		$this->id_prazos->SqlOrderBy = "";
		$this->fields['id_prazos'] = &$this->id_prazos;

		// comentarios
		$this->comentarios = new crField('pedidos', 'pedidos', 'x_comentarios', 'comentarios', '`comentarios`', 200, EWR_DATATYPE_STRING, -1);
		$this->comentarios->Sortable = TRUE; // Allow sort
		$this->comentarios->DateFilter = "";
		$this->comentarios->SqlSelect = "";
		$this->comentarios->SqlOrderBy = "";
		$this->fields['comentarios'] = &$this->comentarios;

		// id_representante
		$this->id_representante = new crField('pedidos', 'pedidos', 'x_id_representante', 'id_representante', '`id_representante`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_representante->Sortable = TRUE; // Allow sort
		$this->id_representante->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_representante->DateFilter = "";
		$this->id_representante->SqlSelect = "";
		$this->id_representante->SqlOrderBy = "";
		$this->fields['id_representante'] = &$this->id_representante;

		// comissao_representante
		$this->comissao_representante = new crField('pedidos', 'pedidos', 'x_comissao_representante', 'comissao_representante', '`comissao_representante`', 200, EWR_DATATYPE_STRING, -1);
		$this->comissao_representante->Sortable = TRUE; // Allow sort
		$this->comissao_representante->DateFilter = "";
		$this->comissao_representante->SqlSelect = "";
		$this->comissao_representante->SqlOrderBy = "";
		$this->fields['comissao_representante'] = &$this->comissao_representante;

		// tipo_pedido
		$this->tipo_pedido = new crField('pedidos', 'pedidos', 'x_tipo_pedido', 'tipo_pedido', '`tipo_pedido`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipo_pedido->Sortable = TRUE; // Allow sort
		$this->tipo_pedido->DateFilter = "";
		$this->tipo_pedido->SqlSelect = "";
		$this->tipo_pedido->SqlOrderBy = "";
		$this->fields['tipo_pedido'] = &$this->tipo_pedido;

		// id_cliente
		$this->id_cliente = new crField('pedidos', 'pedidos', 'x_id_cliente', 'id_cliente', '`id_cliente`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id_cliente->Sortable = TRUE; // Allow sort
		$this->id_cliente->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->id_cliente->DateFilter = "";
		$this->id_cliente->SqlSelect = "";
		$this->id_cliente->SqlOrderBy = "";
		$this->fields['id_cliente'] = &$this->id_cliente;

		// status
		$this->status = new crField('pedidos', 'pedidos', 'x_status', 'status', '`status`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->status->DateFilter = "";
		$this->status->SqlSelect = "";
		$this->status->SqlOrderBy = "";
		$this->fields['status'] = &$this->status;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
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
			if ($ofld->GroupingFieldId == 0)
				$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pedidos`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		global $grDashboardReport;
		return "";
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $grLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $grLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

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

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
