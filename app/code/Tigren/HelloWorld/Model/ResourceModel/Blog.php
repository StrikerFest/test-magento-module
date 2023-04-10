<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\HelloWorld\Model\ResourceModel;
class Blog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct()
    {
        $this->_init('Tigren_blog', 'blog_id');
    }
}
