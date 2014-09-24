<?php
/**
 * Get a list of Items
 */
class modDevToolsChunkGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'modChunk';
	public $classKey = 'modChunk';
	public $defaultSortField = 'modChunk.name';
	public $defaultSortDirection = 'ASC';
	public $renderers = '';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modDevToolsLink','Link','modChunk.id=Link.child');
        $c->where(array(
            'Link.link_type' => $this->getProperty('link_type'),
            'Link.parent' => $this->getProperty('parent'),
        ));
		return $c;
	}

}

return 'modDevToolsChunkGetListProcessor';