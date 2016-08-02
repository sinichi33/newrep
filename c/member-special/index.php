<?php
define('MAGENTO_ROOT', __DIR__ . '/../..');

require_once ( MAGENTO_ROOT. "/app/Mage.php" );
Mage::app('default');

$cookie = Mage::getSingleton('core/cookie');
$magentoBase = Mage::getBaseUrl();

if(Mage::getSingleton('customer/session')->isLoggedIn())
{
   echo Mage::getSingleton('customer/session')->getId();
}else
{
?><!DOCTYPE html>
<html lang="id">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- TITLE -->
      <title>Welcome to Ruparupa.com!</title>
      <meta name="description" content="Selamat datang di Ruparupa, toko online pertama dari Kawan Lama Group, nikmati rupa-rupa penawaranfb menarik khusus dari Kawan Lama Group untuk berbagai produk pilihan">
      <meta name="keywords" content="furniture, perkakas, mainan, otomotif, berkebun">
      <meta name="author" content="ruparupa.com">
      <script type="text/javascript" src="https://www.ruparupa.com/js/google/ga.js"></script>
      <!-- FAVICON  -->
      <link rel="shortcut icon" href="https://www.ruparupa.com/c/klg-special/images/favicon.ico" type="image/x-icon">
      <link rel="icon" href="https://www.ruparupa.com/c/klg-special/images/favicon.ico" type="image/x-icon">
      <script src="js/jquery1.11.2.min.js"></script>
      <script src="js/jquery.js"></script>
	  <script src="js/jquery-1.11.1.js"></script>
      <!-- BOOTSTRAP CSS -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- CUSTOM STYLESHEET -->
      <link rel="stylesheet" href="css/style.css">
      <!-- GOOGLE FONTS -->
      <link href="css/css" rel="stylesheet" type="text/css">
      <!-- RESPONSIVE FIXES -->
      <link rel="stylesheet" href="css/responsive.css">
      <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <body data-spy="scroll" data-target="#main-navbar" cz-shortcut-listen="true">
   <noscript>
      <iframe src="//www.googletagmanager.com/ns.html?id=GTM-547VGP" height="0" width="0" style="display:none;visibility:hidden"></iframe>
   </noscript>
   <script type="text/javascript">
      //<![CDATA[
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
         var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;
         j.src= '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-547VGP');

      var dlCurrencyCode = 'IDR';
      //]]>
   </script>
      <div class="main-container" id="page">
         <!-- HEADER -->
         <header id="nav2-3">
            <nav class="navbar bg-white" id="main-navbar">
               <div class="container">
                  <div class="navbar-header">
                     <a href="https://www.ruparupa.com/c/member-special/#" class="navbar-brand"><img src="images/logo.png" width="220" alt="logo"></a>
                  </div>
                  <div class="res-navbar-header">
                     <a href="https://www.ruparupa.com/c/member-special/#" class="navbar-brand"><img src="images/logo.png" width="145" alt="logo"></a>
                  </div>
                  <!-- Login -->
                  <div class="navbar-collapse collapse">
                     <div class="navbar-right">
                        <div class="btn-group">
                           <p class="desk-reg">Sudah punya Akun?</p>
                           <div class="login-button dropdown-toggle" data-toggle="dropdown">
                              <p class="desk-login"><img class="user" src="images/login-user.png" width="22" alt="login"/>Login</p>
                              <img src="images/login-user.png" width="30" alt="Login" class="resp-img"/><span class="caret"></span>
                           </div>
                           <div class="dropdown-menu" >
                              <div class="col-sm-12">
                                 <div class="col-sm-12">
                                    <p class="resp-text">Login</p>
                                 </div>
                                 <div class="col-sm-12">
                                    <input type="text" id="email" name="email" placeholder="Masukkan Email Anda..." onclick="return false;" class="form-control input-login" />
                                 </div>
                                 <div class="col-sm-12">
                                    <input type="password" id="nip" name="nip" placeholder="Masukkan Password Anda..." class="form-control input-login" name="password" />
                                 </div>
                                 <div id="result" class="col-sm-12 warning"></div>
                                 <div class="col-sm-12">
                                    <button type="submit" class="btn btn-login" id="login-button">Masuk</button>
                                 </div>

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- End Login -->
               </div>
            </nav>
            <!-- /End Navbar -->
         </header>
         <!-- /End Header -->
      </div>
      <!-- /End Container -->
      <!-- HERO SECTION -->
      <section id="hero8" class="hero hero-countdown bg-orange">
         <div class="container">
            <div class="row">
               <div class="col-md-12 col-sm-12 text-white text-center">
                  <h1 class="shadow">WE WILL SOON BE AVAILABLE FOR EVERYONE!</h1>
                  <h2>Terima kasih atas antusiasme Anda mengunjungi ruparupa.com, untuk saat ini ruparupa.com hanya dapat diakses oleh Member Ace, Informa, Toys Kingdom atau Office 1.</h2>
                  <h2 style="margin-top:-38px;">Jika Anda Member, silakan cek email Anda dan gunakan link yang telah kami kirimkan untuk masuk ke ruparupa.com</h2>
                  <hr class="divide"/>
                  <p class="content">Silakan daftarkan email disini agar Anda tidak melewatkan launching www.ruparupa.com yang akan segera hadir!</p>
                  <form action="//ruparupa.us12.list-manage.com/subscribe/post?u=5b8422184b8c00b4164edfbab&amp;id=dfdb008428" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" onsubmit="return false">
                     <p>
                        <input type="email" name="EMAIL" id="email-chimp" placeholder="Masukkan email Anda..."/>
                        <input type="hidden" name="u" value="5b8422184b8c00b4164edfbab">
                        <input type="hidden" name="id" value="dfdb008428">
                        <input type="button" value="Kirim" id="button-mailchimp"/>
                     </p>
                     <p id="mailchimp-message" class="mailchimp-message alert alert-success"></p>
                  </form>
                  <p style="font-size:12px;">Punya pertanyaan? email ke <a style="color:#FFF; margin-left:0;" href="mailto:help@ruparupa.com">help@ruparupa.com</a></p>
               </div>
            </div>
         </div>
         <!-- /End Container -->
      </section>
      <footer>
         <div class="container">
            <div class="row">
               <div class="col-md-12 align-center">
                  <ul class="socmed">
                     <li><a href="https://www.facebook.com/ruparupacom" target="blank_"><img src="images/fb.png" width="20" alt="facebook-icon"></a></li>
                     <li><a href="https://twitter.com/ruparupacom" target="blank_"><img src="images/twitter.png" width="20" alt="twitter-icon"></a></li>
                     <li><a href="https://plus.google.com/u/0/101666717859056881335" target="blank_"><img src="images/google.png" width="20" alt="googleplus-icon"></a></li>
                     <li><a href="https://www.instagram.com/ruparupacom/" target="blank_"><img src="images/instagram.png" width="20" alt="googleplus-icon"></a></li>
                  </ul>
                  <p class="footer">2016 © PT. Omni Digitama Internusa</p>
               </div>
            </div>
         </div>
      </footer>
      <!-- SCRIPTS -->
      <script src="js/bootstrap.min.js"></script>
      <script src="js/jquery.easing.1.3.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
      <script>
         $(function () {
                 $('.dropdown-menu input').click(function (event) {
                     event.stopPropagation();
                 });
             });
      </script>
      <script>
         $(document).ready(function () {
            $('#nip').keydown(function (event) {
               if ( event.which == 13 ) {
                  submitLogin();
               }
            } );

            $('#login-button').click(function (event) {
               event.preventDefault();
               submitLogin();
               return false;
            });

            function submitLogin () {
               $('#result').html('please wait...');
               var klgemail = $('#email').val();
               var password = $('#nip').val();
               klgemail.replace('@','%40');

               $.ajax({
                  url: '<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK,Mage::getStoreConfig('web/secure/use_in_frontend'))?>ajaxlogin/ajax/index/',
                  method:'post',
                  data: 'ajax=login&email='+klgemail+'&password='+password,
               }).done(function(data) {

                  if (data != 'success') {
                     $('#result').html('invalid NIP or password');
                  }
                  else if (data == 'success') {
                     $('#result').html('success login please wait...');
                     location.href = '<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK,Mage::getStoreConfig('web/secure/use_in_frontend'))?>';
                  }
               });
            }


            $('#button-mailchimp').click(function (event) {
               event.preventDefault();
               submitMailChimp();
               return false;
            });

            function submitMailChimp () {
               var emailchimp = $('#email-chimp').val();
               emailchimp.replace('@','%40');

               if(emailchimp == '') {
                  $('#mailchimp-message').hide();
                  return false;
               }

               $('#mailchimp-message').show();
               $('#mailchimp-message').html('please wait...');

               $.ajax({
                  url: 'https://ruparupa.us12.list-manage.com/subscribe/post-json?c=?',
                  method:'GET',
                  dataType: 'json',
                  cache: false,
                  contentType: "application/json; charset=utf-8",
                  data: $('#mc-embedded-subscribe-form').serialize(),
                  error: function(err) {
                     $('#mailchimp-message').show();
                     $('#mailchimp-message').html('Tidak bisa tersambung ke server. Silakan coba beberapa saat lagi.');
                  }
               }).done(function(data) {
                  $('#mailchimp-message').show();
                  if(data.result == 'success'){
                     //Almost finished... We need to confirm your email address. To complete the subscription process, please click the link in the email we just sent you.
                     $('#mailchimp-message').html('Satu langkah lagi... Silakan cek email Anda dan klik link yang telah kami kirimkan untuk mengkonfirmasi.');
                  }else{
                     $('#mailchimp-message').html('Silakan cek email yang Anda masukkan atau coba beberapa saat lagi');
                  }
               });
            }
         });
      </script>
   </body>
</html>
<?php } ?>