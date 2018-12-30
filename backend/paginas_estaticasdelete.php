<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "paginas_estaticasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$paginas_estaticas_delete = NULL; // Initialize page object first

class cpaginas_estaticas_delete extends cpaginas_estaticas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}";

	// Table name
	var $TableName = 'paginas_estaticas';

	// Page object name
	var $PageObjName = 'paginas_estaticas_delete';

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
		if (!$this->CheckToken || !ew_IsHttpPost())
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
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (paginas_estaticas)
		if (!isset($GLOBALS["paginas_estaticas"]) || get_class($GLOBALS["paginas_estaticas"]) == "cpaginas_estaticas") {
			$GLOBALS["paginas_estaticas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["paginas_estaticas"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'paginas_estaticas', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("paginas_estaticaslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idMenu->SetVisibility();
		$this->orden->SetVisibility();
		$this->titulo->SetVisibility();
		$this->imagenPrincipal->SetVisibility();
		$this->fechaCreacion->SetVisibility();
		$this->usuarioCreacion->SetVisibility();
		$this->fechaModificacion->SetVisibility();
		$this->usuarioModificacion->SetVisibility();

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
		global $EW_EXPORT, $paginas_estaticas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($paginas_estaticas);
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
			$this->Page_Terminate("paginas_estaticaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in paginas_estaticas class, paginas_estaticasinfo.php

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
				$this->Page_Terminate("paginas_estaticaslist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
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
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->idMenu->setDbValue($rs->fields('idMenu'));
		$this->orden->setDbValue($rs->fields('orden'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->imagenPrincipal->Upload->DbValue = $rs->fields('imagenPrincipal');
		$this->imagenPrincipal->CurrentValue = $this->imagenPrincipal->Upload->DbValue;
		$this->contenido->setDbValue($rs->fields('contenido'));
		$this->fechaCreacion->setDbValue($rs->fields('fechaCreacion'));
		$this->usuarioCreacion->setDbValue($rs->fields('usuarioCreacion'));
		$this->fechaModificacion->setDbValue($rs->fields('fechaModificacion'));
		$this->usuarioModificacion->setDbValue($rs->fields('usuarioModificacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idMenu->DbValue = $row['idMenu'];
		$this->orden->DbValue = $row['orden'];
		$this->titulo->DbValue = $row['titulo'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->imagenPrincipal->Upload->DbValue = $row['imagenPrincipal'];
		$this->contenido->DbValue = $row['contenido'];
		$this->fechaCreacion->DbValue = $row['fechaCreacion'];
		$this->usuarioCreacion->DbValue = $row['usuarioCreacion'];
		$this->fechaModificacion->DbValue = $row['fechaModificacion'];
		$this->usuarioModificacion->DbValue = $row['usuarioModificacion'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->orden->FormValue == $this->orden->CurrentValue && is_numeric(ew_StrToFloat($this->orden->CurrentValue)))
			$this->orden->CurrentValue = ew_StrToFloat($this->orden->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// idMenu
		// orden
		// titulo
		// descripcion
		// imagenPrincipal
		// contenido
		// fechaCreacion
		// usuarioCreacion
		// fechaModificacion
		// usuarioModificacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idMenu
		if (strval($this->idMenu->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMenu->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `menu`";
		$sWhereWrk = "";
		$this->idMenu->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idMenu, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `orden`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idMenu->ViewValue = $this->idMenu->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idMenu->ViewValue = $this->idMenu->CurrentValue;
			}
		} else {
			$this->idMenu->ViewValue = NULL;
		}
		$this->idMenu->ViewCustomAttributes = "";

		// orden
		$this->orden->ViewValue = $this->orden->CurrentValue;
		$this->orden->ViewCustomAttributes = "";

		// titulo
		$this->titulo->ViewValue = $this->titulo->CurrentValue;
		$this->titulo->ViewCustomAttributes = "";

		// imagenPrincipal
		if (!ew_Empty($this->imagenPrincipal->Upload->DbValue)) {
			$this->imagenPrincipal->ViewValue = $this->imagenPrincipal->Upload->DbValue;
		} else {
			$this->imagenPrincipal->ViewValue = "";
		}
		$this->imagenPrincipal->ViewCustomAttributes = "";

		// fechaCreacion
		$this->fechaCreacion->ViewValue = $this->fechaCreacion->CurrentValue;
		$this->fechaCreacion->ViewValue = ew_FormatDateTime($this->fechaCreacion->ViewValue, 0);
		$this->fechaCreacion->ViewCustomAttributes = "";

		// usuarioCreacion
		$this->usuarioCreacion->ViewValue = $this->usuarioCreacion->CurrentValue;
		$this->usuarioCreacion->ViewCustomAttributes = "";

		// fechaModificacion
		$this->fechaModificacion->ViewValue = $this->fechaModificacion->CurrentValue;
		$this->fechaModificacion->ViewValue = ew_FormatDateTime($this->fechaModificacion->ViewValue, 0);
		$this->fechaModificacion->ViewCustomAttributes = "";

		// usuarioModificacion
		$this->usuarioModificacion->ViewValue = $this->usuarioModificacion->CurrentValue;
		$this->usuarioModificacion->ViewCustomAttributes = "";

			// idMenu
			$this->idMenu->LinkCustomAttributes = "";
			$this->idMenu->HrefValue = "";
			$this->idMenu->TooltipValue = "";

			// orden
			$this->orden->LinkCustomAttributes = "";
			$this->orden->HrefValue = "";
			$this->orden->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// imagenPrincipal
			$this->imagenPrincipal->LinkCustomAttributes = "";
			$this->imagenPrincipal->HrefValue = "";
			$this->imagenPrincipal->HrefValue2 = $this->imagenPrincipal->UploadPath . $this->imagenPrincipal->Upload->DbValue;
			$this->imagenPrincipal->TooltipValue = "";

			// fechaCreacion
			$this->fechaCreacion->LinkCustomAttributes = "";
			$this->fechaCreacion->HrefValue = "";
			$this->fechaCreacion->TooltipValue = "";

			// usuarioCreacion
			$this->usuarioCreacion->LinkCustomAttributes = "";
			$this->usuarioCreacion->HrefValue = "";
			$this->usuarioCreacion->TooltipValue = "";

			// fechaModificacion
			$this->fechaModificacion->LinkCustomAttributes = "";
			$this->fechaModificacion->HrefValue = "";
			$this->fechaModificacion->TooltipValue = "";

			// usuarioModificacion
			$this->usuarioModificacion->LinkCustomAttributes = "";
			$this->usuarioModificacion->HrefValue = "";
			$this->usuarioModificacion->TooltipValue = "";
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
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
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

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

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
				$sThisKey .= $row['id'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->imagenPrincipal->OldUploadPath) . $row['imagenPrincipal']);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("paginas_estaticaslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($paginas_estaticas_delete)) $paginas_estaticas_delete = new cpaginas_estaticas_delete();

// Page init
$paginas_estaticas_delete->Page_Init();

// Page main
$paginas_estaticas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$paginas_estaticas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpaginas_estaticasdelete = new ew_Form("fpaginas_estaticasdelete", "delete");

// Form_CustomValidate event
fpaginas_estaticasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpaginas_estaticasdelete.ValidateRequired = true;
<?php } else { ?>
fpaginas_estaticasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpaginas_estaticasdelete.Lists["x_idMenu"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"_menu"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $paginas_estaticas_delete->ShowPageHeader(); ?>
<?php
$paginas_estaticas_delete->ShowMessage();
?>
<form name="fpaginas_estaticasdelete" id="fpaginas_estaticasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($paginas_estaticas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $paginas_estaticas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="paginas_estaticas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($paginas_estaticas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $paginas_estaticas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($paginas_estaticas->idMenu->Visible) { // idMenu ?>
		<th><span id="elh_paginas_estaticas_idMenu" class="paginas_estaticas_idMenu"><?php echo $paginas_estaticas->idMenu->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->orden->Visible) { // orden ?>
		<th><span id="elh_paginas_estaticas_orden" class="paginas_estaticas_orden"><?php echo $paginas_estaticas->orden->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->titulo->Visible) { // titulo ?>
		<th><span id="elh_paginas_estaticas_titulo" class="paginas_estaticas_titulo"><?php echo $paginas_estaticas->titulo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->imagenPrincipal->Visible) { // imagenPrincipal ?>
		<th><span id="elh_paginas_estaticas_imagenPrincipal" class="paginas_estaticas_imagenPrincipal"><?php echo $paginas_estaticas->imagenPrincipal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->fechaCreacion->Visible) { // fechaCreacion ?>
		<th><span id="elh_paginas_estaticas_fechaCreacion" class="paginas_estaticas_fechaCreacion"><?php echo $paginas_estaticas->fechaCreacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->usuarioCreacion->Visible) { // usuarioCreacion ?>
		<th><span id="elh_paginas_estaticas_usuarioCreacion" class="paginas_estaticas_usuarioCreacion"><?php echo $paginas_estaticas->usuarioCreacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->fechaModificacion->Visible) { // fechaModificacion ?>
		<th><span id="elh_paginas_estaticas_fechaModificacion" class="paginas_estaticas_fechaModificacion"><?php echo $paginas_estaticas->fechaModificacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($paginas_estaticas->usuarioModificacion->Visible) { // usuarioModificacion ?>
		<th><span id="elh_paginas_estaticas_usuarioModificacion" class="paginas_estaticas_usuarioModificacion"><?php echo $paginas_estaticas->usuarioModificacion->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$paginas_estaticas_delete->RecCnt = 0;
$i = 0;
while (!$paginas_estaticas_delete->Recordset->EOF) {
	$paginas_estaticas_delete->RecCnt++;
	$paginas_estaticas_delete->RowCnt++;

	// Set row properties
	$paginas_estaticas->ResetAttrs();
	$paginas_estaticas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$paginas_estaticas_delete->LoadRowValues($paginas_estaticas_delete->Recordset);

	// Render row
	$paginas_estaticas_delete->RenderRow();
?>
	<tr<?php echo $paginas_estaticas->RowAttributes() ?>>
<?php if ($paginas_estaticas->idMenu->Visible) { // idMenu ?>
		<td<?php echo $paginas_estaticas->idMenu->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_idMenu" class="paginas_estaticas_idMenu">
<span<?php echo $paginas_estaticas->idMenu->ViewAttributes() ?>>
<?php echo $paginas_estaticas->idMenu->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->orden->Visible) { // orden ?>
		<td<?php echo $paginas_estaticas->orden->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_orden" class="paginas_estaticas_orden">
<span<?php echo $paginas_estaticas->orden->ViewAttributes() ?>>
<?php echo $paginas_estaticas->orden->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->titulo->Visible) { // titulo ?>
		<td<?php echo $paginas_estaticas->titulo->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_titulo" class="paginas_estaticas_titulo">
<span<?php echo $paginas_estaticas->titulo->ViewAttributes() ?>>
<?php echo $paginas_estaticas->titulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->imagenPrincipal->Visible) { // imagenPrincipal ?>
		<td<?php echo $paginas_estaticas->imagenPrincipal->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_imagenPrincipal" class="paginas_estaticas_imagenPrincipal">
<span<?php echo $paginas_estaticas->imagenPrincipal->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($paginas_estaticas->imagenPrincipal, $paginas_estaticas->imagenPrincipal->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->fechaCreacion->Visible) { // fechaCreacion ?>
		<td<?php echo $paginas_estaticas->fechaCreacion->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_fechaCreacion" class="paginas_estaticas_fechaCreacion">
<span<?php echo $paginas_estaticas->fechaCreacion->ViewAttributes() ?>>
<?php echo $paginas_estaticas->fechaCreacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->usuarioCreacion->Visible) { // usuarioCreacion ?>
		<td<?php echo $paginas_estaticas->usuarioCreacion->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_usuarioCreacion" class="paginas_estaticas_usuarioCreacion">
<span<?php echo $paginas_estaticas->usuarioCreacion->ViewAttributes() ?>>
<?php echo $paginas_estaticas->usuarioCreacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->fechaModificacion->Visible) { // fechaModificacion ?>
		<td<?php echo $paginas_estaticas->fechaModificacion->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_fechaModificacion" class="paginas_estaticas_fechaModificacion">
<span<?php echo $paginas_estaticas->fechaModificacion->ViewAttributes() ?>>
<?php echo $paginas_estaticas->fechaModificacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($paginas_estaticas->usuarioModificacion->Visible) { // usuarioModificacion ?>
		<td<?php echo $paginas_estaticas->usuarioModificacion->CellAttributes() ?>>
<span id="el<?php echo $paginas_estaticas_delete->RowCnt ?>_paginas_estaticas_usuarioModificacion" class="paginas_estaticas_usuarioModificacion">
<span<?php echo $paginas_estaticas->usuarioModificacion->ViewAttributes() ?>>
<?php echo $paginas_estaticas->usuarioModificacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$paginas_estaticas_delete->Recordset->MoveNext();
}
$paginas_estaticas_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $paginas_estaticas_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpaginas_estaticasdelete.Init();
</script>
<?php
$paginas_estaticas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$paginas_estaticas_delete->Page_Terminate();
?>
