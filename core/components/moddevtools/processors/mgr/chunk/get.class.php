<?php
/**
 * Get an Item
 */
class modDevToolsItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'modDevToolsItem';
	public $classKey = 'modDevToolsItem';
	public $languageTopics = array('moddevtools:default');
}

return 'modDevToolsItemGetProcessor';