<?php
/**
 * Get a list of Resource by template
 */
include_once MODX_CORE_PATH . 'model/modx/processors/resource/getlist.class.php';

class modDevToolsResourceGetListProcessor extends modResourceGetListProcessor{

	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->where(array(
            'template' => $this->getProperty('template'),
        ));
		return $c;
	}

}

return 'modDevToolsResourceGetListProcessor';