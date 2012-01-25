<?php

class kkpb{
	
	public function getAllDataKKPB($id){
		
		global $wpdb;

    	$table_name = $wpdb->prefix . "kkpb_projekt";
    	$table_progress_name = $wpdb->prefix . "kkpb_progress";
    	
    	$sql = "SELECT * FROM ".$table_name." LEFT JOIN ".$table_progress_name." ON ".$table_name.".id=".$table_progress_name.".idprojekt WHERE ".$table_name.".id = ".$id;
    	$wynik = $wpdb->get_results($sql);
		return $wynik;
	}
	
	public function getAllProjects(){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_projekt";
		$rows = $wpdb->get_results("SELECT * FROM $table_name");
	
		return $rows;
	}
	
	public function getAllProgressbars(){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_progress";
		$rows = $wpdb->get_results("SELECT idprogress, idprojekt, nazwa  FROM $table_name");
	
		return $rows;
	}
	
	public function getProjectWithProgressbars($id){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_projekt";
		$table_name_progressbar = $wpdb->prefix . "kkpb_progress";
		$rows = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$id LIMIT 1");
		$progressbars = $wpdb->get_results("SELECT * FROM $table_name_progressbar WHERE idprojekt=$id");
	
		$rows->progressbars = $progressbars;
	
		return $rows;
	}
	
	public function isProjectActive($id){
		global $wpdb;
	
		$table_name_progressbar = $wpdb->prefix . "kkpb_progress";
		$progressbars = $wpdb->get_results("SELECT * FROM $table_name_progressbar WHERE idprojekt=$id AND status=1");
	
		if(count($progressbars) > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function getProject($id){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_projekt";
		$rows = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$id");
	
		return $rows;
	}
	
	public function getProjectName($id){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_projekt";
		$rows = $wpdb->get_var("SELECT nazwa FROM $table_name WHERE id=$id");
	
		return $rows;
	}
	
	public function getProjectProgressbars($idproject){
		global $wpdb;
		$table_name_progressbar = $wpdb->prefix . "kkpb_progress";
		$progressbars = $wpdb->get_results("SELECT * FROM $table_name_progressbar WHERE idprojekt=$idproject AND status != 3");
		return $progressbars;
	}
	
	public function getProjectProgressbar($id){
		global $wpdb;
		$table_name_progressbar = $wpdb->prefix . "kkpb_progress";
		$progressbars = $wpdb->get_results("SELECT * FROM $table_name_progressbar WHERE idprogress = $id AND status != 3");
		return $progressbars;
	}
	
	public function getProgressbarsOnStatus($idproject, $status){
		global $wpdb;
		$table_name_progressbar = $wpdb->prefix . "kkpb_progress";
		
		if($status == ''){
			$status = 1;
		}
		
		$progressbars = $wpdb->get_results("SELECT * FROM $table_name_progressbar WHERE idprojekt=$idproject AND status = $status");
		return $progressbars;
	}
	
	public function getProjectPercentage($id){
		global $wpdb;
		$table_progress_name = $wpdb->prefix . "kkpb_progress";
		
		$progresses = $wpdb->get_results("SELECT typ, procent, aktualna_wartosc, docelowa_wartosc FROM $table_progress_name WHERE idprojekt=$id AND status != 3");
		$count = 0;
		$perc = 0;
		
		if(count($progresses) > 0){
			foreach($progresses as $progress){
				if($progress->typ == 1){
					$perc += $progress->procent; 
				}else if($progress->typ == 2){
					$perc += ($progress->aktualna_wartosc / $progress->docelowa_wartosc) * 100;
				}
				$count++;
			}
			
			$perc = $perc / $count;
		}else{
			$perc = 0;
		}
		
		return $perc;
	}
	
	public function saveNewProject($name, $description, $link){
		global $wpdb;
		
		$table_name = $wpdb->prefix . "kkpb_projekt";
		$result = $wpdb->insert( $table_name, array( 'nazwa' => $name, 'opis' => $description, 'link' => $link, 'typ' => '2' ));
		
		if($result == 1){
			$id_last = mysql_insert_id();
		}else{
			$id_last = NULL;
		}
		return $id_last;
	}
	
	public function updateProject($id, $name, $description, $link){
		global $wpdb;
		
		$table_name = $wpdb->prefix . "kkpb_projekt";
		$result = $wpdb->update( $table_name, array( 'nazwa' => $name, 'opis' => $description, 'link' => $link), array('id' => $id));
		
		if($result == 1){
			$id_last = true;
		}else{
			$id_last = false;
		}
		return $id_last;
	}
	
	public function saveNewProgressbar($idprojekt, $nazwa_progress, $typ, $procent, $aktualna_wartosc, $docelowa_wartosc, $status){
		global $wpdb;
		
		$table_name = $wpdb->prefix . "kkpb_progress";
		$result = $wpdb->insert( $table_name, array( 'idprojekt' => $idprojekt, 'nazwa' => $nazwa_progress, 'typ' => $typ, 'procent' => $procent, 'aktualna_wartosc' => $aktualna_wartosc, 'docelowa_wartosc' => $docelowa_wartosc, 'status' => $status ));
		
		if($result == 1){
			$id_last = mysql_insert_id();
			$odp = array('hasError' => false, 'response' => '', 'id' => $id_last);
		}else{
			$id_last = NULL;
			$odp = array('hasError' => true, 'response' => '', 'id' => $id_last);
		}
		return json_encode($odp);
	}
	
	public function updateProgressbar($id, $nazwa_progress, $typ, $procent, $aktualna_wartosc, $docelowa_wartosc){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_progress";
		$result = $wpdb->update( $table_name, array( 'nazwa' => $nazwa_progress, 'typ' => $typ, 'procent' => $procent, 'aktualna_wartosc' => $aktualna_wartosc, 'docelowa_wartosc' => $docelowa_wartosc ), array('idprogress' => $id));
	
		if($result == 1){
			$odp = array('hasError' => false, 'response' => '');
		}else{
			$odp = array('hasError' => true, 'response' => '');
		}
		return json_encode($odp);
	}
	
	public function deleteProject($id){
		global $wpdb;
		
		$table_name = $wpdb->prefix . "kkpb_progress";
		$sql = "DELETE FROM ".$table_name." WHERE idprojekt = '".$id."'";
		$result = $wpdb->query($sql);
		
		if($result !== false){
			
			$table_name = $wpdb->prefix . "kkpb_projekt";
			$sql = "DELETE FROM ".$table_name." WHERE id = '".$id."' LIMIT 1";
			$result = $wpdb->query($sql);
			
			if($result != 0){
				return true;
			}else{
				return false;
			}
			
		}else{
			return false;
		}
	}
	
	public function deleteProgressbar($id){
		global $wpdb;
		$table_name = $wpdb->prefix . "kkpb_progress";
		
		$sql = "DELETE FROM $table_name WHERE idprogress = $id LIMIT 1";
		$wynik = $wpdb->query($sql);
		
		if ($wynik) {
			$odp = array('hasError' => false, 'response' => '');
		} else {
			$odp = array('hasError' => true, 'response' => '');
		}
		return json_encode($odp);
	}
	
	public function updateStatus($id, $status){
		global $wpdb;
	
		$table_name = $wpdb->prefix . "kkpb_progress";
		$result = $wpdb->update( $table_name, array( 'status' => $status ), array('idprogress' => $id));
	
		if($result == 1){
			$odp = array('hasError' => false, 'response' => '');
		}else{
			$odp = array('hasError' => true, 'response' => '');
		}
		return json_encode($odp);
	}
	
}