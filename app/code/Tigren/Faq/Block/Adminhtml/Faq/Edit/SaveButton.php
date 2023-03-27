<?php
/*
 * Copyright (c) 2023.  Magento, Inc. All rights reserved.
 */

namespace Tigren\Faq\Block\Adminhtml\Faq\Edit;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        // TODO: Implement getButtonData() method.
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'sort_order' => 20,
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save'
            ],
        ];

    }
}
