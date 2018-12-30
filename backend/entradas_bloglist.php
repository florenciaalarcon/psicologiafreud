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

$entradas_blog_list = NULL; // Initialize page object first

class centradas_blog_list extends centradas_blog {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}";

	// Table name
	var $TableName = 'entradas_blog';

	// Page object name
	var $PageObjName = 'entradas_blog_list';

	// Grid form hidden field names
	var $FormName = 'fentradas_bloglist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "entradas_blogadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "entradas_blogdelete.php";
		$this->MultiUpdateUrl = "entradas_blogupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fentradas_bloglistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->idCategoria->SetVisibility();
		$this->titulo->SetVisibility();
		$this->imagenPrincipal->SetVisibility();
		$this->fechaCreacion->SetVisibility();
		$this->usuarioCreacion->SetVisibility();
		$this->fechaModificacion->SetVisibility();
		$this->fechaModificacion->Visible = !$this->IsAddOrEdit();
		$this->usuarioModificacion->SetVisibility();
		$this->usuarioModificacion->Visible = !$this->IsAddOrEdit();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fentradas_bloglistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->idCategoria->AdvancedSearch->ToJSON(), ","); // Field idCategoria
		$sFilterList = ew_Concat($sFilterList, $this->titulo->AdvancedSearch->ToJSON(), ","); // Field titulo
		$sFilterList = ew_Concat($sFilterList, $this->descripcion->AdvancedSearch->ToJSON(), ","); // Field descripcion
		$sFilterList = ew_Concat($sFilterList, $this->imagenPrincipal->AdvancedSearch->ToJSON(), ","); // Field imagenPrincipal
		$sFilterList = ew_Concat($sFilterList, $this->contenido->AdvancedSearch->ToJSON(), ","); // Field contenido
		$sFilterList = ew_Concat($sFilterList, $this->fechaCreacion->AdvancedSearch->ToJSON(), ","); // Field fechaCreacion
		$sFilterList = ew_Concat($sFilterList, $this->usuarioCreacion->AdvancedSearch->ToJSON(), ","); // Field usuarioCreacion
		$sFilterList = ew_Concat($sFilterList, $this->fechaModificacion->AdvancedSearch->ToJSON(), ","); // Field fechaModificacion
		$sFilterList = ew_Concat($sFilterList, $this->usuarioModificacion->AdvancedSearch->ToJSON(), ","); // Field usuarioModificacion
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["cmd"] == "savefilters") {
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fentradas_bloglistsrch", $filters);
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field idCategoria
		$this->idCategoria->AdvancedSearch->SearchValue = @$filter["x_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchOperator = @$filter["z_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchCondition = @$filter["v_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchValue2 = @$filter["y_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchOperator2 = @$filter["w_idCategoria"];
		$this->idCategoria->AdvancedSearch->Save();

		// Field titulo
		$this->titulo->AdvancedSearch->SearchValue = @$filter["x_titulo"];
		$this->titulo->AdvancedSearch->SearchOperator = @$filter["z_titulo"];
		$this->titulo->AdvancedSearch->SearchCondition = @$filter["v_titulo"];
		$this->titulo->AdvancedSearch->SearchValue2 = @$filter["y_titulo"];
		$this->titulo->AdvancedSearch->SearchOperator2 = @$filter["w_titulo"];
		$this->titulo->AdvancedSearch->Save();

		// Field descripcion
		$this->descripcion->AdvancedSearch->SearchValue = @$filter["x_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator = @$filter["z_descripcion"];
		$this->descripcion->AdvancedSearch->SearchCondition = @$filter["v_descripcion"];
		$this->descripcion->AdvancedSearch->SearchValue2 = @$filter["y_descripcion"];
		$this->descripcion->AdvancedSearch->SearchOperator2 = @$filter["w_descripcion"];
		$this->descripcion->AdvancedSearch->Save();

		// Field imagenPrincipal
		$this->imagenPrincipal->AdvancedSearch->SearchValue = @$filter["x_imagenPrincipal"];
		$this->imagenPrincipal->AdvancedSearch->SearchOperator = @$filter["z_imagenPrincipal"];
		$this->imagenPrincipal->AdvancedSearch->SearchCondition = @$filter["v_imagenPrincipal"];
		$this->imagenPrincipal->AdvancedSearch->SearchValue2 = @$filter["y_imagenPrincipal"];
		$this->imagenPrincipal->AdvancedSearch->SearchOperator2 = @$filter["w_imagenPrincipal"];
		$this->imagenPrincipal->AdvancedSearch->Save();

		// Field contenido
		$this->contenido->AdvancedSearch->SearchValue = @$filter["x_contenido"];
		$this->contenido->AdvancedSearch->SearchOperator = @$filter["z_contenido"];
		$this->contenido->AdvancedSearch->SearchCondition = @$filter["v_contenido"];
		$this->contenido->AdvancedSearch->SearchValue2 = @$filter["y_contenido"];
		$this->contenido->AdvancedSearch->SearchOperator2 = @$filter["w_contenido"];
		$this->contenido->AdvancedSearch->Save();

		// Field fechaCreacion
		$this->fechaCreacion->AdvancedSearch->SearchValue = @$filter["x_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchOperator = @$filter["z_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchCondition = @$filter["v_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchValue2 = @$filter["y_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->SearchOperator2 = @$filter["w_fechaCreacion"];
		$this->fechaCreacion->AdvancedSearch->Save();

		// Field usuarioCreacion
		$this->usuarioCreacion->AdvancedSearch->SearchValue = @$filter["x_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchOperator = @$filter["z_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchCondition = @$filter["v_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchValue2 = @$filter["y_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioCreacion"];
		$this->usuarioCreacion->AdvancedSearch->Save();

		// Field fechaModificacion
		$this->fechaModificacion->AdvancedSearch->SearchValue = @$filter["x_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchOperator = @$filter["z_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchCondition = @$filter["v_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchValue2 = @$filter["y_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchOperator2 = @$filter["w_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->Save();

		// Field usuarioModificacion
		$this->usuarioModificacion->AdvancedSearch->SearchValue = @$filter["x_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchOperator = @$filter["z_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchCondition = @$filter["v_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchValue2 = @$filter["y_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->SearchOperator2 = @$filter["w_usuarioModificacion"];
		$this->usuarioModificacion->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->titulo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->descripcion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->imagenPrincipal, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contenido, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->usuarioCreacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->usuarioModificacion, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idCategoria); // idCategoria
			$this->UpdateSort($this->titulo); // titulo
			$this->UpdateSort($this->imagenPrincipal); // imagenPrincipal
			$this->UpdateSort($this->fechaCreacion); // fechaCreacion
			$this->UpdateSort($this->usuarioCreacion); // usuarioCreacion
			$this->UpdateSort($this->fechaModificacion); // fechaModificacion
			$this->UpdateSort($this->usuarioModificacion); // usuarioModificacion
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idCategoria->setSort("");
				$this->titulo->setSort("");
				$this->imagenPrincipal->setSort("");
				$this->fechaCreacion->setSort("");
				$this->usuarioCreacion->setSort("");
				$this->fechaModificacion->setSort("");
				$this->usuarioModificacion->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fentradas_bloglistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fentradas_bloglistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fentradas_bloglist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fentradas_bloglistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

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

			// idCategoria
			$this->idCategoria->LinkCustomAttributes = "";
			$this->idCategoria->HrefValue = "";
			$this->idCategoria->TooltipValue = "";

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($entradas_blog_list)) $entradas_blog_list = new centradas_blog_list();

// Page init
$entradas_blog_list->Page_Init();

// Page main
$entradas_blog_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$entradas_blog_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fentradas_bloglist = new ew_Form("fentradas_bloglist", "list");
fentradas_bloglist.FormKeyCountName = '<?php echo $entradas_blog_list->FormKeyCountName ?>';

// Form_CustomValidate event
fentradas_bloglist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentradas_bloglist.ValidateRequired = true;
<?php } else { ?>
fentradas_bloglist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fentradas_bloglist.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias_blog"};

// Form object for search
var CurrentSearchForm = fentradas_bloglistsrch = new ew_Form("fentradas_bloglistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($entradas_blog_list->TotalRecs > 0 && $entradas_blog_list->ExportOptions->Visible()) { ?>
<?php $entradas_blog_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($entradas_blog_list->SearchOptions->Visible()) { ?>
<?php $entradas_blog_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($entradas_blog_list->FilterOptions->Visible()) { ?>
<?php $entradas_blog_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $entradas_blog_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($entradas_blog_list->TotalRecs <= 0)
			$entradas_blog_list->TotalRecs = $entradas_blog->SelectRecordCount();
	} else {
		if (!$entradas_blog_list->Recordset && ($entradas_blog_list->Recordset = $entradas_blog_list->LoadRecordset()))
			$entradas_blog_list->TotalRecs = $entradas_blog_list->Recordset->RecordCount();
	}
	$entradas_blog_list->StartRec = 1;
	if ($entradas_blog_list->DisplayRecs <= 0 || ($entradas_blog->Export <> "" && $entradas_blog->ExportAll)) // Display all records
		$entradas_blog_list->DisplayRecs = $entradas_blog_list->TotalRecs;
	if (!($entradas_blog->Export <> "" && $entradas_blog->ExportAll))
		$entradas_blog_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$entradas_blog_list->Recordset = $entradas_blog_list->LoadRecordset($entradas_blog_list->StartRec-1, $entradas_blog_list->DisplayRecs);

	// Set no record found message
	if ($entradas_blog->CurrentAction == "" && $entradas_blog_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$entradas_blog_list->setWarningMessage(ew_DeniedMsg());
		if ($entradas_blog_list->SearchWhere == "0=101")
			$entradas_blog_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$entradas_blog_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$entradas_blog_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($entradas_blog->Export == "" && $entradas_blog->CurrentAction == "") { ?>
<form name="fentradas_bloglistsrch" id="fentradas_bloglistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($entradas_blog_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fentradas_bloglistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="entradas_blog">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($entradas_blog_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($entradas_blog_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $entradas_blog_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($entradas_blog_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($entradas_blog_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($entradas_blog_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($entradas_blog_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $entradas_blog_list->ShowPageHeader(); ?>
<?php
$entradas_blog_list->ShowMessage();
?>
<?php if ($entradas_blog_list->TotalRecs > 0 || $entradas_blog->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid entradas_blog">
<div class="panel-heading ewGridUpperPanel">
<?php if ($entradas_blog->CurrentAction <> "gridadd" && $entradas_blog->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($entradas_blog_list->Pager)) $entradas_blog_list->Pager = new cPrevNextPager($entradas_blog_list->StartRec, $entradas_blog_list->DisplayRecs, $entradas_blog_list->TotalRecs) ?>
<?php if ($entradas_blog_list->Pager->RecordCount > 0 && $entradas_blog_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($entradas_blog_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($entradas_blog_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $entradas_blog_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($entradas_blog_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($entradas_blog_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $entradas_blog_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $entradas_blog_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $entradas_blog_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $entradas_blog_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($entradas_blog_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fentradas_bloglist" id="fentradas_bloglist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($entradas_blog_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $entradas_blog_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="entradas_blog">
<div id="gmp_entradas_blog" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($entradas_blog_list->TotalRecs > 0) { ?>
<table id="tbl_entradas_bloglist" class="table ewTable">
<?php echo $entradas_blog->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$entradas_blog_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$entradas_blog_list->RenderListOptions();

// Render list options (header, left)
$entradas_blog_list->ListOptions->Render("header", "left");
?>
<?php if ($entradas_blog->idCategoria->Visible) { // idCategoria ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->idCategoria) == "") { ?>
		<th data-name="idCategoria"><div id="elh_entradas_blog_idCategoria" class="entradas_blog_idCategoria"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->idCategoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCategoria"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->idCategoria) ?>',1);"><div id="elh_entradas_blog_idCategoria" class="entradas_blog_idCategoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->idCategoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->idCategoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->idCategoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($entradas_blog->titulo->Visible) { // titulo ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->titulo) == "") { ?>
		<th data-name="titulo"><div id="elh_entradas_blog_titulo" class="entradas_blog_titulo"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->titulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="titulo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->titulo) ?>',1);"><div id="elh_entradas_blog_titulo" class="entradas_blog_titulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->titulo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->titulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->titulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($entradas_blog->imagenPrincipal->Visible) { // imagenPrincipal ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->imagenPrincipal) == "") { ?>
		<th data-name="imagenPrincipal"><div id="elh_entradas_blog_imagenPrincipal" class="entradas_blog_imagenPrincipal"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->imagenPrincipal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="imagenPrincipal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->imagenPrincipal) ?>',1);"><div id="elh_entradas_blog_imagenPrincipal" class="entradas_blog_imagenPrincipal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->imagenPrincipal->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->imagenPrincipal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->imagenPrincipal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($entradas_blog->fechaCreacion->Visible) { // fechaCreacion ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->fechaCreacion) == "") { ?>
		<th data-name="fechaCreacion"><div id="elh_entradas_blog_fechaCreacion" class="entradas_blog_fechaCreacion"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->fechaCreacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaCreacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->fechaCreacion) ?>',1);"><div id="elh_entradas_blog_fechaCreacion" class="entradas_blog_fechaCreacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->fechaCreacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->fechaCreacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->fechaCreacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($entradas_blog->usuarioCreacion->Visible) { // usuarioCreacion ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->usuarioCreacion) == "") { ?>
		<th data-name="usuarioCreacion"><div id="elh_entradas_blog_usuarioCreacion" class="entradas_blog_usuarioCreacion"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->usuarioCreacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuarioCreacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->usuarioCreacion) ?>',1);"><div id="elh_entradas_blog_usuarioCreacion" class="entradas_blog_usuarioCreacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->usuarioCreacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->usuarioCreacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->usuarioCreacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($entradas_blog->fechaModificacion->Visible) { // fechaModificacion ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->fechaModificacion) == "") { ?>
		<th data-name="fechaModificacion"><div id="elh_entradas_blog_fechaModificacion" class="entradas_blog_fechaModificacion"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->fechaModificacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaModificacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->fechaModificacion) ?>',1);"><div id="elh_entradas_blog_fechaModificacion" class="entradas_blog_fechaModificacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->fechaModificacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->fechaModificacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->fechaModificacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($entradas_blog->usuarioModificacion->Visible) { // usuarioModificacion ?>
	<?php if ($entradas_blog->SortUrl($entradas_blog->usuarioModificacion) == "") { ?>
		<th data-name="usuarioModificacion"><div id="elh_entradas_blog_usuarioModificacion" class="entradas_blog_usuarioModificacion"><div class="ewTableHeaderCaption"><?php echo $entradas_blog->usuarioModificacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuarioModificacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $entradas_blog->SortUrl($entradas_blog->usuarioModificacion) ?>',1);"><div id="elh_entradas_blog_usuarioModificacion" class="entradas_blog_usuarioModificacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $entradas_blog->usuarioModificacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($entradas_blog->usuarioModificacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($entradas_blog->usuarioModificacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$entradas_blog_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($entradas_blog->ExportAll && $entradas_blog->Export <> "") {
	$entradas_blog_list->StopRec = $entradas_blog_list->TotalRecs;
} else {

	// Set the last record to display
	if ($entradas_blog_list->TotalRecs > $entradas_blog_list->StartRec + $entradas_blog_list->DisplayRecs - 1)
		$entradas_blog_list->StopRec = $entradas_blog_list->StartRec + $entradas_blog_list->DisplayRecs - 1;
	else
		$entradas_blog_list->StopRec = $entradas_blog_list->TotalRecs;
}
$entradas_blog_list->RecCnt = $entradas_blog_list->StartRec - 1;
if ($entradas_blog_list->Recordset && !$entradas_blog_list->Recordset->EOF) {
	$entradas_blog_list->Recordset->MoveFirst();
	$bSelectLimit = $entradas_blog_list->UseSelectLimit;
	if (!$bSelectLimit && $entradas_blog_list->StartRec > 1)
		$entradas_blog_list->Recordset->Move($entradas_blog_list->StartRec - 1);
} elseif (!$entradas_blog->AllowAddDeleteRow && $entradas_blog_list->StopRec == 0) {
	$entradas_blog_list->StopRec = $entradas_blog->GridAddRowCount;
}

// Initialize aggregate
$entradas_blog->RowType = EW_ROWTYPE_AGGREGATEINIT;
$entradas_blog->ResetAttrs();
$entradas_blog_list->RenderRow();
while ($entradas_blog_list->RecCnt < $entradas_blog_list->StopRec) {
	$entradas_blog_list->RecCnt++;
	if (intval($entradas_blog_list->RecCnt) >= intval($entradas_blog_list->StartRec)) {
		$entradas_blog_list->RowCnt++;

		// Set up key count
		$entradas_blog_list->KeyCount = $entradas_blog_list->RowIndex;

		// Init row class and style
		$entradas_blog->ResetAttrs();
		$entradas_blog->CssClass = "";
		if ($entradas_blog->CurrentAction == "gridadd") {
		} else {
			$entradas_blog_list->LoadRowValues($entradas_blog_list->Recordset); // Load row values
		}
		$entradas_blog->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$entradas_blog->RowAttrs = array_merge($entradas_blog->RowAttrs, array('data-rowindex'=>$entradas_blog_list->RowCnt, 'id'=>'r' . $entradas_blog_list->RowCnt . '_entradas_blog', 'data-rowtype'=>$entradas_blog->RowType));

		// Render row
		$entradas_blog_list->RenderRow();

		// Render list options
		$entradas_blog_list->RenderListOptions();
?>
	<tr<?php echo $entradas_blog->RowAttributes() ?>>
<?php

// Render list options (body, left)
$entradas_blog_list->ListOptions->Render("body", "left", $entradas_blog_list->RowCnt);
?>
	<?php if ($entradas_blog->idCategoria->Visible) { // idCategoria ?>
		<td data-name="idCategoria"<?php echo $entradas_blog->idCategoria->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_idCategoria" class="entradas_blog_idCategoria">
<span<?php echo $entradas_blog->idCategoria->ViewAttributes() ?>>
<?php echo $entradas_blog->idCategoria->ListViewValue() ?></span>
</span>
<a id="<?php echo $entradas_blog_list->PageObjName . "_row_" . $entradas_blog_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($entradas_blog->titulo->Visible) { // titulo ?>
		<td data-name="titulo"<?php echo $entradas_blog->titulo->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_titulo" class="entradas_blog_titulo">
<span<?php echo $entradas_blog->titulo->ViewAttributes() ?>>
<?php echo $entradas_blog->titulo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($entradas_blog->imagenPrincipal->Visible) { // imagenPrincipal ?>
		<td data-name="imagenPrincipal"<?php echo $entradas_blog->imagenPrincipal->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_imagenPrincipal" class="entradas_blog_imagenPrincipal">
<span<?php echo $entradas_blog->imagenPrincipal->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($entradas_blog->imagenPrincipal, $entradas_blog->imagenPrincipal->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($entradas_blog->fechaCreacion->Visible) { // fechaCreacion ?>
		<td data-name="fechaCreacion"<?php echo $entradas_blog->fechaCreacion->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_fechaCreacion" class="entradas_blog_fechaCreacion">
<span<?php echo $entradas_blog->fechaCreacion->ViewAttributes() ?>>
<?php echo $entradas_blog->fechaCreacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($entradas_blog->usuarioCreacion->Visible) { // usuarioCreacion ?>
		<td data-name="usuarioCreacion"<?php echo $entradas_blog->usuarioCreacion->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_usuarioCreacion" class="entradas_blog_usuarioCreacion">
<span<?php echo $entradas_blog->usuarioCreacion->ViewAttributes() ?>>
<?php echo $entradas_blog->usuarioCreacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($entradas_blog->fechaModificacion->Visible) { // fechaModificacion ?>
		<td data-name="fechaModificacion"<?php echo $entradas_blog->fechaModificacion->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_fechaModificacion" class="entradas_blog_fechaModificacion">
<span<?php echo $entradas_blog->fechaModificacion->ViewAttributes() ?>>
<?php echo $entradas_blog->fechaModificacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($entradas_blog->usuarioModificacion->Visible) { // usuarioModificacion ?>
		<td data-name="usuarioModificacion"<?php echo $entradas_blog->usuarioModificacion->CellAttributes() ?>>
<span id="el<?php echo $entradas_blog_list->RowCnt ?>_entradas_blog_usuarioModificacion" class="entradas_blog_usuarioModificacion">
<span<?php echo $entradas_blog->usuarioModificacion->ViewAttributes() ?>>
<?php echo $entradas_blog->usuarioModificacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$entradas_blog_list->ListOptions->Render("body", "right", $entradas_blog_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($entradas_blog->CurrentAction <> "gridadd")
		$entradas_blog_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($entradas_blog->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($entradas_blog_list->Recordset)
	$entradas_blog_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($entradas_blog->CurrentAction <> "gridadd" && $entradas_blog->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($entradas_blog_list->Pager)) $entradas_blog_list->Pager = new cPrevNextPager($entradas_blog_list->StartRec, $entradas_blog_list->DisplayRecs, $entradas_blog_list->TotalRecs) ?>
<?php if ($entradas_blog_list->Pager->RecordCount > 0 && $entradas_blog_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($entradas_blog_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($entradas_blog_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $entradas_blog_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($entradas_blog_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($entradas_blog_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $entradas_blog_list->PageUrl() ?>start=<?php echo $entradas_blog_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $entradas_blog_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $entradas_blog_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $entradas_blog_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $entradas_blog_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($entradas_blog_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($entradas_blog_list->TotalRecs == 0 && $entradas_blog->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($entradas_blog_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fentradas_bloglistsrch.FilterList = <?php echo $entradas_blog_list->GetFilterList() ?>;
fentradas_bloglistsrch.Init();
fentradas_bloglist.Init();
</script>
<?php
$entradas_blog_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$entradas_blog_list->Page_Terminate();
?>
