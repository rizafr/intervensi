<?php
require_once('JSON.php');
abstract class DataSource{

	protected $PrefixEditLink;
	
	protected $KeyIdEditFromQuery;
	
	protected $KeyEditForJson;
	
	protected $ColumnLabelEdit;
	
	
	protected $PrefixDeleteLink;	
	
	protected $KeyIdDeleteFromQuery;
	
	protected $KeyDeleteForJson;
	
	protected $ColumnLabelDelete;
	

	public function __construct(){
		// Define defaults
		$results = -1; // default get all
		$startIndex = 0; // default start at 0
		$sort = null; // default don't sort
		$dir = 'asc'; // default sort dir is asc
		$sort_dir = SORT_ASC;
		
		// How many records to get?
		if(strlen($_GET['results']) > 0) {
			$results = $_GET['results'];
		}
		
		// Start at which record?
		if(strlen($_GET['startIndex']) > 0) {
			$startIndex = $_GET['startIndex'];
		}
		
		// Sorted?
		if(strlen($_GET['sort']) > 0) {
			$sort = $_GET['sort'];
		}
		
		// Sort dir?
		if((strlen($_GET['dir']) > 0) && ($_GET['dir'] == 'desc')) {
			$dir = 'desc';
			$sort_dir = SORT_DESC;
		}
		else {
			$dir = 'asc';
			$sort_dir = SORT_ASC;
		}
		
		$this->GetDataSource($results, $startIndex, $sort, $dir, $sort_dir);
	}

	public function GetDataSource($results, $startIndex, $sort, $dir, $sort_dir) {
	
		## header('Content-type: application/json');
		## Set Link Edit & Delete Variabel
		$this->SetLinkEditVariabel();		
		$this->SetLinkDeleteVariabel();
		
		// All records
		$allRecords = $this->InitArray();		
		$allRecords = $this->InsertLink($allRecords,$this->PrefixEditLink,$this->KeyIdEditFromQuery,$this->PrefixDeleteLink,$this->KeyIdDeleteFromQuery);
		//$allRecords = $this->InsertLink($allRecords,);
		//$allRecords = $this->ChangeNumberKolom($allRecords);
		// Need to sort records
		if(!is_null($sort)) {
	
			// Obtain a list of columns
			foreach ($allRecords as $key => $row) {			
				$sortByCol[$key] = $row[$sort];			
			}
	
			// Valid sort value
			if(count($sortByCol) > 0) {
				// Sort the original data
				// Add $allRecords as the last parameter, to sort by the common key
				array_multisort($sortByCol, $sort_dir, $allRecords);
			}
		}
		
		$allRecords = $this->InsertNumberToArray($allRecords,'No');
		//echo "TEST CODE..";
		//Debug::PreVar($allRecords);
		
		// Invalid start value
		if(is_null($startIndex) || !is_numeric($startIndex) || ($startIndex < 0)) {
			// Default is zero
			$startIndex = 0;
		}
		// Valid start value
		else {
			// Convert to number
			$startIndex += 0;
		}
	
		// Invalid results value
		if(is_null($results) || !is_numeric($results) ||
				($results < 1) || ($results >= count($allRecords))) {
			// Default is all
			$results = count($allRecords);
		}
		// Valid results value
		else {
			// Convert to number
			$results += 0;
		}
	
		// Iterate through records and return from start index
		$data = array();
		$lastIndex = $startIndex+$results;
		
		if($lastIndex > count($allRecords)) {
			$lastIndex = count($allRecords);
		}
		//$startIndex  = 1;
		//$lastIndex = 50;
		for($i=$startIndex; $i<($lastIndex); $i++) {
			$data[] = $allRecords[$i];
		}
	
		// Create return value
		$returnValue = array(
			'recordsReturned'=>count($data),
			'totalRecords'=>count($allRecords),
			'startIndex'=>$startIndex,
			'sort'=>$sort,
			'dir'=>$dir,
			'pageSize'=>$results,
			'records'=>$data
		);
	
		$json = new Services_JSON();
		echo ($json->encode($returnValue)); // Instead of json_encode
	}
	
	private function InsertNumberToArray($arraySource,$KeyNumber){
		
		$y = 1;
		for($x=0; $x<count($arraySource); $x++){
			$arraySource[$x][$KeyNumber] = $y.".";
			$y++;
		}
		return $arraySource;
	}
	
	/*	
	private function SetDeleteMethod($FunctionName, $Query){
		$this->DelMethod = $FunctionName
	}
	*/
	
	/*
	private function InsertLink($ArrSrc,$Prefix,$KeyAddToLink,$KeyLinkName,$TextLink,$IsDelete=false){
		if(is_array($KeyAddToLink)){
			for($x=0; $x<count($ArrSrc); $x++){
				$Link = $Prefix;
				foreach($KeyAddToLink as $keyGet => $GetValues){
					$Link .= "&".$keyGet."=".$ArrSrc[$x][$GetValues];

				}
				if(!$IsDelete)
					$ArrSrc[$x][$KeyLinkName] = '<a href="'.$Link.'" class="link2">'.$TextLink.'</a>';
				else 
					//$ArrSrc[$x][$KeyLinkName] = '<a href="javascript:ConfirmDelete(\''.$Link.'\')" class="link2">'.$TextLink.'</a>';
					$ArrSrc[$x][$KeyLinkName] = '<a href="javascript:'.$this->DelMethod.'(\''.$Link.'\')" class="link2">'.$TextLink.'</a>';
			}			
		} else {
			for($x=0; $x<count($ArrSrc); $x++){
				if(!$IsDelete)
					$ArrSrc[$x][$KeyLinkName] = '<a href="'.$Prefix.$ArrSrc[$x][$KeyAddToLink].'" class="link2">'.$TextLink.'</a>';
				else 
					$ArrSrc[$x][$KeyLinkName] = '<a href="javascript:'.$this->DelMethod.'(\''.$Prefix.$ArrSrc[$x][$KeyAddToLink].'\')" class="link2">'.$TextLink.'</a>';
			}
		}
		return $ArrSrc;
	}
	*/
	
	private function InsertLink($ArrSrc, $PrefixEdit, $KeyAddToEditLink, $PrefixDelete, $KeyAddToDeleteLink){	
		$LinkEdit 	= $PrefixEdit;
		$LinkDelete = $PrefixDelete;				
		if(is_array($KeyAddToEditLink)){
			for($x=0; $x<count($ArrSrc); $x++){
				foreach($KeyAddToEditLink as $keyGet => $GetValues){
					$LinkEdit .= "&".$keyGet."=".$ArrSrc[$x][$GetValues];
				}				
				foreach($KeyAddToDeleteLink as $keyGet => $GetValues){
					$LinkDelete .= "&".$keyGet."=".$ArrSrc[$x][$GetValues];
				}								
				$ArrSrc[$x]['Edit'] = '<a href="'.$LinkEdit.'" class="ajaxify"><img src="images/edit.png" border="0"></a>';				
				$ArrSrc[$x]['Delete'] = '<a href="javascript:ConfirmDelete(\''.$LinkDelete.'\')" class="link2"><img src="images/delete.png" border="0"></a>';
			}			
		} else {
			for($x=0; $x<count($ArrSrc); $x++){			
				$ArrSrc[$x]['Edit'] = '<a href="'.$LinkEdit.$ArrSrc[$x][$KeyAddToEditLink].'" class="ajaxify"><img src="images/edit.png" border="0"></a>';
				$ArrSrc[$x]['Delete'] = '<a href="javascript:ConfirmDelete(\''.$LinkDelete.$ArrSrc[$x][$KeyAddToDeleteLink].'\')" class="link2"><img src="images/delete.png" border="0"></a>';
			}
		}
		return $ArrSrc;
	}
		
	## Method ini akan di override sesuai kebutuhan
	protected function ChangeNumberKolom($data){
		return $data;
	}
	
	## This Method must implemented for collecting data source to send it to the json encode
	## 
	abstract protected function InitArray();
	
	## Setting Link Edit Variabel
	abstract protected function SetLinkEditVariabel();
	
	## Setting Link Delete Variabel
	abstract protected function SetLinkDeleteVariabel();
	
	
}


?>