<?php
	$school_id = $_POST['school_id'];
	$school_content = array();
	$jsonData = array();
	
    $dbconn = pg_connect("host=punyapat.org port=5432 dbname=taggerbot user=db password=db");
	$result = pg_query("SELECT * FROM school_all where id = '".$school_id."'");
    $row = pg_fetch_row($result);
		
	$school_content['id'] = $row[0];
	$school_content['name'] = $row[1];
	$school_content['subdistrict'] = $row[2];
	$school_content['district'] = $row[3];
	$school_content['province'] = $row[4];
	$school_content['postcode'] = $row[5];
	$school_content['type'] = $row[6];
	$school_content['department'] = $row[7];
	$school_content['telephone'] = $row[8];
	$school_content['fax'] = $row[9];
	$school_content['website'] = $row[10];
	$school_content['email'] = $row[11];
	$school_content['latitude'] = $row[12];
	$school_content['longtitude'] = $row[13];
	$jsonData[] = $school_content;
		
	echo json_encode($jsonData,JSON_UNESCAPED_UNICODE)
?>