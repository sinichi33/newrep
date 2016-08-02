<?php
/*
 * - Mobile: footer
 */
$installer = $this;
$installer->startSetup();

/* Category Top Menu */
$cmsBlock = Mage::getModel('cms/block')->load('m-footer', 'identifier');

$content =<<<EOF
<div class="call-center">
    <table>
        <tbody>
            <tr>
                <td>
                    <img src="{{skin url="images/sample/footer/logo-callcenter.png"}}" alt="Call Center" />
                </td>
                <td>
                    <p>Call Center<br/>
                    <strong>(021) 5552234</strong></p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="footer-link">
    <ul>
        <li>
            <a href="{{store url="about-us"}}">Info Ruparupa</a>
        </li>
        <li>
            <a href="{{store url="faq"}}">Layanan Konsumen</a>
        </li>
    </ul>
</div>

EOF;

$cmsBlock->setStores(array(0))
        ->setTitle('Mobile Footer')
        ->setIdentifier('m-footer')
        ->setContent($content)
        ->setIsActive(1)
        ->save();


$installer->endSetup();
