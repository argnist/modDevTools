<?php

class modDevToolsReplaceProcessor extends modProcessor {

    public function process() {

        $props = $this->getProperties();
        /**
         * @var modElement $element
         */
        $element = $this->modx->getObject($props['class'], $props['id']);
        $content = $element->getContent();

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

        $element->setContent($content);
        $element->save();

        $name = $element->get('name');
        if (!isset($name)) {
            $name = $element->get('templatename');
        }
        $object = array(
            'id' => $element->get('id'),
            'name' => $name,
            'class' => $element->_alias,
            'content' => $this->modx->moddevtools->getSearchContent($content, $props['search'], $offset),
            'offset' => $offset,
        );

        return $this->success('', $object);
    }


}

return 'modDevToolsReplaceProcessor';