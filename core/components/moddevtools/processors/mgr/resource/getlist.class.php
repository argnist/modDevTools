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

    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $array = parent::prepareRow($object);
        $allowedFields = array(
            'id' => true,
            'template' => true,
            'pagetitle' => true,
            'parent' => true,
            'published' => true,
            'deleted' => true,
            'menuindex' => true,
            'createdon' => true,
            'publishedon' => true,
            'context_key' => true,
        );
        $array = array_intersect_key($array, $allowedFields);


        $datetime = date_create($array['createdon']);
        $array['createdon'] = date_format($datetime, $this->modx->getOption('manager_date_format') . ' ' . $this->modx->getOption('manager_time_format'));
        $array['preview_url'] = $this->modx->makeUrl($array['id'],$array['context_key']);

        $array['actions'] = array();

        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-pencil-square-o',
            'title' => $this->modx->lexicon('edit'),
            'action' => 'editResource',
            'button' => true,
            'menu' => true,
        );

        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-globe',
            'title' => $this->modx->lexicon('preview'),
            'action' => 'previewResource',
            'button' => true,
            'menu' => true,
        );

        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-wrench',
            'title' => $this->modx->lexicon('moddevtools_template_change'),
            'action' => 'changeTemplate',
            'button' => true,
            'menu' => true,
        );

        if (!$array['published']) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('publish'),
                'action' => 'publishResource',
                'button' => true,
                'menu' => true,
            );
        }
        else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('unpublish'),
                'action' => 'unpublishResource',
                'button' => true,
                'menu' => true,
            );
        }

        if (!$array['deleted']) {
            // Remove
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-trash-o action-red',
                'title' => $this->modx->lexicon('remove'),
                'action' => 'removeResource',
                'button' => true,
                'menu' => true,
            );
        } else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-trash-o action-green',
                'title' => $this->modx->lexicon('undelete'),
                'action' => 'undeleteResource',
                'button' => true,
                'menu' => true,
            );
        }

        return $array;
    }

}

return 'modDevToolsResourceGetListProcessor';