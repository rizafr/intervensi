<?php
class Biodata_DataDiklat_Service {
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
			
				$result = $db->fetchAll("select i_nik,c_pelatihan,n_pend_latih,d_pend_tahunlatih,q_pelatihan,n_satuan_jamlat,n_alamat_latih,e_keterangan,
										c_peg_latih,c_teknis,n_penyelengara,i_sertifikat,d_sertifikat,i_entry,d_entry,id_pelatihan,c_verifikasi,
										cast(Month(d_sertifikat)AS VARCHAR) as bulansertifikat,
										DATENAME(d, d_sertifikat) as tanggalsertifikat,
										DATENAME(yyyy, d_sertifikat) as tahunsertifikat
										from tm_diklat where 1=1 $cari order by i_nik desc ");
										
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"id_pelatihan" =>(string)$result[$j]->id_pelatihan,
									"c_pelatihan" =>(string)$result[$j]->c_pelatihan,
									"n_pend_latih" =>(string)$result[$j]->n_pend_latih,
									"d_pend_tahunlatih" =>(string)$result[$j]->d_pend_tahunlatih,
									"q_pelatihan" =>(string)$result[$j]->q_pelatihan,
									"n_satuan_jamlat" =>(string)$result[$j]->n_satuan_jamlat,
									"n_alamat_latih" =>(string)$result[$j]->n_alamat_latih,
									"e_keterangan" =>(string)$result[$j]->e_keterangan,
									"c_peg_latih" =>(string)$result[$j]->c_peg_latih,
									"c_teknis" =>(string)$result[$j]->c_teknis,
									"n_penyelengara" =>(string)$result[$j]->n_penyelengara,
									"i_sertifikat" =>(string)$result[$j]->i_sertifikat,
									"d_sertifikat" =>(string)$result[$j]->d_sertifikat,
									"c_verifikasi" =>(string)$result[$j]->c_verifikasi,	
									"tanggalsertifikat"=>(String)$result[$j]->tanggalsertifikat,	
									"bulansertifikat"=>(String)$result[$j]->bulansertifikat,	
									"tahunsertifikat"=>(String)$result[$j]->tahunsertifikat,										
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
								"c_pelatihan"=>$data['c_pelatihan'],
								"n_pend_latih"=>$data['n_pend_latih'],
								"d_pend_tahunlatih"=>$data['d_pend_tahunlatih'],
								"q_pelatihan"=>$data['q_pelatihan'],
								"n_satuan_jamlat"=>$data['n_satuan_jamlat'],
								"n_alamat_latih"=>$data['n_alamat_latih'],
								"e_keterangan"=>$data['e_keterangan'],
								"c_peg_latih"=>$data['c_peg_latih'],
								"c_teknis"=>$data['c_teknis'],
								"n_penyelengara"=>$data['n_penyelengara'],
								"i_sertifikat"=>$data['i_sertifikat'],
								"d_sertifikat"=>$data['d_sertifikat'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_diklat',$tambah_data);
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
								"c_pelatihan"=>$data['c_pelatihan'],
								"n_pend_latih"=>$data['n_pend_latih'],
								"d_pend_tahunlatih"=>$data['d_pend_tahunlatih'],
								"q_pelatihan"=>$data['q_pelatihan'],
								"n_satuan_jamlat"=>$data['n_satuan_jamlat'],
								"n_alamat_latih"=>$data['n_alamat_latih'],
								"e_keterangan"=>$data['e_keterangan'],
								"c_peg_latih"=>$data['c_peg_latih'],
								"c_teknis"=>$data['c_teknis'],
								"n_penyelengara"=>$data['n_penyelengara'],
								"i_sertifikat"=>$data['i_sertifikat'],
								"d_sertifikat"=>$data['d_sertifikat'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_diklat',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id_pelatihan = '".trim($data['id_pelatihan'])."' ");
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
		 $db->delete('tm_diklat', $where);
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
