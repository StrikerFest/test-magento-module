<?php
/*
 * Copyright (c) 2023.  Magento, Inc. All rights reserved.
 */

namespace Tigren\Faq\Block\Adminhtml\Faq\Edit;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        // TODO: Implement getButtonData() method.
        return[
            'label' => __('Back'),
            'onclick' => sprintf("location.href = '%s'", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10,
        ];
    }

    private function getBackUrl(): string
    {
        return $this->getUrl('*/');
    }
}
