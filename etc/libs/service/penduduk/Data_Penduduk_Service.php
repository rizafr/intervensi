<?php
class Data_Penduduk_Service {
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
	
	public function getAll(){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchAll("select * from data_penduduk");
		     return $result;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	
	public function getkel($kel){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
				$result = $db->fetchAll("select * from data_penduduk where kelurahan='$kel'");
		    return $result;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	}
	
	public function getcaripend($cari){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
			$result = $db->fetchAll("select * from data_penduduk where NIK like '$cari%'");
		    return $result;
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	public function getdetail($NIK){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
			$result = $db->fetchAll("select * from data_penduduk where NIK='$NIK'");
		    return $result;
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	public function getedit($NIK){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 		
			$result = $db->fetchAll("select * from data_penduduk where NIK='$NIK'");
		    return $result;
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	public function getsimpanedit(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			
			//var_dump($dataMasukan);
			$paramInput = array("NIK" => $dataMasukan['NIK'],
								"nama_lengkap" => $dataMasukan['nama_lengkap'],
								"nama_kep" => $dataMasukan['nama_kep'],
								"alamat" => $dataMasukan['alamat'],
								"rt" => $dataMasukan['rt'],
								"rw" => $dataMasukan['rw'],
								"dusun" => $dataMasukan['dusun'],
								"kode_pos" => $dataMasukan['kode_pos'],
								"jk" => $dataMasukan['jk'],
								"tempat_lahir" => $dataMasukan['tempat_lahir'],
								"tgl_lahir" => $dataMasukan['tgl_lahir'],
								"no_akta" => $dataMasukan['no_akta'],
								"gol_darah" => $dataMasukan['gol_darah'],
								"agama" => $dataMasukan['agama'],
								"pekerjaan" => $dataMasukan['pekerjaan'],
								"nama_ibu" => $dataMasukan['nama_ibu'],
								"nama_ayah" => $dataMasukan['nama_ayah'],
								"status" => $dataMasukan['status'],
								"kelurahan" => $dataMasukan['kelurahan']);
								
			$where[] = " NIK = '".$dataMasukan['NIK']."'";
			
			$db->update('data_penduduk',$paramInput, $where);
			$db->commit();			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "sukses";
			}
	   }
	}
	
	public function userhapus($NIK) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			// $paramInput = array("NIK"	=> 'NIK');	
								
			$where[] = " NIK = '".$NIK."'";
			
			$db->delete('data_penduduk', $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "sukses";
			}
	   }
	}
	
	public function getTmAlamat($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
				$result = $db->fetchAll("select id,i_nik,d_mulai,d_akhir,n_alamat,c_rt,c_rw,n_kelurahan,
										n_kecamatan,c_propinsi,c_kota,i_kodepos,i_tlprmh,e_keterangan,i_entry,d_entry,										
										cast(Month(d_mulai)AS VARCHAR) as bulanmulai,
										DATENAME(d, d_mulai) as tanggalmulai,
										DATENAME(yyyy, d_mulai) as tahunmulai,
										cast(Month(d_akhir)AS VARCHAR) as bulanakhir,
										DATENAME(d, d_akhir) as tanggalakhir,
										DATENAME(yyyy, d_akhir) as tahunakhir
										from tm_alamat where 1=1 $cari order by d_mulai desc");

									
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$n_propinsi = $db->fetchOne('select n_propinsi from tr_propinsi where c_propinsi = ?',$result[$j]->c_propinsi);
				$n_kota = $db->fetchOne('select n_kab from tr_kabupaten where c_kab = ?',$result[$j]->c_kota);
			
				$data[$j] = array("id"=>(string)$result[$j]->id,
								"i_nik"=>(string)$result[$j]->i_nik,
								"d_mulai"=>(string)$result[$j]->d_mulai,
								"d_akhir"=>(string)$result[$j]->d_akhir,
								"n_alamat"=>(string)$result[$j]->n_alamat,
								"c_rt"=>(string)$result[$j]->c_rt,
								"c_rw"=>(string)$result[$j]->c_rw,
								"n_kelurahan"=>(string)$result[$j]->n_kelurahan,
								"n_kecamatan"=>(string)$result[$j]->n_kecamatan,
								"c_propinsi"=>(string)$result[$j]->c_propinsi,
								"n_propinsi"=>$n_propinsi,
								"c_kota"=>(string)$result[$j]->c_kota,
								"n_kota"=>$n_kota,
								"i_kodepos"=>(string)$result[$j]->i_kodepos,
								"i_tlprmh"=>(string)$result[$j]->i_tlprmh,
								"e_keterangan"=>(string)$result[$j]->e_keterangan,
								"bulanmulai"=>(string)$result[$j]->bulanmulai,
								"tanggalmulai"=>(string)$result[$j]->tanggalmulai,
								"tahunmulai"=>(string)$result[$j]->tahunmulai,
								"bulanakhir"=>(string)$result[$j]->bulanakhir,
								"tanggalakhir"=>(string)$result[$j]->tanggalakhir,
								"tahunakhir"=>(string)$result[$j]->tahunakhir,
								"i_entry"=>(string)$result[$j]->i_entry,
								"d_entry"=>(string)$result[$j]->d_entry);								
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
							"d_mulai"=>$data['d_mulai'],
							"d_akhir"=>$data['d_akhir'],
							"n_alamat"=>$data['n_alamat'],
							"c_rt"=>$data['c_rt'],
							"c_rw"=>$data['c_rw'],
							"n_kelurahan"=>$data['n_kelurahan'],
							"n_kecamatan"=>$data['n_kecamatan'],
							"c_propinsi"=>$data['c_propinsi'],
							"c_kota"=>$data['c_kota'],
							"i_kodepos"=>$data['i_kodepos'],
							"i_tlprmh"=>$data['i_telp'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));
									
	     $db->insert('tm_alamat',$tambah_data);
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
							"d_mulai"=>$data['d_mulai'],
							"d_akhir"=>$data['d_akhir'],
							"n_alamat"=>$data['n_alamat'],
							"c_rt"=>$data['c_rt'],
							"c_rw"=>$data['c_rw'],
							"n_kelurahan"=>$data['n_kelurahan'],
							"n_kecamatan"=>$data['n_kecamatan'],
							"c_propinsi"=>$data['c_propinsi'],
							"c_kota"=>$data['c_kota'],
							"i_kodepos"=>$data['i_kodepos'],
							"i_tlprmh"=>$data['i_telp'],
							"e_keterangan"=>$data['e_keterangan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));

		$db->update('tm_alamat',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."' ");
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
		 $db->delete('tm_alamat', $where);
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
