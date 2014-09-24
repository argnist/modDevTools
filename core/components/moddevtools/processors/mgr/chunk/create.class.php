<?php
/**
 * Create an Item
 */
class modDeveloperToolsItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'modDeveloperToolsItem';
	public $classKey = 'modDeveloperToolsItem';
	public $languageTopics = array('moddevtools');
	public $permission = 'new_document';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$alreadyExists = $this->modx->getObject('modDeveloperToolsItem', array(
			'name' => $this->getProperty('name'),
		));
		if ($alreadyExists) {
			$this->modx->error->addField('name', $this->modx->lexicon('moddevtools_item_err_ae'));
		}

		return !$this->hasErrors();
	}

}

return 'modDeveloperToolsItemCreateProcessor';