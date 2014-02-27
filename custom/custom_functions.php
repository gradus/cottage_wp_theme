<?php
/* By taking advantage of hooks, filters, and the Custom Loop API, you can make Thesis
 * do ANYTHING you want. For more information, please see the following articles from
 * the Thesis User's Guide or visit the members-only Thesis Support Forums:
 * 
 * Hooks: http://diythemes.com/thesis/rtfm/customizing-with-hooks/
 * Filters: http://diythemes.com/thesis/rtfm/customizing-with-filters/
 * Custom Loop API: http://diythemes.com/thesis/rtfm/custom-loop-api/

 ---:[ place your custom code below this line ]:---*/

//<!--Do not remove this line
//Designed by TheCreatology-->
//<div id="slim-subscribe">
//<span>Need Blog Updates?</span>
//<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=thecreatology', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
//<h4>Subscribe to TheCreatology via <a href="http://feeds.feedburner.com/thecreatology" target="_blank" rel="nofollow">RSS</a> | <a href="http://feedburner.google.com/fb/a/mailverify?uri=thecreatology">Email</a></h4>
//<input type="text" name="email" value="Your e-Mail address..." onblur="if (this.value == '') {this.value = 'Your e-Mail address...';}" onfocus="if (this.value == 'Your e-Mail address...') {this.value = '';}">
//<input type="hidden" value="thecreatology" name="uri"/>
//<input type="hidden" name="loc" value="en_US"/>
//<input type="submit" value="Subscribe">
//</form>
//</div>
//<!--Designed by TheCreatology, Author: Aky Joe-->



function create_shop() {
  $shop_args = array(
  'label' => __('Shop'),
  'singular_label' => __('Shop'),
  'public' => true,
  'show_ui' => true,
  'capability_type' => 'post',
  'hierarchical' => false,
  'taxonomies' => array('post_tag', 'category'),
  'rewrite' => array( 'slug' => 'shop', 'with_front' => false ),
  'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'comments', 'trackbacks', 'author')
  );
  register_post_type('shop', $shop_args);
}

add_action('init', 'create_shop');

function shop_admin_menu() {
  $post_options = new thesis_post_options;
  $post_options->meta_boxes();
  foreach ($post_options->meta_boxes as $meta_name => $meta_box)
  {
    add_meta_box($meta_box['id'], $meta_box['title'], array('thesis_post_options', 'output_' . $meta_name . '_box'), 'shop', 'normal', 'high'); #wp
  }
  add_action('save_post', array('thesis_post_options', 'save_meta')); #wp
}

add_action('admin_menu','shop_admin_menu');

function shop_edit_columns($shop_columns){
  $shop_columns = array(
  "cb" => "<input type=\"checkbox\" />",
  "title" => "Item Name",
  "description" => "Description",
  );
  return $shop_columns;
}

function shop_columns_display($shop_columns){
  switch ($shop_columns)
  {
    case "description":
    the_excerpt();
    break;
  }
}

add_filter('manage_edit-shop_columns', 'shop_edit_columns');
add_action('manage_posts_custom_column', 'shop_columns_display');

function get_first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  
  // need to do something with this hacky logic
  if($first_img == "http://cottageatthecrossroads.com/wp-content/uploads/2013/02/Currier-and-Ives-plates-010.jpg") {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $matches [0] [0], $match);
    preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $match [0] [0] , $output);
    $first_img = str_replace('<img class="aligncenter size-full wp-image-5623" alt="white enamel decorative shelf" src="','',$output [0] [0]);
    $first_img = str_replace('" width="640" height="424" /></a>This white enamel shelf with 3 pot holders is as vintage looking as you can get! It is a reproduction that is a copy of a French antique that was used in the kitchen or laundry room to hold soaps. Even though these are reproductions, we were lucky to have found 2 of them!<a href="http://cottageatthecrossroads.com/wp-content/uploads/2013/02/Currier-and-Ives-plates-010.jpg"><img class="aligncenter size-full wp-image-5624" alt="white enamel decorative shelf" src="http://cottageatthecrossroads.com/wp-content/uploads/2013/02/Currier-and-Ives-plates-010.jpg" width="640" height="424" /></a></p>','',$first_img);
    echo $first_img;
  } else {
    if($first_img == "http://cottageatthecrossroads.com/wp-content/uploads/2013/02/vintage-metal-Burpee-watering-can.jpg") {
      echo str_replace(".jpg","-300x208.jpg",$first_img);
    } else if($first_img == "http://cottageatthecrossroads.com/wp-content/uploads/2013/05/terracotta-pot-holder-with-price-label.jpg") {
      echo $first_img;
    } else {
      echo str_replace(".jpg","-300x198.jpg",$first_img);
    }
  }

  //echo str_replace(".jpg","-300x198.jpg",$first_img);
  //return $first_img;
}

function shop_header_image() {
  if (is_page('5485', 'Shop')) { ?>
<img src="<?php bloginfo('template_directory'); ?>/custom/images/shop_header.jpg" style="margin-left:-18px" />

<?php
	}
}

function shop_post_header_image() { ?>
<img src="<?php bloginfo('template_directory'); ?>/custom/images/shop_header.jpg" style="margin-left:-18px" />
<?php
}

function radio_header_image() {
  if (is_page('6862', 'Crossroads Radio')) { ?>
<img src="<?php bloginfo('template_directory'); ?>/custom/images/radio_header.jpg" style="margin-left:-3px" />

<?php
	}
}

add_action('thesis_hook_header','shop_header_image');
add_action('thesis_hook_header','radio_header_image');


function top_shop_copy() {
  if (is_page('5485', 'Shop')) { ?>

<div style="font-size:19px; margin:20px 20px 8px 0; line-height:20px; text-align:center;">
Welcome to our shop featuring items that we've hand-picked.
</div>
<div style="font-size:14px; margin:5px 20px 10px 0; line-height:20px; text-align:center;">
Look around, click on any item to see more pics and information, and email us at 
<a href="mailto:cottageatthecrossroads@gmail.com">cottageatthecrossroads@gmail.com</a> 
if you are interested in purchasing any of these items.
</div>
<?php
	}
}

add_action('thesis_hook_feature_box','top_shop_copy');

function custom_page_type_shop() {
  if (is_page('5485', 'Shop')) { ?>
    <table>
    <tr>
    <?php
    query_posts(array('post_type' => 'shop', 'posts_per_page' => 60));
    $count = 0;
    while ( have_posts() ) : the_post();
    if ($count%3 == 0 && $count != 0) {
    ?>
    </tr>
    <tr>
    <?php }
    ?>
      <td>
        <a href='<?php the_permalink() ?>' rel='bookmark' title='<?php the_title(); ?>'>
          <img src='<?php  get_first_image() ?>' width='275px' height="173px" style='padding:15px' />
          <h2 style="text-align:center"><?php the_title(); ?><h2>
        </a>
      </td>
  <?php
    $count++;
    endwhile;
    ?>
    </tr>
    </table>
    <!-- <div class="navigation" style="font-size: 16px; float: right; padding:5px 30px 0 0;">
    <p><?php // posts_nav_link(); ?></p>
    </div> -->
    <div style="clear:both;"></div>
    <div style="font-size:14px; margin:20px 25px 10px 25px; line-height:20px;">
    The price quoted does not include shipping and handling which will be calculated based on destination and weight.  &nbsp;
    Right now we are only shipping to areas in the continental United States.  &nbsp;
    We accept credit cards through PayPal and will pack and ship your item within 48 hours.  &nbsp;
    Refunds will be made with return shipping paid by the buyer.  &nbsp;<br /><br />
    If you do not see anything today, please come back again soon as we are constantly updating our stock.
    </div>
  <?php }
}

remove_action('thesis_hook_custom_template', 'thesis_custom_template_sample');
add_action('thesis_hook_custom_template', 'custom_page_type_shop');

//function rm_linkwithin_shop_post($content){
    //global $linkwithin_code_start, $linkwithin_code_end;
    //$posStart = strpos($content, $linkwithin_code_start);
    //$posEnd = strpos($content, $linkwithin_code_end);
    //if ($posStart){
        //if ($posEnd == false){
            //$content = str_replace(substr($content,$posStart,strlen($content)),'',$content);
        //} else {
            //$content = str_replace(substr($content,$posStart,$posEnd-$posStart+strlen($linkwithin_code_end)),'',$content);
        //}
    //}
    //$content = $content . linkwithin_add_code('');
    //return $content;
//}

function shop_post_link() { ?>
<br /><br />
<a id="back_link" href="/shop">Back to Shop</a>
<br /><br />
<?php
}

function add_social_media($content){
  //global $linkwithin_code_start;
  //$posStart = strpos($content, $linkwithin_code_start);
  //$content = str_replace(substr($content,$posStart,0),'',$content);

$social = '<div style="margin-left:135px">
  <a href="http://www.facebook.com/CottageattheCrossroads" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_1.jpg" /></a>
  <a href="http://pinterest.com/janewindham/" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_2.jpg" /></a>
  <a href="https://twitter.com/janewindham" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_3.jpg" /></a>
  <a href="https://plus.google.com/u/0/109228096052617439238/posts" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_4.jpg" /></a>
  <a href="http://www.hometalk.com/janewindham" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_5.jpg" /></a>
  <a href="http://feeds.feedburner.com/cottageatthecrossroads" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_6.jpg" /></a>
  <a href="http://feedburner.google.com/fb/a/mailverify?uri=CottageAtTheCrossroads" target="_blank">
  <img style="float:left;" src="/wp-content/themes/thesis_182/custom/images/social_media/social_media_7.jpg" /></a>
  </div><br /><br />';

//$content = $content . $social . linkwithin_add_code('');

echo $social;
}

function add_shareaholic_buttons() {
  $shareaholic_div = "<div class='shareaholic-canvas' data-app='share_buttons' data-app-id='6144'></div>";
  echo $shareaholic_div;
}

function shop_post_rules() {
  if (! (is_page('5485', 'Shop') || is_page('6862', 'Crossroads Radio')) and (! preg_match("/\/shop\//",$_SERVER["REQUEST_URI"]))) { ?>
    <div class="adsense_leaderboard"><script type="text/javascript"><!--
    google_ad_client = "ca-pub-6075822128245371";
    /* Leaderboard */
    google_ad_slot = "9189994096";
    google_ad_width = 728;
    google_ad_height = 90;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script></div>
  <?php // add_action('the_content','add_social_media');
  add_action('thesis_hook_after_post', 'add_ad_network');
  add_action('thesis_hook_after_post','add_social_media');
  //add_action('thesis_hook_after_post','add_shareaholic_buttons');
    ?>
  <?php }
  if (preg_match("/\/shop\//",$_SERVER["REQUEST_URI"])) {
    remove_action('thesis_hook_header','shop_header_image');
    add_action('thesis_hook_header','shop_post_header_image');
    add_action('thesis_hook_after_post', 'shop_post_link');
    //add_action('the_content','rm_linkwithin_shop_post');
?>
  <style>
    .custom #header {
      background: url('images/shop_header.jpg') 0 0 no-repeat;
      height: 355px !important;
    }
    #___plus_0 {
      height: 0px !important;
    }
    .format_text p {
      margin-bottom: 0px !important;
    }
    img.aligncenter, img.center {
      margin-top: 1.571em !important;
    }
    #comments {
      display: none !important;
    }
    #back_link {
      font-size:30px;
      margin-left: 240px;
      text-decoration: none;
    }
    a#back_link:hover {
      text-decoration: underline;
    }
    .headline_meta {
      //display: none !important;
    }
  </style>
<?php
  }
}

add_action('thesis_hook_before_header', 'shop_post_rules');

function radio_rules() {
  if (is_page('6862', 'Crossroads Radio') ) {
?>
  <style>
    .custom #header {
      background: url('images/radio_header.jpg') 0 0 no-repeat;
      height: 305px !important;
    }
    #___plus_0 {
      height: 0px !important;
    }
    .format_text p {
      margin-bottom: 0px !important;
    }
    img.aligncenter, img.center {
      margin-top: 1.571em !important;
    }
    #comments {
      //display: none !important;
    }
    #back_link {
      font-size:30px;
      margin-left: 240px;
      text-decoration: none;
    }
    a#back_link:hover {
      text-decoration: underline;
    }
    .headline_meta {
      //display: none !important;
    }

  </style>
<?php
  }
}

add_action('thesis_hook_before_header', 'radio_rules');

function add_site_verification() {
  $site_verification = '<meta name="google-site-verification" content="3xTq3lHy_lb2Nwtzaj4G7QyOrXdjSZfQoyOH4oXwxMU" />';
  echo $site_verification;
}
add_action('wp_head', 'add_site_verification');

function add_ad_network() {
  $ad_network = '<br /><div style="text-align:center"><script type="text/javascript" src="http://ap.lijit.com/www/delivery/fpi.js?z=216483&u=cottageatthecrossroads&width=300&height=250"></script></div>';
  echo $ad_network;
}
