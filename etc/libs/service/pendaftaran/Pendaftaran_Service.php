<?php
class Pendaftaran_Service {
    private static $instance;
  
    private function __construct() {
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }
	
	//MENU PENDAFTARAN
	public function getKegiatan(){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchAll("select * from kegiatan");
		     return $result;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	
	public function getcarikegiatan(array $dataMasukan, $pageNumber, $itemPerPage,$total){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;

			$whereOpt = " where p.KodeKegiatan=k.KodeKegiatan and ($kategoriCari like '%$katakunciCari%')";
			if($katakunciCari != "") { $where = $whereOpt;} 
			$order = " order by k.KodeKegiatan ";
			$sqlProses = "SELECT DISTINCT k . * , p . * FROM pendaftaran p 
							RIGHT OUTER JOIN kegiatan k ON p.KodeKegiatan = k.KodeKegiatan ".$where;	
			$sqlProses1 = $sqlProses.$order;
			
			if(($pageNumber==0) && ($itemPerPage==0)){	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);
			}else{
				$sqlData = $sqlProses.$order." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);				
			}
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("KodeKegiatan"  =>(string)$result[$j]->KodeKegiatan,
										"NamaKegiatan"  =>(string)$result[$j]->NamaKegiatan,
										"JadwalAwal"  	=>(string)$result[$j]->JadwalAwal,
										"JadwalAkhir"  	=>(string)$result[$j]->JadwalAkhir,
										"KodeInstansi"  	=>(string)$result[$j]->KodeInstansi,
										"KodeKomponen"  	=>(string)$result[$j]->KodeKomponen,
										"KodeSubKomponen"  	=>(string)$result[$j]->KodeSubKomponen,
										"KodeDetailSubKomponen"  	=>(string)$result[$j]->KodeDetailSubKomponen,
										"NIK"  	=>(string)$result[$j]->NIK,
										"Nama"  	=>(string)$result[$j]->Nama,
										"Alamat"  	=>(string)$result[$j]->Alamat,
										"Ket"  	=>(string)$result[$j]->Ket,										
										"Anggaran"  	=>(string)$result[$j]->Anggaran);
			}	
			return $hasilAkhir;  
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
		
	}
	
	public function getPendaftaran(){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchAll("select * from pendaftaran");
		     return $result;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	
	public function getsimpanpendaftaran(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("NIK" => $dataMasukan['NIK'],
								"Nama" => $dataMasukan['Nama'],
								"Alamat" => $dataMasukan['Alamat'],
								"KodeKegiatan" => $dataMasukan['KodeKegiatan'],
								"Ket" => $dataMasukan['Ket']);
			
			$db->insert('pendaftaran',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			 $db->rollBack();
			 echo $e->getMessage().'<br>';
			 return 'gagal';
		}
	}	
	
	public function getpendaftaranedit($NIK){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where NIK = '$NIK' ";
			$sqlProses = "select * from pendaftaran";	

			
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("NIK"  	=>(string)$result->NIK,
								"Nama"  =>(string)$result->Nama,
								"Alamat"  =>(string)$result->Alamat,
								"KodeKegiatan"  =>(int)$result->KodeKegiatan,
								"Ket"  		=>(string)$result->Ket);
			return $hasilAkhir;					
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	public function getsimpanpendaftaranedit(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
		$paramInput = array("NIK" => $dataMasukan['NIK'],
								"Nama" => $dataMasukan['Nama'],
								"Alamat" => $dataMasukan['Alamat'],
								"KodeKegiatan" => $dataMasukan['KodeKegiatan'],
								"Ket" => $dataMasukan['Ket']);
			
			$where[] = " NIK = '".$dataMasukan['NIK']."'";
			
			$db->update('pendaftaran',$paramInput, $where);
			$db->commit();			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "sukses";
			}
	   }
	}	
	
	public function gethapuskegiatan($KodeKegiatan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = " NIK = '".$NIK."'";
			
			$db->delete('pendaftaran', $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "sukses";
			}
	   }
	}
}
?>
