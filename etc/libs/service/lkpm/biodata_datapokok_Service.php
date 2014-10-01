<?php
class Biodata_Datapokok_Service {
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

public function getDataPerusahaan($cari, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;

			$where =  " where 1 = 1 ".$cari;
			$order = " order by id ";
			$sqlProses = "select id, n_perusahaan, c_badanusaha, n_pemilik, n_akta_pendirian, n_namanotaris, n_pengesahankumham, n_npwp, c_kategori, n_bidangusaha, n_alamat_proyek, i_kel_proyek, i_kec_proyek, n_telp_proyek, n_fax_proyek, n_email_proyek, n_alamat_korespondensi, n_telp_korespondensi, n_fax_korespondensi, n_email_korespondensi, c_status, c_statusdelete, i_entry, d_entry	from tm_perusahaan".$where;	
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);

			}
			else
			{
				
				$sqlData = $sqlProses.$order." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);
			}
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				    $c_badanusaha = $db->fetchOne('select n_badanusaha from tr_badanusaha where id = ?',$result[$j]->c_badanusaha);
					$n_kec = $db->fetchOne('select n_kec from tr_kecamatan where id = ?',$result[$j]->i_kec_proyek);
					$n_kel = $db->fetchOne('select n_kel from tr_kelurahan where id = ?',$result[$j]->i_kel_proyek);
					
					$hasilAkhir[$j] = array(
						"id"=>(string)$result[$j]->id,
						"n_perusahaan"=>(string)$result[$j]->n_perusahaan,
						"c_badanusaha"=>$c_badanusaha,
						"n_pemilik"=>(string)$result[$j]->n_pemilik,
						"n_akta_pendirian"=>(string)$result[$j]->n_akta_pendirian,
						"n_namanotaris"=>(string)$result[$j]->n_namanotaris,
						"n_pengesahankumham"=>(string)$result[$j]->n_pengesahankumham,
						"n_npwp"=>(string)$result[$j]->n_npwp,
						"c_kategori"=>(string)$result[$j]->c_kategori,
						"n_bidangusaha"=>(string)$result[$j]->n_bidangusaha,
						"n_alamat_proyek"=>(string)$result[$j]->n_alamat_proyek,
						"i_kel_proyek"=>$n_kel,
						"i_kec_proyek"=>$n_kec,
						"n_telp_proyek"=>(string)$result[$j]->n_telp_proyek,
						"n_fax_proyek"=>(string)$result[$j]->n_fax_proyek,
						"n_email_proyek"=>(string)$result[$j]->n_email_proyek,
						"n_alamat_korespondensi"=>(string)$result[$j]->n_alamat_korespondensi,
						"n_telp_korespondensi"=>(string)$result[$j]->n_telp_korespondensi,
						"n_fax_korespondensi"=>(string)$result[$j]->n_fax_korespondensi,
						"n_email_korespondensi"=>(string)$result[$j]->n_email_korespondensi,
						"c_status"=>(string)$result[$j]->c_status,
						"c_statusdelete"=>(string)$result[$j]->c_statusdelete,
						"i_entry"=>(string)$result[$j]->i_entry,
						"d_entry"=>(string)$result[$j]->d_entry);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function detailPerusahaanById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where id = '$id' ";
			$sqlProses = "select id, n_perusahaan, c_badanusaha, n_pemilik, n_akta_pendirian, n_namanotaris, n_pengesahankumham, n_npwp, c_kategori, n_bidangusaha, n_alamat_proyek, i_kel_proyek, i_kec_proyek, n_telp_proyek, n_fax_proyek, n_email_proyek, n_alamat_korespondensi, n_telp_korespondensi, n_fax_korespondensi, n_email_korespondensi, c_status, c_statusdelete, i_entry, d_entry	from tm_perusahaan";

			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("id"  	=>(string)$result->id,
								"n_perusahaan"  	=>(string)$result->n_perusahaan,
								"c_badanusaha"  	=>(string)$result->c_badanusaha,
								"n_pemilik"  	=>(string)$result->n_pemilik,
								"n_akta_pendirian"  	=>(string)$result->n_akta_pendirian,
								"n_namanotaris"  	=>(string)$result->n_namanotaris,
								"n_pengesahankumham"  	=>(string)$result->n_pengesahankumham,
								"n_npwp"  	=>(string)$result->n_npwp,
								"c_kategori"  	=>(string)$result->c_kategori,
								"n_bidangusaha"  	=>(string)$result->n_bidangusaha,
								"n_alamat_proyek"  	=>(string)$result->n_alamat_proyek,
								"i_kel_proyek"  	=>(string)$result->i_kel_proyek,
								"i_kec_proyek"  	=>(string)$result->i_kec_proyek,
								"n_telp_proyek"  	=>(string)$result->n_telp_proyek,
								"n_fax_proyek"  	=>(string)$result->n_fax_proyek,
								"n_email_proyek"  	=>(string)$result->n_email_proyek,
								"n_alamat_korespondensi"  	=>(string)$result->n_alamat_korespondensi,
								"n_telp_korespondensi"  	=>(string)$result->n_telp_korespondensi,
								"n_fax_korespondensi"  	=>(string)$result->n_fax_korespondensi,
								"n_email_korespondensi"  	=>(string)$result->n_email_korespondensi,
								"c_status"  	=>(string)$result->c_status
								//"d_bulan_kuesioner"  	=>(string)$result->d_bulan_kuesioner,
								//"d_tahun_kuesioner"  	=>(string)$result->d_tahun_kuesioner,
								//"n_responden"  	=>(string)$result->n_responden,
								//"n_jabatan"  	=>(string)$result->n_jabatan,
								//"i_entry"  	=>(string)$result->i_entry,
								//"d_updateResponden"  	=>(string)$result->d_entry
								);
			//var_dump($hasilAkhir);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	public function tambahData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 
		 $n_perusahaan = $data['n_perusahaan'];
		 $c_badanusaha= $data['c_badanusaha'];
		 $n_pemilik= $data['n_pemilik'];
		 $n_akta_pendirian  	= $data['n_akta_pendirian'];
		 $n_namanotaris  	= $data['n_namanotaris'];
		 $n_pengesahankumham  	= $data['n_pengesahankumham'];
		 $n_npwp  	= $data['n_npwp'];
		 $c_kategori  	= $data['c_kategori'];
		 $n_bidangusaha  	= $data['n_bidangusaha'];
		 $n_alamat_proyek  	= $data['n_alamat_proyek'];
		 $i_kel_proyek  	= $data['i_kel_proyek'];
		 $i_kec_proyek  	= $data['i_kec_proyek'];
		 $n_telp_proyek  	= $data['n_telp_proyek'];
		 $n_fax_proyek  	= $data['n_fax_proyek'];
		 $n_email_proyek  	= $data['n_email_proyek'];
		 $n_alamat_korespondensi  	= $data['n_alamat_korespondensi'];
		 $n_telp_korespondensi  	= $data['n_telp_korespondensi'];
		 $n_fax_korespondensi  	= $data['n_fax_korespondensi'];
		 $n_email_korespondensi  	= $data['n_email_korespondensi'];
	     $c_status  	= $data['c_status'];
	     
		 $tambah_data = array(
										"n_perusahaan"  	=>  $n_perusahaan,
										"c_badanusaha"  	=>  $c_badanusaha,
										"n_pemilik"  	=>  $n_pemilik,
										"n_akta_pendirian"  	=>  $n_akta_pendirian,
										"n_namanotaris"  	=>  $n_namanotaris,
										"n_pengesahankumham"  	=>  $n_pengesahankumham,
										"n_npwp"  	=>  $n_npwp,
										"c_kategori"  	=>  $c_kategori,
										"n_bidangusaha"  	=>  $n_bidangusaha,
										"n_alamat_proyek"  	=>  $n_alamat_proyek,
										"i_kel_proyek"  	=>  $i_kel_proyek,
										"i_kec_proyek"  	=>  $i_kec_proyek,
										"n_telp_proyek"  	=>  $n_telp_proyek,
										"n_fax_proyek"  	=>  $n_fax_proyek,
										"n_email_proyek"  	=>  $n_email_proyek,
										"n_alamat_korespondensi"  	=>  $n_alamat_korespondensi,
										"n_telp_korespondensi"  	=>  $n_telp_korespondensi,
										"n_fax_korespondensi"  	=>  $n_fax_korespondensi,
										"n_email_korespondensi"  	=>  $n_email_korespondensi,
										"c_status" => $c_status,
										"i_entry"=>$data['i_entry'],
										"d_entry"=>date("Y-m-d"));
		 
		 $db->insert('tm_perusahaan',$tambah_data);
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
	     $ubah_data = array(	"i_nip"=>$data['i_nip'],
								"i_nik"=>$data['i_nik'],
								"i_nidn"=>$data['i_nidn'],
								"c_pendidik"=>$data['c_pendidik'],
								"n_nama"=>$data['n_nama'],
								"n_gelar"=>$data['n_gelar'],
								"n_tmplahir"=>$data['n_tmplahir'],
								"d_tgllahir"=>$data['d_tgllahir'],
								"n_jnskelamin"=>$data['n_jnskelamin'],
								"c_agama"=>$data['c_agama'],
								"c_goldarah"=>$data['c_goldarah'],
								"n_hobby "=>$data['n_hobby'],
								"c_pendidikan "=>$data['c_pendidikan'],
								"c_identitas "=>$data['c_identitas'],
								"n_identitas "=>$data['n_identitas'],
								"c_status "=>$data['c_status'],
								"c_bagian "=>$data['c_bagian'],
								"c_jabatan "=>$data['c_jabatan'],
								"c_gol "=>$data['c_gol'],
								"c_jurusan "=>$data['c_jurusan'],
								"d_pegawai"=>$data['d_pegawai'],
								"n_alamatrmh "=>$data['n_alamatrmh'],
								"i_kodepos "=>$data['i_kodepos'],
								"c_propinsi "=>$data['c_propinsi'],
								"c_kota "=>$data['c_kota'],
								"i_telp "=>$data['i_telp'],
								"i_hp "=>$data['i_hp'],
								"n_email "=>$data['n_email'],
								"i_entry "=>$data['i_entry'],
								"d_entry_update"=>date("Y-m-d"));

		$db->update('tm_pegawai',$ubah_data, "id = '".trim($data['id'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function hapusData($id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
			$where[] = "id = '".$id."'";
			$db->delete('tm_pegawai', $where);
			$db->commit();		
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
	
public function getTmPegawaiCv($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select i_nip,n_nama,n_gelar,n_tmplahir,d_tgllahir,n_jnskelamin,c_agama,
									c_goldarah,n_hobby, c_pendidikan, c_identitas, n_identitas, c_status, 
									c_bagian, c_jabatan, c_gol, c_jurusan, n_alamatrmh, i_kodepos, 
									c_propinsi, c_kota, i_telp, i_hp, n_email, i_entry, d_entry, 
									i_entry_update, d_entry_update,
									cast(Month(d_tglLahir)AS VARCHAR) as bulanlahir,
									DATENAME(d, d_tglLahir) as tanggallahir,
									DATENAME(yyyy, d_tglLahir) as tahunlahir,
									i_nik,i_nidn,c_pendidik,id
									from tm_pegawai where 1=1 $cari order by n_nama desc");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			
				$n_status = $db->fetchOne('select n_statusikatandosen from tr_statusikatandosen where c_statusikatandosen = ?',$result[$j]->c_status);
				$n_pendidikan = $db->fetchOne('select n_tingkatpend from tr_tingkatpend where c_tingkatpend = ?',$result[$j]->c_pendidikan);
				$n_agama = $db->fetchOne('select n_agama from tr_agama where c_agama = ?',$result[$j]->c_agama);
				$n_propinsi = $db->fetchOne('select n_propinsi from tr_propinsi where c_propinsi = ?',$result[$j]->c_propinsi);
				$n_kota = $db->fetchOne('select n_kab from tr_kabupaten where c_kab = ?',$result[$j]->c_kota);
				$n_goldarah = $db->fetchOne('select n_goldarah from tr_goldarah where c_goldarah = ?',$result[$j]->c_goldarah);
				$n_jabatan = $db->fetchOne('select n_jbtstruktural from tr_jbtstruktural where c_jbtstruktural = ?',$result[$j]->c_jabatan);
				$n_jurusan = $db->fetchOne('select n_prodi from tr_prodi where c_prodi = ?',$result[$j]->c_jurusan);
				$n_golongan = $db->fetchOne('select n_golongan from tr_golongan where n_golongan = ?',$result[$j]->c_gol);
				$n_pangkat = $db->fetchOne('select n_alias from tr_golongan where n_golongan = ?',$result[$j]->c_gol);
				
				
				$data[$j] = array("i_nip"=>(string)$result[$j]->i_nip,
								"i_nik"=>(String)$result[$j]->i_nik,
								"i_nidn"=>(String)$result[$j]->i_nidn,
								"c_pendidik"=>(String)$result[$j]->c_pendidik,
								"id"=>(String)$result[$j]->id,	
								"n_nama"=>(string)$result[$j]->n_nama,
								"n_gelar"=>(string)$result[$j]->n_gelar,
								"n_tmplahir"=>(string)$result[$j]->n_tmplahir,
								"d_tgllahir"=>(string)$result[$j]->d_tgllahir,
								"n_jnskelamin"=>(string)$result[$j]->n_jnskelamin,
								"c_agama"=>(string)$result[$j]->c_agama,
								"n_agama"=>$n_agama,
								"c_goldarah"=>(string)$result[$j]->c_goldarah,
								"n_goldarah"=>$n_goldarah,								
								"n_hobby"=>(string)$result[$j]->n_hobby ,
								"c_pendidikan"=>(string)$result[$j]->c_pendidikan ,								
								"n_pendidikan"=>$n_pendidikan,
								"c_identitas"=>(string)$result[$j]->c_identitas ,
								"n_identitas"=>(string)$result[$j]->n_identitas ,
								"c_status"=>(string)$result[$j]->c_status ,
								"n_status"=>$n_status ,
								"c_bagian"=>(string)$result[$j]->c_bagian ,
								"c_jabatan"=>(string)$result[$j]->c_jabatan ,
								"n_jabatan"=>$n_jabatan ,
								"c_gol"=>(string)$result[$j]->c_gol ,
								"n_golongan"=>$n_golongan,
								"n_pangkat"=>$n_pangkat,
								"c_jurusan"=>(string)$result[$j]->c_jurusan ,
								"n_jurusan"=>$n_jurusan ,
								"n_alamatrmh"=>(string)$result[$j]->n_alamatrmh ,
								"i_kodepos"=>(string)$result[$j]->i_kodepos ,
								"c_propinsi"=>(string)$result[$j]->c_propinsi,
								"n_propinsi"=>$n_propinsi,
								"c_kota"=>(string)$result[$j]->c_kota ,
								"n_kota"=>$n_kota ,
								"i_telp"=>(string)$result[$j]->i_telp ,
								"i_hp"=>(string)$result[$j]->i_hp ,
								"n_email"=>(string)$result[$j]->n_email ,
								"tanggallahir"=>(String)$result[$j]->tanggallahir,	
								"bulanlahir"=>(String)$result[$j]->bulanlahir,	
								"tahunlahir"=>(String)$result[$j]->tahunlahir,	
								"i_entry"=>(string)$result[$j]->i_entry ,
								"d_entry"=>(string)$result[$j]->d_entry ,								
								"i_entry_update"=>(string)$result[$j]->i_entry_update ,
								"d_entry_update"=>(string)$result[$j]->d_entry_update);
				}
						
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}		

}
?>
