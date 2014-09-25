<?php
/**
 * Remove an Item
 */
class modDevToolsItemRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'modDevToolsItem';
	public $classKey = 'modDevToolsItem';
	public $languageTopics = array('moddevtools');

}

return 'modDevToolsItemRemoveProcessor';