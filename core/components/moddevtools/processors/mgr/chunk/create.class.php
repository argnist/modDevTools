<?php
/**
 * Create an Item
 */
class modDevToolsItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'modDevToolsItem';
	public $classKey = 'modDevToolsItem';
	public $languageTopics = array('moddevtools');
	public $permission = 'new_document';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$alreadyExists = $this->modx->getObject('modDevToolsItem', array(
			'name' => $this->getProperty('name'),
		));
		if ($alreadyExists) {
			$this->modx->error->addField('name', $this->modx->lexicon('moddevtools_item_err_ae'));
		}

		return !$this->hasErrors();
	}

}

return 'modDevToolsItemCreateProcessor';