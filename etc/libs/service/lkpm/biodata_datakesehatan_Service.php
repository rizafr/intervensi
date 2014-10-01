<?php
class Biodata_Datakesehatan_Service {
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
public function getTmKesehatan($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				$result = $db->fetchAll("select id,i_nik,n_penyakit,d_sakit_mulai,
										cast(Month(d_sakit_mulai)AS VARCHAR) as bulanmulai,
										DATENAME(d, d_sakit_mulai) as tanggalmulai,
										DATENAME(yyyy, d_sakit_mulai) as tahunmulai,
										cast(Month(d_sakit_selesai)AS VARCHAR) as bulanselesai,
										DATENAME(d, d_sakit_selesai) as tanggalselesai,
										DATENAME(yyyy, d_sakit_selesai) as tahunselesai,
										d_sakit_selesai,n_rumahsakit,n_alamat_rs,e_keterangan,
										i_entry,d_entry from tm_kesehatan where 1=1 $cari order by d_sakit_mulai asc ");

										
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			
			
				$data[$j] = array("id" =>(string)$result[$j]->id,
									"i_nik" =>(string)$result[$j]->i_nik,
									"n_penyakit" =>(string)$result[$j]->n_penyakit,
									"d_sakit_mulai" =>(string)$result[$j]->d_sakit_mulai,
									"bulanmulai" =>(string)$result[$j]->bulanmulai,
									"tanggalmulai" =>(string)$result[$j]->tanggalmulai,
									"tahunmulai" =>(string)$result[$j]->tahunmulai,
									"bulanselesai" =>(string)$result[$j]->bulanselesai,
									"tanggalselesai" =>(string)$result[$j]->tanggalselesai,
									"tahunselesai" =>(string)$result[$j]->tahunselesai,
									"d_sakit_selesai" =>(string)$result[$j]->d_sakit_selesai,
									"n_rumahsakit" =>(string)$result[$j]->n_rumahsakit,
									"n_alamat_rs" =>(string)$result[$j]->n_alamat_rs,
									"e_keterangan" =>(string)$result[$j]->e_keterangan,									
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

	     $tambah_data = array(	"i_nik" =>$data['i_nik'],
								"n_penyakit" =>$data['n_penyakit'],
								"d_sakit_mulai" =>$data['d_sakit_mulai'],
								"d_sakit_selesai" =>$data['d_sakit_selesai'],
								"n_rumahsakit" =>$data['n_rumahsakit'],
								"n_alamat_rs" =>$data['n_alamat_rs'],
								"e_keterangan" =>$data['e_keterangan'],	
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

							
								
	     $db->insert('tm_kesehatan',$tambah_data);
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
	     $ubah_data = array(	"i_nik" =>$data['i_nik'],
								"n_penyakit" =>$data['n_penyakit'],
								"d_sakit_mulai" =>$data['d_sakit_mulai'],
								"d_sakit_selesai" =>$data['d_sakit_selesai'],
								"n_rumahsakit" =>$data['n_rumahsakit'],
								"n_alamat_rs" =>$data['n_alamat_rs'],
								"e_keterangan" =>$data['e_keterangan'],	
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_kesehatan',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function hapusData($i_nik,$id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and id= '".$id."' ";
		 $db->delete('tm_kesehatan', $where);
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
