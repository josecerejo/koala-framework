<?php
class Kwc_Directories_List_ViewAjax_ViewController_ContentData extends Kwf_Data_Table
{
    private $_componentId;
    public function __construct($componentId)
    {
        $this->_componentId = $componentId;
        parent::__construct();
    }

    public function load($row)
    {
        $config = array(
            'id' => $row->id,
            'class' => 'Kwf_Component_Partial_Id',
            'params' => array(
                'componentId' => $this->_componentId,
            ),
            'info' => array()
        );
        $renderer = new Kwf_Component_Renderer();
        $renderer->setEnableCache(null);
        $helper = new Kwf_Component_View_Helper_Partials();
        $helper->setRenderer($renderer);
        $ret = $helper->singlePartial($this->_componentId, $config, $row->id);
        return $renderer->render($ret);

    }
}

class Kwc_Directories_List_ViewAjax_ViewController extends Kwf_Controller_Action_Auto_Kwc_Grid
{
    protected $_buttons = array();
    protected $_paging = 25;

    private $_component;
    private $_searchResult;

    protected function _initColumns()
    {
        $c = Kwf_Component_Data_Root::getInstance()->getComponentById($this->_getParam('componentId'));
        if (!$c || $c->componentClass != $this->_getParam('class')) {
            throw new Kwf_Exception_NotFound();
        }
        $this->_component = $c;
        $this->_model = $c->parent->getComponent()->getItemDirectory()->getComponent()->getChildModel();
        $this->_hasComponentId = $this->_model->hasColumn('component_id');

//         $this->_columns->add(new Kwf_Grid_Column('title'));
        $this->_columns->add(new Kwf_Grid_Column('content'))
            ->setData(new Kwc_Directories_List_ViewAjax_ViewController_ContentData($this->_getParam('componentId')));
    }


    protected function _getSelect()
    {
        //$ret = parent::_getSelect(); what do we lose by not using that?
        if ($this->_getParam('filterComponentId')) {
            $filter = Kwf_Component_Data_Root::getInstance()
                ->getComponentById($this->_getParam('filterComponentId'));
            if (!is_instance_of($filter->componentClass, 'Kwc_Directories_List_Component')) {
                $filter = $filter->getChildComponent('-list'); //TODO don't hardcode that here
            }
            $view = $filter->getChildComponent('-view'); //TODO don't hardcode that here
        } else {
            $view = $this->_component;
        }
        $view = $view->getComponent();
        if ($view->hasSearchForm()) {
            $sf = $view->getSearchForm();
            $params = $this->getRequest()->getParams();
            $params[$sf->componentId.'-post'] = true; //post
            $params[$sf->componentId] = true; //submit
            $sf->getComponent()->processInput($params); //TODO don't do processInput here in _getSelect()
        }
        $ret = $view->getSelect();
        return $ret;
    }

    //TODO implement correctly?
    //eventually on a per-detail level
    protected function _isAllowedComponent()
    {
        return true;
    }


    public function jsonDataAction()
    {
        parent::jsonDataAction();
        Kwf_Component_Cache::getInstance()->writeBuffer();
    }
}