<?php
class Biodata_DataPelatihan_Service {
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
public function getTmPelatihan($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select i_nik,id_pelatihan,n_pelatihan,d_mulai,
										cast(Month(d_mulai)AS VARCHAR) as bulanmulai,
										DATENAME(d, d_mulai) as tanggalmulai,
										DATENAME(yyyy, d_mulai) as tahunmulai,
										d_akhir,cast(Month(d_akhir)AS VARCHAR) as bulanakhir,
										DATENAME(d, d_akhir) as tanggalakhir,
										DATENAME(yyyy, d_akhir) as tahunakhir,
										n_penyelenggara,a_tempat,i_sertifikat,
										cast(Month(d_sertifikat)AS VARCHAR) as bulansertifikat,
										DATENAME(d, d_sertifikat) as tanggalsertifikat,
										DATENAME(yyyy, d_sertifikat) as tahunsertifikat,										
										i_sk,n_pejabatsk,
										cast(Month(d_sk)AS VARCHAR) as bulansk,
										DATENAME(d, d_sk) as tanggalsk,
										DATENAME(yyyy, d_sk) as tahunsk,a_file
										from tm_pelatihan where 1=1 $cari order by id_pelatihan asc ");
										

										
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"id_pelatihan" =>(string)$result[$j]->id_pelatihan,
									"n_pelatihan" =>(string)$result[$j]->n_pelatihan,
									"d_mulai" =>(string)$result[$j]->d_mulai,
									"bulanmulai" =>(string)$result[$j]->bulanmulai,
									"tanggalmulai" =>(string)$result[$j]->tanggalmulai,
									"tahunmulai" =>(string)$result[$j]->tahunmulai,
									"bulanakhir" =>(string)$result[$j]->bulanakhir,
									"tanggalakhir" =>(string)$result[$j]->tanggalakhir,
									"tahunakhir" =>(string)$result[$j]->tahunakhir,
									"n_penyelenggara" =>(string)$result[$j]->n_penyelenggara,
									"a_tempat" =>(string)$result[$j]->a_tempat,
									"i_sertifikat" =>(string)$result[$j]->i_sertifikat,
									"bulansertifikat" =>(string)$result[$j]->bulansertifikat,
									"tanggalsertifikat" =>(string)$result[$j]->tanggalsertifikat,
									"tahunsertifikat" =>(string)$result[$j]->tahunsertifikat,									
									"i_sk" =>(string)$result[$j]->i_sk,
									"n_pejabatsk" =>(string)$result[$j]->n_pejabatsk,
									"bulansk" =>(string)$result[$j]->bulansk,
									"tanggalsk" =>(string)$result[$j]->tanggalsk,
									"tahunsk" =>(string)$result[$j]->tahunsk,	
									"a_file" =>(string)$result[$j]->a_file,	
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
								"n_pelatihan"=>$data['n_pelatihan'],
								"d_mulai"=>$data['d_mulai'],
								"d_akhir"=>$data['d_akhir'],
								"n_penyelenggara"=>$data['n_penyelenggara'],
								"a_tempat"=>$data['a_tempat'],
								"i_sertifikat"=>$data['i_sertifikat'],
								"d_sertifikat"=>$data['d_sertifikat'],
								"i_sk"=>$data['i_sk'],
								"n_pejabatsk"=>$data['n_pejabatsk'],
								"d_sk"=>$data['d_sk'],
								"a_file"=>$data['a_file'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_pelatihan',$tambah_data);
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
	     $ubah_data = array("i_nik"=>$data['i_nik'],
								"n_pelatihan"=>$data['n_pelatihan'],
								"d_mulai"=>$data['d_mulai'],
								"d_akhir"=>$data['d_akhir'],
								"n_penyelenggara"=>$data['n_penyelenggara'],
								"a_tempat"=>$data['a_tempat'],
								"i_sertifikat"=>$data['i_sertifikat'],
								"d_sertifikat"=>$data['d_sertifikat'],
								"i_sk"=>$data['i_sk'],
								"n_pejabatsk"=>$data['n_pejabatsk'],
								"d_sk"=>$data['d_sk'],
								"a_file"=>$data['a_file'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_pelatihan',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id_pelatihan = '".trim($data['id_pelatihan'])."' ");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function hapusData($i_nik,$id_pelatihan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and id_pelatihan = '".$id_pelatihan."' ";
		 $db->delete('tm_pelatihan', $where);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function ubahDataverifikasi(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ubah_data = array(	"c_verifikasi"=>$data['c_verifikasi']);

		$db->update('tm_diklat',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id_pelatihan = '".trim($data['id_pelatihan'])."'");
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
