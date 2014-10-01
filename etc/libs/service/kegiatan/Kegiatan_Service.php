<?php
class Kegiatan_Service {
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
	
	//MENU KEGIATAN
	public function getKelurahanListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$result = $db->fetchAll('SELECT * FROM m_kelurahan order by Kelurahan');		 
			$jmlResult = count($result);
			return $result;
	    } catch (Exception $e) {
			 echo $e->getMessage().'<br>';
			 return 'gagal';
	   }
	}	
	
	public function getInstansiListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT * FROM m_instansi i, pengguna p where i.KodeInstansi=p.KodeInstansi order by Instansi');
				
		 
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function getKomponenListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT * FROM m_komponen order by KodeKomponen");
				
		 
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function getSubKomponenListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);		
		
			$result = $db->fetchAll("SELECT * FROM m_komponen_sub  order by KodeSubKomponen");
		
		
		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function getSubKomponenDetailListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
			$result = $db->fetchAll("SELECT * FROM m_komponen_sub_detail  order by KodeDetailSubKomponen");		

		$jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	public function getsimpankegiatan(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("KodeKegiatan" => $dataMasukan['KodeKegiatan'],
								"NamaKegiatan" => $dataMasukan['NamaKegiatan'],
								"JadwalAwal" => $dataMasukan['JadwalAwal'],
								"JadwalAkhir" => $dataMasukan['JadwalAkhir'],
								"KodeInstansi" => $dataMasukan['KodeInstansi'],
								"KodeKomponen" => $dataMasukan['KodeKomponen'],
								"KodeSubKomponen" => $dataMasukan['KodeSubKomponen'],
								"KodeDetailSubKomponen" => $dataMasukan['KodeDetailSubKomponen'],
								"Anggaran" => $dataMasukan['Anggaran']);
			$db->insert('kegiatan',$paramInput);
			$db->commit();
			return 'sukses';
		} catch (Exception $e) {
			 $db->rollBack();
			 echo $e->getMessage().'<br>';
			 return 'gagal';
		}
	}

	public function getInstansi($KodeInstansi) {
	
	  $registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
					
			$sql = "SELECT * 
						FROM m_instansi 
						WHERE KodeInstansi ='$KodeInstansi'";
			$result = $db->fetchRow($sql);
			
			$hasilAkhir = array("KodeInstansi"  	=>(string)$result->KodeInstansi,
								"Instansi"  		=>(string)$result->Instansi	);
			return $hasilAkhir;		
		   } catch (Exception $e) {
			 echo $e->getMessage().'<br>';
			 return 'Data tidak ada <br>';
		   }
	}	
	
	function getkegiatanedit($KodeKegiatan, $KodeInstansi){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " and KodeKegiatan = '$KodeKegiatan' ";
			if($KodeInstansi==0){
				$sqlProses = "select k.KodeKegiatan, k.NamaKegiatan, k.JadwalAwal,k.KodeInstansi,
				k.JadwalAkhir, k.KodeKomponen, k.KodeSubKomponen, k.KodeDetailSubKomponen, 
				k.Anggaran, i.Instansi from kegiatan k, m_instansi i where k.KodeInstansi=i.KodeInstansi";
			}else{
				$sqlProses = "select k.KodeKegiatan, k.NamaKegiatan, k.JadwalAwal,k.KodeInstansi,
				k.JadwalAkhir, k.KodeKomponen, k.KodeSubKomponen, k.KodeDetailSubKomponen, 
				k.Anggaran, i.Instansi from kegiatan k, m_instansi i where k.KodeInstansi=i.KodeInstansi 
				and k.KodeInstansi='$KodeInstansi'";
			}
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			$hasilAkhir = array("KodeKegiatan"  	=>(string)$result->KodeKegiatan,
								"NamaKegiatan"  		=>(string)$result->NamaKegiatan,
								"JadwalAwal"  		=>(string)$result->JadwalAwal,
								"JadwalAkhir"  		=>(string)$result->JadwalAkhir,
								"KodeInstansi"  		=>(string)$result->KodeInstansi,
								"Instansi"  		=>(string)$result->Instansi,
								"KodeKomponen"  		=>(string)$result->KodeKomponen,
								"KodeSubKomponen"  		=>(string)$result->KodeSubKomponen,
								"KodeDetailSubKomponen"  		=>(string)$result->KodeDetailSubKomponen,
								"Anggaran"  	        =>(string)$result->Anggaran	);
			return $hasilAkhir;					
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	public function getsimpankegiatanedit(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
		$paramInput = array("KodeKegiatan" => $dataMasukan['KodeKegiatan'],
							"NamaKegiatan" => $dataMasukan['NamaKegiatan'],
							"JadwalAwal" => $dataMasukan['JadwalAwal'],
							"JadwalAkhir" => $dataMasukan['JadwalAkhir'],
							"KodeInstansi" => $dataMasukan['KodeInstansi'],
							"KodeKomponen" => $dataMasukan['KodeKomponen'],
							"KodeSubKomponen" => $dataMasukan['KodeSubKomponen'],
							"KodeDetailSubKomponen" => $dataMasukan['KodeDetailSubKomponen'],
							"Anggaran" => $dataMasukan['Anggaran']);
			
			$where[] = " KodeKegiatan = '".$dataMasukan['KodeKegiatan']."'";
			
			$db->update('kegiatan',$paramInput, $where);
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
			$where[] = " KodeKegiatan = '".$KodeKegiatan."'";
			
			$db->delete('kegiatan', $where);
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
	
	public function getcarikegiatan($KodeInstansi, array $dataMasukan, $pageNumber, $itemPerPage,$total){
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

			$whereOpt = " and ($kategoriCari like '%$katakunciCari%')";
			if($katakunciCari != "") { $where = $whereOpt;} 
			$order = " order by k.JadwalAkhir desc";
			if($KodeInstansi == 0){
				$sqlProses = "select k.KodeKegiatan, k.NamaKegiatan, k.JadwalAwal,k.KodeInstansi,
				k.JadwalAkhir, k.KodeKomponen, k.KodeSubKomponen, k.KodeDetailSubKomponen, 
				k.Anggaran, i.Instansi from kegiatan k, m_instansi i where k.KodeInstansi=i.KodeInstansi".$where;
			}else{
				$sqlProses = "select k.KodeKegiatan, k.NamaKegiatan, k.JadwalAwal,k.KodeInstansi,
				k.JadwalAkhir, k.KodeKomponen, k.KodeSubKomponen, k.KodeDetailSubKomponen, 
				k.Anggaran, i.Instansi from kegiatan k, m_instansi i where k.KodeInstansi=i.KodeInstansi 
				and k.KodeInstansi=$KodeInstansi".$where;
			}				
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
										"Instansi"  	=>(string)$result[$j]->Instansi,
										"KodeKomponen"  	=>(string)$result[$j]->KodeKomponen,
										"KodeSubKomponen"  	=>(string)$result[$j]->KodeSubKomponen,
										"KodeDetailSubKomponen"  	=>(string)$result[$j]->KodeDetailSubKomponen,
										"Anggaran"  	=>(string)$result[$j]->Anggaran);
			}	
			return $hasilAkhir;  
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
		
	}
	
	//==============================================================================================================================================
	public function getNamaPenduduk2(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$NIK 			= ($dataMasukan['NIK']);		
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			if($NIK == ''){
				$sql = "SELECT * FROM data_penduduk ";
				$result = $db->fetchAll($sql);
				// $kataKunci = '--';
			}else{			
				$sql = "SELECT * FROM data_penduduk where NIK like '$NIK%' ";
				$result = $db->fetchAll($sql);
			}
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("NIK"				=>(string)$result[$j]->NIK,	
								 "NamaLengkap"	=>(string)$result[$j]->NamaLengkap,
								 "Alamat"			=>(string)$result[$j]->Alamat,
								 "RT"		=>(string)$result[$j]->RT,
								 "RW"			=>(string)$result[$j]->RW,
								 "Dusun"			=>(string)$result[$j]->Dusun,
								 "KodePos"			=>(string)$result[$j]->KodePos,
								 "JK"			=>(string)$result[$j]->JK,
								 "TempatLahir"			=>(string)$result[$j]->TempatLahir,
								 "TglLahir"			=>(string)$result[$j]->TglLahir,
								 "NoAkta"			=>(string)$result[$j]->NoAkta,
								 "GolDarah"			=>(string)$result[$j]->GolDarah,
								 "Agama"				=>(string)$result[$j]->Agama,
								 "Pekerjaan"			=>(string)$result[$j]->Pekerjaan,
								 "NamaIbu"		=>(string)$result[$j]->NamaIbu,
								 "NamaAyah"		=>(string)$result[$j]->NamaAyah,
								 "Status"			=>(string)$result[$j]->Status,
								 "Kelurahan"			=>(string)$result[$j]->Kelurahan
								);
				}				
			 return $data;
		   } catch (Exception $e) {
			 echo $e->getMessage().'<br>';
			 return 'Data tidak ada <br>';
		   }
	}
	
	public function getpenerima($KodeKegiatan, array $dataMasukan, $pageNumber, $itemPerPage, $total){
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

			$whereOpt = " AND ($kategoriCari like '%$katakunciCari%')";
			if($katakunciCari != "") { $where = $whereOpt;} 
			$group = " GROUP BY p.KodeKegiatan ";
			$order = " order by p.NIK ";
			
			
			$sqlProses = "SELECT p.NIK, p.NamaLengkap, p.Alamat, p.Status , p.Kelurahan, p.Ket, p.KodeKegiatan
							FROM pendaftaran p 
							WHERE p.KodeKegiatan = '$KodeKegiatan' ".$where;	
			$sqlProses1 = $sqlProses.$group.$order;
			
			if(($pageNumber==0) && ($itemPerPage==0)){	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);
			}else{
				$sqlData = $sqlProses.$order." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);				
			}
			$jmlResult = count($result);		
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("NIK"  	=>(string)$result[$j]->NIK,
										"NamaLengkap"  	=>(string)$result[$j]->NamaLengkap,
										"Alamat"  	=>(string)$result[$j]->Alamat,
										"Status"  	=>(string)$result[$j]->Status,
										"Kelurahan"  	=>(string)$result[$j]->Kelurahan,
										"KodeKegiatan"  =>(string)$result[$j]->KodeKegiatan,
										"Ket"  	=>(string)$result[$j]->Ket								
										);
			}	
			return $hasilAkhir; 
			
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getpenerima2($KodeKegiatan, $pageNumber, $itemPerPage, $total){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;	
			
			$sqlProses = "select * from kegiatan where KodeKegiatan = '$KodeKegiatan'";	
			$sqlProses1 = $sqlProses.$group.$order;
			
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
										"Anggaran"  	=>(string)$result[$j]->Anggaran															
										);
			}	
			return $hasilAkhir; 
			
		} catch (Exception $e) {
			echo $e->getMessage().'<br>';
			return 'gagal <br>';
		}
	}
	
	public function getsimpanpenerima(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("IdPendaftaran" => $dataMasukan['IdPendaftaran'],
								"NIK" => $dataMasukan['NIK'],
								"NamaLengkap" => $dataMasukan['NamaLengkap'],
								"Alamat" => $dataMasukan['Alamat'],
								"Status" => $dataMasukan['Status'],
								"Kelurahan" => $dataMasukan['Kelurahan'],
								"KodeKegiatan" =>$dataMasukan['KodeKegiatan'],
								"Ket" => $dataMasukan['Ket']);
			$db->insert('pendaftaran',$paramInput);
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
	
	public function getCekDuplikasi($NIK, $KodeKegiatan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
					
			$sql = "SELECT * 
						FROM pendaftaran 
						WHERE NIK ='$NIK' AND KodeKegiatan='$KodeKegiatan'";
			$result = $db->fetchAll($sql);
			
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("IdPendaftaran"  	=>(string)$result->IdPendaftaran,
								"NIK"  	=>(string)$result->NIK,
								"NamaLengkap"  =>(string)$result->NamaLengkap,
								"Alamat"  =>(string)$result->Alamat,
								"Status"  =>(string)$result->Status,
								"Kelurahan"  =>(string)$result->Kelurahan,
								"KodeKegiatan"  =>(int)$result->KodeKegiatan,
								"Ket"  		=>(string)$result->Ket);
				}				
			 return $data;
		   } catch (Exception $e) {
			 echo $e->getMessage().'<br>';
			 return 'Data tidak ada <br>';
		   }
	}
	
	public function getpenerimaedit($NIK, $KodeKegiatan){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
			$sqlProses = " SELECT p.*, k.NamaKegiatan 
								FROM pendaftaran p, kegiatan k 
								WHERE p.KodeKegiatan=k.KodeKegiatan and NIK = '$NIK' and p.KodeKegiatan='$KodeKegiatan'";	

			$sqlData = $sqlProses;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("IdPendaftaran"  	=>(string)$result->IdPendaftaran,
								"NIK"  	=>(string)$result->NIK,
								"NamaLengkap"  =>(string)$result->NamaLengkap,
								"Alamat"  =>(string)$result->Alamat,
								"Status"  =>(string)$result->Status,
								"Kelurahan"  =>(string)$result->Kelurahan,
								"KodeKegiatan"  =>(int)$result->KodeKegiatan,
								"NamaKegiatan"  =>(string)$result->NamaKegiatan,
								"Ket"  		=>(string)$result->Ket);
			return $hasilAkhir;					
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	public function getsimpanpenerimaedit(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
		$db->beginTransaction();
		$paramInput = array("IdPendaftaran" => $dataMasukan['IdPendaftaran'],
							"NIK" => $dataMasukan['NIK'],
							"NamaLengkap" => $dataMasukan['NamaLengkap'],
							"Alamat" => $dataMasukan['Alamat'],
							"Status" => $dataMasukan['Status'],
							"Kelurahan" => $dataMasukan['Kelurahan'],
							"KodeKegiatan" => $dataMasukan['KodeKegiatan'],
							"Ket" => $dataMasukan['Ket']);
			
			$where[] = " NIK = '".$dataMasukan['NIK']."' and KodeKegiatan ='".$dataMasukan['KodeKegiatan']."' ";
			
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
	
	public function gethapuspenerima($NIK, $KodeKegiatan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$where[] = " NIK = '".$NIK."' and KodeKegiatan='".$KodeKegiatan."' ";
			
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
