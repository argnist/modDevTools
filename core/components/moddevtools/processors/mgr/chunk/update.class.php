<?php
/**
 * Update an Item
 */
class modDevToolsItemUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'modDevToolsItem';
	public $classKey = 'modDevToolsItem';
	public $languageTopics = array('moddevtools');
	public $permission = 'edit_document';
}

return 'modDevToolsItemUpdateProcessor';
