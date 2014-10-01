<?php
class Biodata_Datapangkat_Service {
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
public function getTmPangkat($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
				$result = $db->fetchAll("select i_nik,c_golongan,i_sk,d_sk,d_tmt_golongan,
										cast(Month(d_sk)AS VARCHAR) as bulansk,
										DATENAME(d, d_sk) as tanggalsk,
										DATENAME(yyyy, d_sk) as tahunsk,
										cast(Month(d_tmt_golongan)AS VARCHAR) as bulangolongan,
										DATENAME(d, d_tmt_golongan) as tanggalgolongan,
										DATENAME(yyyy, d_tmt_golongan) as tahungolongan,
										q_kerja_bulan,q_kerja_tahun,n_pejabat_ttd,v_gaji_pokok,e_keterangan,
										a_file,n_tempat_kerja,i_entry,d_entry from tm_pangkat where 1=1 $cari 
										order by c_golongan desc ");

										
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$n_pangkat = $db->fetchOne('select n_alias from tr_golongan where n_golongan = ?',$result[$j]->c_golongan);						
						
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"c_golongan" =>(string)$result[$j]->c_golongan,
									"i_sk" =>(string)$result[$j]->i_sk,
									"d_sk" =>(string)$result[$j]->d_sk,
									"d_tmt_golongan" =>(string)$result[$j]->d_tmt_golongan,	
									"n_pangkat" =>$n_pangkat,
									"i_entry" =>(string)$result[$j]->i_entry,
									"bulansk"=>(String)$result[$j]->bulansk,	
									"tanggalsk"=>(String)$result[$j]->tanggalsk,	
									"tahunsk"=>(String)$result[$j]->tahunsk,
									"bulangolongan"=>(String)$result[$j]->bulangolongan,	
									"tanggalgolongan"=>(String)$result[$j]->tanggalgolongan,	
									"tahungolongan"=>(String)$result[$j]->tahungolongan,
									"q_kerja_bulan"=>(String)$result[$j]->q_kerja_bulan,
									"q_kerja_tahun"=>(String)$result[$j]->q_kerja_tahun,
									"n_pejabat_ttd"=>(String)$result[$j]->n_pejabat_ttd,
									"v_gaji_pokok"=>(String)$result[$j]->v_gaji_pokok,
									"e_keterangan"=>(String)$result[$j]->e_keterangan,
									"n_tempat_kerja"=>(String)$result[$j]->n_tempat_kerja,
									"a_file"=>(String)$result[$j]->a_file,
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
								"c_golongan"=>$data['c_golongan'],
								"i_sk"=>$data['i_sk'],
								"d_sk"=>$data['d_sk'],
								"d_tmt_golongan"=>$data['d_tmt_golongan'],
								"i_entry"=>$data['i_entry'],								
								"q_kerja_bulan"=>$data['q_kerja_bulan'],
								"q_kerja_tahun"=>$data['q_kerja_tahun'],
								"n_pejabat_ttd"=>$data['n_pejabat_ttd'],
								"v_gaji_pokok"=>$data['v_gaji_pokok'],
								"e_keterangan"=>$data['e_keterangan'],
								"n_tempat_kerja"=>$data['n_tempat_kerja'],
								"a_file"=>$data['a_file'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_pangkat',$tambah_data);
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
								"c_golongan"=>$data['c_golongan'],
								"i_sk"=>$data['i_sk'],
								"d_sk"=>$data['d_sk'],
								"d_tmt_golongan"=>$data['d_tmt_golongan'],
								"q_kerja_bulan"=>$data['q_kerja_bulan'],
								"q_kerja_tahun"=>$data['q_kerja_tahun'],
								"n_pejabat_ttd"=>$data['n_pejabat_ttd'],
								"v_gaji_pokok"=>$data['v_gaji_pokok'],
								"e_keterangan"=>$data['e_keterangan'],
								"n_tempat_kerja"=>$data['n_tempat_kerja'],	
								"a_file"=>$data['a_file'],	
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_pangkat',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_golongan = '".trim($data['c_golongan2'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function hapusData($i_nik,$c_golongan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_golongan = '".$c_golongan."'";
		 $db->delete('tm_pangkat', $where);
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
