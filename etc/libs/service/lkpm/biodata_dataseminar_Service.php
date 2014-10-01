<?php
class Biodata_DataSeminar_Service {
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
public function getTmSeminar($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select id,i_nik,n_seminar,d_seminar_mulai,d_seminar_akhir,n_seminar_peran,n_seminar_lembaga,
										a_seminar,e_keterangan,i_entry,d_entry,cast(Month(d_seminar_mulai)AS VARCHAR) as bulanmulai,
										DATENAME(d, d_seminar_mulai) as tanggalmulai,
										DATENAME(yyyy, d_seminar_mulai) as tahunmulai,
										cast(Month(d_seminar_akhir)AS VARCHAR) as bulanakhir,
										DATENAME(d, d_seminar_akhir) as tanggalakhir,
										DATENAME(yyyy, d_seminar_akhir) as tahunakhir,
										i_sk,n_pejabatsk,
										cast(Month(d_sk)AS VARCHAR) as bulansk,
										DATENAME(d, d_sk) as tanggalsk,
										DATENAME(yyyy, d_sk) as tahunsk,a_file
										from tm_seminar where 1=1 $cari order by id desc ");
							
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,	
									"id" =>(string)$result[$j]->id,
									"n_seminar" =>(string)$result[$j]->n_seminar,
									"d_seminar_mulai" =>(string)$result[$j]->d_seminar_mulai,
									"d_seminar_akhir" =>(string)$result[$j]->d_seminar_akhir,
									"n_seminar_peran" =>(string)$result[$j]->n_seminar_peran,
									"n_seminar_lembaga" =>(string)$result[$j]->n_seminar_lembaga,
									"a_seminar" =>(string)$result[$j]->a_seminar,
									"e_keterangan" =>(string)$result[$j]->e_keterangan,
									"bulanmulai"=>(string)$result[$j]->bulanmulai,
									"tanggalmulai"=>(string)$result[$j]->tanggalmulai,
									"tahunmulai"=>(string)$result[$j]->tahunmulai,
									"bulanakhir"=>(string)$result[$j]->bulanakhir,
									"tanggalakhir"=>(string)$result[$j]->tanggalakhir,
									"tahunakhir"=>(string)$result[$j]->tahunakhir,
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

	     $tambah_data = array(		"i_nik" =>$data['i_nik'],
									"n_seminar" =>$data['n_seminar'],
									"d_seminar_mulai" =>$data['d_seminar_mulai'],
									"d_seminar_akhir" =>$data['d_seminar_akhir'],
									"n_seminar_peran" =>$data['n_seminar_peran'],
									"n_seminar_lembaga" =>$data['n_seminar_lembaga'],
									"a_seminar" =>$data['a_seminar'],
									"e_keterangan" =>$data['e_keterangan'],	
									"i_sk"=>$data['i_sk'],
									"n_pejabatsk"=>$data['n_pejabatsk'],
									"d_sk"=>$data['d_sk'],
									"a_file"=>$data['a_file'],									
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));									
									
	     $db->insert('tm_seminar',$tambah_data);
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
	     $ubah_data = array(		"n_seminar" =>$data['n_seminar'],
									"d_seminar_mulai" =>$data['d_seminar_mulai'],
									"d_seminar_akhir" =>$data['d_seminar_akhir'],
									"n_seminar_peran" =>$data['n_seminar_peran'],
									"n_seminar_lembaga" =>$data['n_seminar_lembaga'],
									"a_seminar" =>$data['a_seminar'],
									"e_keterangan" =>$data['e_keterangan'],	
									"i_sk"=>$data['i_sk'],
									"n_pejabatsk"=>$data['n_pejabatsk'],
									"d_sk"=>$data['d_sk'],
									"a_file"=>$data['a_file'],									
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_seminar',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."' ");
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
		 $db->delete('tm_seminar', $where);
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
