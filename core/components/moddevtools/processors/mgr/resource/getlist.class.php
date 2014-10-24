<?php
/**
 * Get a list of Resource by template
 */
include_once MODX_CORE_PATH . 'model/modx/processors/resource/getlist.class.php';

class modDevToolsResourceGetListProcessor extends modResourceGetListProcessor{

    /**
     * @param string $link
     * @param bool|string $nested
     * @return array
     */
    public function getParentsByLink($link, $nested = false) {
        $c = $this->modx->newQuery('modDevToolsLink');
        if ($nested) {
            $c->innerJoin('modDevToolsLink', 'Link', 'Link.parent=modDevToolsLink.child');
            $c->where(array(
                'modDevToolsLink.link_type' => $nested,
            ));
            $child = 'Link.';
            $parent = 'modDevToolsLink.';
        } else {
            $child = '';
            $parent = '';
        }
        $c->where(array(
            $child.'link_type' => $link,
            $child.'child' => $this->getProperty('id'),
        ));
        $c->select($parent.'parent');
        if ($c->prepare() && $c->stmt->execute()) {
            return $c->stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            return array();
        }
    }

	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        if ($linkType = $this->getProperty('link_type', false)) {
            $templates = array();
            $resources = array();

            if ($linkType === 'res-chunk') {
                $templates = $this->getParentsByLink('temp-chunk');
                $templates = array_merge($templates, $this->getParentsByLink('chunk-chunk', 'temp-chunk'));
                $resources = $this->getParentsByLink('res-chunk');
            } else if ($linkType === 'res-snip') {
                $templates = $this->getParentsByLink('temp-snip');
                $templates = array_merge($templates, $this->getParentsByLink('chunk-snip', 'temp-chunk'));
                $resources = $this->getParentsByLink('res-snip');
            }

            $templates = array_unique($templates);
            $tempCount = count($templates);
            $resCount = count($resources);

            if (($tempCount == 0) && ($resCount == 0)) {
                $c->where(array(
                    'id' => 0
                ));
            } else {
                if ($tempCount > 0) {
                    $c->where(array(
                        'template:IN' => $templates
                    ));
                }
                if ($resCount > 0) {
                    $c->orCondition(array(
                        'id:IN' => $resources
                    ));
                }
            }
        } else {
            $c->where(array(
                'template' => $this->getProperty('id'),
            ));
        }

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

        if ($this->modx->hasPermission('edit_document')) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-pencil-square-o',
                'title' => $this->modx->lexicon('edit'),
                'action' => 'editResource',
                'button' => true,
                'menu' => true,
            );
        }

        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-globe',
            'title' => $this->modx->lexicon('preview'),
            'action' => 'previewResource',
            'button' => true,
            'menu' => true,
        );

        if ($this->modx->hasPermission('save_document')) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-wrench',
                'title' => $this->modx->lexicon('moddevtools_template_change'),
                'action' => 'changeTemplate',
                'button' => true,
                'menu' => true,
            );
        }

        if ($this->modx->hasPermission('publish_document')) {
            if (!$array['published']) {
                $array['actions'][] = array(
                    'cls' => '',
                    'icon' => 'icon icon-power-off action-green',
                    'title' => $this->modx->lexicon('publish'),
                    'action' => 'publishResource',
                    'button' => true,
                    'menu' => true,
                );
            } else {
                $array['actions'][] = array(
                    'cls' => '',
                    'icon' => 'icon icon-power-off action-gray',
                    'title' => $this->modx->lexicon('unpublish'),
                    'action' => 'unpublishResource',
                    'button' => true,
                    'menu' => true,
                );
            }
        }

        if ($this->modx->hasPermission('delete_document')) {
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
        }

        return $array;
    }

}

return 'modDevToolsResourceGetListProcessor';