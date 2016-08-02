<?php
header("Location: https://more.ruparupa.com/welcome");

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
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- TITLE -->
        <title>Welcome to Ruparupa.com!</title>

        <meta name="description" content="Selamat datang di Ruparupa, toko online pertama dari Kawan Lama Group, nikmati rupa-rupa penawaranfb menarik khusus dari Kawan Lama Group untuk berbagai produk pilihan" />
        <meta name="keywords" content="furniture, perkakas, mainan, otomotif, berkebun" />
        <meta name="author" content="ruparupa.com">

        <!-- FAVICON  -->
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon">
        <script src="js/plugins/jquery1.11.2.min.js"></script>
        <script src="js/plugins/jquery.js"></script>
        <!-- =========================
           STYLESHEETS
        ============================== -->
        <!-- BOOTSTRAP CSS -->
        <link rel="stylesheet" href="css/plugins/bootstrap.min.css">

        <!-- FONT ICONS -->
        <link rel="stylesheet" href="css/icons/iconfont.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <!-- GOOGLE FONTS -->
        <link href='//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
        <!-- CUSTOM STYLESHEET -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/buttonEffect.css" />
        <!-- WORD CYCLE -->
        <link rel="stylesheet" href="css/wordcycle.css" />

        <!-- RESPONSIVE FIXES -->
        <link rel="stylesheet" href="css/responsive.css">

        <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


    </head>

    <body data-spy="scroll" data-target="#main-navbar">
    <div class="main-container" id="page">

        <!-- =========================
            HEADER
        ============================== -->
        <header id="nav2-3">

            <nav class="navbar navbar-fixed-top bg-white" id="main-navbar">
                <div class="container">
                    <div class="navbar-header">
                        <a href="#" class="navbar-brand logo-black smooth-scroll"><img src="images/logo.png" width="220" alt="logo" /></a>
                    </div>

                    <!-- Socmed Links -->
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="https://www.facebook.com/ruparupacom"  target="blank_"><img src="images/fb.png" width="20" alt="facebook-icon"/></a></li>
                        <li><a href="https://twitter.com/ruparupacom"  target="blank_"><img src="images/twitter.png" width="20" alt="twitter-icon"/></a></li>
                        <li><a href="https://plus.google.com/u/0/101666717859056881335"  target="blank_"><img src="images/google.png" width="20" alt="googleplus-icon"/></a></li>
                        <li><a href="https://www.instagram.com/ruparupacom/"  target="blank_"><img src="images/instagram.png" width="20" alt="googleplus-icon"/></a></li>
                    </ul>
                </div>

    </div><!-- /End Container -->
    </nav><!-- /End Navbar -->
    </header>
    <!-- /End Header -->


    <!-- =========================
       HERO SECTION
    ============================== -->
    <section id="hero8" class="hero hero-countdown bg-orange">
        <!-- <div class="overlay"></div> -->

        <div class="container">
            <!-- Hero Conten -->
            <div class="row">
                <div class="col-md-7 col-sm-12 text-white">
                    <h1>Now Launching!</h1>
                    <h2>Selamat datang di Ruparupa, toko online pertama dari Kawan Lama Group</h2>
                    <p class="text-white responsive-text">
                        Login sekarang dan nikmati:
                    </p><ul class="text-white f-w-300 indent responsive-text">
                        <li>Diskon mulai 10% untuk seluruh produk Ace Hardware, Informa &amp; Toys Kingdom.</li>
                        <li>Diskon 10%-25% untuk seluruh produk Krisbow.</li>
                        <li>Diskon mulai 30% untuk seluruh produk Kawan Lama Internusa.</li>
                        <li>Nikmati pula <span class="f-w-700">gratis ongkir</span> serta fasilitas <span class="f-w-700">cicilan 0%</span> selama promo berlangsung.</li>
                    </ul>
                    <br>
                    Untuk Bantuan dan Informasi hubungi Ext. 6674
                    <p></p>
                    <br/>
                </div>
                <!-- Login Form -->
                <div class="col-md-5 col-sm-12 block bg-white">
                    <div class="bg-grey block-title">
                        <p class="l-spacing"> Login Form</p>
                    </div>
                    <form class="form-inline form-white" id="form" novalidate>
                        <div class="form-group">
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" required="" title="Mohon masukkan NIP anda" aria-required="true">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan E-mail" required="" title="Mohon masukkan Email anda" aria-required="true">
                        </div>
                        <button type="button" id="login-button" value="login" class="btn btn-4 btn-4c icon-arrow-right">Masuk</button>
                    </form>
                    <div id="result" class="warning"></div>
                </div><!-- /End Row -->

                <div class="col-md-7 col-sm-12 text-white hidden-2">
                    <p class="text-white">
                        Login sekarang dan nikmati:
                    </p><ul class="text-white f-w-300 indent">
                        <li>Diskon mulai 10% untuk seluruh produk Ace Hardware, Informa &amp; Toys Kingdom.</li>
                        <li>Diskon 10%-25% untuk seluruh produk Krisbow.</li>
                        <li>Diskon mulai 30% untuk seluruh produk Kawan Lama Internusa.</li>
                        <li>Nikmati pula <span class="f-w-700">gratis ongkir</span> serta fasilitas <span class="f-w-700">cicilan 0%</span> selama promo berlangsung.</li>
                    </ul>
                    <br/>
                    <p>Untuk Bantuan dan Informasi hubungi Ext. 6674</p>
                    <br/>
                </div><!-- /responsive -->

            </div><!-- /End Container -->
    </section>
    <!-- /End Hero Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-8 text-container">
                <div class="container-white">
                    <div class="shell">
                        <p class="text-rotator"> <img src="images/letsgetmore.png" width="300" alt="lead" /><span class="verb">Furnished</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-sm-12">
                <div class="thumb">
                    <img src="images/ruparupa-dots.png" width="120" alt="thumb"/>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="col-md-12 align-center bg-grey">
            <p>2016 &copy; PT. Omni Digitama Internusa</p>
        </div>
    </footer>
    </div><!-- /End Main Container -->

    <!-- =========================
         SCRIPTS
    ============================== -->
    <script src="js/plugins/bootstrap.min.js"></script>
    <script src="js/plugins/jquery.easing.1.3.min.js"></script>

    <script src="js/plugins/jquery.mockjax.js"></script>
    <script src="js/plugins/jquery.form.js"></script>
    <script src="js/plugins/jquery.validate.js"></script>
    <!-- Custom Script -->
    <script src="js/custom.js"></script>
    <script src="js/classListShim.js"></script>
    <script src="js/app.js"></script>
    <script>
        $(document).ready(function () {

            $('#email').keydown(function (event) {

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
        });
    </script>
    </body>
    </html>

<?php } ?>