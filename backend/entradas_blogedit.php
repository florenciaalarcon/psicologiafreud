<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "entradas_bloginfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$entradas_blog_edit = NULL; // Initialize page object first

class centradas_blog_edit extends centradas_blog {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}";

	// Table name
	var $TableName = 'entradas_blog';

	// Page object name
	var $PageObjName = 'entradas_blog_edit';

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

		// Table object (entradas_blog)
		if (!isset($GLOBALS["entradas_blog"]) || get_class($GLOBALS["entradas_blog"]) == "centradas_blog") {
			$GLOBALS["entradas_blog"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["entradas_blog"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'entradas_blog', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("entradas_bloglist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idCategoria->SetVisibility();
		$this->titulo->SetVisibility();
		$this->descripcion->SetVisibility();
		$this->imagenPrincipal->SetVisibility();
		$this->contenido->SetVisibility();
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
		global $EW_EXPORT, $entradas_blog;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($entradas_blog);
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
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("entradas_bloglist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("entradas_bloglist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "entradas_bloglist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->imagenPrincipal->Upload->Index = $objForm->Index;
		$this->imagenPrincipal->Upload->UploadFile();
		$this->imagenPrincipal->CurrentValue = $this->imagenPrincipal->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->idCategoria->FldIsDetailKey) {
			$this->idCategoria->setFormValue($objForm->GetValue("x_idCategoria"));
		}
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->contenido->FldIsDetailKey) {
			$this->contenido->setFormValue($objForm->GetValue("x_contenido"));
		}
		if (!$this->fechaCreacion->FldIsDetailKey) {
			$this->fechaCreacion->setFormValue($objForm->GetValue("x_fechaCreacion"));
			$this->fechaCreacion->CurrentValue = ew_UnFormatDateTime($this->fechaCreacion->CurrentValue, 0);
		}
		if (!$this->usuarioCreacion->FldIsDetailKey) {
			$this->usuarioCreacion->setFormValue($objForm->GetValue("x_usuarioCreacion"));
		}
		if (!$this->fechaModificacion->FldIsDetailKey) {
			$this->fechaModificacion->setFormValue($objForm->GetValue("x_fechaModificacion"));
			$this->fechaModificacion->CurrentValue = ew_UnFormatDateTime($this->fechaModificacion->CurrentValue, 0);
		}
		if (!$this->usuarioModificacion->FldIsDetailKey) {
			$this->usuarioModificacion->setFormValue($objForm->GetValue("x_usuarioModificacion"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idCategoria->CurrentValue = $this->idCategoria->FormValue;
		$this->titulo->CurrentValue = $this->titulo->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->contenido->CurrentValue = $this->contenido->FormValue;
		$this->fechaCreacion->CurrentValue = $this->fechaCreacion->FormValue;
		$this->fechaCreacion->CurrentValue = ew_UnFormatDateTime($this->fechaCreacion->CurrentValue, 0);
		$this->usuarioCreacion->CurrentValue = $this->usuarioCreacion->FormValue;
		$this->fechaModificacion->CurrentValue = $this->fechaModificacion->FormValue;
		$this->fechaModificacion->CurrentValue = ew_UnFormatDateTime($this->fechaModificacion->CurrentValue, 0);
		$this->usuarioModificacion->CurrentValue = $this->usuarioModificacion->FormValue;
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
		$this->idCategoria->setDbValue($rs->fields('idCategoria'));
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
		$this->idCategoria->DbValue = $row['idCategoria'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idCategoria
		// titulo
		// descripcion
		// imagenPrincipal
		// contenido
		// fechaCreacion
		// usuarioCreacion
		// fechaModificacion
		// usuarioModificacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idCategoria
		if (strval($this->idCategoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias_blog`";
		$sWhereWrk = "";
		$this->idCategoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCategoria->ViewValue = $this->idCategoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCategoria->ViewValue = $this->idCategoria->CurrentValue;
			}
		} else {
			$this->idCategoria->ViewValue = NULL;
		}
		$this->idCategoria->ViewCustomAttributes = "";

		// titulo
		$this->titulo->ViewValue = $this->titulo->CurrentValue;
		$this->titulo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// imagenPrincipal
		if (!ew_Empty($this->imagenPrincipal->Upload->DbValue)) {
			$this->imagenPrincipal->ViewValue = $this->imagenPrincipal->Upload->DbValue;
		} else {
			$this->imagenPrincipal->ViewValue = "";
		}
		$this->imagenPrincipal->ViewCustomAttributes = "";

		// contenido
		$this->contenido->ViewValue = $this->contenido->CurrentValue;
		$this->contenido->ViewCustomAttributes = "";

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

			// idCategoria
			$this->idCategoria->LinkCustomAttributes = "";
			$this->idCategoria->HrefValue = "";
			$this->idCategoria->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// imagenPrincipal
			$this->imagenPrincipal->LinkCustomAttributes = "";
			$this->imagenPrincipal->HrefValue = "";
			$this->imagenPrincipal->HrefValue2 = $this->imagenPrincipal->UploadPath . $this->imagenPrincipal->Upload->DbValue;
			$this->imagenPrincipal->TooltipValue = "";

			// contenido
			$this->contenido->LinkCustomAttributes = "";
			$this->contenido->HrefValue = "";
			$this->contenido->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idCategoria
			$this->idCategoria->EditAttrs["class"] = "form-control";
			$this->idCategoria->EditCustomAttributes = "";
			if (trim(strval($this->idCategoria->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categorias_blog`";
			$sWhereWrk = "";
			$this->idCategoria->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idCategoria->EditValue = $arwrk;

			// titulo
			$this->titulo->EditAttrs["class"] = "form-control";
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);
			$this->titulo->PlaceHolder = ew_RemoveHtml($this->titulo->FldCaption());

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// imagenPrincipal
			$this->imagenPrincipal->EditAttrs["class"] = "form-control";
			$this->imagenPrincipal->EditCustomAttributes = "";
			if (!ew_Empty($this->imagenPrincipal->Upload->DbValue)) {
				$this->imagenPrincipal->EditValue = $this->imagenPrincipal->Upload->DbValue;
			} else {
				$this->imagenPrincipal->EditValue = "";
			}
			if (!ew_Empty($this->imagenPrincipal->CurrentValue))
				$this->imagenPrincipal->Upload->FileName = $this->imagenPrincipal->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->imagenPrincipal);

			// contenido
			$this->contenido->EditAttrs["class"] = "form-control";
			$this->contenido->EditCustomAttributes = "";
			$this->contenido->EditValue = ew_HtmlEncode($this->contenido->CurrentValue);
			$this->contenido->PlaceHolder = ew_RemoveHtml($this->contenido->FldCaption());

			// fechaCreacion
			$this->fechaCreacion->EditAttrs["class"] = "form-control";
			$this->fechaCreacion->EditCustomAttributes = 'data-visible="false"';
			$this->fechaCreacion->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaCreacion->CurrentValue, 8));
			$this->fechaCreacion->PlaceHolder = ew_RemoveHtml($this->fechaCreacion->FldCaption());

			// usuarioCreacion
			$this->usuarioCreacion->EditAttrs["class"] = "form-control";
			$this->usuarioCreacion->EditCustomAttributes = 'data-visible="false"';
			$this->usuarioCreacion->EditValue = ew_HtmlEncode($this->usuarioCreacion->CurrentValue);
			$this->usuarioCreacion->PlaceHolder = ew_RemoveHtml($this->usuarioCreacion->FldCaption());

			// fechaModificacion
			// usuarioModificacion
			// Edit refer script
			// idCategoria

			$this->idCategoria->LinkCustomAttributes = "";
			$this->idCategoria->HrefValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";

			// imagenPrincipal
			$this->imagenPrincipal->LinkCustomAttributes = "";
			$this->imagenPrincipal->HrefValue = "";
			$this->imagenPrincipal->HrefValue2 = $this->imagenPrincipal->UploadPath . $this->imagenPrincipal->Upload->DbValue;

			// contenido
			$this->contenido->LinkCustomAttributes = "";
			$this->contenido->HrefValue = "";

			// fechaCreacion
			$this->fechaCreacion->LinkCustomAttributes = "";
			$this->fechaCreacion->HrefValue = "";

			// usuarioCreacion
			$this->usuarioCreacion->LinkCustomAttributes = "";
			$this->usuarioCreacion->HrefValue = "";

			// fechaModificacion
			$this->fechaModificacion->LinkCustomAttributes = "";
			$this->fechaModificacion->HrefValue = "";

			// usuarioModificacion
			$this->usuarioModificacion->LinkCustomAttributes = "";
			$this->usuarioModificacion->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

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
		if (!$this->idCategoria->FldIsDetailKey && !is_null($this->idCategoria->FormValue) && $this->idCategoria->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idCategoria->FldCaption(), $this->idCategoria->ReqErrMsg));
		}
		if (!$this->titulo->FldIsDetailKey && !is_null($this->titulo->FormValue) && $this->titulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->titulo->FldCaption(), $this->titulo->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->fechaCreacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaCreacion->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// idCategoria
			$this->idCategoria->SetDbValueDef($rsnew, $this->idCategoria->CurrentValue, 0, $this->idCategoria->ReadOnly);

			// titulo
			$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, "", $this->titulo->ReadOnly);

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, $this->descripcion->ReadOnly);

			// imagenPrincipal
			if ($this->imagenPrincipal->Visible && !$this->imagenPrincipal->ReadOnly && !$this->imagenPrincipal->Upload->KeepFile) {
				$this->imagenPrincipal->Upload->DbValue = $rsold['imagenPrincipal']; // Get original value
				if ($this->imagenPrincipal->Upload->FileName == "") {
					$rsnew['imagenPrincipal'] = NULL;
				} else {
					$rsnew['imagenPrincipal'] = $this->imagenPrincipal->Upload->FileName;
				}
			}

			// contenido
			$this->contenido->SetDbValueDef($rsnew, $this->contenido->CurrentValue, NULL, $this->contenido->ReadOnly);

			// fechaCreacion
			$this->fechaCreacion->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaCreacion->CurrentValue, 0), NULL, $this->fechaCreacion->ReadOnly);

			// usuarioCreacion
			$this->usuarioCreacion->SetDbValueDef($rsnew, $this->usuarioCreacion->CurrentValue, NULL, $this->usuarioCreacion->ReadOnly);

			// fechaModificacion
			$this->fechaModificacion->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['fechaModificacion'] = &$this->fechaModificacion->DbValue;

			// usuarioModificacion
			$this->usuarioModificacion->SetDbValueDef($rsnew, CurrentUserName(), NULL);
			$rsnew['usuarioModificacion'] = &$this->usuarioModificacion->DbValue;
			if ($this->imagenPrincipal->Visible && !$this->imagenPrincipal->Upload->KeepFile) {
				if (!ew_Empty($this->imagenPrincipal->Upload->Value)) {
					if ($this->imagenPrincipal->Upload->FileName == $this->imagenPrincipal->Upload->DbValue) { // Overwrite if same file name
						$this->imagenPrincipal->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['imagenPrincipal'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagenPrincipal->UploadPath), $rsnew['imagenPrincipal']); // Get new file name
					}
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->imagenPrincipal->Visible && !$this->imagenPrincipal->Upload->KeepFile) {
						if (!ew_Empty($this->imagenPrincipal->Upload->Value)) {
							$this->imagenPrincipal->Upload->SaveToFile($this->imagenPrincipal->UploadPath, $rsnew['imagenPrincipal'], TRUE);
						}
						if ($this->imagenPrincipal->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->imagenPrincipal->OldUploadPath) . $this->imagenPrincipal->Upload->DbValue);
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// imagenPrincipal
		ew_CleanUploadTempPath($this->imagenPrincipal, $this->imagenPrincipal->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("entradas_bloglist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_idCategoria":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias_blog`";
			$sWhereWrk = "";
			$this->idCategoria->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
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
if (!isset($entradas_blog_edit)) $entradas_blog_edit = new centradas_blog_edit();

// Page init
$entradas_blog_edit->Page_Init();

// Page main
$entradas_blog_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$entradas_blog_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fentradas_blogedit = new ew_Form("fentradas_blogedit", "edit");

// Validate form
fentradas_blogedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idCategoria");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $entradas_blog->idCategoria->FldCaption(), $entradas_blog->idCategoria->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_titulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $entradas_blog->titulo->FldCaption(), $entradas_blog->titulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaCreacion");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($entradas_blog->fechaCreacion->FldErrMsg()) ?>");

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
fentradas_blogedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentradas_blogedit.ValidateRequired = true;
<?php } else { ?>
fentradas_blogedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fentradas_blogedit.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias_blog"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$entradas_blog_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $entradas_blog_edit->ShowPageHeader(); ?>
<?php
$entradas_blog_edit->ShowMessage();
?>
<form name="fentradas_blogedit" id="fentradas_blogedit" class="<?php echo $entradas_blog_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($entradas_blog_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $entradas_blog_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="entradas_blog">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($entradas_blog_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($entradas_blog->idCategoria->Visible) { // idCategoria ?>
	<div id="r_idCategoria" class="form-group">
		<label id="elh_entradas_blog_idCategoria" for="x_idCategoria" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->idCategoria->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->idCategoria->CellAttributes() ?>>
<span id="el_entradas_blog_idCategoria">
<select data-table="entradas_blog" data-field="x_idCategoria" data-value-separator="<?php echo $entradas_blog->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x_idCategoria" name="x_idCategoria"<?php echo $entradas_blog->idCategoria->EditAttributes() ?>>
<?php echo $entradas_blog->idCategoria->SelectOptionListHtml("x_idCategoria") ?>
</select>
<input type="hidden" name="s_x_idCategoria" id="s_x_idCategoria" value="<?php echo $entradas_blog->idCategoria->LookupFilterQuery() ?>">
</span>
<?php echo $entradas_blog->idCategoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($entradas_blog->titulo->Visible) { // titulo ?>
	<div id="r_titulo" class="form-group">
		<label id="elh_entradas_blog_titulo" for="x_titulo" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->titulo->CellAttributes() ?>>
<span id="el_entradas_blog_titulo">
<input type="text" data-table="entradas_blog" data-field="x_titulo" name="x_titulo" id="x_titulo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($entradas_blog->titulo->getPlaceHolder()) ?>" value="<?php echo $entradas_blog->titulo->EditValue ?>"<?php echo $entradas_blog->titulo->EditAttributes() ?>>
</span>
<?php echo $entradas_blog->titulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($entradas_blog->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_entradas_blog_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->descripcion->CellAttributes() ?>>
<span id="el_entradas_blog_descripcion">
<textarea data-table="entradas_blog" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($entradas_blog->descripcion->getPlaceHolder()) ?>"<?php echo $entradas_blog->descripcion->EditAttributes() ?>><?php echo $entradas_blog->descripcion->EditValue ?></textarea>
</span>
<?php echo $entradas_blog->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($entradas_blog->imagenPrincipal->Visible) { // imagenPrincipal ?>
	<div id="r_imagenPrincipal" class="form-group">
		<label id="elh_entradas_blog_imagenPrincipal" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->imagenPrincipal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->imagenPrincipal->CellAttributes() ?>>
<span id="el_entradas_blog_imagenPrincipal">
<div id="fd_x_imagenPrincipal">
<span title="<?php echo $entradas_blog->imagenPrincipal->FldTitle() ? $entradas_blog->imagenPrincipal->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($entradas_blog->imagenPrincipal->ReadOnly || $entradas_blog->imagenPrincipal->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="entradas_blog" data-field="x_imagenPrincipal" name="x_imagenPrincipal" id="x_imagenPrincipal"<?php echo $entradas_blog->imagenPrincipal->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_imagenPrincipal" id= "fn_x_imagenPrincipal" value="<?php echo $entradas_blog->imagenPrincipal->Upload->FileName ?>">
<?php if (@$_POST["fa_x_imagenPrincipal"] == "0") { ?>
<input type="hidden" name="fa_x_imagenPrincipal" id= "fa_x_imagenPrincipal" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_imagenPrincipal" id= "fa_x_imagenPrincipal" value="1">
<?php } ?>
<input type="hidden" name="fs_x_imagenPrincipal" id= "fs_x_imagenPrincipal" value="255">
<input type="hidden" name="fx_x_imagenPrincipal" id= "fx_x_imagenPrincipal" value="<?php echo $entradas_blog->imagenPrincipal->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_imagenPrincipal" id= "fm_x_imagenPrincipal" value="<?php echo $entradas_blog->imagenPrincipal->UploadMaxFileSize ?>">
</div>
<table id="ft_x_imagenPrincipal" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $entradas_blog->imagenPrincipal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($entradas_blog->contenido->Visible) { // contenido ?>
	<div id="r_contenido" class="form-group">
		<label id="elh_entradas_blog_contenido" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->contenido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->contenido->CellAttributes() ?>>
<span id="el_entradas_blog_contenido">
<?php ew_AppendClass($entradas_blog->contenido->EditAttrs["class"], "editor"); ?>
<textarea data-table="entradas_blog" data-field="x_contenido" name="x_contenido" id="x_contenido" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($entradas_blog->contenido->getPlaceHolder()) ?>"<?php echo $entradas_blog->contenido->EditAttributes() ?>><?php echo $entradas_blog->contenido->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fentradas_blogedit", "x_contenido", 35, 4, <?php echo ($entradas_blog->contenido->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $entradas_blog->contenido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($entradas_blog->fechaCreacion->Visible) { // fechaCreacion ?>
	<div id="r_fechaCreacion" class="form-group">
		<label id="elh_entradas_blog_fechaCreacion" for="x_fechaCreacion" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->fechaCreacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->fechaCreacion->CellAttributes() ?>>
<span id="el_entradas_blog_fechaCreacion">
<input type="text" data-table="entradas_blog" data-field="x_fechaCreacion" name="x_fechaCreacion" id="x_fechaCreacion" placeholder="<?php echo ew_HtmlEncode($entradas_blog->fechaCreacion->getPlaceHolder()) ?>" value="<?php echo $entradas_blog->fechaCreacion->EditValue ?>"<?php echo $entradas_blog->fechaCreacion->EditAttributes() ?>>
</span>
<?php echo $entradas_blog->fechaCreacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($entradas_blog->usuarioCreacion->Visible) { // usuarioCreacion ?>
	<div id="r_usuarioCreacion" class="form-group">
		<label id="elh_entradas_blog_usuarioCreacion" for="x_usuarioCreacion" class="col-sm-2 control-label ewLabel"><?php echo $entradas_blog->usuarioCreacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $entradas_blog->usuarioCreacion->CellAttributes() ?>>
<span id="el_entradas_blog_usuarioCreacion">
<input type="text" data-table="entradas_blog" data-field="x_usuarioCreacion" name="x_usuarioCreacion" id="x_usuarioCreacion" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($entradas_blog->usuarioCreacion->getPlaceHolder()) ?>" value="<?php echo $entradas_blog->usuarioCreacion->EditValue ?>"<?php echo $entradas_blog->usuarioCreacion->EditAttributes() ?>>
</span>
<?php echo $entradas_blog->usuarioCreacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="entradas_blog" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($entradas_blog->id->CurrentValue) ?>">
<?php if (!$entradas_blog_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $entradas_blog_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fentradas_blogedit.Init();
</script>
<?php
$entradas_blog_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$entradas_blog_edit->Page_Terminate();
?>
