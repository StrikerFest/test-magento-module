<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    protected $_faqFactory;
    public function __construct(\Tigren\Faq\Model\FaqFactory
    $faqFactory) {
        $this->_faqFactory = $faqFactory;
    }
    public function install(ModuleDataSetupInterface $setup,
        ModuleContextInterface $context)
    {
        $data = [
            'question' => "why are you selling",
            'author' => "James Smith",
            'answer' => "why are you buying",
            'status' => "answered",
            'product' => 1,
            'position' => 1

        ];
        $faq = $this->_faqFactory->create();
        $faq->addData($data)->save();
    }
}
