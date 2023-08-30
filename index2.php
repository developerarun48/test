<?php
ob_start();

session_start(); 

require_once('../config_timkerr.php');

require '../smtp_config.php';

include '../spamvalidate.php';

include 'csrf.class.php';

$csrf = new csrf();

$token_id = $csrf->get_token_id();

$token_value = $csrf->get_token($token_id);

$result=$db->prepare('SELECT * FROM tbl_updatepages where id=?');

$result->execute(array('1'));

$row = $result->fetch(PDO::FETCH_ASSOC);

$pageinformation=str_replace("../","",$row['page_information']);

$metatitle=$row['metatitle'];

$metakey=$row['metakeyword'];

$metadescription=$row['description'];

if($_GET['Mobile']=='') {

  $url =$_SERVER['HTTP_REFERER'];

  $query = parse_url($url, PHP_URL_QUERY);

  parse_str($query);

  parse_str($query, $arr);

  $request = $_SERVER['HTTP_REFERER'];

  $urlname=explode('?',$request);

  $urlname= $urlname[1];

  if($urlname=='Mobile=Off' || $Mobile=='Off') {

    echo "<script>window.location='index.php?Mobile=Off';</script>";

    exit;
  }
}

if($_GET['Mobile']=='') {

  $useragent=$_SERVER['HTTP_USER_AGENT'];

  if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))

    header('Location:mobile/index.php');

} 
if($_POST['listsubmit1']=="btnsubmit1")
{ 

     if(isset($_POST['g-recaptcha-response'])) {

        $captcha=$_POST['g-recaptcha-response'];
    }

    $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);


    if($response.success==false)  {

        echo '<h2>You are spammer ! Get the @$%K out</h2>';

    } else 
    {


        $F1 = $_POST["first"];

        $F8 = $_POST["last"];

        $F2 = $_POST["email"];

        $F3 = $_POST["phone"];

        $F6 = $_POST["comments"];

        $nowdate=date(Y).'-'.date(m).'-'.date(d);

        $page="CONTACT";

        $name = $F1.' '.$F8;

        $body.= "<table align='left'>

    <tr><td>Name : ".$F1." ".$F8."</td></tr>

    <tr><td>Email Address : ".$F2."</td></tr>

    <tr><td>Phone : ".$F3."</td></tr>

    <tr><td>Comments : ".$F6."</td></tr>

    </table>";

        $fzero=0;

        // wpforms code//

        $F61 = $body;

        if(spamvalidate()){
      
      
            echo "<script>alert('Your message has been sent. Please enjoy the rest of the site.'); window.location.href ='https://tksir.com'</script>";
      
        }else {


            if($csrf->check_valid('post')) {
                if (!empty($_POST['website'])) {
                    die();
                } else {
                    if($F6 == strip_tags($F6)) {
                        if(session_id() != "") {
                            
                            $stmt = $db->prepare("insert into tbl_storeddata(Name,email,createon,content,pagename,phone_no,delete_status,comments)values(:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8)");

                            $stmt->execute(array(':field1' => $name, ':field2' => $F2, ':field3' => $nowdate, ':field4' => $body, ':field5' => $page, ':field6' => $F3, ':field7' => $fzero, ':field8' => $F6));

                            $affected_rows = $stmt->rowCount();
            
                            $subject = "Tim Kerr - Contact Form";
                            
              $result = $db->prepare('SELECT * FROM tbl_sitetemplate_email where id=1');

              $result->execute(array());

              $contact_email = $result->fetch(PDO::FETCH_ASSOC);

              $mail->setFrom($contact_email['email_sendwebsite']);

              $string = $contact_email['contact_us'];

              $str_arr = explode (",", $string);  

              $str_count = count($str_arr); 

              //echo $str_count; exit;
              $i=0;
              for($i=0; $i<$str_count; $i++)
              {
              //  print_r($str_arr[$i]); exit;
              $mail->addAddress($str_arr[$i]);     // Add a recipient

              }
              $mail->addReplyTo($F2);
              // Content
              $mail->isHTML(true);                                  // Set email format to HTML
              $mail->Subject = $subject;
              $mail->Body    = $body;

              $mail->send();
              if($mail){
              //  echo 'Message has been sent';
              }else{
              //    echo "Message hasn't been sent";
              }

                          echo "<script>alert('Your message has been sent. Please enjoy the rest of the site.'); window.location.assign(document.URL);</script>";  

                        }
                    }
                }
            }
            }
        }

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="index, follow" />
<meta name="viewport" content="width=device-width">
<?php
$actuallink=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$pagenamin=str_replace("index.php","mobile/index.php",$actuallink);
$pagenamn=str_replace("Mobile=Off","",$pagenamin);
?>
<link rel="alternate" href="<?php echo $pagenamn;?>"/>
<meta name="keywords" content="<?php echo $metakey; ?>"/>
<meta name="description"  content="<?php echo $metadescription; ?>"/>
<title>Premier Real Estate | NJ Shore Points<?php //echo $metatitle; ?></title>
<link href="styles.css" rel="stylesheet" type="text/css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gudea:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<link rel="SHORTCUT ICON" href="images/timkerr.ico">
</head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-22ZSW3351F"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-22ZSW3351F');
</script>
<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
function onloadCallback() {
$('#g-recaptcha-response').attr('aria-hidden', true);
$('#g-recaptcha-response').attr('aria-label', 'do not use');
$('#g-recaptcha-response').attr('aria-readonly', true);
}
</script>

<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback'></script>

   <script>

        function validation()
        { 
            if (document.getElementById('name').value==''){

                alert('Please Enter First Name and Try Again.');

                document.getElementById('name').focus();

                return false;
            }

            if (document.getElementById('name2').value==''){

                alert('Please Enter Last Name and Try Again.');

                document.getElementById('name2').focus();

                return false;
            }

            if (document.getElementById('name4').value==''){

                alert('Please Enter Email Address and Try Again.');

                document.getElementById('name4').focus();

                return false;
            }

            var x = document.getElementById('name4').value;

            var atpos = x.indexOf("@");

            var dotpos = x.lastIndexOf(".");

            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {

                alert("Please Enter Valid Email Address and Try Again.");

                document.getElementById('name4').focus();

                return false;
            }



            if (document.getElementById('comments').value==''){

                alert('Please Enter Comments and Try Again.');

                document.getElementById('comments').focus();

                return false;
            }
         
          var captcha_response = grecaptcha.getResponse();

            if(captcha_response.length == 0)
            {
                alert('Please Enter reCaptcha');

                return false;
            } 
            else   
            {   
                document.getElementById('contactform').submit();

                return true;
          }
        }

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

    </script>
  <style>
      .visuallyhidden {
      border: 0;
      clip: rect(0 0 0 0);
      height: 1px;
      margin: -1px;
      overflow: hidden;
      padding: 0;
      position: absolute;
      width: 1px;
      }

      .bottom_shodow{
        background-position: bottom;
        background-repeat: no-repeat;
        background-size: contain;
      }
      h1, h2, h3, h4{
        font-family: Libre Caslon Display !important;
      }
      </style>
  


<link rel="stylesheet" href="nivo/nivo-slider.css" type="text/css" media="screen" />

<script type="text/javascript" src="nivo/jquery-1.7.1.min.js"></script>

<script type="text/javascript" src="nivo/jquery.nivo.slider.pack.js"></script>

<script type="text/javascript">

var jq=$.noConflict();

  jq(window).load(function () {

    setTimeout(function () {

      document.getElementById('imagenone').style.display = "none";

      document.getElementById('slider').style.display = "block";

    }, 2);

  });

  jq(window).load(function() {

    jq('#slider').nivoSlider({

      effect: 'fade',
    animSpeed: 440,
      pauseTime: 4000,
    pauseOnHover: false
     

    });

  });

</script>


<body style="overflow-x:hidden!important;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center"><?php include('head.php'); ?></td></tr>
<tr>
     <td  align="center"><table width="1000" border="0" cellspacing="0" cellpadding="0">
  
   
      
            <div id="imagenone" align="center" style="width:1920;height:750;display:block;">
       
      </div>
       <?php

      $safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? 'true' : 'false';

      $chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? 'true' : 'false';

      $firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? 'true' : 'false';

      if($safari=='true')
      {
      ?>

      <div id="slider" class="nivoSlider" style="position:relative; margin-left:0px; display:none; height:805px;  z-index: -2;  width: 1920; overflow-x:hidden;margin-top:-809px">

        <?php
        }
        else if($chrome=='true')
        {
        ?>
        <div id="slider" class="nivoSlider" style="position:relative; margin-left:0px; display:none; height:805px;  z-index: -2;  width: 1920; overflow-x:hidden;margin-top:-809px">
          <?php
          }
          else if($firefox=='true')
          {
          ?>
          <div id="slider" class="nivoSlider" style="position:relative; margin-left:0px; display:none; height:805px;  z-index: -2;  width: 1920; overflow-x:hidden;margin-top:-809px">
            <?php
            }
            ?>
      <?php

            $tbstmt=$db->prepare('SELECT * FROM tbl_slider where delete_status=? order by order_id ASC');

            $tbstmt->execute(array('0'));

            $i=1;

            while($fetchslidedata = $tbstmt->fetch(PDO::FETCH_ASSOC))
            {
      $image=$fetchslidedata['image'];
      $image1=explode(".",$image);
              ?>
      
              <img style="display:block;object-fit:cover" class="bgcenter" align="center" src="sliderimages/<?php  echo $fetchslidedata['image']; ?>" alt="<?php  echo $image1[0]; ?>" data-transition="fade"  height ="100%" width="100%" >

            <?php } ?>
      </div>

   

  
   </table></td>
  </tr> 



<tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="5"><img src="images/t.gif" width="50" height="40" alt="" border="0" /></td>
        </tr>
      <tr>
        <td colspan="5" align="center" valign="middle"><span class="size34">Our Markets</span></td>
      </tr>
      <tr>
        <td colspan="5"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
      </tr>
      <tr>
        <td width="1%"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
        <td width="49%" style="background-image: url('images/avalon.jpg');background-size: cover;background-position:100% 100%;" class="norepeat"><table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/bluepropertyshadow.png" class="repeaty bottom_shodow">
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
            </tr>
          <tr>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
            <td width="" align="left" valign="bottom" class="size40 white" style="font-size: 30px!important">
              AVALON
            </td>
            <td width="" align="right" valign="bottom"><table width="150" border="0" cellspacing="0" cellpadding="12">
              <tr>
                <td align="center" valign="middle" background="images/blackbg.png"><a href="avalon2.php" class="whitelink size18">LEARN MORE</a></td>
              </tr>
            </table></td>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
          </tr>
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
            </tr>
        </table></td>
        <td width="1%"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
        <td width="49%" style="background-image: url('images/stoneharbor.jpg');background-size: cover;background-position:100% 100%;" class="norepeat"><table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/bluepropertyshadow.png" class="repeaty bottom_shodow">
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
          </tr>
          <tr>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
            <td width="" align="left" valign="bottom" class="size40 white" style="font-size: 30px!important">
              STONE HARBOR
            </td>
            <td width="" align="right" valign="bottom"><table width="150" border="0" cellspacing="0" cellpadding="12">
              <tr>
                <td align="center" valign="middle" background="images/blackbg.png"><a href="stoneharbor2.php" class="whitelink size18">LEARN MORE</a></td>
              </tr>
            </table></td>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
          </tr>
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
          </tr>
        </table></td>
        <td width="1%"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
      </tr>
      <tr>
        <td colspan="5"><img src="images/t.gif" width="50" height="40" alt="" border="0" /></td>
      </tr>
      


      <tr>
        <td width="1%"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
        <td width="49%" style="background-image: url('images/avalon.jpg');background-size: cover;background-position:100% 100%;" class="norepeat"><table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/bluepropertyshadow.png" class="repeaty bottom_shodow">
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
            </tr>
          <tr>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
            <td width="" align="left" valign="bottom" class="size40 white" style="font-size: 30px!important">
              CAPE MAY
            </td>
            <td width="" align="right" valign="bottom"><table width="150" border="0" cellspacing="0" cellpadding="12">
              <tr>
                <td align="center" valign="middle" background="images/blackbg.png"><a href="capemay.php" class="whitelink size18">LEARN MORE</a></td>
              </tr>
            </table></td>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
          </tr>
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
            </tr>
        </table></td>
        <td width="1%"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
        <td width="49%" style="background-image: url('images/stoneharbor.jpg');background-size: cover;background-position:100% 100%;" class="norepeat"><table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/bluepropertyshadow.png" class="repeaty bottom_shodow">
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
          </tr>
          <tr>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
            <td width="" align="left" valign="bottom" class="size40 white" style="font-size: 30px!important">
              SEA ISLE CITY
            </td>
            <td width="" align="right" valign="bottom"><table width="150" border="0" cellspacing="0" cellpadding="12">
              <tr>
                <td align="center" valign="middle" background="images/blackbg.png"><a href="seaislecity.php" class="whitelink size18">LEARN MORE</a></td>
              </tr>
            </table></td>
            <td width="1%"><img src="images/t.gif" width="30" height="625" alt="" border="0" /></td>
          </tr>
          <tr>
            <td colspan="4"><img src="images/t.gif" width="30" height="30" alt="" border="0" /></td>
          </tr>
        </table></td>
        <td width="1%"><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
      </tr>
      <tr>
        <td colspan="5"><img src="images/t.gif" width="50" height="40" alt="" border="0" /></td>
      </tr>
    </table></td>
  </tr>




<?php echo $pageinformation; ?>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="images/t.gif" width="40" height="40" alt="" border="0" /></td>
      </tr>
    <tr>
        <td class="repeatx" bgcolor="#E9EAEC"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="position:relative;height:770px;" >
          <tr>
            <td width="30%" align="center" valign="middle" style="position: relative;left: -1%;"><table width="550" border="0" cellpadding="80" cellspacing="0" class="medspacing" >
      <tr>
                <td><h1 align="center">The Collection | 2021</h1>
                  <p class="size24" align="center"><strong>NOTHING COMPARES</strong></p>
                  <p align="center"><img src="images/line1.png" width="75" height="20" alt="Page Spacing" border="0" /></p>
                  <!--<p>Our signature real estate booklet is a comprehensive visual of Avalon & Stone Harbor's most coveted beach lifestyles.  The Collection, our flagship seasonal marketing campaign, features the latest luxury properties for sale and rent.  Blended together with market reports, insights, and helpful community information. </p>-->
          <p>Our signature Avalon & Stone Harbor real estate booklet is an excellent resourc.. The Collection beautifully blends new listings, market reports, insights, and community information. This seasonal marketing campaign highlights our current inventory of real estate - including a featured catalog of Avalon NJ rentals.</p>
                  <table width="175" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="images/t.gif" width="40" height="15" alt="" border="0" /></td>
                    </tr>
                    <tr>
                      <td><table width="175" border="0" cellspacing="0" cellpadding="20">
                        <tr>
                          <td align="center" valign="middle" bgcolor="#112546"><a href="thecollection.php" class="whitelink">Take A Look</a></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
            </table></td>
            <!--<td style="background-image: url('images/thecollection.jpg');background-repeat: no-repeat ;position: relative;width: 70%;background-size: 100% 85%;top: 6px;" ><img src="images/t.gif" width="50%" height="100" alt="" border="0" /></td>-->
      <!--<td style="position:absolute;top: -31px;"><img src="images/collectionbg.jpg" width="100%" height="100%" alt="" border="0" />
      
      </td>-->
      
      <td>
      <style>
      .collect_img > img {
      position: absolute;
      top: -6.5%;
      width: 80%;
      height: 100%;
      }
      </style>
      <div class="collect_img">
      <img src="images/thecollection.jpg" width="60%" height="100%" alt="" border="0" />
      <!--<br/>
      <img src="images/t.gif" width="100%" height="265px" alt="" border="0" />-->
      </div>
      </td>
          </tr>
        </table></td>
      </tr>
  
  <tr>
    <td align="center" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="images/t.gif" width="50" height="20" alt="" border="0" /></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><h2>Contact Us</h2>
          <span class="size60">MAKE YOUR</span><br />
            <strong class="medspacing size70">NEXT MOVE</strong>
            <table width="1000" border="0" cellspacing="0" cellpadding="0">
      <form name="contactform" id="contactform" method="post">
        <input type="hidden" name="<?= $token_id; ?>"value="<?= $token_value; ?>" />
            <tr>
              <td colspan="2" align="left"><img src="images/t.gif" width="40" height="50" alt="" border="0" /></td>
            </tr>
            <tr>
              <td align="left"><label for="name" class="visuallyhidden">first</label><input name="first" placeholder="*First Name" type="text" class="padding" id="name" style="width: 93%" /></td>
              <td align="right"><label for="name2" class="visuallyhidden">last</label><input name="last" placeholder="*Last Name" type="text" class="padding" id="name2" style="width: 93%" /></td>
            </tr>
            <tr>
              <td colspan="2" align="left"><img src="images/t.gif" width="40" height="25" alt="" border="0" /></td>
            </tr>
            <tr>
              <td align="left"><label for="name4" class="visuallyhidden">email</label><input name="email" oncopy="return false" onpaste="return false" placeholder="*Email Address" type="text" class="padding" id="name4" style="width: 93%" /></td>
              <td align="right"><label for="name5" class="visuallyhidden">phone</label><input onkeypress="return isNumber(event)" name="phone" placeholder="Phone Number" type="text" class="padding" id="name5" style="width: 93%" /></td>
            </tr>
            <tr>
              <td colspan="2" align="left"><img src="images/t.gif" width="40" height="25" alt="" border="0" /></td>
            </tr>
            <tr>
              <td colspan="2" align="left"><label for="comments" class="visuallyhidden">comments</label><textarea name="comments" rows="8" class="padding" id="comments" style="width: 98%" placeholder="*Comments" oncopy="return false" onpaste="return false"></textarea></td>
            </tr>
            <tr>
              <td colspan="2" align="center"><img src="images/t.gif" width="40" height="25" alt="" border="0" /></td>
            </tr>
      <tr>
        <td colspan="2" align="center">
        <div style="margin-bottom: -10px;" class="g-recaptcha" id="recaptcha2" data-sitekey="<?php echo $sitekey;?>"></div><br>
        </td>
        </tr>
            <tr>
              <td colspan="2" align="center"><table width="220" border="0" cellspacing="0" cellpadding="20">
                <tr><input type="hidden" id="listsubmit1" name="listsubmit1" value="btnsubmit1">
                  <td align="center" valign="middle" bgcolor="#112546" class="fontlarge"><a href="#" class="whitelink" onclick="return validation();">CONTACT US</a></td>
                </tr>
              </table></td>
            </tr></form>
            </table>
         </td>
      </tr>
      <tr>
        <td><img src="images/t.gif" width="50" height="40" alt="" border="0" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><?php include('footercontent.php'); ?></td>
  </tr>
</table>

</body>
</html>