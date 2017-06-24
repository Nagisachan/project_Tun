<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php
	#$school_id = $_POST['school_id'];
	$school_id = 1091560018;
	$file_content = array();
	$jsonData = array();
	
    $dbconn = pg_connect("host=punyapat.org port=5432 dbname=taggerbot user=db password=db");
	$result = pg_query("select file.file_id,string_agg(distinct tag_category_item.name, ','), file.file_name from file inner join tag on file.file_id = tag.file_id inner join tag_category_item on (tag_category_item.category_id || '-' || tag_category_item.item) = tag.tag where file.school = '".$school_id."' group by file.file_id");
	while($row = pg_fetch_row($result))
		{
			$file_content['school_id'] = $school_id;
			$file_content['file_id'] = $row[0];
			$file_content['tag'] = $row[1];
			$file_content['file_name'] = $row[2];
			$jsonData[] = $file_content;
		}
		
	echo json_encode($jsonData,JSON_UNESCAPED_UNICODE)
?>