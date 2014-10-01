<?php
/**
 * Get a list of Items
 */
class modDevToolsTemplateGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'modTemplate';
	public $classKey = 'modTemplate';
	public $defaultSortField = 'modTemplate.templatename';
	public $defaultSortDirection = 'ASC';
	public $renderers = '';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modDevToolsLink','Link','modTemplate.id=Link.parent');
        $c->where(array(
            'Link.link_type' => $this->getProperty('link_type'),
            'Link.child' => $this->getProperty('child'),
        ));
		return $c;
	}

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $array = array(
            'id' => $object->get('id'),
            'name' => $object->get('templatename'),
            'snippet' => $object->get('content'),
        );
        return $array;
    }

}

return 'modDevToolsTemplateGetListProcessor';