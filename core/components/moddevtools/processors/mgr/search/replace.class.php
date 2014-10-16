<?php

include_once MODX_CORE_PATH . 'model/modx/processors/element/update.class.php';
class modDevToolsReplaceProcessor extends modElementUpdateProcessor {

    public $nameField = 'name';
    public $contentField = 'content';

    public function run() {
        $this->classKey = $this->getProperty('class');
        switch ($this->classKey) {
            case 'modChunk':
                $this->object = 'chunk';
                $this->permission = 'save_chunk';
                $this->contentField = 'snippet';
                break;
            case 'modTemplate':
                $this->object = 'template';
                $this->permission = 'save_template';
                $this->nameField = 'templatename';
                break;
            default: return false; break;
        }

        return parent::run();
    }

    public function beforeSet() {
        $props = $this->getProperties();

        $content = $this->object->getContent();

        if ($props['all']) {
            $content = str_replace($props['search'], $props['replace'], $content);
            $offset = 0;
        } else {
            $offset = (int)$this->getProperty('offset', 0);
            $offsetString = substr($content, 0, $offset);
            $newContent = substr($content, $offset);
            $strings = explode($props['search'], $newContent, 2);
            $newContent = implode($props['replace'], $strings);

            if (strpos($strings[1], $props['search']) === false) {
                $offset = 0;
            } else {
                $offset = $offset + strlen($strings[0]) + strlen($props['replace']);
            }
            $content = $offsetString . $newContent;
        }
        $this->setProperty($this->contentField, $content);
        $this->setProperty($this->nameField, $this->object->get($this->nameField));
        $this->setProperty('offset', $offset);

        return parent::beforeSet();
    }

    public function cleanup() {
        $object = array(
            'id' => $this->object->get('id'),
            'name' => $this->object->get($this->nameField),
            'class' => $this->classKey,
            'content' => $this->modx->moddevtools->getSearchContent(
                $this->object->get($this->contentField),
                $this->getProperty('search'),
                $this->getProperty('offset')),
            'offset' => $this->getProperty('offset'),
        );

        return $this->success('', $object);
    }

}

return 'modDevToolsReplaceProcessor';