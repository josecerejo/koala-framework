<?php
class Kwf_Component_Generator_GetComponentByClass_Root extends Kwf_Component_NoCategoriesRoot
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['generators']['page']['model'] = new Kwf_Model_FnF(array('data'=>array(
            array('id'=>1, 'pos'=>1, 'visible'=>true, 'name'=>'Home', 'filename' => 'home',
                  'parent_id'=>'root', 'component'=>'table', 'is_home'=>true, 'category' =>'main', 'hide'=>false),
        )));
        $ret['generators']['page']['component'] = array('table' => 'Kwf_Component_Generator_GetComponentByClass_Table');
        $ret['generators']['box']['component'] = array();
        return $ret;
    }
}
