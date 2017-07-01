<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>TaggerBot</title>

    <!-- Bootstrap -->
    <link href="lib/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Local css -->
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsxK2jlO7zzJyyhPG9ZcpJFJ_vswGdkag"></script>
    <script type="text/javascript" src="js/infobox.js"></script>

  </head>
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #428bca; border-color: #428bca; height: 51px;">
      <div class="container-fluid" >
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
          </button>
          <a href="#" class="navbar-brand"><img src="img/logo-white@2x.png" alt="logo"/></a> 
        </div>
        <div class="collapse navbar-collapse" id="myNavbar" style="background-color: #428bca; border-color: #428bca;">
          <ul class="nav navbar-nav">
            <li class="active"><a href="main.php">หน้าหลัก</a></li>
            <li><a href="#">เกี่ยวกับเรา</a></li>
            <li><a href="#">ติดต่อเรา</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <!-- <li><a href="Javascript: waitingDialog.show();"><span class="glyphicon glyphicon-log-in"></span> เข้าสู่ระบบ</a></li> -->
          </ul>
        </div>
      </div>
    </nav>
	
	<!--autorization-->
    <div class="container-fluid fill-height text-center" style="padding-top: 51px;">
      <div class="row content fill-height">
        <div class="col-md-2 sidenav left fill-height text-left">
          <div id="schoolSearchPanel" class="row">
            <select id="schoolListSelect2Multiple" multiple="multiple">
              <?php
                $dbconn = pg_connect("host=punyapat.org port=5432 dbname=taggerbot user=db password=db");
                $result = pg_query("select school_all.id,school_all.name from file join school_all on file.school = school_all.id group by school_all.id");
                  //$row = pg_fetch_row($result);
                                  
                while($row = pg_fetch_row($result))
                {
              ?>
                  <option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option>
              <?php
                }
                pg_close($dbconn);
              ?>
            </select>
          </div>
          <div id="keywordSearchPanel" class="row">
            <input id="keywordSearch" class="keywordSearch" type="text" placeholder="Keyword">
			
          </div>
          <div id="tagSearchPanel" class="row">
  		      <?php 
              $dbconn = pg_connect("host=punyapat.org port=5432 dbname=taggerbot user=db password=db");
              $result = pg_query("select * from tag_category join tag_category_item on tag_category.id = tag_category_item.category_id ");
              $tag_category = array();
              array_push($tag_category,"ครูผู้สอน - พัฒนาครู");
              $tag_content = array();
              $subCategory = array();
              $id = 32;
              while($row = pg_fetch_row($result)) {
                if($id == $row[0]) {
  						    $tag_content['item'] = $row[6];
  						    $tag_content['item_name'] = $row[7];
  						    $tag_content['item_status'] = $row[8];
  						    $tag_content['check_status'] = "false";
  						    $category_name = $row[1];
  						    $subCategory[$category_name][] = $tag_content;
                }
                else {
  						    $id ++;
  						    array_push($tag_category,$row[1]);
  						    $tag_content['item'] = $row[6];
  						    $tag_content['item_name'] = $row[7];
  						    $tag_content['item_status'] = $row[8];
  						    $tag_content['check_status'] = "false";
  						    $category_name = $row[1];
  						    $subCategory[$category_name][] = $tag_content;
  					     }
  				    }
              pg_close($dbconn);
            ?>

            <?php for($domainTagNo = 32; $domainTagNo <= $id; $domainTagNo++) { ?>
              <div class="row" style="margin: 0px; padding: 0px;">
                <ul class="checklist_tag checkbox domainTag col-xs-11">
                  <li style="list-style: none;">
                    <label><input id="domainTag" type="checkbox" > <?php $tag_category_name = $tag_category[$domainTagNo-32]; echo $tag_category_name; ?></label>
    							  <ul id="subDomainTag<?php echo $domainTagNo ?>List" class="subDomainTagList">
    							  <?php for($subDomainTagNo = 0; $subDomainTagNo <= count($subCategory[$tag_category[$domainTagNo-32]])-1; $subDomainTagNo++) { ?>
                      <li class="checkbox col-xs-12 subDomainTag">
                        <label><input type="checkbox" class="name_tag" value="<?php echo $domainTagNo."-".$subCategory[$tag_category[$domainTagNo-32]][$subDomainTagNo]['item'] ?>"><?php echo $subCategory[$tag_category[$domainTagNo-32]][$subDomainTagNo]['item_name'] ?></label>
                      </li>
                    <?php } ?>
                    </ul>
                  </li>
                </ul>
                <a id="moreTag<?php echo $domainTagNo ?>Toggle" class="col-xs-1 moreTagToggle" href="JavaScript: moreTagToggle(<?php echo $domainTagNo ?>);">[+]</a>
              </div>
            <?php } ?>
          </div>
          <div class="row" style="padding: 5px 15px 10px 15px;">
            <input id="submitSearch" class="submitSearch" type="button" value="search" onclick="search(1)" >
          </div>
        </div>

        <div class="col-md-5 fill-height text-center" style="margin: 0px; padding: 0px;"> 
          <div id="googleMap" style="width: 100%; height: 100%; display: block;">
          </div>
          <div id="schoolPopup" onclick="">
          </div>
        </div>

        <div class="col-md-5 sidenav right fill-height" >
          <div class="row" style="margin: 0px; padding: 0px 0px 10px 0px; width: 100%;">
            <div class="col-xs-5" style="padding: 0px 0px 0px 0px; text-align: left;">
              หน้า <input id="pageNo" class="pageNo" type="number" value="1" min="1" max="10" step="1"> จาก <label id="numberOfPage">0</label>
            </div>
            <div class="col-xs-7" style="padding: 0px; text-align: right;">
              เรียงลำดับ:
              <select id="orderBy">
                <option value="0" selected>วันที่เผยแพร่ - ล่าสุด</option>
  				      <option value="1">วันที่เผยแพร่ - เก่าสุด</option>
  				      <option value="2">จำนวนครั้งที่ดาวน์โหลด - จากมากไปน้อย</option>
  				      <option value="3">จำนวนครั้งที่ดาวน์โหลด - จากน้อยไปมาก</option>
              </select>
            </div>
		      </div>
          <div id="loader" style="display: none;"></div>
		      <div id="searchResult">
            </br> กรุณาค้นหาจาก Tag ด้านซ้าย
          </div>


        </div>

      </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>    
  </body>
</html>