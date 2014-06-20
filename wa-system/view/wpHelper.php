<?php 
if(wa()->getEnv() == 'frontend') {
    $info = array(
        'id' => 'custommenu',
        'app_id' => 'shop',
    );
    $CM = new shopCustommenuPlugin($info);
    $test = $CM->displayMenu();
    $this->assign('wa_test', $test);
}
		  
		  
          $con=mysql_connect("193.0.61.200","fonarik_fonarik2","221976");
	      if($con){ mysql_select_db("fonarik_fonarik2",$con); $test=""; }
	      else{ $test="fail"; }
		  mysql_query('SET character_set_database = utf8');
		  mysql_query('SET NAMES utf8');
          
		  $sql_banner = "SELECT DISTINCT p.post_title, p.guid, p.ID, p.post_date, m.meta_value FROM wp_posts AS p
		  LEFT JOIN wp_postmeta AS m ON p.ID = m.post_id 
		  WHERE p.post_status = 'publish' AND p.post_type = 'slideshow'
		  AND m.meta_key = 'meta_options' ORDER BY p.post_date";					
		  $rsd_banner = mysql_query($sql_banner);
		  $b = 1; $controller = ''; $slider = ''; $slider_small='';
		  while($banner = mysql_fetch_array($rsd_banner)) {
			  $banner_meta = unserialize($banner['meta_value']);
			  $sql_file = 'SELECT guid FROM wp_posts WHERE ID = '.$banner_meta['image_id'].' LIMIT 0,1';
			  $rsd_file = mysql_query($sql_file);
			  $file = mysql_fetch_assoc($rsd_file);	
			  	
			  if($banner_meta['type'] == '6'){	  
			  $slider .='<div id="slide'.$b.'">
			 <a href="'.$banner_meta['link'].'"><img src="'.$file['guid'].'" alt="" title="" width="984"/></a>
			 <div class="slid_text"> <h3 class="slid_title"><span>'.$banner_meta['slogan1'].'</span></h3>			
			 <p><span><a href="'.$banner_meta['link'].'" style="color:#999;">'.$banner_meta['slogan2'].'</a></span></p></div>	  
            </div>';
			  $controller .= '<div class="control"><span>'.$b.'</span></div>';
			  $b++;
			  } else if($banner_meta['type'] == '7'){
				$slider_small .= '<div class="grid_4" style="width:320px;margin:0px 0px 0px 12px;">
                 <a href="'.$banner_meta['link'].'" class="button_block best_price">
                 <img src="'.$file['guid'].'" alt="Banner 1" width="320" height="101"/>
                 </a></div>';
			  }

		  }
		  
		  $this->assign('wa_banner', $slider); 
		  $this->assign('wa_small_banner', $slider_small); 
		  $this->assign('wa_controller', $controller);?>