<?php

class modDevToolsReplaceProcessor extends modProcessor {

    public function process() {

        $props = $this->getProperties();
        /**
         * @var modElement $element
         */
        $element = $this->modx->getObject($props['class'], $props['id']);
        $content = $element->getContent();

        $offset = (int)$this->getProperty('offset', 0);

        $off = 0;
        while (($pos = stripos($content, $props['search'], $offset+$off)) !== false) {
            $content = substr($content, 0, $pos) . $props['replace'] . substr($content, $pos+strlen($props['search']));
            $off = $pos + strlen($props['replace']);
            if (!$props['all']) {
                break;
            }
        }
        $element->setContent($content);
        $element->save();

        $name = $element->get('name');
        if (!isset($name)) {
            $name = $element->get('templatename');
        }
        /* @TODO неправильное выделение после замены */
        $object = array(
            'id' => $element->get('id'),
            'name' => $name,
            'class' => $element->_alias,
            'content' => $this->modx->moddevtools->getContent($content, $props['search'], $props['offset']),
            'offset' => $pos + strlen($props['replace']) + 1,
        );

        return $this->success('', $object);
    }


}

return 'modDevToolsReplaceProcessor';