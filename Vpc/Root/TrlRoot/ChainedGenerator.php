<?php
class Vpc_Root_TrlRoot_ChainedGenerator extends Vps_Component_Generator_PseudoPage_Table
{
    protected $_idColumn = 'filename';
    protected $_hasNumericIds = false;
    protected $_inherits = true;
    public function getPagesControllerConfig($component, $generatorClass = null)
    {
        $ret = parent::getPagesControllerConfig($component, $generatorClass);
        $ret['icon'] = 'font';
        return $ret;
    }
}