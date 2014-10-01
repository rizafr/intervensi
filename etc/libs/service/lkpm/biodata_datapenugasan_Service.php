<?php
class Biodata_DataPenugasan_Service {
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
public function getTmPenugasan($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				$result = $db->fetchAll("select i_nik,c_penugasan,n_penugasan, cast(Month(d_mulai)AS VARCHAR) as bulanmulai, DATENAME(d, d_mulai) as tanggalmulai,DATENAME(yyyy, d_mulai) as tahunmulai,cast(Month(d_akhir)AS VARCHAR) as bulanakhir, DATENAME(d, d_akhir) as tanggalakhir,DATENAME(yyyy, d_akhir) as tahunakhir,a_file, a_tempat,i_sk, cast(Month(d_sk)AS VARCHAR) as bulansk,DATENAME(d,d_sk) as tanggalsk,DATENAME(yyyy, d_sk) as tahunsk,n_pejabatsk from tm_penugasan where 1=1 $cari order by d_mulai asc ");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			

				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"c_penugasan" =>(string)$result[$j]->c_penugasan,
									"n_penugasan" =>(string)$result[$j]->n_penugasan,
									"bulanmulai" =>(string)$result[$j]->bulanmulai,
									"tanggalmulai" =>(string)$result[$j]->tanggalmulai,
									"tahunmulai" =>(string)$result[$j]->tahunmulai,
									"tanggalakhir" =>(string)$result[$j]->tanggalakhir,
									"bulanakhir" =>(string)$result[$j]->bulanakhir,
									"tahunakhir" =>(string)$result[$j]->tahunakhir,
									"a_file" =>(string)$result[$j]->a_file,
									"a_tempat" =>(string)$result[$j]->a_tempat,
									"i_sk" =>(string)$result[$j]->i_sk,
									"bulansk" =>(string)$result[$j]->bulansk,										
									"tanggalsk" =>(string)$result[$j]->tanggalsk,
									"tahunsk" =>(string)$result[$j]->tahunsk,
									"n_pejabatsk" =>(string)$result[$j]->n_pejabatsk);
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

	     $tambah_data = array(	"i_nik" =>$data['i_nik'],
								"n_penugasan" =>$data['n_penugasan'],
								"d_mulai" =>$data['d_mulai'],
								"d_akhir" =>$data['d_akhir'],
								"a_file" =>$data['a_file'],
								"a_tempat" =>$data['a_tempat'],
								"i_sk" =>$data['i_sk'],
								"d_sk" =>$data['d_sk'],
								"n_pejabatsk" =>$data['n_pejabatsk'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
									
	     $db->insert('tm_penugasan',$tambah_data);
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
	     $ubah_data = array(	
								"n_penugasan" =>$data['n_penugasan'],
								"d_mulai" =>$data['d_mulai'],
								"d_akhir" =>$data['d_akhir'],
								"a_file" =>$data['a_file'],
								"a_tempat" =>$data['a_tempat'],
								"i_sk" =>$data['i_sk'],
								"d_sk" =>$data['d_sk'],
								"n_pejabatsk" =>$data['n_pejabatsk'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_penugasan',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_penugasan = '".trim($data['c_penugasan'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function hapusData($i_nik,$c_penugasan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_penugasan= '".$c_penugasan."' ";
		 $db->delete('tm_penugasan', $where);
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
