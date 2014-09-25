<?php
/**
 * Remove an Items
 */
class modDevToolsItemsRemoveProcessor extends modProcessor {
    public $checkRemovePermission = true;
	public $objectType = 'modDevToolsItem';
	public $classKey = 'modDevToolsItem';
	public $languageTopics = array('moddevtools');

	public function process() {

        foreach (explode(',',$this->getProperty('items')) as $id) {
            $item = $this->modx->getObject($this->classKey, $id);
            $item->remove();
        }
        
        return $this->success();

	}

}

return 'modDevToolsItemsRemoveProcessor';