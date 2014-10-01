<?php
class Biodata_Datajbtakademik_Service {
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
public function getTmJbtAkademik($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				$result = $db->fetchAll("select i_nik,c_jbtakademik,d_jbtakademik,i_sk,d_sk,
										cast(Month(d_jbtakademik)AS VARCHAR) as bulanjabatan,
										DATENAME(d, d_jbtakademik) as tanggaljabatan,
										DATENAME(yyyy, d_jbtakademik) as tahunjabatan,
										cast(Month(d_sk)AS VARCHAR) as bulansk,
										DATENAME(d, d_sk) as tanggalsk,
										DATENAME(yyyy, d_sk) as tahunsk,
										a_file,	
										i_entry,d_entry from tm_jbtakademik where 1=1 $cari order by d_jbtakademik asc ");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$n_jbtakademik = $db->fetchOne('select n_jbtakademik from tr_jbtakademik where c_jbtakademik = ?',$result[$j]->c_jbtakademik);
			
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"c_jbtakademik" =>(string)$result[$j]->c_jbtakademik,
									"n_jbtakademik" =>$n_jbtakademik,
									"i_sk" =>(string)$result[$j]->i_sk,
									"d_jbtakademik" =>(string)$result[$j]->d_jbtakademik,
									"d_sk" =>(string)$result[$j]->d_sk,
									"bulanjabatan"=>(string)$result[$j]->bulanjabatan,
									"tanggaljabatan"=>(string)$result[$j]->tanggaljabatan,
									"tahunjabatan"=>(string)$result[$j]->tahunjabatan,	
									"bulansk"=>(string)$result[$j]->bulansk,
									"tanggalsk"=>(string)$result[$j]->tanggalsk,
									"tahunsk"=>(string)$result[$j]->tahunsk,
									"a_file"=>(string)$result[$j]->a_file,									
									"i_entry" =>(string)$result[$j]->i_entry,
									"d_entry" =>(string)$result[$j]->d_entry);
				}
						
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}
	
	public function tambahData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $tambah_data = array(
								"i_nik"=>$data['i_nik'],
								"c_jbtakademik"=>$data['c_jbtakademik'],
								"i_sk"=>$data['i_sk'],
								"d_jbtakademik"=>$data['d_jbtakademik'],
								"d_sk"=>$data['d_sk'],
								"a_file"=>$data['a_file'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_jbtakademik',$tambah_data);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function ubahData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ubah_data = array(	"i_nik"=>$data['i_nik'],
								"c_jbtakademik"=>$data['c_jbtakademik'],
								"i_sk"=>$data['i_sk'],
								"d_jbtakademik"=>$data['d_jbtakademik'],
								"d_sk"=>$data['d_sk'],
								"a_file"=>$data['a_file'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_jbtakademik',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_jbtakademik = '".trim($data['c_jbtakademik2'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function hapusData($i_nik,$c_jbtakademik) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_jbtakademik= '".$c_jbtakademik."' ";
		 $db->delete('tm_jbtakademik', $where);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


}
?>
