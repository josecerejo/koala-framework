<?php
class Vpc_Root_Category_Trl_Generator extends Vpc_Chained_Trl_Generator
{
    public function getPagesControllerConfig($component)
    {
        $ret = parent::getPagesControllerConfig($component);

        foreach ($ret['actions'] as &$a) $a = false;
        $ret['actions']['properties'] = true;
        $ret['actions']['visible'] = true;

        return $ret;
    }

    public function getChildData($parentData, $select = array())
    {
        $filename = null;
        $limit = null;
        $ignoreVisible = $select->hasPart(Vps_Component_Select::IGNORE_VISIBLE) ?
            $select->getPart(Vps_Component_Select::IGNORE_VISIBLE) : false;
            static $showInvisible;
        if (is_null($showInvisible)) {
            $showInvisible = Vps_Registry::get('config')->showInvisible;
        }
        if ($showInvisible) $ignoreVisible = true;

        // Nach Filename selbst suchen, da ja andere Sprache
        if ($select->hasPart(Vps_Component_Select::WHERE_FILENAME)) {
            $filename = $select->getPart(Vps_Component_Select::WHERE_FILENAME);
            $select->unsetPart(Vps_Component_Select::WHERE_FILENAME);
            if ($select->hasPart(Vps_Component_Select::LIMIT_COUNT)) {
                $limit = $select->getPart(Vps_Component_Select::LIMIT_COUNT);
                $select->unsetPart(Vps_Component_Select::LIMIT_COUNT);
            }
        }
        $select->ignoreVisible();

        $ret = array();
        foreach (parent::getChildData($parentData, $select) as $key => $c) {
            if (($ignoreVisible || $c->visible) &&
                (!$filename || $c->filename == $filename)
            ){
                $ret[$key] = $c;
            }
            if ($limit && count($ret) == $limit) return $ret;
        }
        return $ret;
    }

    protected function _formatConfig($parentData, $row)
    {
        $ret = parent::_formatConfig($parentData, $row);
        $model = Vps_Model_Abstract::getInstance('Vpc_Root_Category_Trl_GeneratorModel');
        $dbRow = $model->getRow($ret['componentId']);
        if (!$dbRow) {
            $dbRow = $model->createRow(array(
                'component_id' => $ret['componentId'],
                'name' => $row->getRow()->name,
                'filename' => $row->getRow()->filename,
                'visible' => $row->isHome,
                'custom_filename' => $row->getRow()->custom_filename
            ));
        }
        $ret['row'] = $dbRow;
        $ret['name'] = $dbRow->name;
        $ret['filename'] = $dbRow->filename;
        $ret['visible'] = $dbRow->visible;
        $ret['tags'] = $row->getRow()->tags;
        $ret['isHome'] = $row->getRow()->is_home;
        return $ret;
    }
/*
    protected function _getDataClass($config, $id)
    {
        if (isset($config['isHome']) && $config['isHome']) {
            return 'Vps_Component_Data_Home';
        } else {
            return parent::_getDataClass($config, $id);
        }
    }
*/
}