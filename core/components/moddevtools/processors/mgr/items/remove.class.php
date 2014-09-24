<?php
/**
 * Remove an Items
 */
class modDeveloperToolsItemsRemoveProcessor extends modProcessor {
    public $checkRemovePermission = true;
	public $objectType = 'modDeveloperToolsItem';
	public $classKey = 'modDeveloperToolsItem';
	public $languageTopics = array('moddevtools');

	public function process() {

        foreach (explode(',',$this->getProperty('items')) as $id) {
            $item = $this->modx->getObject($this->classKey, $id);
            $item->remove();
        }
        
        return $this->success();

	}

}

return 'modDeveloperToolsItemsRemoveProcessor';