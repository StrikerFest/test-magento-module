<?php
/*
 * Copyright (c) 2023.  Magento, Inc. All rights reserved.
 */

namespace Tigren\Faq\Block\Adminhtml\Faq\Edit;


use Magento\Framework\UrlInterface;

class GenericButton
{
    public function __construct(
    private UrlInterface $url
){}

    public function getUrl(string $route = '' , array $params = []): string
    {
        return $this->url->getUrl($route,$params);
    }
}
