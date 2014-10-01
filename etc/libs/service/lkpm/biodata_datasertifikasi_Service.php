<?php
class Biodata_DataSertifikasi_Service {
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
public function getTmSertifikat($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select id,i_nik,n_sertifikasi,i_sertifikasi,d_sertifikasi,
										n_sertifikasi_lembaga,d_sertifikasi_mulai,d_sertifikasi_selesai,e_keterangan,i_entry,d_entry,
										cast(Month(d_sertifikasi)AS VARCHAR) as bulansertifikasi,
										DATENAME(d, d_sertifikasi) as tanggalsertifikasi,
										DATENAME(yyyy, d_sertifikasi) as tahunsertifikasi,
										cast(Month(d_sertifikasi_mulai)AS VARCHAR) as bulanmulai,
										DATENAME(d, d_sertifikasi_mulai) as tanggalmulai,
										DATENAME(yyyy, d_sertifikasi_mulai) as tahunmulai,
										cast(Month(d_sertifikasi_selesai)AS VARCHAR) as bulanakhir,
										DATENAME(d, d_sertifikasi_selesai) as tanggalakhir,
										DATENAME(yyyy, d_sertifikasi_selesai) as tahunakhir,
										a_file
										from tm_sertifikasi where 1=1 $cari order by id desc ");

										
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,	
									"id" =>(string)$result[$j]->id,
									"n_sertifikasi" =>(string)$result[$j]->n_sertifikasi,
									"d_sertifikasi" =>(string)$result[$j]->d_sertifikasi,
									"i_sertifikasi" =>(string)$result[$j]->i_sertifikasi,
									"n_sertifikasi_lembaga" =>(string)$result[$j]->n_sertifikasi_lembaga,
									"d_sertifikasi_mulai" =>(string)$result[$j]->d_sertifikasi_mulai,
									"d_sertifikasi_selesai" =>(string)$result[$j]->d_sertifikasi_selesai,
									"e_keterangan" =>(string)$result[$j]->e_keterangan,
									"bulansertifikasi"=>(string)$result[$j]->bulansertifikasi,
									"tanggalsertifikasi"=>(string)$result[$j]->tanggalsertifikasi,
									"tahunsertifikasi"=>(string)$result[$j]->tahunsertifikasi,
									"bulanmulai"=>(string)$result[$j]->bulanmulai,
									"tanggalmulai"=>(string)$result[$j]->tanggalmulai,
									"tahunmulai"=>(string)$result[$j]->tahunmulai,
									"bulanakhir"=>(string)$result[$j]->bulanakhir,
									"tanggalakhir"=>(string)$result[$j]->tanggalakhir,
									"tahunakhir"=>(string)$result[$j]->tahunakhir,
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
								"i_nik" =>$data['i_nik'],
								"n_sertifikasi" =>$data['n_sertifikasi'],
								"d_sertifikasi" =>$data['d_sertifikasi'],
								"i_sertifikasi" =>$data['i_sertifikasi'],
								"n_sertifikasi_lembaga" =>$data['n_sertifikasi_lembaga'],
								"d_sertifikasi_mulai" =>$data['d_sertifikasi_mulai'],
								"d_sertifikasi_selesai" =>$data['d_sertifikasi_selesai'],
								"e_keterangan" =>$data['e_keterangan'],
								"a_file" =>$data['a_file'],
								"i_entry" =>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));									
									
	     $db->insert('tm_sertifikasi',$tambah_data);
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
								"n_sertifikasi" =>$data['n_sertifikasi'],
								"d_sertifikasi" =>$data['d_sertifikasi'],
								"i_sertifikasi" =>$data['i_sertifikasi'],
								"n_sertifikasi_lembaga" =>$data['n_sertifikasi_lembaga'],
								"d_sertifikasi_mulai" =>$data['d_sertifikasi_mulai'],
								"d_sertifikasi_selesai" =>$data['d_sertifikasi_selesai'],
								"e_keterangan" =>$data['e_keterangan'],
								"a_file" =>$data['a_file'],
								"i_entry" =>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_sertifikasi',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."' ");
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
		 $where[] = "i_nik = '".$i_nik."' and id = '".$id."' ";
		 $db->delete('tm_sertifikasi', $where);
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
