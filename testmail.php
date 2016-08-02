<?php

$msg = '<html xmlns=http://www.w3.org/1999/xhtml>
<head>
    <title>[REMINDER] Segera Lakukan Pembayaran</title>
 	<meta name=viewport content=width=device-width, initial-scale=1.0/>
    
    <style>
	body{margin:0; padding:0; font-family:Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; color:#52555f;}
	p {font-weight: 400;}
	p a {text-decoration:none; color:#52555f; font-size:18px;}
	table tr td.fontsize {font-size:14px;}
	hr {opacity:.7;}
	table#table-content {text-align:center; margin:auto; font-size:14px; font-weight:400; border-collapse: collapse;}
	table#table-content tr td {padding:15px; text-align:left;}
	table#table-content th {color: white; background-color: #545761; font-weight:300; padding:10px 0; text-align:center;}
	table#table-content th td {border: 1px solid #545761;}
	.total {width:350px; float:right; font-size:14px; font-weight:400; text-align:right;}
	.total table tr td.first {font-weight:700;}
	.disclaimer {border: 1px solid #f26525; border-radius:5px; width:100%; display:inline-block; margin-top:15px;}
	.disclaimer img {display:inline-block; margin: 0 10px 3px 10px;}
	.disclaimer p {width:550px; display:inline-block; font-size:14px; line-height:20px;}
	.va-instruction {font-size:14px; padding:15px; margin: 25px auto; border: 1px solid #E1E1E1; display:inline-block;}
	.va-steps {line-height:22px;}
	table#total tr td {padding:2px;}
  .contact-if {display: inline-block; margin-top: 20px; font-size: 14px; width:600px;}
  hr {margin-bottom:15px;}
  .bold {font-weight: bold;}
   table {border-color: #545761;}
    </style>


</head>	

<body>

    <table align=center border=0 cellpadding=0 cellspacing=0 width=600>
     <tr>
      <td align=center style=padding: 25px 0 25px 0; colspan=2>
         <a href=https://www.ruparupa.com/ target=blank_><img src=https://img.ruparupa.com/media/static/email/logo.png alt=logo width=202 height=50 style=display: block;/></a>
      </td>
     </tr>
     <tr bgcolor=#f6f6f6 height=36>
 	  <td align=center colspan=2>
       <p class=navbar><a href=https://www.ruparupa.com/ target=blank_>SHOP <span style=color:#f36525;>NOW!</span></a></p>
      </td>
     </tr>
     <tr height=15></tr>
     
     <tr><!-- start content wrapper -->
      <td colspan=2>
      
       <p ><!-- opening -->
       <span style=font-size:16px; font-weight:700;>Hi {name},</span>
       </p>
       <p style=font-size:14px; line-height:22px;>
	   Terima kasih telah berbelanja di Ruparupa. Email ini merupakan pengingat bahwa Anda memiliki <strong>4 jam</strong> lagi untuk melakukan pembayaran sesuai dengan metode yang Anda pilih sebelum pesanan Anda dibatalkan secara otomatis.
       <br/><br/>Berikut detail pesanan Anda:
       </p>
       <table>
       	<tr>
        	<td class=fontsize>Nomor Pesanan</td>
            <td style=width:20px; height:22px; text-align:center;>:</td>
            <td class=fontsize bold>ODI1234565</td>
        </tr>
        <tr>
        	<td class=fontsize>Waktu Transaksi</td>
            <td style=text-align:center; height:22px;>:</td>
            <td class=fontsize bold>27 Februari 2016</td>
        </tr> 
        <tr>
        	<td class=fontsize>Metode Transaksi</td>
            <td style=text-align:center; height:22px;>:</td>
            <td class=fontsize bold>Transfer Bank</td>
        </tr>
   
       </table><!--- end opening -->
       <br/>
       <hr/>
       
       <p style=font-weight:700; font-size:14px;>INFORMASI PESANAN</p>
       
       <table width=100% id=table-content border=1>
       	<tr>
        	<th style=width:60%>Produk</th>
            <th style=width:5%>SKU</th>
            <th style=width:5%>Jml</th>
            <th style=width:30%>Harga</th>
        </tr>
        <tr>
        	<td>Comfortable Modern Red Egg Chair</td>
            <td>12345</td>
            <td>1</td>
            <td style=text-align:right;>Rp 15.599.999</td>
        </tr>
        <tr>
        	<td>Comfortable Modern Red Egg Chair</td>
            <td>12345</td>
            <td>1</td>
            <td style=text-align:right;>Rp 15.599.999</td>
        </tr>
        <tr>
        	<td>Comfortable Modern Red Egg Chair</td>
            <td>12345</td>
            <td>1</td>
            <td style=text-align:right;>Rp 15.599.999</td>
        </tr>
        <tr>
        	<td>Comfortable Modern Red Egg Chair</td>
            <td>12345</td>
            <td>1</td>
            <td style=text-align:right;>Rp 15.599.999</td>
        </tr>
       </table><!-- end product list -->
       <br/>
       <div class=total>
       	<table style=float:right; id=total>
        	<tr>
            	<td class=first style=width:100px;>Subtotal</td>
                <td style=width:20px;></td>
                <td>Rp 39.089.879</td>
            </tr>
            <tr>
            	<td class=first style=width:100px;>Biaya Kirim</td>
                <td style=width:20px;></td>
                <td>Rp0</td>
            </tr>
            <tr>
            	<td class=first style=width:100px;>Voucher</td>
                <td style=width:20px;></td>
                <td>(-) Rp 100.000</td>
            </tr>
            <tr>
            	<td class=first style=width:100px;>TOTAL</td>
                <td style=width:20px;></td>
                <td class=first>Rp 39.089.879</td>
            </tr>
         </table>
       </div><!-- end total -->
       
	<div class=contact-if>
       <hr/>
        <p>Klik <a href=https://www.ruparupa.com/payment/ target=_blank style=font-size:14px; color:#008ccf; text-decoration:underline; font-weight:bold;/>disini</a> untuk melihat detail cara pembayaran.</p>
         
        </div>
       <!-- <div class=disclaimer>
       	<img src=https://img.ruparupa.com/media/static/email/alert.png alt=alert width=22/><p> Pesanan Anda akan segera kami proses setelah melakukan pembayaran melalui nomor rekening dibawah ini. Mohon selesaikan pembayaran dalam waktu <strong>1 x 12 jam.</strong>
        </p>
       </div> -->
       
       <div style=margin-top:45px;>
       <table>
       	<tr>
        	<td style=border-bottom:1px solid #E1E1E1; padding-bottom:5px; color:#F36525;>Sarah</td>
        </tr>
        <tr>
        	<td>Ruparupa.com</td>
        </tr>
	   </table>
       </div>
       
       </td>
        <!-- Footer -->
     </tr>
     <tr height=15></tr>
     <tr>
      <td style=padding-bottom:21px; bgcolor=#008ccf colspan=4></td>
     </tr>
     <tr>
      <td>
       <p style=font-size:12px; font-weight:400; margin-top:15px;> Ada pertanyaan? Hubungi kami di:</p>
       <p style=font-weight:500; margin-top:-5px;><img src=https://img.ruparupa.com/media/static/email/telephone.png alt=telephone width=12/> &nbsp;<a href=tel:021-582 9191 style=font-size:16px;>021 - 582 9191</a></p>
       <p style=font-weight:500; margin-top:-5px;><img src=https://img.ruparupa.com/media/static/email/email.png alt=email width=12 /> &nbsp;<a href=mailto:help@ruparupa.com target=_top style=font-size:16px;>help@ruparupa.com</a></p>
      </td>
      <td style=padding-left:60px;>
       <p style=font-size:12px; font-weight:400; vertical-align:text-top; margin-top:-25px;> Terhubung dengan kami </p>
       <a href=https://twitter.com/ruparupacom target=blank_><img src=https://img.ruparupa.com/media/static/email/twitter.png alt=twitter width=15 style=margin-right:4px/></a> &nbsp; <a href=https://www.facebook.com/ruparupacom target=blank_><img src=https://img.ruparupa.com/media/static/email/facebook.png alt=facebook width=15 style=margin-right:4px/></a> &nbsp; <a href=https://www.instagram.com/ruparupacom/ target=blank_><img src=https://img.ruparupa.com/media/static/email/instagram.png alt=instagram width=15 style=margin-right:4px/></a> &nbsp; <a href=https://plus.google.com/u/0/101666717859056881335 target=blank_><img src=https://img.ruparupa.com/media/static/email/gplus.png alt=google-plus width=20 style=margin-right:4px/></a> 
      </td>
     </tr>
    </table>

</body>
</html>';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From:Sales Ruparupa.com <help@ruparupa.com>' . "\r\n";
$subject = '[REMINDER] Segera Lakukan Pembayaran';

mail("sutrisno.yao@gmail.com",$subject,$msg,$headers);


?>