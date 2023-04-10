<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\HelloWorld\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
class UpgradeData implements UpgradeDataInterface
{
    protected $_blogFactory;
    public function __construct(\Tigren\HelloWorld\Model\BlogFactory $blogFactory)
    {
        $this->_blogFactory = $blogFactory;
    }
    public function upgrade( ModuleDataSetupInterface $setup,
        ModuleContextInterface $context ) {
//        if ( version_compare($context->getVersion(), '1.0.1', '<' )) {
            $data = [
                'title' => "Sample title 2",
                'content' => "Sample content 2"
            ];
            $blog = $this->_blogFactory->create();
            $blog->addData($data)->save();
//        }
    }
}
