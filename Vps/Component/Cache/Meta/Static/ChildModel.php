<?php
/**
 * Wenn eine Row des Childmodels geändert wird, wird der Komponentencache mit dem
 * Wert der Spalte component_id gelöscht
 */
class Vps_Component_Cache_Meta_Static_ChildModel extends Vps_Component_Cache_Meta_Static_OwnModel
{
    public function getModelname($componentClass)
    {
        return Vpc_Abstract::getSetting($componentClass, 'childModel');
    }
}