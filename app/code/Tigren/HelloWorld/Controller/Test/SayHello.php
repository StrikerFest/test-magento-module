<?php

namespace Tigren\HelloWorld\Controller\Test;
class SayHello extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    protected $_postFactory;
//    public function __construct(
//        \Magento\Framework\App\Action\Context $context,
//        \Magento\Framework\View\Result\PageFactory $pageFactory,
//        \Tigren\HelloWorld\Model\PostFactory $postFactory
//    )
//    {
//        $this->_pageFactory = $pageFactory;
//        $this->_postFactory = $postFactory;
//        return parent::__construct($context);
//    }
    public function execute()
    {
//        $post = $this->_postFactory->create();
//        $collection = $post->getCollection();
//        foreach ($collection as $item) {
//            echo "<pre>";
//            print_r($item->getData());
//            echo "</pre>";
//        }
        echo 'Hello World! Welcome to Tigren.com';
        exit;
    }
}
