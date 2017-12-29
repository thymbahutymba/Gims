<?php

	abstract class Qualifica{
		const Admin = 0;
		const Segretario = 1;
		const Personal_Trainer = 2;
		const Atleta = 3;
	}

	function check_string($string)
	{
		if(preg_match_all("/\W/", $string)){
			return false;
		}
		return true;
	}

	function send_message($page, $msg)
	{
		$pos = strpos($_COOKIE['url'], "?");
		$url = substr($_COOKIE['url'], 0, $pos+1);
		$tmp = "location: http://".$url;
		foreach ($page as $key => $value) {
			$tmp .= $key."=".$value."&";
		}

		if(substr($tmp, -1)!="&"){
			$tmp.="&";
		}
		$tmp .="m=".$msg;
		$_SESSION['Letto']=1;
		header($tmp);
		$connection->close();
		die();
	}

	/*
	 * Search gyms
	 */

	function search_p($connection){
		foreach($_POST as $key => $value){
			${"p_".$key} = $value;
		}
		global $error;

		if(!check_string($p_text)){
			send_message($page, "Nome della palestra non ammesso.");
		}

		if(!$p_text){
			$query = "SELECT * FROM Palestra P ";
			if($p_citta){
				$query .= "WHERE P.Citta='".$p_citta."'";
			}
		}else{
			$word = explode(" ", $p_text);
			for($i = 0; $i < sizeof($word); $i++){
				$query = "SELECT * FROM Palestra P WHERE ";
				if($p_citta){
					$query .= "P.Citta='".$p_citta."' AND ";
				}
				$query .= "P.Nome LIKE '%".$word[$i]."%' ";
				if($i+1!=sizeof($word)){
					$query .= "UNION ";
				}
			}
		}
		$res = $connection->query($query);
		return $res;
	}

	/*
	 * Check if gyms is open
	 */

	function check_open($ora_apertura, $ora_chisura){
		date_default_timezone_set("Europe/Rome");
		if($ora_apertura<=date("H:i:s") && $ora_chisura>=date("H:i:s")){
			return true;
		}
		return false;
	}

	/*
	 * Check login
	 */

	function check_login(){
		if(session_status() == PHP_SESSION_NONE){
			session_start();
		}
		$pos = strpos($_COOKIE['url'], "?");
		$url = substr($_COOKIE['url'], 0, $pos+1);
		if(!isset($_SESSION['Login'])){
			$_SESSION['Letto']=1;
			header("location: http://".$url."p=login&m=Devi prima loggare.");
		}
	}

	/*
	 * Check match password
	 */

	function your_psw($connection, $psw){
	 $query = "SELECT Password FROM Persona WHERE Email='".$_SESSION['Email']."'";
	 $res = $connection->query($query);
	 $row = $res->fetch_assoc();
	 if($row['Password']!=hash('sha256', $psw)){
		 return false;
	 }
	 return true;
	}

	/*
	 * controlliamo se il grando è tale da poter poter effettuare la Promozione
	 */
	function check_qualifica($connection, $palestra, $new_qualifica, $page){
		if(get_qualifica($connection, $palestra)>$new_qualifica){
			send_message($page, "Non disponi del grado necessario per proseguire, inserimento interrotto!");
		}
	}

	/*
	 * Get qualifica from logged user into reference gyms
	 */
	function get_qualifica($connection, $palestra){
		if(session_status() == PHP_SESSION_NONE){
		 session_start();
		}
		$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=(
			SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."')
			AND ID_Palestra=".$palestra;
		$res = $connection->query($query);
		$row = $res->fetch_assoc();
		return $row['Qualifica'];
	}

	/*
 	 * passaggio dei parametri alla valuta js dentro home_gyms.php
 	 */
	function valuta_php($connection, $valutazione, $palestra){ 
		if(session_status()==PHP_SESSION_NONE){
			session_start();
		}
		$query = "SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'";
		$res = $connection->query($query);
		$tmp = $res->fetch_assoc();
		echo "valuta(".$valutazione.", ".$palestra.", ".$tmp['ID_Persona'].")";
	}

	/*
 	 * Stampa palestra
 	 */
	function stampa($row){

		require("connect.php");

		$query = "SELECT AVG(D.Valutazione) as Valutazione FROM Dispone D 
			WHERE D.ID_Palestra=".$row['ID_Palestra']." AND D.Valutazione is not NULL
			GROUP BY D.ID_Palestra";
		$tmp = $connection->query($query);
		$valutazione = 0;
		if($tmp){
			$valutazione = ($tmp->fetch_assoc())['Valutazione'];
		}

		echo "<a href=\"index.php?id=".$row['ID_Palestra']."\">";
			echo "<div class=\"tupla\">";
				if(file_exists("images/palestra/".$row['ID_Palestra'])){
					echo "<img class=\"display_gPropic\" src=\"./images/palestra/"
						.$row['ID_Palestra']."\" alt=\"propic\">";
				}else{
					echo "<img class=\"display_gPropic\" src=\"./images/palestra/none.jpg\" alt=\"propic\">";
				}
				echo "<div class=\"left\">";
					echo "<h2>".$row['Nome']."</h2>";
					echo "<p>".$row['Citta']."</p>";
				echo "</div>";
				echo "<div class=\"valutazione\">";

					for($i=0; $i<5;++$i){
						if($i+1<=$valutazione){
							echo "<img src=\"images/icon/icon_full.png\" alt=\"valutazione\""
								."class=\"img".$i."\"> ";

						}elseif($i+1-$valutazione<1 && $i+1-floor($valutazione)>=0.5){
							echo "<img src=\"images/icon/icon_semi.png\" alt=\"valutazione\""
								."class=\"img".$i."\"> ";

						}else{
							echo "<img src=\"images/icon/icon_clear.png\" alt=\"valutazione\"" 
								."class=\"img".$i."\"> ";
						}
					}

				echo "</div>";
				echo "<div class=\"right\">";

					if(check_open($row['OrarioApertura'], $row['OrarioChiusura'])){
						echo "<h3>Aperta</h3>";
					}else {
						echo "<h3>Chiusa</h3>";
					}

					echo "<span>".date("H:i", strtotime($row['OrarioApertura']))." - "
						.date("H:i", strtotime($row['OrarioChiusura']))."</span>";
				echo "</div>";
			echo "</div>";
		echo "</a>";
	}
?>
