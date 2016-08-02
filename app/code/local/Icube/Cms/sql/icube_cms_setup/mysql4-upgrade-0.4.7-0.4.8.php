<?php
/*
 * - Category Top Menu
 */
$installer = $this;
$installer->startSetup();

$store = Mage::getModel('core/store')->load('mobile', 'code');
$model = Mage::getModel('cms/block');

/* Category Top Menu */
$content =<<<EOF
<ul>
    <li  class="level0 nav-1 first parent">
        <a href="https://m.ruparupa.com/dapur-minimalis.html" class="level0 has-children">Dapur Minimalis</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/dapur-minimalis.html">View All Dapur Minimalis</a>
            </li>
            <li  class="level1 nav-1-1 first parent">
                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak.html" class="level1 has-children">PERLENGKAPAN MEMASAK</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak.html">View All PERLENGKAPAN MEMASAK</a>
                    </li>
                    <li  class="level2 nav-1-1-1 first parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak.html" class="level2 has-children">PERALATAN MEMASAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak.html">View All PERALATAN MEMASAK</a>
                            </li>
                            <li  class="level3 nav-1-1-1-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/spatula.html" class="level3 ">SPATULA</a>
                            </li>
                            <li  class="level3 nav-1-1-1-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/pengocok.html" class="level3 ">PENGOCOK</a>
                            </li>
                            <li  class="level3 nav-1-1-1-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/sendok-besar.html" class="level3 ">SENDOK BESAR</a>
                            </li>
                            <li  class="level3 nav-1-1-1-4">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/alat-pembuka.html" class="level3 ">ALAT PEMBUKA</a>
                            </li>
                            <li  class="level3 nav-1-1-1-5">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/alat-pengupas.html" class="level3 ">ALAT PENGUPAS</a>
                            </li>
                            <li  class="level3 nav-1-1-1-6">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/skimmer.html" class="level3 ">SKIMMER</a>
                            </li>
                            <li  class="level3 nav-1-1-1-7">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/timbangan.html" class="level3 ">TIMBANGAN</a>
                            </li>
                            <li  class="level3 nav-1-1-1-8 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-memasak/perlengkapan-dapur.html" class="level3 ">PERLENGKAPAN DAPUR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-1-2 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-pembuat-kue.html" class="level2 has-children">PERALATAN PEMBUAT KUE</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-pembuat-kue.html">View All PERALATAN PEMBUAT KUE</a>
                            </li>
                            <li  class="level3 nav-1-1-2-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-pembuat-kue/loyang.html" class="level3 ">LOYANG</a>
                            </li>
                            <li  class="level3 nav-1-1-2-2 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/peralatan-pembuat-kue/alat-pembuat-kue-dan-aksesoris.html" class="level3 ">ALAT PEMBUAT KUE DAN AKSESORIS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-1-3 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/perabotan-dapur.html" class="level2 has-children">PERABOTAN DAPUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/perabotan-dapur.html">View All PERABOTAN DAPUR</a>
                            </li>
                            <li  class="level3 nav-1-1-3-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/perabotan-dapur/wajan.html" class="level3 ">WAJAN</a>
                            </li>
                            <li  class="level3 nav-1-1-3-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/perabotan-dapur/panci-presto.html" class="level3 ">PANCI PRESTO</a>
                            </li>
                            <li  class="level3 nav-1-1-3-3 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/perabotan-dapur/alat-pengukus.html" class="level3 ">ALAT PENGUKUS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-1-4 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan.html" class="level2 has-children">PISAU DAN TALENAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan.html">View All PISAU DAN TALENAN</a>
                            </li>
                            <li  class="level3 nav-1-1-4-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan/pisau-daging.html" class="level3 ">PISAU DAGING</a>
                            </li>
                            <li  class="level3 nav-1-1-4-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan/pisau-serba-guna.html" class="level3 ">PISAU SERBA GUNA</a>
                            </li>
                            <li  class="level3 nav-1-1-4-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan/pisau-kebutuhan-khusus.html" class="level3 ">PISAU KEBUTUHAN KHUSUS</a>
                            </li>
                            <li  class="level3 nav-1-1-4-4">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan/set-pisau.html" class="level3 ">SET PISAU</a>
                            </li>
                            <li  class="level3 nav-1-1-4-5">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan/pengasah-pisau.html" class="level3 ">PENGASAH PISAU</a>
                            </li>
                            <li  class="level3 nav-1-1-4-6 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/pisau-dan-talenan/sarung-pisau.html" class="level3 ">SARUNG PISAU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-1-5 last parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/tekstil-dapur.html" class="level2 has-children">TEKSTIL DAPUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/tekstil-dapur.html">View All TEKSTIL DAPUR</a>
                            </li>
                            <li  class="level3 nav-1-1-5-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/tekstil-dapur/celemek.html" class="level3 ">CELEMEK</a>
                            </li>
                            <li  class="level3 nav-1-1-5-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/tekstil-dapur/sarung-tangan-dapur.html" class="level3 ">SARUNG TANGAN DAPUR</a>
                            </li>
                            <li  class="level3 nav-1-1-5-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/tekstil-dapur/sarung-tangan-tahan-panas.html" class="level3 ">SARUNG TANGAN TAHAN PANAS</a>
                            </li>
                            <li  class="level3 nav-1-1-5-4 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-memasak/tekstil-dapur/tisu.html" class="level3 ">TISU</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-1-2 parent">
                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan.html" class="level1 has-children">TEMPAT PENYIMPANAN MAKANAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan.html">View All TEMPAT PENYIMPANAN MAKANAN</a>
                    </li>
                    <li  class="level2 nav-1-2-1 first parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/botol-minum.html" class="level2 has-children">BOTOL MINUM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/botol-minum.html">View All BOTOL MINUM</a>
                            </li>
                            <li  class="level3 nav-1-2-1-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/botol-minum/botol-minum.html" class="level3 ">BOTOL MINUM</a>
                            </li>
                            <li  class="level3 nav-1-2-1-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/botol-minum/termos.html" class="level3 ">TERMOS</a>
                            </li>
                            <li  class="level3 nav-1-2-1-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/botol-minum/tumblers.html" class="level3 ">TUMBLERS</a>
                            </li>
                            <li  class="level3 nav-1-2-1-4 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/botol-minum/teko-minum.html" class="level3 ">TEKO MINUM</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-2-2 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/tempat-makan.html" class="level2 has-children">TEMPAT MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/tempat-makan.html">View All TEMPAT MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-2-2-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/tempat-makan/stoples.html" class="level3 ">STOPLES</a>
                            </li>
                            <li  class="level3 nav-1-2-2-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/tempat-makan/wadah-plastik.html" class="level3 ">WADAH PLASTIK</a>
                            </li>
                            <li  class="level3 nav-1-2-2-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/tempat-makan/wadah-kaca.html" class="level3 ">WADAH KACA</a>
                            </li>
                            <li  class="level3 nav-1-2-2-4 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/tempat-makan/tempat-roti.html" class="level3 ">TEMPAT ROTI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-2-3 last parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/kantong-makan-dan-penghangat-makanan.html" class="level2 has-children">KANTONG MAKAN DAN PENGHANGAT MAKANAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/kantong-makan-dan-penghangat-makanan.html">View All KANTONG MAKAN DAN PENGHANGAT MAKANAN</a>
                            </li>
                            <li  class="level3 nav-1-2-3-1 first last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/tempat-penyimpanan-makanan/kantong-makan-dan-penghangat-makanan/kotak-makan.html" class="level3 ">KOTAK MAKAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-1-3 parent">
                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan.html" class="level1 has-children">PERLENGKAPAN MAKAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan.html">View All PERLENGKAPAN MAKAN</a>
                    </li>
                    <li  class="level2 nav-1-3-1 first parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-minum.html" class="level2 has-children">PERALATAN MINUM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-minum.html">View All PERALATAN MINUM</a>
                            </li>
                            <li  class="level3 nav-1-3-1-1 first last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-minum/cangkir-dan-cawan.html" class="level3 ">CANGKIR DAN CAWAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-3-2 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-makan.html" class="level2 has-children">PERALATAN MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-makan.html">View All PERALATAN MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-2-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-makan/piring.html" class="level3 ">PIRING</a>
                            </li>
                            <li  class="level3 nav-1-3-2-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-makan/mangkuk.html" class="level3 ">MANGKUK</a>
                            </li>
                            <li  class="level3 nav-1-3-2-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-makan/alat-saji.html" class="level3 ">ALAT SAJI</a>
                            </li>
                            <li  class="level3 nav-1-3-2-4 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/peralatan-makan/penghangat-makanan.html" class="level3 ">PENGHANGAT MAKANAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-3-3 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/perlengkapan-makan.html" class="level2 has-children">PERLENGKAPAN MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/perlengkapan-makan.html">View All PERLENGKAPAN MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-3-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/perlengkapan-makan/set-alat-makan.html" class="level3 ">SET ALAT MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-3-2 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/perlengkapan-makan/sendok.html" class="level3 ">SENDOK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-3-4 last parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan.html" class="level2 has-children">AKSESORIS MEJA MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan.html">View All AKSESORIS MEJA MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-4-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/tatakan-piring.html" class="level3 ">TATAKAN PIRING</a>
                            </li>
                            <li  class="level3 nav-1-3-4-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/nampan.html" class="level3 ">NAMPAN</a>
                            </li>
                            <li  class="level3 nav-1-3-4-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/taplak-meja-makan.html" class="level3 ">TAPLAK MEJA MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-4-4">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/tempat-tisu-meja-makan.html" class="level3 ">TEMPAT TISU MEJA MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-4-5">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/liner-meja-makan.html" class="level3 ">LINER MEJA MAKAN</a>
                            </li>
                            <li  class="level3 nav-1-3-4-6">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/sarung-kulkas.html" class="level3 ">SARUNG KULKAS</a>
                            </li>
                            <li  class="level3 nav-1-3-4-7 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/perlengkapan-makan/aksesoris-meja-makan/serbet-dapur.html" class="level3 ">SERBET DAPUR</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-1-4 parent">
                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur.html" class="level1 has-children">PERALATAN DAPUR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur.html">View All PERALATAN DAPUR</a>
                    </li>
                    <li  class="level2 nav-1-4-1 first parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga.html" class="level2 has-children">PERALATAN LISTRIK RUMAH TANGGA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga.html">View All PERALATAN LISTRIK RUMAH TANGGA</a>
                            </li>
                            <li  class="level3 nav-1-4-1-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/blender.html" class="level3 ">BLENDER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/food-processor.html" class="level3 ">FOOD PROCESSOR</a>
                            </li>
                            <li  class="level3 nav-1-4-1-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/beverage-maker.html" class="level3 ">BEVERAGE MAKER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-4">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/toaser-pemanggang-fryer.html" class="level3 ">TOASER PEMANGGANG FRYER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-5">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/mixer.html" class="level3 ">MIXER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-6">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/food-sealer.html" class="level3 ">FOOD SEALER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-7">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/juicer.html" class="level3 ">JUICER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-8">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/water-dispenser.html" class="level3 ">WATER DISPENSER</a>
                            </li>
                            <li  class="level3 nav-1-4-1-9 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/peralatan-listrik-rumah-tangga/dessert-dan-snack-maker.html" class="level3 ">DESSERT DAN  SNACK MAKER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-4-2 last parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak.html" class="level2 has-children">ALAT MASAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak.html">View All ALAT MASAK</a>
                            </li>
                            <li  class="level3 nav-1-4-2-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak/rice-cooker.html" class="level3 ">RICE COOKER</a>
                            </li>
                            <li  class="level3 nav-1-4-2-2">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak/slow-cooker.html" class="level3 ">SLOW COOKER</a>
                            </li>
                            <li  class="level3 nav-1-4-2-3">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak/kompor-gas.html" class="level3 ">KOMPOR GAS</a>
                            </li>
                            <li  class="level3 nav-1-4-2-4">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak/microwave.html" class="level3 ">MICROWAVE</a>
                            </li>
                            <li  class="level3 nav-1-4-2-5 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/peralatan-dapur/alat-masak/oven.html" class="level3 ">OVEN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-1-5 last parent">
                <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur.html" class="level1 has-children">FURNITURE DAPUR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur.html">View All FURNITURE DAPUR</a>
                    </li>
                    <li  class="level2 nav-1-5-1 first parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/kereta-dapur.html" class="level2 has-children">KERETA DAPUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/kereta-dapur.html">View All KERETA DAPUR</a>
                            </li>
                            <li  class="level3 nav-1-5-1-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/kereta-dapur/dengan-roda.html" class="level3 ">DENGAN RODA</a>
                            </li>
                            <li  class="level3 nav-1-5-1-2 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/kereta-dapur/kereta-dapur-tanpa-roda.html" class="level3 ">KERETA DAPUR TANPA RODA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-5-2 parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/tempat-cuci-piring.html" class="level2 has-children">TEMPAT CUCI PIRING</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/tempat-cuci-piring.html">View All TEMPAT CUCI PIRING</a>
                            </li>
                            <li  class="level3 nav-1-5-2-1 first last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/tempat-cuci-piring/tempat-cuci-piring-logam.html" class="level3 ">TEMPAT CUCI PIRING LOGAM</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-1-5-3 last parent">
                        <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/rak-dapur.html" class="level2 has-children">RAK DAPUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/rak-dapur.html">View All RAK DAPUR</a>
                            </li>
                            <li  class="level3 nav-1-5-3-1 first">
                                <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/rak-dapur/rak-piring.html" class="level3 ">RAK PIRING</a>
                            </li>
                            <li  class="level3 nav-1-5-3-2 last">
                                <a href="https://m.ruparupa.com/dapur-minimalis/furniture-dapur/rak-dapur/rak.html" class="level3 ">RAK</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-2 parent">
        <a href="https://m.ruparupa.com/bed-dan-bath.html" class="level0 has-children">Bed &amp; Bath</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/bed-dan-bath.html">View All Bed &amp; Bath</a>
            </li>
            <li  class="level1 nav-2-1 first parent">
                <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur.html" class="level1 has-children">AKSESORIS TEMPAT TIDUR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur.html">View All AKSESORIS TEMPAT TIDUR</a>
                    </li>
                    <li  class="level2 nav-2-1-1 first parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/matras.html" class="level2 has-children">MATRAS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/matras.html">View All MATRAS</a>
                            </li>
                            <li  class="level3 nav-2-1-1-1 first last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/matras/kasur-lipat.html" class="level3 ">KASUR LIPAT</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-1-2 parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/aksesoris-tempat-tidur.html" class="level2 has-children">AKSESORIS TEMPAT TIDUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/aksesoris-tempat-tidur.html">View All AKSESORIS TEMPAT TIDUR</a>
                            </li>
                            <li  class="level3 nav-2-1-2-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/aksesoris-tempat-tidur/bantal.html" class="level3 ">BANTAL</a>
                            </li>
                            <li  class="level3 nav-2-1-2-2">
                                <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/aksesoris-tempat-tidur/guling.html" class="level3 ">GULING</a>
                            </li>
                            <li  class="level3 nav-2-1-2-3 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/aksesoris-tempat-tidur/selimut.html" class="level3 ">SELIMUT</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-1-3 last parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/tekstil-ruang-tidur.html" class="level2 has-children">TEKSTIL RUANG TIDUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/tekstil-ruang-tidur.html">View All TEKSTIL RUANG TIDUR</a>
                            </li>
                            <li  class="level3 nav-2-1-3-1 first last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/aksesoris-tempat-tidur/tekstil-ruang-tidur/set-seprai.html" class="level3 ">SET SEPRAI</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-2-2 parent">
                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi.html" class="level1 has-children">PERLENGKAPAN KAMAR MANDI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi.html">View All PERLENGKAPAN KAMAR MANDI</a>
                    </li>
                    <li  class="level2 nav-2-2-1 first parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/peralatan-kamar-mandi.html" class="level2 has-children">PERALATAN KAMAR MANDI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/peralatan-kamar-mandi.html">View All PERALATAN KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-1-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/peralatan-kamar-mandi/tempat-tisu-toilet.html" class="level3 ">TEMPAT TISU TOILET</a>
                            </li>
                            <li  class="level3 nav-2-2-1-2 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/peralatan-kamar-mandi/rak-kamar-mandi.html" class="level3 ">RAK KAMAR MANDI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-2-2 parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/gorden-kamar-mandi.html" class="level2 has-children">GORDEN KAMAR MANDI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/gorden-kamar-mandi.html">View All GORDEN KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-2-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/gorden-kamar-mandi/gorden-kamar-mandi-kain.html" class="level3 ">GORDEN KAMAR MANDI KAIN</a>
                            </li>
                            <li  class="level3 nav-2-2-2-2 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/gorden-kamar-mandi/ring-rail-dan-aksesoris.html" class="level3 ">RING RAIL DAN AKSESORIS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-2-3 parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi.html" class="level2 has-children">AKSESORIS KAMAR MANDI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi.html">View All AKSESORIS KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-3-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi/kepala-shower.html" class="level3 ">KEPALA SHOWER</a>
                            </li>
                            <li  class="level3 nav-2-2-3-2">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi/toilet-duduk.html" class="level3 ">TOILET DUDUK</a>
                            </li>
                            <li  class="level3 nav-2-2-3-3">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi/aksesoris-kamar-mandi.html" class="level3 ">AKSESORIS KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-3-4">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi/aksesoris-cermin-kamar-mandi.html" class="level3 ">AKSESORIS CERMIN KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-3-5 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/aksesoris-kamar-mandi/dispenser-sabun.html" class="level3 ">DISPENSER SABUN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-2-4 parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/timbangan-badan.html" class="level2 has-children">TIMBANGAN BADAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/timbangan-badan.html">View All TIMBANGAN BADAN</a>
                            </li>
                            <li  class="level3 nav-2-2-4-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/timbangan-badan/timbangan-badan-digital.html" class="level3 ">TIMBANGAN BADAN DIGITAL</a>
                            </li>
                            <li  class="level3 nav-2-2-4-2 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/timbangan-badan/timbangan-badan-mekanik.html" class="level3 ">TIMBANGAN BADAN MEKANIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-2-5 parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/pembersih-kamar-mandi.html" class="level2 has-children">PEMBERSIH KAMAR MANDI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/pembersih-kamar-mandi.html">View All PEMBERSIH KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-5-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/pembersih-kamar-mandi/pembersih-toilet.html" class="level3 ">PEMBERSIH TOILET</a>
                            </li>
                            <li  class="level3 nav-2-2-5-2">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/pembersih-kamar-mandi/keramik-dan-shower.html" class="level3 ">KERAMIK DAN SHOWER</a>
                            </li>
                            <li  class="level3 nav-2-2-5-3 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/pembersih-kamar-mandi/sikat-kamar-mandi.html" class="level3 ">SIKAT KAMAR MANDI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-2-6 last parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/tekstil-kamar-mandi.html" class="level2 has-children">TEKSTIL KAMAR MANDI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/tekstil-kamar-mandi.html">View All TEKSTIL KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-6-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/tekstil-kamar-mandi/handuk-mandi.html" class="level3 ">HANDUK MANDI</a>
                            </li>
                            <li  class="level3 nav-2-2-6-2">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/tekstil-kamar-mandi/handuk-tangan.html" class="level3 ">HANDUK TANGAN</a>
                            </li>
                            <li  class="level3 nav-2-2-6-3 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/perlengkapan-kamar-mandi/tekstil-kamar-mandi/handuk-wajah-dan-rambut.html" class="level3 ">HANDUK WAJAH DAN RAMBUT</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-2-3 parent">
                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci.html" class="level1 has-children">PERALATAN MENCUCI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci.html">View All PERALATAN MENCUCI</a>
                    </li>
                    <li  class="level2 nav-2-3-1 first parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/pengering-pakaian.html" class="level2 has-children">PENGERING PAKAIAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/pengering-pakaian.html">View All PENGERING PAKAIAN</a>
                            </li>
                            <li  class="level3 nav-2-3-1-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/pengering-pakaian/pengering-pakaian-outdoor.html" class="level3 ">PENGERING PAKAIAN OUTDOOR</a>
                            </li>
                            <li  class="level3 nav-2-3-1-2 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/pengering-pakaian/pengering-pakaian-indoor.html" class="level3 ">PENGERING PAKAIAN INDOOR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-3-2 parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/setrika.html" class="level2 has-children">SETRIKA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/setrika.html">View All SETRIKA</a>
                            </li>
                            <li  class="level3 nav-2-3-2-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/setrika/papan-setrika.html" class="level3 ">PAPAN SETRIKA</a>
                            </li>
                            <li  class="level3 nav-2-3-2-2 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/setrika/sarung-papan-setrika.html" class="level3 ">SARUNG PAPAN SETRIKA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-2-3-3 last parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/aksesoris-cuci.html" class="level2 has-children">AKSESORIS CUCI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/aksesoris-cuci.html">View All AKSESORIS CUCI</a>
                            </li>
                            <li  class="level3 nav-2-3-3-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/aksesoris-cuci/keranjang-pakaian.html" class="level3 ">KERANJANG PAKAIAN</a>
                            </li>
                            <li  class="level3 nav-2-3-3-2 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/peralatan-mencuci/aksesoris-cuci/aksesoris-indoor.html" class="level3 ">AKSESORIS INDOOR</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-2-4 last parent">
                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi.html" class="level1 has-children">PERLENGKAPAN LEDENG DAN SANITASI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi.html">View All PERLENGKAPAN LEDENG DAN SANITASI</a>
                    </li>
                    <li  class="level2 nav-2-4-1 first last parent">
                        <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng.html" class="level2 has-children">PIPA LEDENG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng.html">View All PIPA LEDENG</a>
                            </li>
                            <li  class="level3 nav-2-4-1-1 first">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/kran.html" class="level3 ">KRAN</a>
                            </li>
                            <li  class="level3 nav-2-4-1-2">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/katup.html" class="level3 ">KATUP</a>
                            </li>
                            <li  class="level3 nav-2-4-1-3">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/penyaring-air.html" class="level3 ">PENYARING AIR</a>
                            </li>
                            <li  class="level3 nav-2-4-1-4">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/tabung.html" class="level3 ">TABUNG</a>
                            </li>
                            <li  class="level3 nav-2-4-1-5">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/pelampung-toilet-katup.html" class="level3 ">PELAMPUNG TOILET KATUP</a>
                            </li>
                            <li  class="level3 nav-2-4-1-6">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/peralatan-pipa-ledeng.html" class="level3 ">PERALATAN PIPA LEDENG</a>
                            </li>
                            <li  class="level3 nav-2-4-1-7">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/perlengkapan-pembersih-saluran-air.html" class="level3 ">PERLENGKAPAN PEMBERSIH SALURAN AIR</a>
                            </li>
                            <li  class="level3 nav-2-4-1-8">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/perekat-pipa.html" class="level3 ">PEREKAT PIPA</a>
                            </li>
                            <li  class="level3 nav-2-4-1-9">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/pembersih-saluran-pembuangan.html" class="level3 ">PEMBERSIH SALURAN PEMBUANGAN</a>
                            </li>
                            <li  class="level3 nav-2-4-1-10">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/konektor-fleksibel.html" class="level3 ">KONEKTOR FLEKSIBEL</a>
                            </li>
                            <li  class="level3 nav-2-4-1-11">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/fitting.html" class="level3 ">FITTING</a>
                            </li>
                            <li  class="level3 nav-2-4-1-12 last">
                                <a href="https://m.ruparupa.com/bed-dan-bath/ledeng-sanitasi/pipa-ledeng/pipa.html" class="level3 ">PIPA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-3 parent">
        <a href="https://m.ruparupa.com/home-improvement.html" class="level0 has-children">Home Improvement</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/home-improvement.html">View All Home Improvement</a>
            </li>
            <li  class="level1 nav-3-1 first parent">
                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan.html" class="level1 has-children">PERALATAN RINGAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/peralatan-ringan.html">View All PERALATAN RINGAN</a>
                    </li>
                    <li  class="level2 nav-3-1-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda.html" class="level2 has-children">GERGAJI DAN GERINDA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda.html">View All GERGAJI DAN GERINDA</a>
                            </li>
                            <li  class="level3 nav-3-1-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda/kompas.html" class="level3 ">KOMPAS</a>
                            </li>
                            <li  class="level3 nav-3-1-1-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda/kampak.html" class="level3 ">KAMPAK</a>
                            </li>
                            <li  class="level3 nav-3-1-1-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda/gergaji-besi.html" class="level3 ">GERGAJI BESI</a>
                            </li>
                            <li  class="level3 nav-3-1-1-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda/gergaji-tangan.html" class="level3 ">GERGAJI TANGAN</a>
                            </li>
                            <li  class="level3 nav-3-1-1-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda/gerinda.html" class="level3 ">GERINDA</a>
                            </li>
                            <li  class="level3 nav-3-1-1-6 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/gergaji-dan-gerinda/aksesoris-gergaji-dan-gerinda.html" class="level3 ">AKSESORIS GERGAJI DAN GERINDA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-2 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak.html" class="level2 has-children">PALU DAN KAPAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak.html">View All PALU DAN KAPAK</a>
                            </li>
                            <li  class="level3 nav-3-1-2-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/fiberglass-clawrip.html" class="level3 ">FIBERGLASS CLAW/RIP</a>
                            </li>
                            <li  class="level3 nav-3-1-2-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/ball-pein.html" class="level3 ">BALL PEIN</a>
                            </li>
                            <li  class="level3 nav-3-1-2-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/drilling.html" class="level3 ">DRILLING</a>
                            </li>
                            <li  class="level3 nav-3-1-2-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/palu-khusus.html" class="level3 ">PALU KHUSUS</a>
                            </li>
                            <li  class="level3 nav-3-1-2-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/palu-kayu.html" class="level3 ">PALU KAYU </a>
                            </li>
                            <li  class="level3 nav-3-1-2-6">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/kapak-hickory.html" class="level3 ">KAPAK HICKORY</a>
                            </li>
                            <li  class="level3 nav-3-1-2-7 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/palu-dan-kapak/kapak.html" class="level3 ">KAPAK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-3 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pahat.html" class="level2 has-children">PAHAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pahat.html">View All PAHAT</a>
                            </li>
                            <li  class="level3 nav-3-1-3-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pahat/pahat-batu.html" class="level3 ">PAHAT BATU</a>
                            </li>
                            <li  class="level3 nav-3-1-3-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pahat/pahat-kayu.html" class="level3 ">PAHAT KAYU</a>
                            </li>
                            <li  class="level3 nav-3-1-3-3 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pahat/alat-pelubang.html" class="level3 ">ALAT PELUBANG</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-4 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/perkakas-kayu.html" class="level2 has-children">PERKAKAS KAYU</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/perkakas-kayu.html">View All PERKAKAS KAYU</a>
                            </li>
                            <li  class="level3 nav-3-1-4-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/perkakas-kayu/pengayak-pasir.html" class="level3 ">PENGAYAK PASIR</a>
                            </li>
                            <li  class="level3 nav-3-1-4-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/perkakas-kayu/alat-penghalus-kayu.html" class="level3 ">ALAT PENGHALUS KAYU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-5 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat.html" class="level2 has-children">ALAT PEREKAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat.html">View All ALAT PEREKAT</a>
                            </li>
                            <li  class="level3 nav-3-1-5-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat/staplers.html" class="level3 ">STAPLERS</a>
                            </li>
                            <li  class="level3 nav-3-1-5-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat/paku.html" class="level3 ">PAKU</a>
                            </li>
                            <li  class="level3 nav-3-1-5-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat/rivet-alat-keling.html" class="level3 ">RIVET/ ALAT KELING</a>
                            </li>
                            <li  class="level3 nav-3-1-5-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat/pasak.html" class="level3 ">PASAK</a>
                            </li>
                            <li  class="level3 nav-3-1-5-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat/lem-panas.html" class="level3 ">LEM PANAS</a>
                            </li>
                            <li  class="level3 nav-3-1-5-6 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/alat-perekat/lem-leleh.html" class="level3 ">LEM LELEH</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-6 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik.html" class="level2 has-children">PENGUNGKIT DAN PENARIK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik.html">View All PENGUNGKIT DAN PENARIK</a>
                            </li>
                            <li  class="level3 nav-3-1-6-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik/set-penjepit.html" class="level3 ">SET PENJEPIT</a>
                            </li>
                            <li  class="level3 nav-3-1-6-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik/linggis.html" class="level3 ">LINGGIS</a>
                            </li>
                            <li  class="level3 nav-3-1-6-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik/pengungkit-kayu.html" class="level3 ">PENGUNGKIT KAYU</a>
                            </li>
                            <li  class="level3 nav-3-1-6-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik/batang-pengungkit.html" class="level3 ">BATANG PENGUNGKIT</a>
                            </li>
                            <li  class="level3 nav-3-1-6-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik/rangka-baja.html" class="level3 ">RANGKA BAJA</a>
                            </li>
                            <li  class="level3 nav-3-1-6-6 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/pengungkit-dan-penarik/ripping-bars.html" class="level3 ">RIPPING BARS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-7 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng.html" class="level2 has-children">OBENG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng.html">View All OBENG</a>
                            </li>
                            <li  class="level3 nav-3-1-7-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng/obeng.html" class="level3 ">OBENG</a>
                            </li>
                            <li  class="level3 nav-3-1-7-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng/set-obeng.html" class="level3 ">SET OBENG</a>
                            </li>
                            <li  class="level3 nav-3-1-7-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng/kunci-pas-obeng.html" class="level3 ">KUNCI PAS OBENG</a>
                            </li>
                            <li  class="level3 nav-3-1-7-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng/obeng-screwholding.html" class="level3 ">OBENG SCREWHOLDING</a>
                            </li>
                            <li  class="level3 nav-3-1-7-5 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/obeng/obeng-clutch.html" class="level3 ">OBENG CLUTCH </a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-8 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas.html" class="level2 has-children">SOKET DAN KUNCI PAS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas.html">View All SOKET DAN KUNCI PAS</a>
                            </li>
                            <li  class="level3 nav-3-1-8-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/kunci-inggris.html" class="level3 ">KUNCI INGGRIS</a>
                            </li>
                            <li  class="level3 nav-3-1-8-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/kunci-pas.html" class="level3 ">KUNCI PAS</a>
                            </li>
                            <li  class="level3 nav-3-1-8-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/kunci-l.html" class="level3 ">KUNCI L</a>
                            </li>
                            <li  class="level3 nav-3-1-8-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/kunci-pas-torsi.html" class="level3 ">KUNCI PAS TORSI</a>
                            </li>
                            <li  class="level3 nav-3-1-8-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/set-kunci-pas.html" class="level3 ">SET KUNCI PAS</a>
                            </li>
                            <li  class="level3 nav-3-1-8-6">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/ring-kunci-pas.html" class="level3 ">RING KUNCI PAS</a>
                            </li>
                            <li  class="level3 nav-3-1-8-7 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/soket-dan-kunci-pas/soket.html" class="level3 ">SOKET</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-9 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong.html" class="level2 has-children">TANG DAN PEMOTONG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong.html">View All TANG DAN PEMOTONG</a>
                            </li>
                            <li  class="level3 nav-3-1-9-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/tang-solid-joint.html" class="level3 ">TANG SOLID JOINT</a>
                            </li>
                            <li  class="level3 nav-3-1-9-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/tang-slip.html" class="level3 ">TANG SLIP</a>
                            </li>
                            <li  class="level3 nav-3-1-9-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/set-tang.html" class="level3 ">SET TANG</a>
                            </li>
                            <li  class="level3 nav-3-1-9-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/tang-pemotong.html" class="level3 ">TANG PEMOTONG</a>
                            </li>
                            <li  class="level3 nav-3-1-9-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/pemotong-serbaguna.html" class="level3 ">PEMOTONG SERBAGUNA</a>
                            </li>
                            <li  class="level3 nav-3-1-9-6">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/pemotong-baut.html" class="level3 ">PEMOTONG BAUT</a>
                            </li>
                            <li  class="level3 nav-3-1-9-7">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/pembuat-gelombang.html" class="level3 ">PEMBUAT GELOMBANG</a>
                            </li>
                            <li  class="level3 nav-3-1-9-8 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/tang-dan-pemotong/kunci-inggris.html" class="level3 ">KUNCI INGGRIS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-1-10 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan.html" class="level2 has-children">KOTAK PERALATAN DAN PERLENGKAPAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan.html">View All KOTAK PERALATAN DAN PERLENGKAPAN</a>
                            </li>
                            <li  class="level3 nav-3-1-10-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan/kotak-peralatan-plastik.html" class="level3 ">KOTAK PERALATAN PLASTIK</a>
                            </li>
                            <li  class="level3 nav-3-1-10-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan/kotak-peralatan-besi.html" class="level3 ">KOTAK PERALATAN BESI</a>
                            </li>
                            <li  class="level3 nav-3-1-10-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan/lemari-beroda.html" class="level3 ">LEMARI BERODA</a>
                            </li>
                            <li  class="level3 nav-3-1-10-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan/multi-tool-kit.html" class="level3 ">MULTI TOOL KIT</a>
                            </li>
                            <li  class="level3 nav-3-1-10-5 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-ringan/kotak-peralatan-dan-perlengkapan/specialty-tool-boxes.html" class="level3 ">SPECIALTY TOOL BOXES</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-2 parent">
                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik.html" class="level1 has-children">PERALATAN LISTRIK</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/peralatan-listrik.html">View All PERALATAN LISTRIK</a>
                    </li>
                    <li  class="level2 nav-3-2-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/bor.html" class="level2 has-children">BOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-listrik/bor.html">View All BOR</a>
                            </li>
                            <li  class="level3 nav-3-2-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/bor/bor.html" class="level3 ">BOR</a>
                            </li>
                            <li  class="level3 nav-3-2-1-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/bor/bor-tanpa-kabel.html" class="level3 ">BOR TANPA KABEL</a>
                            </li>
                            <li  class="level3 nav-3-2-1-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/bor/bor-tangan.html" class="level3 ">BOR TANGAN</a>
                            </li>
                            <li  class="level3 nav-3-2-1-4 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/bor/aksesoris-bor.html" class="level3 ">AKSESORIS BOR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-2-2 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat.html" class="level2 has-children">PERALATAN BERAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat.html">View All PERALATAN BERAT</a>
                            </li>
                            <li  class="level3 nav-3-2-2-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/gergaji.html" class="level3 ">GERGAJI</a>
                            </li>
                            <li  class="level3 nav-3-2-2-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/mesin-perata.html" class="level3 ">MESIN PERATA</a>
                            </li>
                            <li  class="level3 nav-3-2-2-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/impact-wrenches.html" class="level3 ">IMPACT WRENCHES</a>
                            </li>
                            <li  class="level3 nav-3-2-2-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/mesin-grinda.html" class="level3 ">MESIN GRINDA</a>
                            </li>
                            <li  class="level3 nav-3-2-2-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/mesin-penghalus.html" class="level3 ">MESIN PENGHALUS</a>
                            </li>
                            <li  class="level3 nav-3-2-2-6">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/kompresor.html" class="level3 ">KOMPRESOR</a>
                            </li>
                            <li  class="level3 nav-3-2-2-7">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/heat-gun.html" class="level3 ">HEAT GUN</a>
                            </li>
                            <li  class="level3 nav-3-2-2-8">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/alat-pemotong.html" class="level3 ">ALAT PEMOTONG</a>
                            </li>
                            <li  class="level3 nav-3-2-2-9">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/polisher.html" class="level3 ">POLISHER</a>
                            </li>
                            <li  class="level3 nav-3-2-2-10 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-listrik/peralatan-berat/obeng.html" class="level3 ">OBENG</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-3 parent">
                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri.html" class="level1 has-children">PERALATAN INDUSTRI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/peralatan-industri.html">View All PERALATAN INDUSTRI</a>
                    </li>
                    <li  class="level2 nav-3-3-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur.html" class="level2 has-children">ALAT PENGUKUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur.html">View All ALAT PENGUKUR</a>
                            </li>
                            <li  class="level3 nav-3-3-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur/alat-pengukur-elektronik.html" class="level3 ">ALAT PENGUKUR ELEKTRONIK</a>
                            </li>
                            <li  class="level3 nav-3-3-1-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur/alat-pengukur-beroda.html" class="level3 ">ALAT PENGUKUR BERODA</a>
                            </li>
                            <li  class="level3 nav-3-3-1-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur/meteran.html" class="level3 ">METERAN</a>
                            </li>
                            <li  class="level3 nav-3-3-1-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur/caliper.html" class="level3 ">CALIPER</a>
                            </li>
                            <li  class="level3 nav-3-3-1-5 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-pengukur/alat-presisi-khusus.html" class="level3 ">ALAT PRESISI KHUSUS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-3-2 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan.html" class="level2 has-children">ALAT KESELAMATAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan.html">View All ALAT KESELAMATAN</a>
                            </li>
                            <li  class="level3 nav-3-3-2-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/pelindung-mata.html" class="level3 ">PELINDUNG MATA</a>
                            </li>
                            <li  class="level3 nav-3-3-2-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/pelindung-kepala.html" class="level3 ">PELINDUNG KEPALA</a>
                            </li>
                            <li  class="level3 nav-3-3-2-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/pelindung-tubuh.html" class="level3 ">PELINDUNG TUBUH</a>
                            </li>
                            <li  class="level3 nav-3-3-2-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/pelindung-kaki.html" class="level3 ">PELINDUNG KAKI</a>
                            </li>
                            <li  class="level3 nav-3-3-2-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/pengikat-khusus.html" class="level3 ">PENGIKAT KHUSUS</a>
                            </li>
                            <li  class="level3 nav-3-3-2-6">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/apron-konstruksi.html" class="level3 ">APRON KONSTRUKSI</a>
                            </li>
                            <li  class="level3 nav-3-3-2-7">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/ikat-pinggang-dan-tali-selempang.html" class="level3 ">IKAT PINGGANG DAN TALI SELEMPANG</a>
                            </li>
                            <li  class="level3 nav-3-3-2-8 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-keselamatan/pelindung-tangan.html" class="level3 ">PELINDUNG TANGAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-3-3 parent">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata.html" class="level2 has-children">PENGHALUS DAN PERATA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata.html">View All PENGHALUS DAN PERATA</a>
                            </li>
                            <li  class="level3 nav-3-3-3-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/cetok-semen.html" class="level3 ">CETOK SEMEN</a>
                            </li>
                            <li  class="level3 nav-3-3-3-2">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/float.html" class="level3 ">FLOAT</a>
                            </li>
                            <li  class="level3 nav-3-3-3-3">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/alat-semen-khusus.html" class="level3 ">ALAT SEMEN KHUSUS</a>
                            </li>
                            <li  class="level3 nav-3-3-3-4">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/sekop-drywall.html" class="level3 ">SEKOP DRYWALL</a>
                            </li>
                            <li  class="level3 nav-3-3-3-5">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/ampelas-tangan-dan-tiang.html" class="level3 ">AMPELAS TANGAN DAN TIANG</a>
                            </li>
                            <li  class="level3 nav-3-3-3-6">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/pisau-perata.html" class="level3 ">PISAU PERATA</a>
                            </li>
                            <li  class="level3 nav-3-3-3-7">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/peralatan-drywall.html" class="level3 ">PERALATAN DRYWALL</a>
                            </li>
                            <li  class="level3 nav-3-3-3-8">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/alat-perata-semen.html" class="level3 ">ALAT PERATA SEMEN</a>
                            </li>
                            <li  class="level3 nav-3-3-3-9">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/alat-perata-khusus.html" class="level3 ">ALAT PERATA KHUSUS</a>
                            </li>
                            <li  class="level3 nav-3-3-3-10">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/aksesoris-drywall.html" class="level3 ">AKSESORIS DRYWALL</a>
                            </li>
                            <li  class="level3 nav-3-3-3-11 last">
                                <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/penghalus-dan-perata/sekop.html" class="level3 ">SEKOP</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-3-4 last">
                        <a href="https://m.ruparupa.com/home-improvement/peralatan-industri/alat-las-dan-aksesoris.html" class="level2 ">ALAT LAS DAN AKSESORIS</a>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-4 parent">
                <a href="https://m.ruparupa.com/home-improvement/power-supply.html" class="level1 has-children">POWER SUPPLY</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/power-supply.html">View All POWER SUPPLY</a>
                    </li>
                    <li  class="level2 nav-3-4-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/power-supply/genset.html" class="level2 has-children">GENSET</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/power-supply/genset.html">View All GENSET</a>
                            </li>
                            <li  class="level3 nav-3-4-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/genset/generator-diesel.html" class="level3 ">GENERATOR DIESEL</a>
                            </li>
                            <li  class="level3 nav-3-4-1-2">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/genset/generator-gas.html" class="level3 ">GENERATOR GAS</a>
                            </li>
                            <li  class="level3 nav-3-4-1-3">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/genset/generator-bensin.html" class="level3 ">GENERATOR BENSIN</a>
                            </li>
                            <li  class="level3 nav-3-4-1-4">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/genset/generator-solar.html" class="level3 ">GENERATOR SOLAR</a>
                            </li>
                            <li  class="level3 nav-3-4-1-5 last">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/genset/generator-angin.html" class="level3 ">GENERATOR ANGIN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-4-2 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/power-supply/aksesoris-power-supply.html" class="level2 has-children">AKSESORIS POWER SUPPLY</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/power-supply/aksesoris-power-supply.html">View All AKSESORIS POWER SUPPLY</a>
                            </li>
                            <li  class="level3 nav-3-4-2-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/aksesoris-power-supply/power-supply-darurat.html" class="level3 ">POWER SUPPLY DARURAT</a>
                            </li>
                            <li  class="level3 nav-3-4-2-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/power-supply/aksesoris-power-supply/inverter.html" class="level3 ">INVERTER</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-5 parent">
                <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan.html" class="level1 has-children">PERLINDUNGAN DAN KEAMANAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan.html">View All PERLINDUNGAN DAN KEAMANAN</a>
                    </li>
                    <li  class="level2 nav-3-5-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/alarm-dan-keamanan.html" class="level2 has-children">ALARM DAN KEAMANAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/alarm-dan-keamanan.html">View All ALARM DAN KEAMANAN</a>
                            </li>
                            <li  class="level3 nav-3-5-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/alarm-dan-keamanan/sistem-alarm.html" class="level3 ">SISTEM ALARM</a>
                            </li>
                            <li  class="level3 nav-3-5-1-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/alarm-dan-keamanan/gembok.html" class="level3 ">GEMBOK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-5-2 parent">
                        <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/perlindungan-keselamatan.html" class="level2 has-children">PERLINDUNGAN KESELAMATAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/perlindungan-keselamatan.html">View All PERLINDUNGAN KESELAMATAN</a>
                            </li>
                            <li  class="level3 nav-3-5-2-1 first last">
                                <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/perlindungan-keselamatan/keamanan-anak.html" class="level3 ">KEAMANAN ANAK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-5-3 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/keselamatan-dan-tanda-bahaya.html" class="level2 has-children">KESELAMATAN DAN TANDA BAHAYA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/keselamatan-dan-tanda-bahaya.html">View All KESELAMATAN DAN TANDA BAHAYA</a>
                            </li>
                            <li  class="level3 nav-3-5-3-1 first last">
                                <a href="https://m.ruparupa.com/home-improvement/perlindungan-dan-keamanan/keselamatan-dan-tanda-bahaya/sinyal-lampu.html" class="level3 ">SINYAL LAMPU</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-6 parent">
                <a href="https://m.ruparupa.com/home-improvement/brankas.html" class="level1 has-children">BRANKAS</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/brankas.html">View All BRANKAS</a>
                    </li>
                    <li  class="level2 nav-3-6-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/brankas/brankas-tahan-api.html" class="level2 has-children">BRANKAS TAHAN API</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/brankas/brankas-tahan-api.html">View All BRANKAS TAHAN API</a>
                            </li>
                            <li  class="level3 nav-3-6-1-1 first last">
                                <a href="https://m.ruparupa.com/home-improvement/brankas/brankas-tahan-api/brankas-tahan-api.html" class="level3 ">BRANKAS TAHAN API</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-6-2 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/brankas/brankas-besi.html" class="level2 has-children">BRANKAS BESI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/brankas/brankas-besi.html">View All BRANKAS BESI</a>
                            </li>
                            <li  class="level3 nav-3-6-2-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/brankas/brankas-besi/brankas-kombinasi.html" class="level3 ">BRANKAS KOMBINASI</a>
                            </li>
                            <li  class="level3 nav-3-6-2-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/brankas/brankas-besi/brankas-kunci.html" class="level3 ">BRANKAS KUNCI</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-7 parent">
                <a href="https://m.ruparupa.com/home-improvement/hardware.html" class="level1 has-children">HARDWARE</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/hardware.html">View All HARDWARE</a>
                    </li>
                    <li  class="level2 nav-3-7-1 first last parent">
                        <a href="https://m.ruparupa.com/home-improvement/hardware/part-pengencang.html" class="level2 has-children">PART PENGENCANG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/hardware/part-pengencang.html">View All PART PENGENCANG</a>
                            </li>
                            <li  class="level3 nav-3-7-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/hardware/part-pengencang/tali.html" class="level3 ">TALI</a>
                            </li>
                            <li  class="level3 nav-3-7-1-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/hardware/part-pengencang/rantai.html" class="level3 ">RANTAI</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-8 parent">
                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya.html" class="level1 has-children">CAT DAN PERALATANNYA</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya.html">View All CAT DAN PERALATANNYA</a>
                    </li>
                    <li  class="level2 nav-3-8-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/cat-semprot.html" class="level2 has-children">CAT SEMPROT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/cat-semprot.html">View All CAT SEMPROT</a>
                            </li>
                            <li  class="level3 nav-3-8-1-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/cat-semprot/warna-dasar.html" class="level3 ">WARNA DASAR</a>
                            </li>
                            <li  class="level3 nav-3-8-1-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/cat-semprot/warna-khusus.html" class="level3 ">WARNA KHUSUS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-8-2 parent">
                        <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/perangkat-cat.html" class="level2 has-children">PERANGKAT CAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/perangkat-cat.html">View All PERANGKAT CAT</a>
                            </li>
                            <li  class="level3 nav-3-8-2-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/perangkat-cat/lem.html" class="level3 ">LEM</a>
                            </li>
                            <li  class="level3 nav-3-8-2-2">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/perangkat-cat/sealant.html" class="level3 ">SEALANT</a>
                            </li>
                            <li  class="level3 nav-3-8-2-3 last">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/perangkat-cat/kuas.html" class="level3 ">KUAS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-8-3 parent">
                        <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/peralatan-mengecat.html" class="level2 has-children">PERALATAN MENGECAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/peralatan-mengecat.html">View All PERALATAN MENGECAT</a>
                            </li>
                            <li  class="level3 nav-3-8-3-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/peralatan-mengecat/pengikis.html" class="level3 ">PENGIKIS</a>
                            </li>
                            <li  class="level3 nav-3-8-3-2">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/peralatan-mengecat/ampelas.html" class="level3 ">AMPELAS</a>
                            </li>
                            <li  class="level3 nav-3-8-3-3">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/peralatan-mengecat/kuas-rol.html" class="level3 ">KUAS ROL</a>
                            </li>
                            <li  class="level3 nav-3-8-3-4 last">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/peralatan-mengecat/gagang-tambahan.html" class="level3 ">GAGANG TAMBAHAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-8-4 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/pita-perekat.html" class="level2 has-children">PITA PEREKAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/pita-perekat.html">View All PITA PEREKAT</a>
                            </li>
                            <li  class="level3 nav-3-8-4-1 first">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/pita-perekat/alat-perekat-plastik.html" class="level3 ">ALAT PEREKAT PLASTIK</a>
                            </li>
                            <li  class="level3 nav-3-8-4-2 last">
                                <a href="https://m.ruparupa.com/home-improvement/cat-dan-peralatannya/pita-perekat/penambal.html" class="level3 ">PENAMBAL</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-3-9 last parent">
                <a href="https://m.ruparupa.com/home-improvement/bahan-bangunan.html" class="level1 has-children">BAHAN BANGUNAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/home-improvement/bahan-bangunan.html">View All BAHAN BANGUNAN</a>
                    </li>
                    <li  class="level2 nav-3-9-1 first parent">
                        <a href="https://m.ruparupa.com/home-improvement/bahan-bangunan/lantai.html" class="level2 has-children">LANTAI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/bahan-bangunan/lantai.html">View All LANTAI</a>
                            </li>
                            <li  class="level3 nav-3-9-1-1 first last">
                                <a href="https://m.ruparupa.com/home-improvement/bahan-bangunan/lantai/lantai-outdoor.html" class="level3 ">LANTAI OUTDOOR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-3-9-2 last parent">
                        <a href="https://m.ruparupa.com/home-improvement/bahan-bangunan/hiasan-dinding.html" class="level2 has-children">HIASAN DINDING</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/home-improvement/bahan-bangunan/hiasan-dinding.html">View All HIASAN DINDING</a>
                            </li>
                            <li  class="level3 nav-3-9-2-1 first last">
                                <a href="https://m.ruparupa.com/home-improvement/bahan-bangunan/hiasan-dinding/wall-paper.html" class="level3 ">WALL PAPER</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-4 parent">
        <a href="https://m.ruparupa.com/furniture.html" class="level0 has-children">Furniture</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/furniture.html">View All Furniture</a>
            </li>
            <li  class="level1 nav-4-1 first parent">
                <a href="https://m.ruparupa.com/furniture/ruang-tamu.html" class="level1 has-children">RUANG TAMU</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/ruang-tamu.html">View All RUANG TAMU</a>
                    </li>
                    <li  class="level2 nav-4-1-1 first last parent">
                        <a href="https://m.ruparupa.com/furniture/ruang-tamu/sofa-club.html" class="level2 has-children">SOFA CLUB</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/ruang-tamu/sofa-club.html">View All SOFA CLUB</a>
                            </li>
                            <li  class="level3 nav-4-1-1-1 first last">
                                <a href="https://m.ruparupa.com/furniture/ruang-tamu/sofa-club/kursi-putar.html" class="level3 ">KURSI PUTAR</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-2 parent">
                <a href="https://m.ruparupa.com/furniture/ruang-makan.html" class="level1 has-children">RUANG MAKAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/ruang-makan.html">View All RUANG MAKAN</a>
                    </li>
                    <li  class="level2 nav-4-2-1 first parent">
                        <a href="https://m.ruparupa.com/furniture/ruang-makan/meja-makan.html" class="level2 has-children">MEJA MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/ruang-makan/meja-makan.html">View All MEJA MAKAN</a>
                            </li>
                            <li  class="level3 nav-4-2-1-1 first">
                                <a href="https://m.ruparupa.com/furniture/ruang-makan/meja-makan/meja-santai.html" class="level3 ">MEJA SANTAI</a>
                            </li>
                            <li  class="level3 nav-4-2-1-2 last">
                                <a href="https://m.ruparupa.com/furniture/ruang-makan/meja-makan/meja-makan.html" class="level3 ">MEJA MAKAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-2-2 last parent">
                        <a href="https://m.ruparupa.com/furniture/ruang-makan/kursi-makan.html" class="level2 has-children">KURSI MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/ruang-makan/kursi-makan.html">View All KURSI MAKAN</a>
                            </li>
                            <li  class="level3 nav-4-2-2-1 first last">
                                <a href="https://m.ruparupa.com/furniture/ruang-makan/kursi-makan/kursi-makan.html" class="level3 ">KURSI MAKAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-3 parent">
                <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi.html" class="level1 has-children">KAMAR ANAK &amp; BAYI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi.html">View All KAMAR ANAK &amp; BAYI</a>
                    </li>
                    <li  class="level2 nav-4-3-1 first parent">
                        <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-anak.html" class="level2 has-children">FURNITUR ANAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-anak.html">View All FURNITUR ANAK</a>
                            </li>
                            <li  class="level3 nav-4-3-1-1 first">
                                <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-anak/kursi-anak.html" class="level3 ">KURSI ANAK</a>
                            </li>
                            <li  class="level3 nav-4-3-1-2 last">
                                <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-anak/aksesoris-kamar.html" class="level3 ">AKSESORIS KAMAR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-3-2 last parent">
                        <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-bayi.html" class="level2 has-children">FURNITUR BAYI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-bayi.html">View All FURNITUR BAYI</a>
                            </li>
                            <li  class="level3 nav-4-3-2-1 first">
                                <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-bayi/tempat-tidur-bayi.html" class="level3 ">TEMPAT TIDUR BAYI</a>
                            </li>
                            <li  class="level3 nav-4-3-2-2 last">
                                <a href="https://m.ruparupa.com/furniture/kamar-anak-dan-bayi/furnitur-bayi/kursi-bayi.html" class="level3 ">KURSI BAYI</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-4 parent">
                <a href="https://m.ruparupa.com/furniture/ruang-kerja.html" class="level1 has-children">RUANG KERJA</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/ruang-kerja.html">View All RUANG KERJA</a>
                    </li>
                    <li  class="level2 nav-4-4-1 first last parent">
                        <a href="https://m.ruparupa.com/furniture/ruang-kerja/kursi-ruang-kerja.html" class="level2 has-children">KURSI RUANG KERJA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/ruang-kerja/kursi-ruang-kerja.html">View All KURSI RUANG KERJA</a>
                            </li>
                            <li  class="level3 nav-4-4-1-1 first">
                                <a href="https://m.ruparupa.com/furniture/ruang-kerja/kursi-ruang-kerja/kursi-eksekutif.html" class="level3 ">KURSI EKSEKUTIF</a>
                            </li>
                            <li  class="level3 nav-4-4-1-2 last">
                                <a href="https://m.ruparupa.com/furniture/ruang-kerja/kursi-ruang-kerja/kursi-staf.html" class="level3 ">KURSI STAF</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-5 parent">
                <a href="https://m.ruparupa.com/furniture/aneka-rak.html" class="level1 has-children">ANEKA RAK</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/aneka-rak.html">View All ANEKA RAK</a>
                    </li>
                    <li  class="level2 nav-4-5-1 first parent">
                        <a href="https://m.ruparupa.com/furniture/aneka-rak/brackets.html" class="level2 has-children">BRACKETS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/aneka-rak/brackets.html">View All BRACKETS</a>
                            </li>
                            <li  class="level3 nav-4-5-1-1 first">
                                <a href="https://m.ruparupa.com/furniture/aneka-rak/brackets/rak-dan-bracket.html" class="level3 ">RAK DAN BRACKET</a>
                            </li>
                            <li  class="level3 nav-4-5-1-2 last">
                                <a href="https://m.ruparupa.com/furniture/aneka-rak/brackets/set-rak-dan-bracket.html" class="level3 ">SET RAK DAN BRACKET</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-5-2 parent">
                        <a href="https://m.ruparupa.com/furniture/aneka-rak/rak-kombinasi.html" class="level2 has-children">RAK KOMBINASI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/aneka-rak/rak-kombinasi.html">View All RAK KOMBINASI</a>
                            </li>
                            <li  class="level3 nav-4-5-2-1 first last">
                                <a href="https://m.ruparupa.com/furniture/aneka-rak/rak-kombinasi/keranjang-penyimpanan.html" class="level3 ">KERANJANG PENYIMPANAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-5-3 last parent">
                        <a href="https://m.ruparupa.com/furniture/aneka-rak/racking-system.html" class="level2 has-children">RACKING SYSTEM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/aneka-rak/racking-system.html">View All RACKING SYSTEM</a>
                            </li>
                            <li  class="level3 nav-4-5-3-1 first last">
                                <a href="https://m.ruparupa.com/furniture/aneka-rak/racking-system/rak-pijak.html" class="level3 ">RAK PIJAK</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-6 parent">
                <a href="https://m.ruparupa.com/furniture/penerangan.html" class="level1 has-children">PENERANGAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/penerangan.html">View All PENERANGAN</a>
                    </li>
                    <li  class="level2 nav-4-6-1 first parent">
                        <a href="https://m.ruparupa.com/furniture/penerangan/lampu.html" class="level2 has-children">LAMPU</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/penerangan/lampu.html">View All LAMPU</a>
                            </li>
                            <li  class="level3 nav-4-6-1-1 first">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu/lampu-meja.html" class="level3 ">LAMPU MEJA</a>
                            </li>
                            <li  class="level3 nav-4-6-1-2">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu/lampu-hias.html" class="level3 ">LAMPU HIAS</a>
                            </li>
                            <li  class="level3 nav-4-6-1-3">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu/lampu-gantung.html" class="level3 ">LAMPU GANTUNG</a>
                            </li>
                            <li  class="level3 nav-4-6-1-4">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu/lampu-langit-langit.html" class="level3 ">LAMPU LANGIT-LANGIT</a>
                            </li>
                            <li  class="level3 nav-4-6-1-5 last">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu/lampu-tidur.html" class="level3 ">LAMPU TIDUR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-6-2 parent">
                        <a href="https://m.ruparupa.com/furniture/penerangan/lampu-outdoor.html" class="level2 has-children">LAMPU OUTDOOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/penerangan/lampu-outdoor.html">View All LAMPU OUTDOOR</a>
                            </li>
                            <li  class="level3 nav-4-6-2-1 first last">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu-outdoor/lampu-tembak.html" class="level3 ">LAMPU TEMBAK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-6-3 parent">
                        <a href="https://m.ruparupa.com/furniture/penerangan/bohlam.html" class="level2 has-children">BOHLAM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/penerangan/bohlam.html">View All BOHLAM</a>
                            </li>
                            <li  class="level3 nav-4-6-3-1 first last">
                                <a href="https://m.ruparupa.com/furniture/penerangan/bohlam/bohlam-led.html" class="level3 ">BOHLAM LED</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-6-4 last parent">
                        <a href="https://m.ruparupa.com/furniture/penerangan/lampu-proyek.html" class="level2 has-children">LAMPU PROYEK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/penerangan/lampu-proyek.html">View All LAMPU PROYEK</a>
                            </li>
                            <li  class="level3 nav-4-6-4-1 first">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu-proyek/halogen.html" class="level3 ">HALOGEN</a>
                            </li>
                            <li  class="level3 nav-4-6-4-2 last">
                                <a href="https://m.ruparupa.com/furniture/penerangan/lampu-proyek/incandescent.html" class="level3 ">INCANDESCENT</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-7 parent">
                <a href="https://m.ruparupa.com/furniture/produk-eksterior.html" class="level1 has-children">PRODUK EKSTERIOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/produk-eksterior.html">View All PRODUK EKSTERIOR</a>
                    </li>
                    <li  class="level2 nav-4-7-1 first parent">
                        <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-taman.html" class="level2 has-children">FURNITUR TAMAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-taman.html">View All FURNITUR TAMAN</a>
                            </li>
                            <li  class="level3 nav-4-7-1-1 first last">
                                <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-taman/kursi-taman-dan-teras.html" class="level3 ">KURSI TAMAN DAN TERAS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-7-2 last parent">
                        <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-teras.html" class="level2 has-children">FURNITUR TERAS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-teras.html">View All FURNITUR TERAS</a>
                            </li>
                            <li  class="level3 nav-4-7-2-1 first">
                                <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-teras/meja-teras.html" class="level3 ">MEJA TERAS</a>
                            </li>
                            <li  class="level3 nav-4-7-2-2">
                                <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-teras/kursi-teras.html" class="level3 ">KURSI TERAS</a>
                            </li>
                            <li  class="level3 nav-4-7-2-3">
                                <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-teras/bangku-teras.html" class="level3 ">BANGKU TERAS</a>
                            </li>
                            <li  class="level3 nav-4-7-2-4 last">
                                <a href="https://m.ruparupa.com/furniture/produk-eksterior/furnitur-teras/set-teras.html" class="level3 ">SET TERAS</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-4-8 last parent">
                <a href="https://m.ruparupa.com/furniture/komersial.html" class="level1 has-children">KOMERSIAL</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/furniture/komersial.html">View All KOMERSIAL</a>
                    </li>
                    <li  class="level2 nav-4-8-1 first parent">
                        <a href="https://m.ruparupa.com/furniture/komersial/hotel-restaurant-cafe.html" class="level2 has-children">HOTEL RESTAURANT CAFE</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/komersial/hotel-restaurant-cafe.html">View All HOTEL RESTAURANT CAFE</a>
                            </li>
                            <li  class="level3 nav-4-8-1-1 first">
                                <a href="https://m.ruparupa.com/furniture/komersial/hotel-restaurant-cafe/bangku-komersial.html" class="level3 ">BANGKU KOMERSIAL</a>
                            </li>
                            <li  class="level3 nav-4-8-1-2 last">
                                <a href="https://m.ruparupa.com/furniture/komersial/hotel-restaurant-cafe/kursi-horeca.html" class="level3 ">KURSI HORECA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-4-8-2 last parent">
                        <a href="https://m.ruparupa.com/furniture/komersial/banquet.html" class="level2 has-children">BANQUET</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/furniture/komersial/banquet.html">View All BANQUET</a>
                            </li>
                            <li  class="level3 nav-4-8-2-1 first">
                                <a href="https://m.ruparupa.com/furniture/komersial/banquet/meja-banquet.html" class="level3 ">MEJA BANQUET</a>
                            </li>
                            <li  class="level3 nav-4-8-2-2">
                                <a href="https://m.ruparupa.com/furniture/komersial/banquet/kursi-banquet.html" class="level3 ">KURSI BANQUET</a>
                            </li>
                            <li  class="level3 nav-4-8-2-3 last">
                                <a href="https://m.ruparupa.com/furniture/komersial/banquet/podium.html" class="level3 ">PODIUM</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-5 parent">
        <a href="https://m.ruparupa.com/otomotif.html" class="level0 has-children">Otomotif</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/otomotif.html">View All Otomotif</a>
            </li>
            <li  class="level1 nav-5-1 first parent">
                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor.html" class="level1 has-children">PERAWATAN MOBIL MOTOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor.html">View All PERAWATAN MOBIL MOTOR</a>
                    </li>
                    <li  class="level2 nav-5-1-1 first parent">
                        <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil.html" class="level2 has-children">ALAT CUCI MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil.html">View All ALAT CUCI MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil/alat-cuci-mobil.html" class="level3 ">ALAT CUCI MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-1-2">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil/chamois-sintetis.html" class="level3 ">CHAMOIS SINTETIS</a>
                            </li>
                            <li  class="level3 nav-5-1-1-3">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil/spons-mobil.html" class="level3 ">SPONS MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-1-4">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil/lap-mobil.html" class="level3 ">LAP MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-1-5">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil/penyedot-debu-mobil.html" class="level3 ">PENYEDOT DEBU MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-1-6 last">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/alat-cuci-mobil/pembersih-serba-guna.html" class="level3 ">PEMBERSIH SERBA GUNA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-1-2 parent">
                        <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil.html" class="level2 has-children">PERAWATAN EKSTERIOR MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil.html">View All PERAWATAN EKSTERIOR MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-2-1 first">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil/penghilang-tar-dan-kutu.html" class="level3 ">PENGHILANG TAR DAN KUTU</a>
                            </li>
                            <li  class="level3 nav-5-1-2-2">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil/compound-auto-polish.html" class="level3 ">COMPOUND AUTO POLISH</a>
                            </li>
                            <li  class="level3 nav-5-1-2-3">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil/detailer.html" class="level3 ">DETAILER</a>
                            </li>
                            <li  class="level3 nav-5-1-2-4">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil/pembersih-ban-dan-roda.html" class="level3 ">PEMBERSIH BAN DAN RODA</a>
                            </li>
                            <li  class="level3 nav-5-1-2-5">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil/pembersih-vinil-dan-plastik.html" class="level3 ">PEMBERSIH VINIL DAN PLASTIK</a>
                            </li>
                            <li  class="level3 nav-5-1-2-6 last">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-eksterior-mobil/pembersih-dan-pelembab-kulit.html" class="level3 ">PEMBERSIH DAN PELEMBAB KULIT</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-1-3 parent">
                        <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin.html" class="level2 has-children">PERAWATAN MESIN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin.html">View All PERAWATAN MESIN</a>
                            </li>
                            <li  class="level3 nav-5-1-3-1 first">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/pembersih-injektor-bahan-bakar.html" class="level3 ">PEMBERSIH INJEKTOR BAHAN BAKAR</a>
                            </li>
                            <li  class="level3 nav-5-1-3-2">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/sistem-pembersihan-bahan-bakar.html" class="level3 ">SISTEM PEMBERSIHAN BAHAN BAKAR</a>
                            </li>
                            <li  class="level3 nav-5-1-3-3">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/aditif-solar.html" class="level3 ">ADITIF SOLAR</a>
                            </li>
                            <li  class="level3 nav-5-1-3-4">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/semprotan-pembersih-karburator-dan-injektor.html" class="level3 ">SEMPROTAN PEMBERSIH KARBURATOR DAN INJEKTOR</a>
                            </li>
                            <li  class="level3 nav-5-1-3-5">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/aditif-transmisi.html" class="level3 ">ADITIF TRANSMISI</a>
                            </li>
                            <li  class="level3 nav-5-1-3-6">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/oli-motor-aditif.html" class="level3 ">OLI MOTOR ADITIF</a>
                            </li>
                            <li  class="level3 nav-5-1-3-7">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/sistem-pendingin-kimia.html" class="level3 ">SISTEM PENDINGIN KIMIA</a>
                            </li>
                            <li  class="level3 nav-5-1-3-8">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/pembersih-kaca-depan-mobil-antifrz.html" class="level3 ">PEMBERSIH KACA DEPAN MOBIL/ ANTIFRZ</a>
                            </li>
                            <li  class="level3 nav-5-1-3-9">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/cairan-pembersih-kaca-depan.html" class="level3 ">CAIRAN PEMBERSIH KACA DEPAN</a>
                            </li>
                            <li  class="level3 nav-5-1-3-10">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/pembersih-rem.html" class="level3 ">PEMBERSIH REM</a>
                            </li>
                            <li  class="level3 nav-5-1-3-11">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/degreaser-mesin.html" class="level3 ">DEGREASER MESIN</a>
                            </li>
                            <li  class="level3 nav-5-1-3-12 last">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/perawatan-mesin/pembersih-bagian-elektrik.html" class="level3 ">PEMBERSIH BAGIAN ELEKTRIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-1-4 parent">
                        <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas.html" class="level2 has-children">OLI DAN PELUMAS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas.html">View All OLI DAN PELUMAS</a>
                            </li>
                            <li  class="level3 nav-5-1-4-1 first">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas/oli-motor-multi-grade.html" class="level3 ">OLI MOTOR MULTI-GRADE</a>
                            </li>
                            <li  class="level3 nav-5-1-4-2">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas/penetrating-lubricant.html" class="level3 ">PENETRATING LUBRICANT</a>
                            </li>
                            <li  class="level3 nav-5-1-4-3">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas/semprotan-otomotif-serbaguna.html" class="level3 ">SEMPROTAN OTOMOTIF SERBAGUNA</a>
                            </li>
                            <li  class="level3 nav-5-1-4-4">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas/cairan-pelumas-belt.html" class="level3 ">CAIRAN PELUMAS BELT</a>
                            </li>
                            <li  class="level3 nav-5-1-4-5 last">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/oli-dan-pelumas/pelumas-rantai.html" class="level3 ">PELUMAS RANTAI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-1-5 parent">
                        <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/eksterior-mobil.html" class="level2 has-children">EKSTERIOR MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/eksterior-mobil.html">View All EKSTERIOR MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-5-1 first">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/eksterior-mobil/perbaikan-cat-mobil.html" class="level3 ">PERBAIKAN CAT MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-5-2">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/eksterior-mobil/undercoating-otomotif.html" class="level3 ">UNDERCOATING OTOMOTIF</a>
                            </li>
                            <li  class="level3 nav-5-1-5-3 last">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/eksterior-mobil/cat-semprot-pembersih.html" class="level3 ">CAT SEMPROT/ PEMBERSIH</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-1-6 last parent">
                        <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil.html" class="level2 has-children">PENGHARUM MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil.html">View All PENGHARUM MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-1-6-1 first">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil/pengharum-mobil-gantung.html" class="level3 ">PENGHARUM MOBIL GANTUNG</a>
                            </li>
                            <li  class="level3 nav-5-1-6-2">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil/pengharum-mobil-klip.html" class="level3 ">PENGHARUM MOBIL KLIP</a>
                            </li>
                            <li  class="level3 nav-5-1-6-3">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil/pengharum-mobil-kaleng.html" class="level3 ">PENGHARUM MOBIL KALENG</a>
                            </li>
                            <li  class="level3 nav-5-1-6-4">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil/pengharum-mobil-semprot.html" class="level3 ">PENGHARUM MOBIL SEMPROT</a>
                            </li>
                            <li  class="level3 nav-5-1-6-5 last">
                                <a href="https://m.ruparupa.com/otomotif/perawatan-mobil-motor/pengharum-mobil/pengharum-mobil-ionizer.html" class="level3 ">PENGHARUM MOBIL IONIZER</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-5-2 parent">
                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor.html" class="level1 has-children">AKSESORIS MOBIL MOTOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor.html">View All AKSESORIS MOBIL MOTOR</a>
                    </li>
                    <li  class="level2 nav-5-2-1 first parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer.html" class="level2 has-children">ORGANIZER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer.html">View All ORGANIZER</a>
                            </li>
                            <li  class="level3 nav-5-2-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer/organizer-belakang-kursi.html" class="level3 ">ORGANIZER BELAKANG KURSI</a>
                            </li>
                            <li  class="level3 nav-5-2-1-2">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer/nampan-mobil.html" class="level3 ">NAMPAN MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-1-3">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer/tempat-sampah.html" class="level3 ">TEMPAT SAMPAH</a>
                            </li>
                            <li  class="level3 nav-5-2-1-4">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer/tempat-koin.html" class="level3 ">TEMPAT KOIN</a>
                            </li>
                            <li  class="level3 nav-5-2-1-5 last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/organizer/tempat-tisu.html" class="level3 ">TEMPAT TISU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-2 parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk.html" class="level2 has-children">JOK DAN ALAS DUDUK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk.html">View All JOK DAN ALAS DUDUK</a>
                            </li>
                            <li  class="level3 nav-5-2-2-1 first">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk/sarung-jok.html" class="level3 ">SARUNG JOK</a>
                            </li>
                            <li  class="level3 nav-5-2-2-2">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk/bantal-duduk.html" class="level3 ">BANTAL DUDUK</a>
                            </li>
                            <li  class="level3 nav-5-2-2-3">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk/bantal-leher.html" class="level3 ">BANTAL LEHER</a>
                            </li>
                            <li  class="level3 nav-5-2-2-4">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk/bantal-punggung.html" class="level3 ">BANTAL PUNGGUNG</a>
                            </li>
                            <li  class="level3 nav-5-2-2-5 last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/jok-dan-alas-duduk/sarung-seat-belt.html" class="level3 ">SARUNG SEAT BELT</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-3 parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/pendingin-dan-penghangat.html" class="level2 has-children">PENDINGIN DAN PENGHANGAT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/pendingin-dan-penghangat.html">View All PENDINGIN DAN PENGHANGAT</a>
                            </li>
                            <li  class="level3 nav-5-2-3-1 first">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/pendingin-dan-penghangat/soft-case.html" class="level3 ">SOFT CASE</a>
                            </li>
                            <li  class="level3 nav-5-2-3-2 last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/pendingin-dan-penghangat/hard-case.html" class="level3 ">HARD CASE</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-4 parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/karpet-mobil.html" class="level2 has-children">KARPET MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/karpet-mobil.html">View All KARPET MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-4-1 first last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/karpet-mobil/pvc.html" class="level3 ">PVC</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-5 parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil.html" class="level2 has-children">AKSESORIS INTERIOR MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil.html">View All AKSESORIS INTERIOR MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-5-1 first">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/aksesoris-anak.html" class="level3 ">AKSESORIS ANAK</a>
                            </li>
                            <li  class="level3 nav-5-2-5-2">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/sun-shade.html" class="level3 ">SUN SHADE</a>
                            </li>
                            <li  class="level3 nav-5-2-5-3">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/jam-mobil.html" class="level3 ">JAM MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-5-4">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/kompas-mobil.html" class="level3 ">KOMPAS MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-5-5">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/holder.html" class="level3 ">HOLDER</a>
                            </li>
                            <li  class="level3 nav-5-2-5-6">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/aksesoris-lighter.html" class="level3 ">AKSESORIS LIGHTER</a>
                            </li>
                            <li  class="level3 nav-5-2-5-7">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/tombol-persneling.html" class="level3 ">TOMBOL PERSNELING</a>
                            </li>
                            <li  class="level3 nav-5-2-5-8">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/aksesoris-pedal.html" class="level3 ">AKSESORIS PEDAL</a>
                            </li>
                            <li  class="level3 nav-5-2-5-9">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/aksesoris-setir.html" class="level3 ">AKSESORIS SETIR</a>
                            </li>
                            <li  class="level3 nav-5-2-5-10 last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-interior-mobil/anti-slip.html" class="level3 ">ANTI SLIP</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-6 parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-eksterior-mobil.html" class="level2 has-children">AKSESORIS EKSTERIOR MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-eksterior-mobil.html">View All AKSESORIS EKSTERIOR MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-6-1 first">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-eksterior-mobil/reflektor.html" class="level3 ">REFLEKTOR</a>
                            </li>
                            <li  class="level3 nav-5-2-6-2 last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/aksesoris-eksterior-mobil/antena-mobil.html" class="level3 ">ANTENA MOBIL</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-7 parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/sarung-mobil.html" class="level2 has-children">SARUNG MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/sarung-mobil.html">View All SARUNG MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-2-7-1 first last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/sarung-mobil/car-cover-prestige.html" class="level3 ">CAR COVER PRESTIGE</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-2-8 last parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/car-carrier.html" class="level2 has-children">CAR CARRIER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/car-carrier.html">View All CAR CARRIER</a>
                            </li>
                            <li  class="level3 nav-5-2-8-1 first last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-mobil-motor/car-carrier/rak-sepeda.html" class="level3 ">RAK SEPEDA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-5-3 parent">
                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif.html" class="level1 has-children">PERALATAN OTOMOTIF</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/peralatan-otomotif.html">View All PERALATAN OTOMOTIF</a>
                    </li>
                    <li  class="level2 nav-5-3-1 first parent">
                        <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/alat-derek.html" class="level2 has-children">ALAT DEREK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/peralatan-otomotif/alat-derek.html">View All ALAT DEREK</a>
                            </li>
                            <li  class="level3 nav-5-3-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/alat-derek/kabel-pengait.html" class="level3 ">KABEL PENGAIT</a>
                            </li>
                            <li  class="level3 nav-5-3-1-2 last">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/alat-derek/kabel-derek.html" class="level3 ">KABEL DEREK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-3-2 last parent">
                        <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/dongkrak.html" class="level2 has-children">DONGKRAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/peralatan-otomotif/dongkrak.html">View All DONGKRAK</a>
                            </li>
                            <li  class="level3 nav-5-3-2-1 first">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/dongkrak/dongkrak-lantai.html" class="level3 ">DONGKRAK LANTAI</a>
                            </li>
                            <li  class="level3 nav-5-3-2-2">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/dongkrak/dongkrak-botol.html" class="level3 ">DONGKRAK BOTOL</a>
                            </li>
                            <li  class="level3 nav-5-3-2-3">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/dongkrak/dongkrak-garasi.html" class="level3 ">DONGKRAK GARASI</a>
                            </li>
                            <li  class="level3 nav-5-3-2-4 last">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-otomotif/dongkrak/dongkrak-berdiri.html" class="level3 ">DONGKRAK BERDIRI</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-5-4 parent">
                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor.html" class="level1 has-children">PERALATAN KENDARAAN BERMOTOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor.html">View All PERALATAN KENDARAAN BERMOTOR</a>
                    </li>
                    <li  class="level2 nav-5-4-1 first parent">
                        <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/peralatan-otomotif.html" class="level2 has-children">PERALATAN OTOMOTIF</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/peralatan-otomotif.html">View All PERALATAN OTOMOTIF</a>
                            </li>
                            <li  class="level3 nav-5-4-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/peralatan-otomotif/peralatan-aki.html" class="level3 ">PERALATAN AKI</a>
                            </li>
                            <li  class="level3 nav-5-4-1-2">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/peralatan-otomotif/pistol-dan-pompa-gemuk.html" class="level3 ">PISTOL DAN POMPA GEMUK</a>
                            </li>
                            <li  class="level3 nav-5-4-1-3 last">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/peralatan-otomotif/corong-syphons-pan.html" class="level3 ">CORONG SYPHONS PAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-4-2 last parent">
                        <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/p3k.html" class="level2 has-children">P3K</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/p3k.html">View All P3K</a>
                            </li>
                            <li  class="level3 nav-5-4-2-1 first">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/p3k/segitiga-keselamatan.html" class="level3 ">SEGITIGA KESELAMATAN</a>
                            </li>
                            <li  class="level3 nav-5-4-2-2">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/p3k/safety-parking.html" class="level3 ">SAFETY PARKING</a>
                            </li>
                            <li  class="level3 nav-5-4-2-3">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/p3k/kunci.html" class="level3 ">KUNCI</a>
                            </li>
                            <li  class="level3 nav-5-4-2-4 last">
                                <a href="https://m.ruparupa.com/otomotif/peralatan-kendaraan-bermotor/p3k/kunci-dengan-alarm.html" class="level3 ">KUNCI DENGAN ALARM</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-5-5 parent">
                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti.html" class="level1 has-children">SUKU CADANG PENGGANTI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti.html">View All SUKU CADANG PENGGANTI</a>
                    </li>
                    <li  class="level2 nav-5-5-1 first parent">
                        <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/aki-dan-aksesoris.html" class="level2 has-children">AKI DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/aki-dan-aksesoris.html">View All AKI DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-5-5-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/aki-dan-aksesoris/aki-mobil.html" class="level3 ">AKI MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-5-1-2">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/aki-dan-aksesoris/charger-aki.html" class="level3 ">CHARGER AKI</a>
                            </li>
                            <li  class="level3 nav-5-5-1-3">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/aki-dan-aksesoris/kabel-aki.html" class="level3 ">KABEL AKI</a>
                            </li>
                            <li  class="level3 nav-5-5-1-4 last">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/aki-dan-aksesoris/aki-hdwr.html" class="level3 ">AKI HDWR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-5-2 parent">
                        <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/wiper-dan-bumper.html" class="level2 has-children">WIPER DAN BUMPER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/wiper-dan-bumper.html">View All WIPER DAN BUMPER</a>
                            </li>
                            <li  class="level3 nav-5-5-2-1 first">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/wiper-dan-bumper/wiper.html" class="level3 ">WIPER</a>
                            </li>
                            <li  class="level3 nav-5-5-2-2">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/wiper-dan-bumper/pelindung-bumper.html" class="level3 ">PELINDUNG BUMPER</a>
                            </li>
                            <li  class="level3 nav-5-5-2-3">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/wiper-dan-bumper/pelindung-pintu.html" class="level3 ">PELINDUNG PINTU</a>
                            </li>
                            <li  class="level3 nav-5-5-2-4 last">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/wiper-dan-bumper/kaca-pelindung.html" class="level3 ">KACA PELINDUNG</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-5-3 parent">
                        <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/roda-mobil.html" class="level2 has-children">RODA MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/roda-mobil.html">View All RODA MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-5-3-1 first last">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/roda-mobil/katup-ban.html" class="level3 ">KATUP BAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-5-4 last parent">
                        <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/ban-mobil.html" class="level2 has-children">BAN MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/ban-mobil.html">View All BAN MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-5-4-1 first">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/ban-mobil/pompa-ban.html" class="level3 ">POMPA BAN</a>
                            </li>
                            <li  class="level3 nav-5-5-4-2">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/ban-mobil/pengukur-tekanan-ban.html" class="level3 ">PENGUKUR TEKANAN BAN</a>
                            </li>
                            <li  class="level3 nav-5-5-4-3 last">
                                <a href="https://m.ruparupa.com/otomotif/suku-cadang-pengganti/ban-mobil/perbaikan-siealant-ban.html" class="level3 ">PERBAIKAN SIEALANT BAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-5-6 parent">
                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil.html" class="level1 has-children">MODIFIKASI MOBIL</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/modifikasi-mobil.html">View All MODIFIKASI MOBIL</a>
                    </li>
                    <li  class="level2 nav-5-6-1 first parent">
                        <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/lampu-mobil.html" class="level2 has-children">LAMPU MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/modifikasi-mobil/lampu-mobil.html">View All LAMPU MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-6-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/lampu-mobil/lampu-baca.html" class="level3 ">LAMPU BACA</a>
                            </li>
                            <li  class="level3 nav-5-6-1-2">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/lampu-mobil/head-lamp.html" class="level3 ">HEAD LAMP</a>
                            </li>
                            <li  class="level3 nav-5-6-1-3 last">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/lampu-mobil/lampu-strobo.html" class="level3 ">LAMPU STROBO</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-5-6-2 last parent">
                        <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/kaca-mobil.html" class="level2 has-children">KACA MOBIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/modifikasi-mobil/kaca-mobil.html">View All KACA MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-6-2-1 first">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/kaca-mobil/kaca-rias-mobil.html" class="level3 ">KACA RIAS MOBIL</a>
                            </li>
                            <li  class="level3 nav-5-6-2-2">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/kaca-mobil/cermin-ruangan.html" class="level3 ">CERMIN RUANGAN</a>
                            </li>
                            <li  class="level3 nav-5-6-2-3">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/kaca-mobil/cermin-blind-spot.html" class="level3 ">CERMIN BLIND SPOT</a>
                            </li>
                            <li  class="level3 nav-5-6-2-4 last">
                                <a href="https://m.ruparupa.com/otomotif/modifikasi-mobil/kaca-mobil/cermin-sisi-belakang.html" class="level3 ">CERMIN SISI/ BELAKANG</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-5-7 last parent">
                <a href="https://m.ruparupa.com/otomotif/aksesoris-motor.html" class="level1 has-children">AKSESORIS MOTOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/otomotif/aksesoris-motor.html">View All AKSESORIS MOTOR</a>
                    </li>
                    <li  class="level2 nav-5-7-1 first last parent">
                        <a href="https://m.ruparupa.com/otomotif/aksesoris-motor/aksesoris-pengendara-motor.html" class="level2 has-children">AKSESORIS PENGENDARA MOTOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/otomotif/aksesoris-motor/aksesoris-pengendara-motor.html">View All AKSESORIS PENGENDARA MOTOR</a>
                            </li>
                            <li  class="level3 nav-5-7-1-1 first">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-motor/aksesoris-pengendara-motor/jaket-rompi-jas-hujan.html" class="level3 ">JAKET/ ROMPI/ JAS HUJAN</a>
                            </li>
                            <li  class="level3 nav-5-7-1-2">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-motor/aksesoris-pengendara-motor/sepeda-motor-skuter.html" class="level3 ">SEPEDA MOTOR/ SKUTER</a>
                            </li>
                            <li  class="level3 nav-5-7-1-3 last">
                                <a href="https://m.ruparupa.com/otomotif/aksesoris-motor/aksesoris-pengendara-motor/sarung-tangan-motor.html" class="level3 ">SARUNG TANGAN MOTOR</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-6 parent">
        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup.html" class="level0 has-children">Hobi &amp; Gaya Hidup</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/hobi-dan-gaya-hidup.html">View All Hobi &amp; Gaya Hidup</a>
            </li>
            <li  class="level1 nav-6-1 first parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik.html" class="level1 has-children">MUSIK</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik.html">View All MUSIK</a>
                    </li>
                    <li  class="level2 nav-6-1-1 first parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/gitar-dan-biola.html" class="level2 has-children">GITAR DAN BIOLA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/gitar-dan-biola.html">View All GITAR DAN BIOLA</a>
                            </li>
                            <li  class="level3 nav-6-1-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/gitar-dan-biola/gitar-klasik.html" class="level3 ">GITAR KLASIK</a>
                            </li>
                            <li  class="level3 nav-6-1-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/gitar-dan-biola/gitar-akustik.html" class="level3 ">GITAR AKUSTIK</a>
                            </li>
                            <li  class="level3 nav-6-1-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/gitar-dan-biola/gitar-elektrik-dan-bass.html" class="level3 ">GITAR ELEKTRIK DAN BASS</a>
                            </li>
                            <li  class="level3 nav-6-1-1-4 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/gitar-dan-biola/biola-dan-biola-elektrik.html" class="level3 ">BIOLA DAN BIOLA ELEKTRIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-1-2 parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/piano-dan-keyboard.html" class="level2 has-children">PIANO DAN KEYBOARD</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/piano-dan-keyboard.html">View All PIANO DAN KEYBOARD</a>
                            </li>
                            <li  class="level3 nav-6-1-2-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/piano-dan-keyboard/keyboard.html" class="level3 ">KEYBOARD</a>
                            </li>
                            <li  class="level3 nav-6-1-2-2 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/piano-dan-keyboard/piano.html" class="level3 ">PIANO</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-1-3 last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/instrumen-dan-aksesoris.html" class="level2 has-children">INSTRUMEN DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/instrumen-dan-aksesoris.html">View All INSTRUMEN DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-6-1-3-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/instrumen-dan-aksesoris/perkusi.html" class="level3 ">PERKUSI</a>
                            </li>
                            <li  class="level3 nav-6-1-3-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/instrumen-dan-aksesoris/musik-instrumen-lainnya.html" class="level3 ">MUSIK INSTRUMEN LAINNYA</a>
                            </li>
                            <li  class="level3 nav-6-1-3-3 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/musik/instrumen-dan-aksesoris/aksesoris-instrumen-musik.html" class="level3 ">AKSESORIS INSTRUMEN MUSIK</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-2 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata.html" class="level1 has-children">WISATA</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata.html">View All WISATA</a>
                    </li>
                    <li  class="level2 nav-6-2-1 first parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel.html" class="level2 has-children">TAS TRAVEL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel.html">View All TAS TRAVEL</a>
                            </li>
                            <li  class="level3 nav-6-2-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel/tas-tangan-kecil.html" class="level3 ">TAS TANGAN KECIL</a>
                            </li>
                            <li  class="level3 nav-6-2-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel/tas-bahu.html" class="level3 ">TAS BAHU</a>
                            </li>
                            <li  class="level3 nav-6-2-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel/tas-ransel.html" class="level3 ">TAS RANSEL</a>
                            </li>
                            <li  class="level3 nav-6-2-1-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel/tas-laptop.html" class="level3 ">TAS LAPTOP</a>
                            </li>
                            <li  class="level3 nav-6-2-1-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel/tas-kerja.html" class="level3 ">TAS KERJA</a>
                            </li>
                            <li  class="level3 nav-6-2-1-6 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/tas-travel/troli-belanja.html" class="level3 ">TROLI BELANJA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-2-2 parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper.html" class="level2 has-children">KOPER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper.html">View All KOPER</a>
                            </li>
                            <li  class="level3 nav-6-2-2-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper/tas-troli.html" class="level3 ">TAS TROLI</a>
                            </li>
                            <li  class="level3 nav-6-2-2-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper/tas-travel.html" class="level3 ">TAS TRAVEL</a>
                            </li>
                            <li  class="level3 nav-6-2-2-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper/tas-kerja-beroda.html" class="level3 ">TAS KERJA BERODA</a>
                            </li>
                            <li  class="level3 nav-6-2-2-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper/troli-koper.html" class="level3 ">TROLI KOPER</a>
                            </li>
                            <li  class="level3 nav-6-2-2-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper/sarung-koper.html" class="level3 ">SARUNG KOPER</a>
                            </li>
                            <li  class="level3 nav-6-2-2-6 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/koper/timbangan-koper.html" class="level3 ">TIMBANGAN KOPER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-2-3 parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata.html" class="level2 has-children">AKSESORIS WISATA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata.html">View All AKSESORIS WISATA</a>
                            </li>
                            <li  class="level3 nav-6-2-3-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/tas-kosmetik.html" class="level3 ">TAS KOSMETIK</a>
                            </li>
                            <li  class="level3 nav-6-2-3-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/dompet-travel.html" class="level3 ">DOMPET TRAVEL</a>
                            </li>
                            <li  class="level3 nav-6-2-3-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/tas-toiletries.html" class="level3 ">TAS TOILETRIES</a>
                            </li>
                            <li  class="level3 nav-6-2-3-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/travel-organizer.html" class="level3 ">TRAVEL ORGANIZER</a>
                            </li>
                            <li  class="level3 nav-6-2-3-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/dompet-passport.html" class="level3 ">DOMPET PASSPORT</a>
                            </li>
                            <li  class="level3 nav-6-2-3-6">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/dompet-kartu.html" class="level3 ">DOMPET KARTU</a>
                            </li>
                            <li  class="level3 nav-6-2-3-7">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/pouch-travel.html" class="level3 ">POUCH TRAVEL</a>
                            </li>
                            <li  class="level3 nav-6-2-3-8">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/gembok-koper.html" class="level3 ">GEMBOK KOPER</a>
                            </li>
                            <li  class="level3 nav-6-2-3-9">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/luggage-tag.html" class="level3 ">LUGGAGE TAG</a>
                            </li>
                            <li  class="level3 nav-6-2-3-10">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/penutup-mata-penutup-telinga.html" class="level3 ">PENUTUP MATA PENUTUP TELINGA</a>
                            </li>
                            <li  class="level3 nav-6-2-3-11">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/bantal-travel.html" class="level3 ">BANTAL TRAVEL</a>
                            </li>
                            <li  class="level3 nav-6-2-3-12 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/aksesoris-wisata/magnetic-holder.html" class="level3 ">MAGNETIC HOLDER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-2-4 last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/payung.html" class="level2 has-children">PAYUNG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/payung.html">View All PAYUNG</a>
                            </li>
                            <li  class="level3 nav-6-2-4-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/payung/payung-lipat.html" class="level3 ">PAYUNG LIPAT</a>
                            </li>
                            <li  class="level3 nav-6-2-4-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/payung/payung-regular.html" class="level3 ">PAYUNG REGULAR</a>
                            </li>
                            <li  class="level3 nav-6-2-4-3 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/wisata/payung/payung-golf.html" class="level3 ">PAYUNG GOLF</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-3 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq.html" class="level1 has-children">PERLENGKAPAN BBQ</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq.html">View All PERLENGKAPAN BBQ</a>
                    </li>
                    <li  class="level2 nav-6-3-1 first parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang.html" class="level2 has-children">ALAT PEMANGGANG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang.html">View All ALAT PEMANGGANG</a>
                            </li>
                            <li  class="level3 nav-6-3-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang/pemanggang-gas.html" class="level3 ">PEMANGGANG GAS</a>
                            </li>
                            <li  class="level3 nav-6-3-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang/pemanggang-arang.html" class="level3 ">PEMANGGANG ARANG</a>
                            </li>
                            <li  class="level3 nav-6-3-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang/pemanggang-elektrik.html" class="level3 ">PEMANGGANG ELEKTRIK</a>
                            </li>
                            <li  class="level3 nav-6-3-1-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang/smoker.html" class="level3 ">SMOKER</a>
                            </li>
                            <li  class="level3 nav-6-3-1-5 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/alat-pemanggang/aksesoris-smoker.html" class="level3 ">AKSESORIS SMOKER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-3-2 parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq.html" class="level2 has-children">PERLENGKAPAN DAN AKSESORIS BBQ</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq.html">View All PERLENGKAPAN DAN AKSESORIS BBQ</a>
                            </li>
                            <li  class="level3 nav-6-3-2-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq/set-peralatan-bbq.html" class="level3 ">SET PERALATAN BBQ</a>
                            </li>
                            <li  class="level3 nav-6-3-2-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq/peralatan-bbq.html" class="level3 ">PERALATAN BBQ</a>
                            </li>
                            <li  class="level3 nav-6-3-2-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq/pembersih-peralatan-bbq.html" class="level3 ">PEMBERSIH PERALATAN BBQ</a>
                            </li>
                            <li  class="level3 nav-6-3-2-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq/part-pemanggang-arang.html" class="level3 ">PART PEMANGGANG ARANG</a>
                            </li>
                            <li  class="level3 nav-6-3-2-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq/part-pemanggang-gas.html" class="level3 ">PART PEMANGGANG GAS</a>
                            </li>
                            <li  class="level3 nav-6-3-2-6 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/perlengkapan-dan-aksesoris-bbq/sarung-panggangan.html" class="level3 ">SARUNG PANGGANGAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-3-3 last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq.html" class="level2 has-children">PERSIAPAN BBQ</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq.html">View All PERSIAPAN BBQ</a>
                            </li>
                            <li  class="level3 nav-6-3-3-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq/briket-arang-batu.html" class="level3 ">BRIKET ARANG BATU</a>
                            </li>
                            <li  class="level3 nav-6-3-3-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq/briketbatu-panggangan-gas.html" class="level3 ">BRIKET/BATU (PANGGANGAN GAS)</a>
                            </li>
                            <li  class="level3 nav-6-3-3-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq/cairan-pembuat-api.html" class="level3 ">CAIRAN PEMBUAT API</a>
                            </li>
                            <li  class="level3 nav-6-3-3-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq/cerobong-pembuat-bara.html" class="level3 ">CEROBONG PEMBUAT BARA</a>
                            </li>
                            <li  class="level3 nav-6-3-3-5 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perlengkapan-bbq/persiapan-bbq/kayu-beraroma.html" class="level3 ">KAYU BERAROMA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-4 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan.html" class="level1 has-children">KEBUTUHAN BINATANG PELIHARAAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan.html">View All KEBUTUHAN BINATANG PELIHARAAN</a>
                    </li>
                    <li  class="level2 nav-6-4-1 first parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing.html" class="level2 has-children">KEBUTUHAN ANJING</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing.html">View All KEBUTUHAN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/makanan-anjing.html" class="level3 ">MAKANAN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/suplemen-anjing.html" class="level3 ">SUPLEMEN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/peralatan-makan-anjing.html" class="level3 ">PERALATAN MAKAN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/pakaian-anjing.html" class="level3 ">PAKAIAN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/mainan-anjing.html" class="level3 ">MAINAN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-6">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/kandang-anjing.html" class="level3 ">KANDANG ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-7">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/tas-pembawa.html" class="level3 ">TAS PEMBAWA </a>
                            </li>
                            <li  class="level3 nav-6-4-1-8">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/tempat-kotoran-anjing.html" class="level3 ">TEMPAT KOTORAN ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-9">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/kalung-anjing.html" class="level3 ">KALUNG ANJING</a>
                            </li>
                            <li  class="level3 nav-6-4-1-10 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-anjing/aksesoris-anjing.html" class="level3 ">AKSESORIS ANJING</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-4-2 last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-hewan-kecil.html" class="level2 has-children">KEBUTUHAN HEWAN KECIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-hewan-kecil.html">View All KEBUTUHAN HEWAN KECIL</a>
                            </li>
                            <li  class="level3 nav-6-4-2-1 first last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kebutuhan-binatang-peliharaan/kebutuhan-hewan-kecil/makanan.html" class="level3 ">MAKANAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-5 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan.html" class="level1 has-children">PERAWATAN HEWAN PELIHARAAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan.html">View All PERAWATAN HEWAN PELIHARAAN</a>
                    </li>
                    <li  class="level2 nav-6-5-1 first parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/pet-grooming.html" class="level2 has-children">PET GROOMING</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/pet-grooming.html">View All PET GROOMING</a>
                            </li>
                            <li  class="level3 nav-6-5-1-1 first last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/pet-grooming/shampoo-hewan.html" class="level3 ">SHAMPOO HEWAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-5-2 last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/kesehatan.html" class="level2 has-children">KESEHATAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/kesehatan.html">View All KESEHATAN</a>
                            </li>
                            <li  class="level3 nav-6-5-2-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/kesehatan/penghilang-kutu.html" class="level3 ">PENGHILANG KUTU</a>
                            </li>
                            <li  class="level3 nav-6-5-2-2 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/perawatan-hewan-peliharaan/kesehatan/penghilang-bau.html" class="level3 ">PENGHILANG BAU</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-6 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium.html" class="level1 has-children">AKUARIUM</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium.html">View All AKUARIUM</a>
                    </li>
                    <li  class="level2 nav-6-6-1 first last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium/akuarium-dan-aksesoris.html" class="level2 has-children">AKUARIUM DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium/akuarium-dan-aksesoris.html">View All AKUARIUM DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-6-6-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium/akuarium-dan-aksesoris/akuarium.html" class="level3 ">AKUARIUM</a>
                            </li>
                            <li  class="level3 nav-6-6-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium/akuarium-dan-aksesoris/pompa-dan-filter-akuarium.html" class="level3 ">POMPA DAN FILTER AKUARIUM</a>
                            </li>
                            <li  class="level3 nav-6-6-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium/akuarium-dan-aksesoris/pembersih-akuarium.html" class="level3 ">PEMBERSIH AKUARIUM</a>
                            </li>
                            <li  class="level3 nav-6-6-1-4 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/akuarium/akuarium-dan-aksesoris/lampu-akuarium.html" class="level3 ">LAMPU AKUARIUM</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-7 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman.html" class="level1 has-children">TAMAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman.html">View All TAMAN</a>
                    </li>
                    <li  class="level2 nav-6-7-1 first parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/tanaman-dan-rumput.html" class="level2 has-children">TANAMAN DAN RUMPUT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/tanaman-dan-rumput.html">View All TANAMAN DAN RUMPUT</a>
                            </li>
                            <li  class="level3 nav-6-7-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/tanaman-dan-rumput/buatan.html" class="level3 ">BUATAN</a>
                            </li>
                            <li  class="level3 nav-6-7-1-2 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/tanaman-dan-rumput/bibit.html" class="level3 ">BIBIT</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-7-2 parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/aksesoris-taman.html" class="level2 has-children">AKSESORIS TAMAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/aksesoris-taman.html">View All AKSESORIS TAMAN</a>
                            </li>
                            <li  class="level3 nav-6-7-2-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/aksesoris-taman/pot-dan-aksesoris.html" class="level3 ">POT DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-6-7-2-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/aksesoris-taman/pagar-dan-teralis.html" class="level3 ">PAGAR DAN TERALIS</a>
                            </li>
                            <li  class="level3 nav-6-7-2-3 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/aksesoris-taman/ornamen.html" class="level3 ">ORNAMEN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-7-3 parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/peralatan-dan-kebutuhan-taman.html" class="level2 has-children">PERALATAN DAN KEBUTUHAN TAMAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/peralatan-dan-kebutuhan-taman.html">View All PERALATAN DAN KEBUTUHAN TAMAN</a>
                            </li>
                            <li  class="level3 nav-6-7-3-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/peralatan-dan-kebutuhan-taman/peralatan-ringan.html" class="level3 ">PERALATAN RINGAN</a>
                            </li>
                            <li  class="level3 nav-6-7-3-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/peralatan-dan-kebutuhan-taman/peralatan-tangan-kecil.html" class="level3 ">PERALATAN TANGAN KECIL</a>
                            </li>
                            <li  class="level3 nav-6-7-3-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/peralatan-dan-kebutuhan-taman/troli-barang.html" class="level3 ">TROLI BARANG</a>
                            </li>
                            <li  class="level3 nav-6-7-3-4 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/peralatan-dan-kebutuhan-taman/trimmer-edger-blower.html" class="level3 ">TRIMMER EDGER BLOWER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-6-7-4 last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/selang-dan-aksesoris.html" class="level2 has-children">SELANG DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/selang-dan-aksesoris.html">View All SELANG DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-6-7-4-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/selang-dan-aksesoris/selang.html" class="level3 ">SELANG</a>
                            </li>
                            <li  class="level3 nav-6-7-4-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/selang-dan-aksesoris/penggantung-selang.html" class="level3 ">PENGGANTUNG SELANG</a>
                            </li>
                            <li  class="level3 nav-6-7-4-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/selang-dan-aksesoris/keran.html" class="level3 ">KERAN</a>
                            </li>
                            <li  class="level3 nav-6-7-4-4 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/taman/selang-dan-aksesoris/semprotan-taman.html" class="level3 ">SEMPROTAN TAMAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-8 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/fashion.html" class="level1 has-children">FASHION</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/fashion.html">View All FASHION</a>
                    </li>
                    <li  class="level2 nav-6-8-1 first last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/fashion/tas-fashion.html" class="level2 has-children">TAS FASHION</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/fashion/tas-fashion.html">View All TAS FASHION</a>
                            </li>
                            <li  class="level3 nav-6-8-1-1 first last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/fashion/tas-fashion/tas-duffel.html" class="level3 ">TAS DUFFEL</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-9 parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu.html" class="level1 has-children">SEPATU</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu.html">View All SEPATU</a>
                    </li>
                    <li  class="level2 nav-6-9-1 first last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria.html" class="level2 has-children">SEPATU PRIA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria.html">View All SEPATU PRIA</a>
                            </li>
                            <li  class="level3 nav-6-9-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria/pantofel-pria.html" class="level3 ">PANTOFEL PRIA</a>
                            </li>
                            <li  class="level3 nav-6-9-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria/boot-pria.html" class="level3 ">BOOT PRIA</a>
                            </li>
                            <li  class="level3 nav-6-9-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria/sepatu-santai-pria.html" class="level3 ">SEPATU SANTAI PRIA</a>
                            </li>
                            <li  class="level3 nav-6-9-1-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria/sepatu-olahraga.html" class="level3 ">SEPATU OLAHRAGA</a>
                            </li>
                            <li  class="level3 nav-6-9-1-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria/safety-shoes.html" class="level3 ">SAFETY SHOES</a>
                            </li>
                            <li  class="level3 nav-6-9-1-6 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/sepatu/sepatu-pria/sandal.html" class="level3 ">SANDAL</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-6-10 last parent">
                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan.html" class="level1 has-children">KECANTIKAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan.html">View All KECANTIKAN</a>
                    </li>
                    <li  class="level2 nav-6-10-1 first last parent">
                        <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik.html" class="level2 has-children">KOSMETIK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik.html">View All KOSMETIK</a>
                            </li>
                            <li  class="level3 nav-6-10-1-1 first">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik/bibir.html" class="level3 ">BIBIR</a>
                            </li>
                            <li  class="level3 nav-6-10-1-2">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik/kuku.html" class="level3 ">KUKU</a>
                            </li>
                            <li  class="level3 nav-6-10-1-3">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik/mata.html" class="level3 ">MATA</a>
                            </li>
                            <li  class="level3 nav-6-10-1-4">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik/wajah.html" class="level3 ">WAJAH</a>
                            </li>
                            <li  class="level3 nav-6-10-1-5">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik/kuas-dan-peralatan.html" class="level3 ">KUAS DAN PERALATAN</a>
                            </li>
                            <li  class="level3 nav-6-10-1-6 last">
                                <a href="https://m.ruparupa.com/hobi-dan-gaya-hidup/kecantikan/kosmetik/kotak-kosmetik.html" class="level3 ">KOTAK KOSMETIK</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-7 parent">
        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga.html" class="level0 has-children">Kesehatan &amp; Olahraga</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/kesehatan-dan-olahraga.html">View All Kesehatan &amp; Olahraga</a>
            </li>
            <li  class="level1 nav-7-1 first parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda.html" class="level1 has-children">SEPEDA</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda.html">View All SEPEDA</a>
                    </li>
                    <li  class="level2 nav-7-1-1 first parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/sepeda-anak.html" class="level2 has-children">SEPEDA ANAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/sepeda-anak.html">View All SEPEDA ANAK</a>
                            </li>
                            <li  class="level3 nav-7-1-1-1 first last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/sepeda-anak/kids-bicycles.html" class="level3 ">KIDS BICYCLES</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-1-2 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris.html" class="level2 has-children">SUKU CADANG DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris.html">View All SUKU CADANG DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-7-1-2-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris/peralatan-sepeda.html" class="level3 ">PERALATAN SEPEDA</a>
                            </li>
                            <li  class="level3 nav-7-1-2-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris/aksesoris-sepeda.html" class="level3 ">AKSESORIS SEPEDA</a>
                            </li>
                            <li  class="level3 nav-7-1-2-3">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris/peralatan-dan-aksesoris-bmx.html" class="level3 ">PERALATAN DAN AKSESORIS BMX</a>
                            </li>
                            <li  class="level3 nav-7-1-2-4">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris/tas-angkut-dewasa-dan-anak.html" class="level3 ">TAS ANGKUT DEWASA DAN ANAK</a>
                            </li>
                            <li  class="level3 nav-7-1-2-5">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris/kunci-gembok-sepeda.html" class="level3 ">KUNCI GEMBOK SEPEDA</a>
                            </li>
                            <li  class="level3 nav-7-1-2-6 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/suku-cadang-dan-aksesoris/suku-cadang-khusus.html" class="level3 ">SUKU CADANG KHUSUS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-1-3 last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/perlengkapan-bersepeda.html" class="level2 has-children">PERLENGKAPAN BERSEPEDA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/perlengkapan-bersepeda.html">View All PERLENGKAPAN BERSEPEDA</a>
                            </li>
                            <li  class="level3 nav-7-1-3-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/perlengkapan-bersepeda/jersey-sepeda.html" class="level3 ">JERSEY SEPEDA</a>
                            </li>
                            <li  class="level3 nav-7-1-3-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/perlengkapan-bersepeda/helm-sepeda.html" class="level3 ">HELM SEPEDA</a>
                            </li>
                            <li  class="level3 nav-7-1-3-3 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/sepeda/perlengkapan-bersepeda/celana-sepeda.html" class="level3 ">CELANA SEPEDA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-7-2 parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes.html" class="level1 has-children">GYM DAN FITNES</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes.html">View All GYM DAN FITNES</a>
                    </li>
                    <li  class="level2 nav-7-2-1 first parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/perlengkapan-gym.html" class="level2 has-children">PERLENGKAPAN GYM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/perlengkapan-gym.html">View All PERLENGKAPAN GYM</a>
                            </li>
                            <li  class="level3 nav-7-2-1-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/perlengkapan-gym/peralatan-yoga-pilates.html" class="level3 ">PERALATAN YOGA/ PILATES</a>
                            </li>
                            <li  class="level3 nav-7-2-1-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/perlengkapan-gym/peralatan-tinju.html" class="level3 ">PERALATAN TINJU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-2-2 last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/peralatan-gym.html" class="level2 has-children">PERALATAN GYM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/peralatan-gym.html">View All PERALATAN GYM</a>
                            </li>
                            <li  class="level3 nav-7-2-2-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/peralatan-gym/sarung-tangan-gym.html" class="level3 ">SARUNG TANGAN GYM</a>
                            </li>
                            <li  class="level3 nav-7-2-2-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/peralatan-gym/aksesoris-fitnes.html" class="level3 ">AKSESORIS FITNES</a>
                            </li>
                            <li  class="level3 nav-7-2-2-3 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/gym-dan-fitnes/peralatan-gym/pakaian-gym.html" class="level3 ">PAKAIAN GYM</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-7-3 parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air.html" class="level1 has-children">OLAHRAGA AIR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air.html">View All OLAHRAGA AIR</a>
                    </li>
                    <li  class="level2 nav-7-3-1 first parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/pakaian.html" class="level2 has-children">PAKAIAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/pakaian.html">View All PAKAIAN</a>
                            </li>
                            <li  class="level3 nav-7-3-1-1 first last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/pakaian/rompi-keselamatan.html" class="level3 ">ROMPI KESELAMATAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-3-2 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/peralatan-bawah-air.html" class="level2 has-children">PERALATAN BAWAH AIR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/peralatan-bawah-air.html">View All PERALATAN BAWAH AIR</a>
                            </li>
                            <li  class="level3 nav-7-3-2-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/peralatan-bawah-air/kacamata-renang-dan-penutup-kepala.html" class="level3 ">KACAMATA RENANG DAN PENUTUP KEPALA</a>
                            </li>
                            <li  class="level3 nav-7-3-2-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/peralatan-bawah-air/tutup-kuping.html" class="level3 ">TUTUP KUPING</a>
                            </li>
                            <li  class="level3 nav-7-3-2-3 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/peralatan-bawah-air/set-alat-snorkle.html" class="level3 ">SET ALAT SNORKLE</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-3-3 last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/aksesoris-lainnya.html" class="level2 has-children">AKSESORIS LAINNYA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/aksesoris-lainnya.html">View All AKSESORIS LAINNYA</a>
                            </li>
                            <li  class="level3 nav-7-3-3-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/aksesoris-lainnya/ban-renang.html" class="level3 ">BAN RENANG</a>
                            </li>
                            <li  class="level3 nav-7-3-3-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/olahraga-air/aksesoris-lainnya/kolam-renang.html" class="level3 ">KOLAM RENANG</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-7-4 parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga.html" class="level1 has-children">PERALATAN OLAHRAGA</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga.html">View All PERALATAN OLAHRAGA</a>
                    </li>
                    <li  class="level2 nav-7-4-1 first parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola.html" class="level2 has-children">BOLA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola.html">View All BOLA</a>
                            </li>
                            <li  class="level3 nav-7-4-1-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola/sepakbola.html" class="level3 ">SEPAKBOLA</a>
                            </li>
                            <li  class="level3 nav-7-4-1-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola/bola-basket.html" class="level3 ">BOLA BASKET</a>
                            </li>
                            <li  class="level3 nav-7-4-1-3">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola/bola-soccer.html" class="level3 ">BOLA SOCCER</a>
                            </li>
                            <li  class="level3 nav-7-4-1-4">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola/bola-voli.html" class="level3 ">BOLA VOLI</a>
                            </li>
                            <li  class="level3 nav-7-4-1-5 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/bola/bola-tenis.html" class="level3 ">BOLA TENIS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-4-2 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/latihan-ketangkasan.html" class="level2 has-children">LATIHAN KETANGKASAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/latihan-ketangkasan.html">View All LATIHAN KETANGKASAN</a>
                            </li>
                            <li  class="level3 nav-7-4-2-1 first last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/latihan-ketangkasan/cincin-ketangkasan.html" class="level3 ">CINCIN KETANGKASAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-4-3 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/raket.html" class="level2 has-children">RAKET</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/raket.html">View All RAKET</a>
                            </li>
                            <li  class="level3 nav-7-4-3-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/raket/raket-bulu-tangkis.html" class="level3 ">RAKET BULU TANGKIS</a>
                            </li>
                            <li  class="level3 nav-7-4-3-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/raket/raket-tenis.html" class="level3 ">RAKET TENIS</a>
                            </li>
                            <li  class="level3 nav-7-4-3-3 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/raket/bet-tenis-meja.html" class="level3 ">BET TENIS MEJA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-4-4 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-permainan.html" class="level2 has-children">OLAHRAGA PERMAINAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-permainan.html">View All OLAHRAGA PERMAINAN</a>
                            </li>
                            <li  class="level3 nav-7-4-4-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-permainan/olahraga-aksi.html" class="level3 ">OLAHRAGA AKSI</a>
                            </li>
                            <li  class="level3 nav-7-4-4-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-permainan/dart.html" class="level3 ">DART</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-4-5 last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-beroda.html" class="level2 has-children">OLAHRAGA BERODA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-beroda.html">View All OLAHRAGA BERODA</a>
                            </li>
                            <li  class="level3 nav-7-4-5-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-beroda/skuter-pedal-kaki.html" class="level3 ">SKUTER PEDAL KAKI</a>
                            </li>
                            <li  class="level3 nav-7-4-5-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-beroda/inline-skate.html" class="level3 ">INLINE SKATE</a>
                            </li>
                            <li  class="level3 nav-7-4-5-3">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-beroda/sepatu-roda-anak.html" class="level3 ">SEPATU RODA ANAK</a>
                            </li>
                            <li  class="level3 nav-7-4-5-4 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-olahraga/olahraga-beroda/skateboard.html" class="level3 ">SKATEBOARD</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-7-5 parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor.html" class="level1 has-children">PERALATAN OUTDOOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor.html">View All PERALATAN OUTDOOR</a>
                    </li>
                    <li  class="level2 nav-7-5-1 first parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/teropong-dan-lensa-pembidik.html" class="level2 has-children">TEROPONG DAN LENSA PEMBIDIK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/teropong-dan-lensa-pembidik.html">View All TEROPONG DAN LENSA PEMBIDIK</a>
                            </li>
                            <li  class="level3 nav-7-5-1-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/teropong-dan-lensa-pembidik/teropong-binokuler.html" class="level3 ">TEROPONG BINOKULER</a>
                            </li>
                            <li  class="level3 nav-7-5-1-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/teropong-dan-lensa-pembidik/teropong-monokuler-teleskop.html" class="level3 ">TEROPONG MONOKULER/ TELESKOP</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-5-2 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/perlengkapan-kemah.html" class="level2 has-children">PERLENGKAPAN KEMAH</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/perlengkapan-kemah.html">View All PERLENGKAPAN KEMAH</a>
                            </li>
                            <li  class="level3 nav-7-5-2-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/perlengkapan-kemah/bag-pack.html" class="level3 ">BAG PACK</a>
                            </li>
                            <li  class="level3 nav-7-5-2-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/perlengkapan-kemah/pisau-saku.html" class="level3 ">PISAU SAKU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-5-3 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/tenda-dan-kantong-tidur.html" class="level2 has-children">TENDA DAN KANTONG TIDUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/tenda-dan-kantong-tidur.html">View All TENDA DAN KANTONG TIDUR</a>
                            </li>
                            <li  class="level3 nav-7-5-3-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/tenda-dan-kantong-tidur/sleeping-bag.html" class="level3 ">SLEEPING BAG</a>
                            </li>
                            <li  class="level3 nav-7-5-3-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/tenda-dan-kantong-tidur/aksesoris-tenda.html" class="level3 ">AKSESORIS TENDA</a>
                            </li>
                            <li  class="level3 nav-7-5-3-3 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/tenda-dan-kantong-tidur/tenda-dome.html" class="level3 ">TENDA DOME</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-5-4 parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/kontainer-dan-tempat-minum.html" class="level2 has-children">KONTAINER DAN TEMPAT MINUM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/kontainer-dan-tempat-minum.html">View All KONTAINER DAN TEMPAT MINUM</a>
                            </li>
                            <li  class="level3 nav-7-5-4-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/kontainer-dan-tempat-minum/ukuran-kecil.html" class="level3 ">UKURAN KECIL</a>
                            </li>
                            <li  class="level3 nav-7-5-4-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/kontainer-dan-tempat-minum/ukuran-sedang.html" class="level3 ">UKURAN SEDANG</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-5-5 last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/pendingin-dan-aksesoris.html" class="level2 has-children">PENDINGIN DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/pendingin-dan-aksesoris.html">View All PENDINGIN DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-7-5-5-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/pendingin-dan-aksesoris/box.html" class="level3 ">BOX</a>
                            </li>
                            <li  class="level3 nav-7-5-5-2 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-outdoor/pendingin-dan-aksesoris/tas-pendingin.html" class="level3 ">TAS PENDINGIN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-7-6 parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/perlengkapan-outdoor.html" class="level1 has-children">PERLENGKAPAN OUTDOOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/perlengkapan-outdoor.html">View All PERLENGKAPAN OUTDOOR</a>
                    </li>
                    <li  class="level2 nav-7-6-1 first last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/perlengkapan-outdoor/jas-hujan.html" class="level2 has-children">JAS HUJAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/perlengkapan-outdoor/jas-hujan.html">View All JAS HUJAN</a>
                            </li>
                            <li  class="level3 nav-7-6-1-1 first last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/perlengkapan-outdoor/jas-hujan/jas-hujan.html" class="level3 ">JAS HUJAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-7-7 last parent">
                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan.html" class="level1 has-children">PERALATAN KESEHATAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan.html">View All PERALATAN KESEHATAN</a>
                    </li>
                    <li  class="level2 nav-7-7-1 first parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien.html" class="level2 has-children">KEBUTUHAN PASIEN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien.html">View All KEBUTUHAN PASIEN</a>
                            </li>
                            <li  class="level3 nav-7-7-1-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/p3k.html" class="level3 ">P3K</a>
                            </li>
                            <li  class="level3 nav-7-7-1-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/alat-bantu-jalan.html" class="level3 ">ALAT BANTU JALAN</a>
                            </li>
                            <li  class="level3 nav-7-7-1-3">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/bantalan-panas-dan-dingin.html" class="level3 ">BANTALAN PANAS DAN DINGIN</a>
                            </li>
                            <li  class="level3 nav-7-7-1-4">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/pispot-pasien.html" class="level3 ">PISPOT PASIEN</a>
                            </li>
                            <li  class="level3 nav-7-7-1-5">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/kursi-tunggu-panjang.html" class="level3 ">KURSI TUNGGU PANJANG</a>
                            </li>
                            <li  class="level3 nav-7-7-1-6">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/kursi-pispot.html" class="level3 ">KURSI PISPOT</a>
                            </li>
                            <li  class="level3 nav-7-7-1-7">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/perawatan-pribadi.html" class="level3 ">PERAWATAN PRIBADI</a>
                            </li>
                            <li  class="level3 nav-7-7-1-8">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/kursi-roda.html" class="level3 ">KURSI RODA</a>
                            </li>
                            <li  class="level3 nav-7-7-1-9">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/tandu.html" class="level3 ">TANDU</a>
                            </li>
                            <li  class="level3 nav-7-7-1-10">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/alat-pengangkut-pasien.html" class="level3 ">ALAT PENGANGKUT PASIEN</a>
                            </li>
                            <li  class="level3 nav-7-7-1-11">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/alat-bantu-dengar.html" class="level3 ">ALAT BANTU DENGAR</a>
                            </li>
                            <li  class="level3 nav-7-7-1-12 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/kebutuhan-pasien/alat-bantu-pernafasan.html" class="level3 ">ALAT BANTU PERNAFASAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-7-7-2 last parent">
                        <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa.html" class="level2 has-children">PERALATAN DIAGNOSA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa.html">View All PERALATAN DIAGNOSA</a>
                            </li>
                            <li  class="level3 nav-7-7-2-1 first">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/termometer.html" class="level3 ">TERMOMETER</a>
                            </li>
                            <li  class="level3 nav-7-7-2-2">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/tekanan-darah.html" class="level3 ">TEKANAN DARAH</a>
                            </li>
                            <li  class="level3 nav-7-7-2-3">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/stetoskop.html" class="level3 ">STETOSKOP</a>
                            </li>
                            <li  class="level3 nav-7-7-2-4">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/alat-ukur-kesehatan.html" class="level3 ">ALAT UKUR KESEHATAN</a>
                            </li>
                            <li  class="level3 nav-7-7-2-5">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/x-ray.html" class="level3 ">X-RAY</a>
                            </li>
                            <li  class="level3 nav-7-7-2-6">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/usg.html" class="level3 ">USG</a>
                            </li>
                            <li  class="level3 nav-7-7-2-7">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/ct-scan.html" class="level3 ">CT-SCAN</a>
                            </li>
                            <li  class="level3 nav-7-7-2-8 last">
                                <a href="https://m.ruparupa.com/kesehatan-dan-olahraga/peralatan-kesehatan/peralatan-diagnosa/bagan-tes-mata.html" class="level3 ">BAGAN TES MATA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-8 parent">
        <a href="https://m.ruparupa.com/elektronik-dan-gadget.html" class="level0 has-children">Elektronik &amp; Gadget</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/elektronik-dan-gadget.html">View All Elektronik &amp; Gadget</a>
            </li>
            <li  class="level1 nav-8-1 first parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga.html" class="level1 has-children">PERALATAN RUMAH TANGGA</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga.html">View All PERALATAN RUMAH TANGGA</a>
                    </li>
                    <li  class="level2 nav-8-1-1 first parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/air-conditioner.html" class="level2 has-children">AIR CONDITIONER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/air-conditioner.html">View All AIR CONDITIONER</a>
                            </li>
                            <li  class="level3 nav-8-1-1-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/air-conditioner/air-cooler.html" class="level3 ">AIR COOLER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-1-2 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pembersih-udara.html" class="level2 has-children">PEMBERSIH UDARA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pembersih-udara.html">View All PEMBERSIH UDARA</a>
                            </li>
                            <li  class="level3 nav-8-1-2-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pembersih-udara/perban.html" class="level3 ">PERBAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-1-3 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/kipas-angin-dan-aksesoris.html" class="level2 has-children">KIPAS ANGIN DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/kipas-angin-dan-aksesoris.html">View All KIPAS ANGIN DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-8-1-3-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/kipas-angin-dan-aksesoris/kipas-angin-berdiri.html" class="level3 ">KIPAS ANGIN BERDIRI</a>
                            </li>
                            <li  class="level3 nav-8-1-3-2 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/kipas-angin-dan-aksesoris/kipas-angin-meja.html" class="level3 ">KIPAS ANGIN MEJA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-1-4 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pemanas-air.html" class="level2 has-children">PEMANAS AIR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pemanas-air.html">View All PEMANAS AIR</a>
                            </li>
                            <li  class="level3 nav-8-1-4-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pemanas-air/pemanas-air-elektrik.html" class="level3 ">PEMANAS AIR ELEKTRIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-1-5 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pompa-air.html" class="level2 has-children">POMPA AIR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pompa-air.html">View All POMPA AIR</a>
                            </li>
                            <li  class="level3 nav-8-1-5-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pompa-air/pompa-submersible.html" class="level3 ">POMPA SUBMERSIBLE</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-1-6 last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pembasmi-serangga.html" class="level2 has-children">PEMBASMI SERANGGA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pembasmi-serangga.html">View All PEMBASMI SERANGGA</a>
                            </li>
                            <li  class="level3 nav-8-1-6-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-rumah-tangga/pembasmi-serangga/pembasmi-nyamuk.html" class="level3 ">PEMBASMI NYAMUK</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-2 parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah.html" class="level1 has-children">PEMBERSIH RUMAH</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah.html">View All PEMBERSIH RUMAH</a>
                    </li>
                    <li  class="level2 nav-8-2-1 first parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/pembersih-tekanan-tinggi.html" class="level2 has-children">PEMBERSIH TEKANAN TINGGI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/pembersih-tekanan-tinggi.html">View All PEMBERSIH TEKANAN TINGGI</a>
                            </li>
                            <li  class="level3 nav-8-2-1-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/pembersih-tekanan-tinggi/air-dingin.html" class="level3 ">AIR DINGIN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-2-2 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/penghisap-debu.html" class="level2 has-children">PENGHISAP DEBU</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/penghisap-debu.html">View All PENGHISAP DEBU</a>
                            </li>
                            <li  class="level3 nav-8-2-2-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/penghisap-debu/lint-remover.html" class="level3 ">LINT REMOVER</a>
                            </li>
                            <li  class="level3 nav-8-2-2-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/penghisap-debu/penghisap-debu-kering.html" class="level3 ">PENGHISAP DEBU KERING</a>
                            </li>
                            <li  class="level3 nav-8-2-2-3 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/penghisap-debu/penghisap-debu-basah-dan-kering.html" class="level3 ">PENGHISAP DEBU BASAH DAN KERING</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-2-3 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-lantai.html" class="level2 has-children">ALAT PEMBERSIH LANTAI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-lantai.html">View All ALAT PEMBERSIH LANTAI</a>
                            </li>
                            <li  class="level3 nav-8-2-3-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-lantai/sikat-lantai.html" class="level3 ">SIKAT LANTAI</a>
                            </li>
                            <li  class="level3 nav-8-2-3-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-lantai/sikat-pengering.html" class="level3 ">SIKAT PENGERING</a>
                            </li>
                            <li  class="level3 nav-8-2-3-3 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-lantai/sapu.html" class="level3 ">SAPU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-2-4 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-uap.html" class="level2 has-children">ALAT PEMBERSIH UAP</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-uap.html">View All ALAT PEMBERSIH UAP</a>
                            </li>
                            <li  class="level3 nav-8-2-4-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/alat-pembersih-uap/kain-pel-uap.html" class="level3 ">KAIN PEL UAP</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-2-5 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/pembersih-ultrasonik.html" class="level2 has-children">PEMBERSIH ULTRASONIK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/pembersih-ultrasonik.html">View All PEMBERSIH ULTRASONIK</a>
                            </li>
                            <li  class="level3 nav-8-2-5-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/pembersih-ultrasonik/pembersih-ultrasonik.html" class="level3 ">PEMBERSIH ULTRASONIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-2-6 last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/aksesoris.html" class="level2 has-children">AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/aksesoris.html">View All AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-8-2-6-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/pembersih-rumah/aksesoris/aksesoris-dan-suku-cadang.html" class="level3 ">AKSESORIS DAN SUKU CADANG</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-3 parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh.html" class="level1 has-children">PERAWATAN TUBUH</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh.html">View All PERAWATAN TUBUH</a>
                    </li>
                    <li  class="level2 nav-8-3-1 first last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut.html" class="level2 has-children">PERAWATAN RAMBUT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut.html">View All PERAWATAN RAMBUT</a>
                            </li>
                            <li  class="level3 nav-8-3-1-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut/penata-rambut.html" class="level3 ">PENATA RAMBUT</a>
                            </li>
                            <li  class="level3 nav-8-3-1-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut/pengering-rambut.html" class="level3 ">PENGERING RAMBUT</a>
                            </li>
                            <li  class="level3 nav-8-3-1-3">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut/pelurus-rambut.html" class="level3 ">PELURUS RAMBUT</a>
                            </li>
                            <li  class="level3 nav-8-3-1-4">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut/pengeriting-rambut.html" class="level3 ">PENGERITING RAMBUT</a>
                            </li>
                            <li  class="level3 nav-8-3-1-5 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perawatan-tubuh/perawatan-rambut/alat-cukur-rambut.html" class="level3 ">ALAT CUKUR RAMBUT</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-4 parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video.html" class="level1 has-children">AUDIO VIDEO</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video.html">View All AUDIO VIDEO</a>
                    </li>
                    <li  class="level2 nav-8-4-1 first last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv.html" class="level2 has-children">AKSESORIS TV</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv.html">View All AKSESORIS TV</a>
                            </li>
                            <li  class="level3 nav-8-4-1-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/antena.html" class="level3 ">ANTENA</a>
                            </li>
                            <li  class="level3 nav-8-4-1-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/rak-tv.html" class="level3 ">RAK TV</a>
                            </li>
                            <li  class="level3 nav-8-4-1-3">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/remote-control.html" class="level3 ">REMOTE CONTROL</a>
                            </li>
                            <li  class="level3 nav-8-4-1-4">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/tv-tuner.html" class="level3 ">TV TUNER</a>
                            </li>
                            <li  class="level3 nav-8-4-1-5">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/bracket-tv.html" class="level3 ">BRACKET TV</a>
                            </li>
                            <li  class="level3 nav-8-4-1-6">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/sensor-tv.html" class="level3 ">SENSOR TV</a>
                            </li>
                            <li  class="level3 nav-8-4-1-7">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/decoder.html" class="level3 ">DECODER</a>
                            </li>
                            <li  class="level3 nav-8-4-1-8">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/hd-generator.html" class="level3 ">HD GENERATOR</a>
                            </li>
                            <li  class="level3 nav-8-4-1-9 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/audio-video/aksesoris-tv/keyboard-wireless.html" class="level3 ">KEYBOARD WIRELESS</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-5 parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik.html" class="level1 has-children">PERALATAN ELEKTRONIK</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik.html">View All PERALATAN ELEKTRONIK</a>
                    </li>
                    <li  class="level2 nav-8-5-1 first parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/baterai.html" class="level2 has-children">BATERAI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/baterai.html">View All BATERAI</a>
                            </li>
                            <li  class="level3 nav-8-5-1-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/baterai/baterai-primer.html" class="level3 ">BATERAI PRIMER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-5-2 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter.html" class="level2 has-children">SENTER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter.html">View All SENTER</a>
                            </li>
                            <li  class="level3 nav-8-5-2-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter/senter-standard.html" class="level3 ">SENTER STANDARD</a>
                            </li>
                            <li  class="level3 nav-8-5-2-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter/senter-premium.html" class="level3 ">SENTER PREMIUM</a>
                            </li>
                            <li  class="level3 nav-8-5-2-3">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter/senter-rechargeable.html" class="level3 ">SENTER RECHARGEABLE</a>
                            </li>
                            <li  class="level3 nav-8-5-2-4">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter/senter-personal.html" class="level3 ">SENTER PERSONAL</a>
                            </li>
                            <li  class="level3 nav-8-5-2-5">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter/lentera.html" class="level3 ">LENTERA</a>
                            </li>
                            <li  class="level3 nav-8-5-2-6 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/senter/lampu-darurat.html" class="level3 ">LAMPU DARURAT</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-5-3 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/kabel.html" class="level2 has-children">KABEL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/kabel.html">View All KABEL</a>
                            </li>
                            <li  class="level3 nav-8-5-3-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/kabel/kabel-antena.html" class="level3 ">KABEL ANTENA</a>
                            </li>
                            <li  class="level3 nav-8-5-3-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/kabel/gulungan-kabel.html" class="level3 ">GULUNGAN KABEL</a>
                            </li>
                            <li  class="level3 nav-8-5-3-3">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/kabel/pengikat.html" class="level3 ">PENGIKAT</a>
                            </li>
                            <li  class="level3 nav-8-5-3-4 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/kabel/terminal-dan-klip.html" class="level3 ">TERMINAL DAN  KLIP</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-5-4 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/saklar.html" class="level2 has-children">SAKLAR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/saklar.html">View All SAKLAR</a>
                            </li>
                            <li  class="level3 nav-8-5-4-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/saklar/sakelar-rocker.html" class="level3 ">SAKELAR ROCKER</a>
                            </li>
                            <li  class="level3 nav-8-5-4-2 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/saklar/sakelar-timmer.html" class="level3 ">SAKELAR TIMMER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-5-5 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/adaptorkonektor.html" class="level2 has-children">ADAPTOR/KONEKTOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/adaptorkonektor.html">View All ADAPTOR/KONEKTOR</a>
                            </li>
                            <li  class="level3 nav-8-5-5-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/adaptorkonektor/adaptor.html" class="level3 ">ADAPTOR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-5-6 last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/sekering.html" class="level2 has-children">SEKERING</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/sekering.html">View All SEKERING</a>
                            </li>
                            <li  class="level3 nav-8-5-6-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/sekering/plug.html" class="level3 ">PLUG</a>
                            </li>
                            <li  class="level3 nav-8-5-6-2 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/peralatan-elektronik/sekering/soket.html" class="level3 ">SOKET</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-6 parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/handphone-dan-gadget.html" class="level1 has-children">HANDPHONE DAN GADGET</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/handphone-dan-gadget.html">View All HANDPHONE DAN GADGET</a>
                    </li>
                    <li  class="level2 nav-8-6-1 first last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/handphone-dan-gadget/aksesoris-telepon-genggam.html" class="level2 has-children">AKSESORIS TELEPON GENGGAM</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/handphone-dan-gadget/aksesoris-telepon-genggam.html">View All AKSESORIS TELEPON GENGGAM</a>
                            </li>
                            <li  class="level3 nav-8-6-1-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/handphone-dan-gadget/aksesoris-telepon-genggam/casing.html" class="level3 ">CASING</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-7 parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor.html" class="level1 has-children">PERLENGKAPAN KANTOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor.html">View All PERLENGKAPAN KANTOR</a>
                    </li>
                    <li  class="level2 nav-8-7-1 first parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-elektronik-kantor.html" class="level2 has-children">ALAT ELEKTRONIK KANTOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-elektronik-kantor.html">View All ALAT ELEKTRONIK KANTOR</a>
                            </li>
                            <li  class="level3 nav-8-7-1-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-elektronik-kantor/alat-penghitung-waktu.html" class="level3 ">ALAT PENGHITUNG WAKTU</a>
                            </li>
                            <li  class="level3 nav-8-7-1-2">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-elektronik-kantor/alat-laminating.html" class="level3 ">ALAT LAMINATING</a>
                            </li>
                            <li  class="level3 nav-8-7-1-3">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-elektronik-kantor/kebutuhan-alat-elektronik-kantor.html" class="level3 ">KEBUTUHAN ALAT ELEKTRONIK KANTOR</a>
                            </li>
                            <li  class="level3 nav-8-7-1-4 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-elektronik-kantor/alat-pendeteksi-uang.html" class="level3 ">ALAT PENDETEKSI UANG</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-7-2 parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/telepon.html" class="level2 has-children">TELEPON</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/telepon.html">View All TELEPON</a>
                            </li>
                            <li  class="level3 nav-8-7-2-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/telepon/telepon-meja.html" class="level3 ">TELEPON MEJA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-7-3 last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-presentasi.html" class="level2 has-children">ALAT PRESENTASI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-presentasi.html">View All ALAT PRESENTASI</a>
                            </li>
                            <li  class="level3 nav-8-7-3-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/perlengkapan-kantor/alat-presentasi/papan-buletin.html" class="level3 ">PAPAN BULETIN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-8-8 last parent">
                <a href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis.html" class="level1 has-children">ALAT TULIS</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis.html">View All ALAT TULIS</a>
                    </li>
                    <li  class="level2 nav-8-8-1 first parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/perlengkapan-kantor.html" class="level2 has-children">PERLENGKAPAN KANTOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/perlengkapan-kantor.html">View All PERLENGKAPAN KANTOR</a>
                            </li>
                            <li  class="level3 nav-8-8-1-1 first">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/perlengkapan-kantor/perlengkapan-dasar.html" class="level3 ">PERLENGKAPAN DASAR</a>
                            </li>
                            <li  class="level3 nav-8-8-1-2 last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/perlengkapan-kantor/kertas.html" class="level3 ">KERTAS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-8-8-2 last parent">
                        <a href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/seni-dan-kerajinan.html" class="level2 has-children">SENI DAN KERAJINAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/seni-dan-kerajinan.html">View All SENI DAN KERAJINAN</a>
                            </li>
                            <li  class="level3 nav-8-8-2-1 first last">
                                <a href="https://m.ruparupa.com/elektronik-dan-gadget/alat-tulis/seni-dan-kerajinan/kerajinan-tangan.html" class="level3 ">KERAJINAN TANGAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-9 parent">
        <a href="https://m.ruparupa.com/mainan-dan-bayi.html" class="level0 has-children">Mainan &amp; Bayi</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/mainan-dan-bayi.html">View All Mainan &amp; Bayi</a>
            </li>
            <li  class="level1 nav-9-1 first parent">
                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan.html" class="level1 has-children">MAINAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/mainan-dan-bayi/mainan.html">View All MAINAN</a>
                    </li>
                    <li  class="level2 nav-9-1-1 first parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan.html" class="level2 has-children">SET MAINAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan.html">View All SET MAINAN</a>
                            </li>
                            <li  class="level3 nav-9-1-1-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/mainan-perang.html" class="level3 ">MAINAN PERANG</a>
                            </li>
                            <li  class="level3 nav-9-1-1-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/senjata.html" class="level3 ">SENJATA</a>
                            </li>
                            <li  class="level3 nav-9-1-1-3">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/balapan.html" class="level3 ">BALAPAN</a>
                            </li>
                            <li  class="level3 nav-9-1-1-4">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/set-mainan-dapur.html" class="level3 ">SET MAINAN DAPUR</a>
                            </li>
                            <li  class="level3 nav-9-1-1-5">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/set-mainan-hobi.html" class="level3 ">SET MAINAN HOBI</a>
                            </li>
                            <li  class="level3 nav-9-1-1-6">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/set-mainan-medis.html" class="level3 ">SET MAINAN MEDIS</a>
                            </li>
                            <li  class="level3 nav-9-1-1-7 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/set-mainan/set-mainan-roleplay.html" class="level3 ">SET MAINAN ROLEPLAY</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-1-2 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka.html" class="level2 has-children">BONEKA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka.html">View All BONEKA</a>
                            </li>
                            <li  class="level3 nav-9-1-2-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka/boneka-fashion.html" class="level3 ">BONEKA FASHION</a>
                            </li>
                            <li  class="level3 nav-9-1-2-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka/aksesoris-boneka.html" class="level3 ">AKSESORIS BONEKA</a>
                            </li>
                            <li  class="level3 nav-9-1-2-3">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka/set-mainan-boneka-bayi.html" class="level3 ">SET MAINAN BONEKA BAYI</a>
                            </li>
                            <li  class="level3 nav-9-1-2-4 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka/set-mainan-boneka-besar.html" class="level3 ">SET MAINAN BONEKA BESAR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-1-3 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka-plush.html" class="level2 has-children">BONEKA PLUSH</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka-plush.html">View All BONEKA PLUSH</a>
                            </li>
                            <li  class="level3 nav-9-1-3-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka-plush/binatang.html" class="level3 ">BINATANG</a>
                            </li>
                            <li  class="level3 nav-9-1-3-2 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/boneka-plush/generik.html" class="level3 ">GENERIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-1-4 last parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/perlengkapan-pesta.html" class="level2 has-children">PERLENGKAPAN PESTA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan/perlengkapan-pesta.html">View All PERLENGKAPAN PESTA</a>
                            </li>
                            <li  class="level3 nav-9-1-4-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan/perlengkapan-pesta/aksesoris-pesta.html" class="level3 ">AKSESORIS PESTA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-9-2 parent">
                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi.html" class="level1 has-children">MAINAN EDUKASI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi.html">View All MAINAN EDUKASI</a>
                    </li>
                    <li  class="level2 nav-9-2-1 first last parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni.html" class="level2 has-children">SAINS DAN SENI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni.html">View All SAINS DAN SENI</a>
                            </li>
                            <li  class="level3 nav-9-2-1-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni/imajinasi.html" class="level3 ">IMAJINASI</a>
                            </li>
                            <li  class="level3 nav-9-2-1-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni/sains.html" class="level3 ">SAINS</a>
                            </li>
                            <li  class="level3 nav-9-2-1-3">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni/seni-dan-kerajinan.html" class="level3 ">SENI DAN KERAJINAN</a>
                            </li>
                            <li  class="level3 nav-9-2-1-4">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni/elektronik-edukasi.html" class="level3 ">ELEKTRONIK EDUKASI</a>
                            </li>
                            <li  class="level3 nav-9-2-1-5 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-edukasi/sains-dan-seni/mainan-musikal.html" class="level3 ">MAINAN MUSIKAL</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-9-3 parent">
                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi.html" class="level1 has-children">MAINAN KOLEKSI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi.html">View All MAINAN KOLEKSI</a>
                    </li>
                    <li  class="level2 nav-9-3-1 first parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/diecast.html" class="level2 has-children">DIECAST</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/diecast.html">View All DIECAST</a>
                            </li>
                            <li  class="level3 nav-9-3-1-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/diecast/miniatur-mobil.html" class="level3 ">MINIATUR MOBIL</a>
                            </li>
                            <li  class="level3 nav-9-3-1-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/diecast/pesawat.html" class="level3 ">PESAWAT</a>
                            </li>
                            <li  class="level3 nav-9-3-1-3 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/diecast/urban.html" class="level3 ">URBAN</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-3-2 last parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/figur.html" class="level2 has-children">FIGUR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/figur.html">View All FIGUR</a>
                            </li>
                            <li  class="level3 nav-9-3-2-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-koleksi/figur/penegak-hukum.html" class="level3 ">PENEGAK HUKUM</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-9-4 parent">
                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control.html" class="level1 has-children">REMOTE CONTROL</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/mainan-dan-bayi/remote-control.html">View All REMOTE CONTROL</a>
                    </li>
                    <li  class="level2 nav-9-4-1 first parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/laut.html" class="level2 has-children">LAUT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/laut.html">View All LAUT</a>
                            </li>
                            <li  class="level3 nav-9-4-1-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/laut/remote-control-perahu.html" class="level3 ">REMOTE CONTROL PERAHU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-4-2 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/militer.html" class="level2 has-children">MILITER</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/militer.html">View All MILITER</a>
                            </li>
                            <li  class="level3 nav-9-4-2-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/militer/tank.html" class="level3 ">TANK</a>
                            </li>
                            <li  class="level3 nav-9-4-2-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/militer/kendaraan-darurat.html" class="level3 ">KENDARAAN DARURAT</a>
                            </li>
                            <li  class="level3 nav-9-4-2-3 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/militer/mobil-polisi.html" class="level3 ">MOBIL POLISI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-4-3 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/kota.html" class="level2 has-children">KOTA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/kota.html">View All KOTA</a>
                            </li>
                            <li  class="level3 nav-9-4-3-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/kota/remote-control-mobil.html" class="level3 ">REMOTE CONTROL MOBIL</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-4-4 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/udara.html" class="level2 has-children">UDARA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/udara.html">View All UDARA</a>
                            </li>
                            <li  class="level3 nav-9-4-4-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/udara/antariksa.html" class="level3 ">ANTARIKSA</a>
                            </li>
                            <li  class="level3 nav-9-4-4-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/udara/pesawat-terbang.html" class="level3 ">PESAWAT TERBANG</a>
                            </li>
                            <li  class="level3 nav-9-4-4-3 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/udara/helikopter.html" class="level3 ">HELIKOPTER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-4-5 last parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/binatang-dan-robot.html" class="level2 has-children">BINATANG DAN ROBOT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/binatang-dan-robot.html">View All BINATANG DAN ROBOT</a>
                            </li>
                            <li  class="level3 nav-9-4-5-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/remote-control/binatang-dan-robot/remote-control-binatang.html" class="level3 ">REMOTE CONTROL BINATANG</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-9-5 parent">
                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor.html" class="level1 has-children">MAINAN OUTDOOR</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor.html">View All MAINAN OUTDOOR</a>
                    </li>
                    <li  class="level2 nav-9-5-1 first parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/mainan-tunggangan.html" class="level2 has-children">MAINAN TUNGGANGAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/mainan-tunggangan.html">View All MAINAN TUNGGANGAN</a>
                            </li>
                            <li  class="level3 nav-9-5-1-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/mainan-tunggangan/skuter.html" class="level3 ">SKUTER</a>
                            </li>
                            <li  class="level3 nav-9-5-1-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/mainan-tunggangan/rider-dan-coaster.html" class="level3 ">RIDER DAN  COASTER</a>
                            </li>
                            <li  class="level3 nav-9-5-1-3 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/mainan-tunggangan/bo-rider.html" class="level3 ">B/O RIDER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-5-2 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor.html" class="level2 has-children">PERMAINAN OUTDOOR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor.html">View All PERMAINAN OUTDOOR</a>
                            </li>
                            <li  class="level3 nav-9-5-2-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor/playhouse.html" class="level3 ">PLAYHOUSE</a>
                            </li>
                            <li  class="level3 nav-9-5-2-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor/perosotan.html" class="level3 ">PEROSOTAN</a>
                            </li>
                            <li  class="level3 nav-9-5-2-3">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor/playbox-dan-fence.html" class="level3 ">PLAYBOX DAN  FENCE</a>
                            </li>
                            <li  class="level3 nav-9-5-2-4">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor/stationary-rider.html" class="level3 ">STATIONARY RIDER</a>
                            </li>
                            <li  class="level3 nav-9-5-2-5 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/permainan-outdoor/soft-play.html" class="level3 ">SOFT PLAY</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-5-3 last parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/kolam-dan-mainan-air.html" class="level2 has-children">KOLAM DAN MAINAN AIR</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/kolam-dan-mainan-air.html">View All KOLAM DAN MAINAN AIR</a>
                            </li>
                            <li  class="level3 nav-9-5-3-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/kolam-dan-mainan-air/kolam-anak.html" class="level3 ">KOLAM ANAK</a>
                            </li>
                            <li  class="level3 nav-9-5-3-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/kolam-dan-mainan-air/peralatan-renang.html" class="level3 ">PERALATAN RENANG</a>
                            </li>
                            <li  class="level3 nav-9-5-3-3">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/kolam-dan-mainan-air/mainan-air.html" class="level3 ">MAINAN AIR</a>
                            </li>
                            <li  class="level3 nav-9-5-3-4 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-outdoor/kolam-dan-mainan-air/aksesoris-kolam.html" class="level3 ">AKSESORIS KOLAM</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-9-6 last parent">
                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi.html" class="level1 has-children">MAINAN BAYI</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi.html">View All MAINAN BAYI</a>
                    </li>
                    <li  class="level2 nav-9-6-1 first parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-makan.html" class="level2 has-children">PERALATAN MAKAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-makan.html">View All PERALATAN MAKAN</a>
                            </li>
                            <li  class="level3 nav-9-6-1-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-makan/botol.html" class="level3 ">BOTOL</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-6-2 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita.html" class="level2 has-children">MAINAN BAYI DAN BALITA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita.html">View All MAINAN BAYI DAN BALITA</a>
                            </li>
                            <li  class="level3 nav-9-6-2-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/kerincingan.html" class="level3 ">KERINCINGAN</a>
                            </li>
                            <li  class="level3 nav-9-6-2-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/gigitan-bayi.html" class="level3 ">GIGITAN BAYI</a>
                            </li>
                            <li  class="level3 nav-9-6-2-3">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/mainan-kereta-bayi.html" class="level3 ">MAINAN KERETA BAYI</a>
                            </li>
                            <li  class="level3 nav-9-6-2-4">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/rockers.html" class="level3 ">ROCKERS</a>
                            </li>
                            <li  class="level3 nav-9-6-2-5">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/gym.html" class="level3 ">GYM</a>
                            </li>
                            <li  class="level3 nav-9-6-2-6">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/mainan-figur-dan-playset.html" class="level3 ">MAINAN FIGUR DAN PLAYSET</a>
                            </li>
                            <li  class="level3 nav-9-6-2-7">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/mainan-balok.html" class="level3 ">MAINAN BALOK</a>
                            </li>
                            <li  class="level3 nav-9-6-2-8">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/mainan-push-and-pull.html" class="level3 ">MAINAN PUSH AND PULL</a>
                            </li>
                            <li  class="level3 nav-9-6-2-9">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/mainan-puzzle.html" class="level3 ">MAINAN PUZZLE</a>
                            </li>
                            <li  class="level3 nav-9-6-2-10">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/mainan-mandi.html" class="level3 ">MAINAN MANDI</a>
                            </li>
                            <li  class="level3 nav-9-6-2-11 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/mainan-bayi-dan-balita/lampu-suara-dan-musik.html" class="level3 ">LAMPU SUARA DAN MUSIK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-6-3 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-dan-perlengkapan-bayi.html" class="level2 has-children">PERALATAN DAN PERLENGKAPAN BAYI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-dan-perlengkapan-bayi.html">View All PERALATAN DAN PERLENGKAPAN BAYI</a>
                            </li>
                            <li  class="level3 nav-9-6-3-1 first">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-dan-perlengkapan-bayi/pagar.html" class="level3 ">PAGAR</a>
                            </li>
                            <li  class="level3 nav-9-6-3-2">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-dan-perlengkapan-bayi/matras-bermain.html" class="level3 ">MATRAS BERMAIN</a>
                            </li>
                            <li  class="level3 nav-9-6-3-3 last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/peralatan-dan-perlengkapan-bayi/aksesoris-perlengkapan-bayi.html" class="level3 ">AKSESORIS PERLENGKAPAN BAYI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-6-4 parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/pakaian-bayi.html" class="level2 has-children">PAKAIAN BAYI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/pakaian-bayi.html">View All PAKAIAN BAYI</a>
                            </li>
                            <li  class="level3 nav-9-6-4-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/pakaian-bayi/aksesoris-bayi.html" class="level3 ">AKSESORIS BAYI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-9-6-5 last parent">
                        <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/ibu-dan-perawatan-kehamilan.html" class="level2 has-children">IBU DAN PERAWATAN KEHAMILAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/ibu-dan-perawatan-kehamilan.html">View All IBU DAN PERAWATAN KEHAMILAN</a>
                            </li>
                            <li  class="level3 nav-9-6-5-1 first last">
                                <a href="https://m.ruparupa.com/mainan-dan-bayi/mainan-bayi/ibu-dan-perawatan-kehamilan/perawatan-ibu-hamil.html" class="level3 ">PERAWATAN IBU HAMIL</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-10 parent">
        <a href="https://m.ruparupa.com/rumah-tangga.html" class="level0 has-children">Rumah Tangga</a>
        <ul class="level0">
            <li class="level1 view-all">
                <a class="level1" href="https://m.ruparupa.com/rumah-tangga.html">View All Rumah Tangga</a>
            </li>
            <li  class="level1 nav-10-1 first parent">
                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah.html" class="level1 has-children">DEKORASI RUMAH</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah.html">View All DEKORASI RUMAH</a>
                    </li>
                    <li  class="level2 nav-10-1-1 first parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/dekorasi-ruangan.html" class="level2 has-children">DEKORASI RUANGAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/dekorasi-ruangan.html">View All DEKORASI RUANGAN</a>
                            </li>
                            <li  class="level3 nav-10-1-1-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/dekorasi-ruangan/tempat-lilin.html" class="level3 ">TEMPAT LILIN</a>
                            </li>
                            <li  class="level3 nav-10-1-1-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/dekorasi-ruangan/minyak-lampu.html" class="level3 ">MINYAK LAMPU</a>
                            </li>
                            <li  class="level3 nav-10-1-1-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/dekorasi-ruangan/dekorasi-dinding.html" class="level3 ">DEKORASI DINDING</a>
                            </li>
                            <li  class="level3 nav-10-1-1-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/dekorasi-ruangan/asbak.html" class="level3 ">ASBAK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-1-2 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/jam-dinding.html" class="level2 has-children">JAM DINDING</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/jam-dinding.html">View All JAM DINDING</a>
                            </li>
                            <li  class="level3 nav-10-1-2-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/jam-dinding/dekorasi-plastik.html" class="level3 ">DEKORASI PLASTIK</a>
                            </li>
                            <li  class="level3 nav-10-1-2-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/jam-dinding/jam-kaca.html" class="level3 ">JAM KACA</a>
                            </li>
                            <li  class="level3 nav-10-1-2-3 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/jam-dinding/alumunium.html" class="level3 ">ALUMUNIUM</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-1-3 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/bingkai-foto.html" class="level2 has-children">BINGKAI FOTO</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/bingkai-foto.html">View All BINGKAI FOTO</a>
                            </li>
                            <li  class="level3 nav-10-1-3-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/bingkai-foto/bingkai-foto-kayu.html" class="level3 ">BINGKAI FOTO KAYU</a>
                            </li>
                            <li  class="level3 nav-10-1-3-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/bingkai-foto/bingkai-foto-logam.html" class="level3 ">BINGKAI FOTO LOGAM</a>
                            </li>
                            <li  class="level3 nav-10-1-3-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/bingkai-foto/bingkai-foto-plastik.html" class="level3 ">BINGKAI FOTO PLASTIK</a>
                            </li>
                            <li  class="level3 nav-10-1-3-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/bingkai-foto/bingkai-foto-mdf.html" class="level3 ">BINGKAI FOTO MDF</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-1-4 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/aksesories-dan-tanaman-buatan.html" class="level2 has-children">AKSESORIES DAN TANAMAN BUATAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/aksesories-dan-tanaman-buatan.html">View All AKSESORIES DAN TANAMAN BUATAN</a>
                            </li>
                            <li  class="level3 nav-10-1-4-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/aksesories-dan-tanaman-buatan/bunga-tiruan.html" class="level3 ">BUNGA TIRUAN</a>
                            </li>
                            <li  class="level3 nav-10-1-4-2 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/aksesories-dan-tanaman-buatan/vas.html" class="level3 ">VAS</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-1-5 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/pengharum-ruangan.html" class="level2 has-children">PENGHARUM RUANGAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/pengharum-ruangan.html">View All PENGHARUM RUANGAN</a>
                            </li>
                            <li  class="level3 nav-10-1-5-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/pengharum-ruangan/lilin-harum.html" class="level3 ">LILIN HARUM</a>
                            </li>
                            <li  class="level3 nav-10-1-5-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/pengharum-ruangan/lilin.html" class="level3 ">LILIN</a>
                            </li>
                            <li  class="level3 nav-10-1-5-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/pengharum-ruangan/aromaterapi.html" class="level3 ">AROMATERAPI</a>
                            </li>
                            <li  class="level3 nav-10-1-5-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/pengharum-ruangan/burner.html" class="level3 ">BURNER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-1-6 last parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni.html" class="level2 has-children">GALERI SENI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni.html">View All GALERI SENI</a>
                            </li>
                            <li  class="level3 nav-10-1-6-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni/lukisan.html" class="level3 ">LUKISAN</a>
                            </li>
                            <li  class="level3 nav-10-1-6-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni/patung.html" class="level3 ">PATUNG</a>
                            </li>
                            <li  class="level3 nav-10-1-6-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni/seni-replika.html" class="level3 ">SENI REPLIKA</a>
                            </li>
                            <li  class="level3 nav-10-1-6-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni/hiasan-dinding.html" class="level3 ">HIASAN DINDING</a>
                            </li>
                            <li  class="level3 nav-10-1-6-5 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/dekorasi-rumah/galeri-seni/pemisah-ruangan.html" class="level3 ">PEMISAH RUANGAN</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-10-2 parent">
                <a href="https://m.ruparupa.com/rumah-tangga/tekstil.html" class="level1 has-children">TEKSTIL</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/rumah-tangga/tekstil.html">View All TEKSTIL</a>
                    </li>
                    <li  class="level2 nav-10-2-1 first parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil.html" class="level2 has-children">DEKORASI TEKSTIL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil.html">View All DEKORASI TEKSTIL</a>
                            </li>
                            <li  class="level3 nav-10-2-1-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/sarung-bantal.html" class="level3 ">SARUNG BANTAL</a>
                            </li>
                            <li  class="level3 nav-10-2-1-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/seprai-linen-bantal-tidur.html" class="level3 ">SEPRAI LINEN/ BANTAL TIDUR</a>
                            </li>
                            <li  class="level3 nav-10-2-1-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/aksesoris-khusus.html" class="level3 ">AKSESORIS KHUSUS</a>
                            </li>
                            <li  class="level3 nav-10-2-1-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/bantal-kursi.html" class="level3 ">BANTAL KURSI</a>
                            </li>
                            <li  class="level3 nav-10-2-1-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/alas-duduk.html" class="level3 ">ALAS DUDUK</a>
                            </li>
                            <li  class="level3 nav-10-2-1-6">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/tekstil-guling.html" class="level3 ">TEKSTIL GULING</a>
                            </li>
                            <li  class="level3 nav-10-2-1-7 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/dekorasi-tekstil/taplak-meja.html" class="level3 ">TAPLAK MEJA</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-2-2 last parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset.html" class="level2 has-children">KESET</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tekstil/keset.html">View All KESET</a>
                            </li>
                            <li  class="level3 nav-10-2-2-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/keset-kamar-mandi.html" class="level3 ">KESET KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-10-2-2-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/keset-dekoratif.html" class="level3 ">KESET DEKORATIF</a>
                            </li>
                            <li  class="level3 nav-10-2-2-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/keset-dapur.html" class="level3 ">KESET DAPUR</a>
                            </li>
                            <li  class="level3 nav-10-2-2-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/keset-eva-puzzle.html" class="level3 ">KESET EVA/ PUZZLE</a>
                            </li>
                            <li  class="level3 nav-10-2-2-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/karpet-tangga.html" class="level3 ">KARPET TANGGA</a>
                            </li>
                            <li  class="level3 nav-10-2-2-6">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/keset-memory-foam.html" class="level3 ">KESET MEMORY FOAM</a>
                            </li>
                            <li  class="level3 nav-10-2-2-7 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tekstil/keset/keset-outdoor.html" class="level3 ">KESET OUTDOOR</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-10-3 parent">
                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan.html" class="level1 has-children">TEMPAT PENYIMPANAN</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan.html">View All TEMPAT PENYIMPANAN</a>
                    </li>
                    <li  class="level2 nav-10-3-1 first parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box.html" class="level2 has-children">BOX</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box.html">View All BOX</a>
                            </li>
                            <li  class="level3 nav-10-3-1-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box/kotak-penyimpanan.html" class="level3 ">KOTAK PENYIMPANAN</a>
                            </li>
                            <li  class="level3 nav-10-3-1-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box/kotak-susun.html" class="level3 ">KOTAK SUSUN</a>
                            </li>
                            <li  class="level3 nav-10-3-1-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box/laci-tempat-tidur.html" class="level3 ">LACI TEMPAT TIDUR</a>
                            </li>
                            <li  class="level3 nav-10-3-1-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box/tempat-sampah-dan-keranjang-sampah.html" class="level3 ">TEMPAT SAMPAH DAN KERANJANG SAMPAH</a>
                            </li>
                            <li  class="level3 nav-10-3-1-5 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/box/boks-bawah-tempat-tidur.html" class="level3 ">BOKS BAWAH TEMPAT TIDUR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-3-2 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/kotak-perhiasan.html" class="level2 has-children">KOTAK PERHIASAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/kotak-perhiasan.html">View All KOTAK PERHIASAN</a>
                            </li>
                            <li  class="level3 nav-10-3-2-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/kotak-perhiasan/kotak-perhiasan.html" class="level3 ">KOTAK PERHIASAN</a>
                            </li>
                            <li  class="level3 nav-10-3-2-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/kotak-perhiasan/penyimpanan-perhiasan.html" class="level3 ">PENYIMPANAN PERHIASAN</a>
                            </li>
                            <li  class="level3 nav-10-3-2-3 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/kotak-perhiasan/kotak-jam.html" class="level3 ">KOTAK JAM</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-3-3 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-sepatu.html" class="level2 has-children">PENYIMPANAN SEPATU</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-sepatu.html">View All PENYIMPANAN SEPATU</a>
                            </li>
                            <li  class="level3 nav-10-3-3-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-sepatu/rak-sepatu.html" class="level3 ">RAK SEPATU</a>
                            </li>
                            <li  class="level3 nav-10-3-3-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-sepatu/lemari-sepatu.html" class="level3 ">LEMARI SEPATU</a>
                            </li>
                            <li  class="level3 nav-10-3-3-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-sepatu/penyimpanan-sepatu.html" class="level3 ">PENYIMPANAN SEPATU</a>
                            </li>
                            <li  class="level3 nav-10-3-3-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-sepatu/kotak-sepatu.html" class="level3 ">KOTAK SEPATU</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-3-4 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dan-tata-letak.html" class="level2 has-children">PENYIMPANAN DAN TATA LETAK</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dan-tata-letak.html">View All PENYIMPANAN DAN TATA LETAK</a>
                            </li>
                            <li  class="level3 nav-10-3-4-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dan-tata-letak/laci-dan-kereta.html" class="level3 ">LACI DAN KERETA</a>
                            </li>
                            <li  class="level3 nav-10-3-4-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dan-tata-letak/rak-dan-unit-penyimpanan.html" class="level3 ">RAK DAN UNIT PENYIMPANAN</a>
                            </li>
                            <li  class="level3 nav-10-3-4-3 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dan-tata-letak/majalah-dan-remote-holder.html" class="level3 ">MAJALAH DAN  REMOTE HOLDER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-3-5 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan.html" class="level2 has-children">LEMARI PENYIMPANAN</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan.html">View All LEMARI PENYIMPANAN</a>
                            </li>
                            <li  class="level3 nav-10-3-5-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan/sistem-lemari-pakaian.html" class="level3 ">SISTEM LEMARI PAKAIAN</a>
                            </li>
                            <li  class="level3 nav-10-3-5-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan/penyimpanan-pakaian.html" class="level3 ">PENYIMPANAN PAKAIAN</a>
                            </li>
                            <li  class="level3 nav-10-3-5-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan/gantungan-inactive.html" class="level3 ">GANTUNGAN (INACTIVE)</a>
                            </li>
                            <li  class="level3 nav-10-3-5-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan/aksesoris-closet.html" class="level3 ">AKSESORIS CLOSET</a>
                            </li>
                            <li  class="level3 nav-10-3-5-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan/perawatan-pakaian.html" class="level3 ">PERAWATAN PAKAIAN</a>
                            </li>
                            <li  class="level3 nav-10-3-5-6 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/lemari-penyimpanan/laci-organizer.html" class="level3 ">LACI ORGANIZER</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-3-6 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait.html" class="level2 has-children">GANTUNGAN DAN PENGAIT</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait.html">View All GANTUNGAN DAN PENGAIT</a>
                            </li>
                            <li  class="level3 nav-10-3-6-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait/gantungan-kayu.html" class="level3 ">GANTUNGAN KAYU</a>
                            </li>
                            <li  class="level3 nav-10-3-6-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait/gantungan-plastik.html" class="level3 ">GANTUNGAN PLASTIK</a>
                            </li>
                            <li  class="level3 nav-10-3-6-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait/gantungan-pintu.html" class="level3 ">GANTUNGAN PINTU</a>
                            </li>
                            <li  class="level3 nav-10-3-6-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait/gantungan-dinding.html" class="level3 ">GANTUNGAN DINDING</a>
                            </li>
                            <li  class="level3 nav-10-3-6-5 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/gantungan-dan-pengait/gantungan-besi.html" class="level3 ">GANTUNGAN BESI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-3-7 last parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi.html" class="level2 has-children">PENYIMPANAN DAPUR DAN KAMAR MANDI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi.html">View All PENYIMPANAN DAPUR DAN KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-10-3-7-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi/penyimpanan-dapur.html" class="level3 ">PENYIMPANAN DAPUR</a>
                            </li>
                            <li  class="level3 nav-10-3-7-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi/penyimpanan-kamar-mandi.html" class="level3 ">PENYIMPANAN KAMAR MANDI</a>
                            </li>
                            <li  class="level3 nav-10-3-7-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi/lemari-penyimpanan.html" class="level3 ">LEMARI PENYIMPANAN</a>
                            </li>
                            <li  class="level3 nav-10-3-7-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi/shower-dan-bathtub-organizer.html" class="level3 ">SHOWER DAN  BATHTUB ORGANIZER</a>
                            </li>
                            <li  class="level3 nav-10-3-7-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi/penyimpanan-kosmetik.html" class="level3 ">PENYIMPANAN KOSMETIK</a>
                            </li>
                            <li  class="level3 nav-10-3-7-6 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-penyimpanan/penyimpanan-dapur-dan-kamar-mandi/penyimpanan-obat.html" class="level3 ">PENYIMPANAN OBAT</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-10-4 parent">
                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah.html" class="level1 has-children">TEMPAT SAMPAH</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/rumah-tangga/tempat-sampah.html">View All TEMPAT SAMPAH</a>
                    </li>
                    <li  class="level2 nav-10-4-1 first parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-dalam-ruang.html" class="level2 has-children">TEMPAT SAMPAH DALAM RUANG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-dalam-ruang.html">View All TEMPAT SAMPAH DALAM RUANG</a>
                            </li>
                            <li  class="level3 nav-10-4-1-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-dalam-ruang/tempat-sampah-kayu-indoor.html" class="level3 ">TEMPAT SAMPAH KAYU INDOOR</a>
                            </li>
                            <li  class="level3 nav-10-4-1-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-dalam-ruang/tempat-sampah-stainless-indoor.html" class="level3 ">TEMPAT SAMPAH STAINLESS INDOOR</a>
                            </li>
                            <li  class="level3 nav-10-4-1-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-dalam-ruang/tempat-sampah-plastik-indoor.html" class="level3 ">TEMPAT SAMPAH PLASTIK INDOOR</a>
                            </li>
                            <li  class="level3 nav-10-4-1-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-dalam-ruang/tempat-sampah-kulit-indoor.html" class="level3 ">TEMPAT SAMPAH KULIT INDOOR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-4-2 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-luar-ruang.html" class="level2 has-children">TEMPAT SAMPAH LUAR RUANG</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-luar-ruang.html">View All TEMPAT SAMPAH LUAR RUANG</a>
                            </li>
                            <li  class="level3 nav-10-4-2-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-luar-ruang/tempat-sampah-kayu-outdoor.html" class="level3 ">TEMPAT SAMPAH KAYU OUTDOOR</a>
                            </li>
                            <li  class="level3 nav-10-4-2-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-luar-ruang/tempat-sampah-stainless-outdoor.html" class="level3 ">TEMPAT SAMPAH STAINLESS OUTDOOR</a>
                            </li>
                            <li  class="level3 nav-10-4-2-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-luar-ruang/tempat-sampah-plastik-outdoor.html" class="level3 ">TEMPAT SAMPAH PLASTIK OUTDOOR</a>
                            </li>
                            <li  class="level3 nav-10-4-2-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/tempat-sampah-luar-ruang/tempat-sampah-kulit-outdoor.html" class="level3 ">TEMPAT SAMPAH KULIT OUTDOOR</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-4-3 last parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/kantong-sampah.html" class="level2 has-children">KANTONG SAMPAH</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/kantong-sampah.html">View All KANTONG SAMPAH</a>
                            </li>
                            <li  class="level3 nav-10-4-3-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/kantong-sampah/kantong-sampah-rumah-tangga.html" class="level3 ">KANTONG SAMPAH RUMAH TANGGA</a>
                            </li>
                            <li  class="level3 nav-10-4-3-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/kantong-sampah/compactor-bags.html" class="level3 ">COMPACTOR BAGS</a>
                            </li>
                            <li  class="level3 nav-10-4-3-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/kantong-sampah/industri-komersial.html" class="level3 ">INDUSTRI/ KOMERSIAL</a>
                            </li>
                            <li  class="level3 nav-10-4-3-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/tempat-sampah/kantong-sampah/aksesoris-tas.html" class="level3 ">AKSESORIS TAS</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li  class="level1 nav-10-5 last parent">
                <a href="https://m.ruparupa.com/rumah-tangga/pembersih.html" class="level1 has-children">PEMBERSIH</a>
                <ul class="level1">
                    <li class="level2 view-all">
                        <a class="level2" href="https://m.ruparupa.com/rumah-tangga/pembersih.html">View All PEMBERSIH</a>
                    </li>
                    <li  class="level2 nav-10-5-1 first parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel.html" class="level2 has-children">ALAT PEL</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel.html">View All ALAT PEL</a>
                            </li>
                            <li  class="level3 nav-10-5-1-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel/gagang-pel.html" class="level3 ">GAGANG PEL</a>
                            </li>
                            <li  class="level3 nav-10-5-1-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel/kemoceng.html" class="level3 ">KEMOCENG</a>
                            </li>
                            <li  class="level3 nav-10-5-1-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel/pel-spons.html" class="level3 ">PEL SPONS</a>
                            </li>
                            <li  class="level3 nav-10-5-1-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel/spons-pengganti.html" class="level3 ">SPONS PENGGANTI</a>
                            </li>
                            <li  class="level3 nav-10-5-1-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel/kemoceng-pengganti.html" class="level3 ">KEMOCENG PENGGANTI</a>
                            </li>
                            <li  class="level3 nav-10-5-1-6 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel/lap-pel.html" class="level3 ">LAP PEL</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-5-2 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/pembersih/sapu-dan-pengki.html" class="level2 has-children">SAPU DAN PENGKI</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/pembersih/sapu-dan-pengki.html">View All SAPU DAN PENGKI</a>
                            </li>
                            <li  class="level3 nav-10-5-2-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/sapu-dan-pengki/sapu-plastik.html" class="level3 ">SAPU PLASTIK</a>
                            </li>
                            <li  class="level3 nav-10-5-2-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/sapu-dan-pengki/sapu-dorong.html" class="level3 ">SAPU DORONG</a>
                            </li>
                            <li  class="level3 nav-10-5-2-3 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/sapu-dan-pengki/pengki.html" class="level3 ">PENGKI</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-5-3 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel-dan-aksesoris.html" class="level2 has-children">ALAT PEL DAN AKSESORIS</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel-dan-aksesoris.html">View All ALAT PEL DAN AKSESORIS</a>
                            </li>
                            <li  class="level3 nav-10-5-3-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel-dan-aksesoris/ember-pel.html" class="level3 ">EMBER PEL</a>
                            </li>
                            <li  class="level3 nav-10-5-3-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel-dan-aksesoris/ember-plastik.html" class="level3 ">EMBER PLASTIK</a>
                            </li>
                            <li  class="level3 nav-10-5-3-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel-dan-aksesoris/peralatan-plastik.html" class="level3 ">PERALATAN PLASTIK</a>
                            </li>
                            <li  class="level3 nav-10-5-3-4 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pel-dan-aksesoris/aksesoris-pel.html" class="level3 ">AKSESORIS PEL</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-5-4 parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih.html" class="level2 has-children">ALAT PEMBERSIH</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih.html">View All ALAT PEMBERSIH</a>
                            </li>
                            <li  class="level3 nav-10-5-4-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/sarung-tangan.html" class="level3 ">SARUNG TANGAN</a>
                            </li>
                            <li  class="level3 nav-10-5-4-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/sikat-gagang.html" class="level3 ">SIKAT GAGANG</a>
                            </li>
                            <li  class="level3 nav-10-5-4-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/spons.html" class="level3 ">SPONS</a>
                            </li>
                            <li  class="level3 nav-10-5-4-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/kuas-lilin.html" class="level3 ">KUAS LILIN</a>
                            </li>
                            <li  class="level3 nav-10-5-4-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/kain-pengkilap.html" class="level3 ">KAIN PENGKILAP</a>
                            </li>
                            <li  class="level3 nav-10-5-4-6">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/pembersih-kaca.html" class="level3 ">PEMBERSIH KACA</a>
                            </li>
                            <li  class="level3 nav-10-5-4-7">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/semprotan.html" class="level3 ">SEMPROTAN</a>
                            </li>
                            <li  class="level3 nav-10-5-4-8">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/dispenser.html" class="level3 ">DISPENSER</a>
                            </li>
                            <li  class="level3 nav-10-5-4-9 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/alat-pembersih/troli-rak.html" class="level3 ">TROLI/RAK</a>
                            </li>
                        </ul>
                    </li>
                    <li  class="level2 nav-10-5-5 last parent">
                        <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia.html" class="level2 has-children">PEMBERSIH KIMIA</a>
                        <ul class="level2">
                            <li class="level3 view-all">
                                <a class="level3" href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia.html">View All PEMBERSIH KIMIA</a>
                            </li>
                            <li  class="level3 nav-10-5-5-1 first">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pembersih-lantai.html" class="level3 ">PEMBERSIH LANTAI</a>
                            </li>
                            <li  class="level3 nav-10-5-5-2">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/perawatan-ubin.html" class="level3 ">PERAWATAN UBIN</a>
                            </li>
                            <li  class="level3 nav-10-5-5-3">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pengkilap-furnitur.html" class="level3 ">PENGKILAP FURNITUR</a>
                            </li>
                            <li  class="level3 nav-10-5-5-4">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pembersih-rumah-tangga.html" class="level3 ">PEMBERSIH RUMAH TANGGA</a>
                            </li>
                            <li  class="level3 nav-10-5-5-5">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pembersih-plastik-kaca.html" class="level3 ">PEMBERSIH PLASTIK/ KACA</a>
                            </li>
                            <li  class="level3 nav-10-5-5-6">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pengharum-ruangan.html" class="level3 ">PENGHARUM RUANGAN</a>
                            </li>
                            <li  class="level3 nav-10-5-5-7">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/tahan-air.html" class="level3 ">TAHAN AIR</a>
                            </li>
                            <li  class="level3 nav-10-5-5-8">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pembersih-kain.html" class="level3 ">PEMBERSIH KAIN</a>
                            </li>
                            <li  class="level3 nav-10-5-5-9">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/detergent.html" class="level3 ">DETERGENT</a>
                            </li>
                            <li  class="level3 nav-10-5-5-10">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pencuci-tangan.html" class="level3 ">PENCUCI TANGAN</a>
                            </li>
                            <li  class="level3 nav-10-5-5-11">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/penyerap-dan-pembersih-minyak.html" class="level3 ">PENYERAP DAN PEMBERSIH MINYAK</a>
                            </li>
                            <li  class="level3 nav-10-5-5-12 last">
                                <a href="https://m.ruparupa.com/rumah-tangga/pembersih/pembersih-kimia/pembersih-sepatu-dan-boot.html" class="level3 ">PEMBERSIH SEPATU DAN BOOT</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li  class="level0 nav-11 last">
        <a href="https://m.ruparupa.com/best-deals.html" class="level0 ">Best Deals</a>
    </li>
</ul>
EOF;


$collection = $model->getCollection()
        ->addFieldToFilter('identifier', 'category-top-menu')
        ->addStoreFilter($store->getId())
        ->addFieldToFilter('store_id', $store->getId());
$cmsBlock = $collection->getFirstItem();

if (!$cmsBlock || !$cmsBlock->getBlockId()) {
    $cmsBlock->setStores(array($store->getId()))->setIdentifier('category-top-menu');
}

$cmsBlock->setContent($content)
    ->setStores(array($store->getId()))
    ->setTitle('Category Top Menu (Mobile)')
    ->save();

$installer->endSetup();
