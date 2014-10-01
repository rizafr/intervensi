<?php
class Biodata_DataOrganisasi_Service {
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
public function getTmOrganisasi($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select id,i_nik,n_org_massa,d_peg_orgmulai,d_peg_orgakhir,a_orgb,
									n_jabatan,n_orgb_pimpinan,e_keterangan,i_entry,d_entry,
										cast(Month(d_peg_orgmulai)AS VARCHAR) as bulanmulai,
										DATENAME(d, d_peg_orgmulai) as tanggalmulai,
										DATENAME(yyyy, d_peg_orgmulai) as tahunmulai,
										cast(Month(d_peg_orgakhir)AS VARCHAR) as bulanakhir,
										DATENAME(d, d_peg_orgakhir) as tanggalakhir,
										DATENAME(yyyy, d_peg_orgakhir) as tahunakhir									
										from tm_organisasi where 1=1 $cari order by id ");
										
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,	
									"id" =>(string)$result[$j]->id,
									"n_org_massa" =>(string)$result[$j]->n_org_massa,
									"d_peg_orgmulai" =>(string)$result[$j]->d_peg_orgmulai,
									"d_peg_orgakhir" =>(string)$result[$j]->d_peg_orgakhir,
									"a_orgb" =>(string)$result[$j]->a_orgb,
									"n_jabatan" =>(string)$result[$j]->n_jabatan,
									"n_orgb_pimpinan" =>(string)$result[$j]->n_orgb_pimpinan,
									"e_keterangan" =>(string)$result[$j]->e_keterangan,
									"bulanmulai"=>(string)$result[$j]->bulanmulai,
									"tanggalmulai"=>(string)$result[$j]->tanggalmulai,
									"tahunmulai"=>(string)$result[$j]->tahunmulai,
									"bulanakhir"=>(string)$result[$j]->bulanakhir,
									"tanggalakhir"=>(string)$result[$j]->tanggalakhir,
									"tahunakhir"=>(string)$result[$j]->tahunakhir,									
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
									"n_org_massa" =>$data['n_org_massa'],
									"d_peg_orgmulai" =>$data['d_peg_orgmulai'],
									"d_peg_orgakhir" =>$data['d_peg_orgakhir'],
									"a_orgb" =>$data['a_orgb'],
									"n_jabatan" =>$data['n_jabatan'],
									"n_orgb_pimpinan" =>$data['n_orgb_pimpinan'],
									"e_keterangan" =>$data['e_keterangan'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));
								
	     $db->insert('tm_organisasi',$tambah_data);
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
									"n_org_massa" =>$data['n_org_massa'],
									"d_peg_orgmulai" =>$data['d_peg_orgmulai'],
									"d_peg_orgakhir" =>$data['d_peg_orgakhir'],
									"a_orgb" =>$data['a_orgb'],
									"n_jabatan" =>$data['n_jabatan'],
									"n_orgb_pimpinan" =>$data['n_orgb_pimpinan'],
									"e_keterangan" =>$data['e_keterangan'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_organisasi',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."' ");
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
		 $db->delete('tm_organisasi', $where);
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
