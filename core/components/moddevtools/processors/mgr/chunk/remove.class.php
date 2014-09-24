<?php
/**
 * Remove an Item
 */
class modDeveloperToolsItemRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'modDeveloperToolsItem';
	public $classKey = 'modDeveloperToolsItem';
	public $languageTopics = array('moddevtools');

}

return 'modDeveloperToolsItemRemoveProcessor';