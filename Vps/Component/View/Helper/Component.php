<?php
class Vps_Component_View_Helper_Component extends Vps_Component_View_Renderer
{
    public function component(Vps_Component_Data $component = null)
    {
        if (!$component) return '';

        $renderer = $this->_getRenderer();
        $config = array();
        $type = 'component';

        if ($renderer instanceof Vps_Component_Renderer_Mail) {
            $type = 'mail';
            $config = array(
                'type' => $renderer->getRenderFormat()
            );
            $plugins = array();
        } else {
            $plugins = $component->getPlugins(); // Plugins werden bei Mail nicht ausgeführt, weil die manuell durchgegangen werden, damit der Recipient übergeben werden kann
        }
        return $this->_getRenderPlaceholder(
            $component->componentId, $config, null, $type, $plugins
        );
    }

    public function render($componentId, $config)
    {
        $component = $this->_getComponentById($componentId);
        $template = Vpc_Abstract::getTemplateFile($component->componentClass);
        if (!$template) throw new Vps_Exception("No Component-Template found for '{$component->componentClass}'");

        $vars = $component->getComponent()->getTemplateVars();
        if (is_null($vars)) throw new Vps_Exception('Return value of getTemplateVars() returns null. Maybe forgot "return $ret?"');

        $view = new Vps_Component_View($this->_getRenderer());
        $view->assign($vars);
        return $view->render($template);
    }
}