<?php
class Biodata_DataPenugasan_Service {
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
public function getTmPenugasan($cari,$pageNumber,$itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
						if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data = $db->fetchOne("select count(*) from tm_penugasan where 1=1 $cari ");
			}
			else		
			{
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				$result = $db->fetchAll("select id,i_nip,n_penugasan,d_penugasan,e_tujuan,q_lama,e_lama,n_pejabat,i_entry,d_entry
										from tm_penugasan where 1=1 $cari order by id desc limit $xLimit offset $xOffset");

							
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nip" =>(string)$result[$j]->i_nip,	
									"id" =>(string)$result[$j]->id,
									"n_penugasan" =>(string)$result[$j]->n_penugasan,
									"d_penugasan" =>(string)$result[$j]->d_penugasan,
									"e_tujuan" =>(string)$result[$j]->e_tujuan,
									"q_lama" =>(string)$result[$j]->q_lama,
									"e_lama" =>(string)$result[$j]->e_lama,
									"n_pejabat" =>(string)$result[$j]->n_pejabat,
									"i_entry" =>(string)$result[$j]->i_entry,
									"d_entry" =>(string)$result[$j]->d_entry);
				}
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

	     $tambah_data = array(		"i_nip" =>$data['i_nip'],
									"id" =>$data['id'],
									"n_penugasan" =>$data['n_penugasan'],
									"d_penugasan" =>$data['d_penugasan'],
									"e_tujuan" =>$data['e_tujuan'],
									"q_lama" =>$data['q_lama'],
									"e_lama" =>$data['e_lama'],
									"n_pejabat" =>$data['n_pejabat'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));	

	     $db->insert('tm_penugasan',$tambah_data);
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
	     $ubah_data = array(		"n_penugasan" =>$data['n_penugasan'],
									"d_penugasan" =>$data['d_penugasan'],
									"e_tujuan" =>$data['e_tujuan'],
									"q_lama" =>$data['q_lama'],
									"e_lama" =>$data['e_lama'],
									"n_pejabat" =>$data['n_pejabat'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_penugasan',$ubah_data, "i_nip = '".trim($data['i_nip'])."' and id = '".trim($data['id'])."' ");
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
