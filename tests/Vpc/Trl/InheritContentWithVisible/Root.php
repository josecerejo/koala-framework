<?php
class Vpc_Trl_InheritContentWithVisible_Root extends Vpc_Root_Abstract
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        unset($ret['generators']['box']);
        unset($ret['generators']['title']);
        $ret['childModel'] = new Vps_Model_FnF(array(
            'toStringField' => 'name',
            'data' => array(
                array('id'=>'1', 'filename'=>'de', 'name'=>'de', 'master'=>true),
                array('id'=>'2', 'filename'=>'en', 'name'=>'en', 'master'=>false),
            )
        ));
        $ret['generators']['de'] = array(
            'class' => 'Vpc_Root_TrlRoot_MasterGenerator',
            'component' => 'Vpc_Trl_InheritContentWithVisible_German',
            'name' => 'de'
        );
        $ret['generators']['en'] = array(
            'class' => 'Vpc_Root_TrlRoot_ChainedGenerator',
            'component' => 'Vpc_Trl_InheritContentWithVisible_English.Vpc_Trl_InheritContentWithVisible_German',
            'name' => 'en'
        );
        return $ret;
    }
}