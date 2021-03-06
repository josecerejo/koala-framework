<?php
class Kwc_Newsletter_Admin extends Kwc_Directories_Item_Directory_Admin
{
    public function addResources(Kwf_Acl $acl)
    {
        parent::addResources($acl);

        if (!$acl->has('kwc_newsletter')) {
            $acl->add(new Kwf_Acl_Resource_MenuDropdown('kwc_newsletter',
                array('text'=>trlKwf('Newsletter'), 'icon'=>'email_open_image.png')), 'kwf_component_root');
        }

        $icon = Kwc_Abstract::getSetting($this->_class, 'componentIcon');
        $menuConfig = array('icon'=>$icon);

        $components = Kwf_Component_Data_Root::getInstance()
                ->getComponentsBySameClass($this->_class, array('ignoreVisible'=>true));
        foreach ($components as $c) {
            $componentName = Kwc_Abstract::getSetting($this->_class, 'componentName');
            $componentName = Kwf_Trl::getInstance()->trlStaticExecute($componentName);
            $menuConfig['text'] = trlKwf('Edit {0}', $componentName);
            if (count($components) > 1) {
                $subRoot = $c;
                while($subRoot = $subRoot->parent) {
                    if (Kwc_Abstract::getFlag($subRoot->componentClass, 'subroot')) break;
                }
                if ($subRoot) {
                    $menuConfig['text'] .= ' ('.$subRoot->name.')';
                }
            }
            $acl->add(new Kwf_Acl_Resource_Component_MenuUrl($c, $menuConfig), 'kwc_newsletter');
        }
    }
}
