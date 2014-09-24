<?php
/**
 * Update an Item
 */
class modDeveloperToolsItemUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'modDeveloperToolsItem';
	public $classKey = 'modDeveloperToolsItem';
	public $languageTopics = array('moddevtools');
	public $permission = 'edit_document';
}

return 'modDeveloperToolsItemUpdateProcessor';
