<?php
/**
 * Get a list of Items
 */
class modDevToolsSnippetGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'modSnippet';
	public $classKey = 'modSnippet';
	public $defaultSortField = 'modSnippet.name';
	public $defaultSortDirection = 'ASC';
	public $renderers = '';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modDevToolsLink','Link','modSnippet.id=Link.child');
        $c->where(array(
            'Link.link_type' => $this->getProperty('link_type'),
            'Link.parent' => $this->getProperty('parent'),
        ));
		return $c;
	}

}

return 'modDevToolsSnippetGetListProcessor';