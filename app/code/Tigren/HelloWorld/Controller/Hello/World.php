<?php

namespace Tigren\Example\Controller\Hello;

class World extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(array('text' => 'Tigren'));
        $this->_eventManager->dispatch('example_hello_world_display_text', ['text' => $textDisplay]);
        echo $textDisplay->getText();
        exit;
    }
}
