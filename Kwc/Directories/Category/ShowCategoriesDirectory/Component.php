<?php
class Kwc_Directories_Category_ShowCategoriesDirectory_Component extends Kwc_Directories_Category_ShowCategories_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['generators']['detail'] = array(
            'class' => 'Kwf_Component_Generator_Table',
            'component' => 'Kwc_Directories_Item_Detail_Component'
        );
        $ret['useDirectorySelect'] = false;
        return $ret;
    }
    protected function _getItemDirectory()
    {
        return $this->getData();
    }
}