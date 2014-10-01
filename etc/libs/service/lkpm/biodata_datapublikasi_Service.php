<?php
class Biodata_DataPublikasi_Service {
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
public function getTmPublikasi($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select i_nik,c_publikasi,c_penelitian,n_publikasi,d_publikasi,
									cast(Month(d_publikasi)AS VARCHAR) as bulanpublikasi,
									DATENAME(d, d_publikasi) as tanggalpublikasi,
									DATENAME(yyyy, d_publikasi) as tahunpublikasi,
									e_judul,n_penulis,a_tempat,i_entry,d_entry,n_media	
									from tm_publikasi where 1=1 $cari order by c_publikasi desc ");
										

						
							
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$n_penelitian = $db->fetchOne('select e_judul from tm_penelitian where c_penelitian = ?',$result[$j]->c_penelitian);
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"c_penelitian" =>(string)$result[$j]->c_penelitian,
									"c_publikasi" =>(string)$result[$j]->c_publikasi,
									"n_publikasi" =>(string)$result[$j]->n_publikasi,
									"d_publikasi" =>(string)$result[$j]->d_publikasi,
									"bulanpublikasi" =>(string)$result[$j]->bulanpublikasi,
									"tanggalpublikasi" =>(string)$result[$j]->tanggalpublikasi,
									"tahunpublikasi" =>(string)$result[$j]->tahunpublikasi,
									"e_judul" =>(string)$result[$j]->e_judul,
									"a_tempat" =>(string)$result[$j]->a_tempat,
									"n_penulis"=>(string)$result[$j]->n_penulis,
									"n_penelitian"=>$n_penelitian,
									"n_media" =>(string)$result[$j]->n_media,									
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
									"c_penelitian" =>$data['c_penelitian'],
									"n_publikasi" =>$data['n_publikasi'],
									"d_publikasi" =>$data['d_publikasi'],
									"e_judul" =>$data['e_judul'],
									"a_tempat" =>$data['a_tempat'],
									"n_penulis"=>$data['n_penulis'],
									"n_media"=>$data['n_media'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));	
									
	     $db->insert('tm_publikasi',$tambah_data);
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
	     $ubah_data = array(		"i_nik" =>$data['i_nik'],
									"c_penelitian" =>$data['c_penelitian'],
									"n_publikasi" =>$data['n_publikasi'],
									"d_publikasi" =>$data['d_publikasi'],
									"e_judul" =>$data['e_judul'],
									"a_tempat" =>$data['a_tempat'],
									"n_penulis"=>$data['n_penulis'],
									"n_media"=>$data['n_media'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_publikasi',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_publikasi = '".trim($data['c_publikasi'])."' ");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function hapusData($i_nik,$c_publikasi) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_publikasi = '".$c_publikasi."' ";
		 $db->delete('tm_publikasi', $where);
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
