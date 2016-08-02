<?php
// initialize Magento

$mageInc = "../app/Mage.php";
include_once $mageInc;

Mage::app('default')->loadArea('frontend');

class Mage_Page_Block_Html_Topmenu_Renderer_Generator extends Mage_Page_Block_Html_Topmenu_Renderer {

    public function generateDefault () {
        $this->_addCacheTags();
        $this->setTemplate('page/html/topmenu/renderer.phtml');

        $this->setPackageName('beta');
        $this->setTheme('default');

        $childrenWrapClass = 'sub-cat-wrapper';

        $menuTree = $this->_menu;
        $menuTree->setOutermostClass('level-top');
        $menuTree->setChildrenWrapClass($childrenWrapClass);

        $includeFilePath = realpath(Mage::getBaseDir('design') . DS . 'frontend/beta/default/template/page/html/topmenu/renderer.phtml');
        $this->_templateFile = $includeFilePath;

        return $this->render($menuTree, $childrenWrapClass);

    }

    public function generateMobile () {
        $this->_addCacheTags();
        $this->setTemplate('page/html/topmenu/renderer.phtml');

        $this->setPackageName('beta');
        $this->setTheme('default');

        $childrenWrapClass = 'sub-cat-wrapper';

        $menuTree = $this->_menu;
        $menuTree->setOutermostClass('level-top');
        $menuTree->setChildrenWrapClass($childrenWrapClass);

        $includeFilePath = realpath(Mage::getBaseDir('design') . DS . 'frontend/beta/mobile/template/page/html/topmenu/renderer.phtml');
        $this->_templateFile = $includeFilePath;

        return $this->render($menuTree, $childrenWrapClass);
    }

}
$t = new Mage_Page_Block_Html_Topmenu_Renderer_Generator();
$t->setTemplate('page/html/topmenu/renderer.phtml');

$t->setPackageName('beta');
$t->setTheme('default');
$t->setChild('topMenu', Mage::getSingleton('core/layout')->createBlock('catalog.topnav.renderer'));

$t->getHtml('level-top','sub-cat-wrapper');
$topmenu_html = $t->generateDefault();

$content = <<<UPDATECONTENT
<nav id="nav">
    <ol class="nav-primary" id="pregen">
        $topmenu_html
    </ol>
</nav>
UPDATECONTENT;

// update to cms block
$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$query = "UPDATE cms_block SET content='$content' WHERE identifier='category-top-menu'";
$update = $writeConnection->query($query);
if ($update) {
    echo "category-top-menu updated, please refresh FPC\n";
}
else {
    echo "error updating\n";
}
