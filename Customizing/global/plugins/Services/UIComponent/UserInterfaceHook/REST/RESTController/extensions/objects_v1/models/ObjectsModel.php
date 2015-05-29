<?php
/**
 * ILIAS REST Plugin for the ILIAS LMS
 *
 * Authors: D.Schaefer, S.Schneider and T. Hufschmidt <(schaefer|schneider|hufschmidt)@hrz.uni-marburg.de>
 * 2014-2015
 */
namespace RESTController\extensions\objects_v1;


class ObjectsModel {
    public function getObject($ref) {
        $a_ref_id = $ref;
        if(!is_numeric($a_ref_id))
            throw new \Exception('ref_id needs to be numeric.');

        $tmp_obj = \ilObjectFactory::getInstanceByRefId($a_ref_id,false)
        if(!$tmp_obj)
            throw new \Exception('Can\'t create Object');

        if(\ilObject::_isInTrash($a_ref_id))
            throw new \Exception('Object has been deleted');

        // Now, $tmp_obj contains *way* to much data to pass it back to the client.
        // In function __appendObject(&$object) in
        // webservice/soap/classes/class.ilObjectXMLWriter.php
        // the cherry-picking is done for relevant attributes, copied here.

        $result = array(
            'title' => $tmp_obj->getTitle(),
            'desc' => $tmp_obj->getDescription(),
            'owner' => $tmp_obj->getOwner(),
            'createDate' => $tmp_obj->getCreateDate(),
            'lastUpdate' => $tmp_obj->getLastUpdateDate(),
            'importId' => $tmp_obj->getImportId()
        );
    }
}
