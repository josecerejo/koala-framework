<?php
/**
 * @group Vpc_Basic_LinkTagParentPage
 **/
class Vpc_Basic_LinkTagParentPage_Test extends PHPUnit_Framework_TestCase
{
    private $_root;

    public function setUp()
    {
        Vps_Component_Data_Root::setComponentClass('Vpc_Basic_LinkTagParentPage_Root');
        $this->_root = Vps_Component_Data_Root::getInstance();
    }

    public function testUrlAndRel()
    {
        $c = $this->_root->getComponentById(1402); // linkt auf 1400
        $this->assertEquals('/foo1', $c->url);
        $this->assertEquals('', $c->rel);

        $c = $this->_root->getComponentById(1401); // ist hauptseite und kann nicht nach oben linken
        $this->assertEquals('', $c->url);
        $this->assertEquals('', $c->rel);
    }

    public function testHtml()
    {
        $output = new Vps_Component_Output_NoCache();
        $html = $output->render($this->_root->getComponentById(1402));
        $this->assertEquals('<a href="/foo1">', $html);
    }
}