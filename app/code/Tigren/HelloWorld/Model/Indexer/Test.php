<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\HelloWorld\Model\Indexer;
class Test implements \Magento\Framework\Indexer\ActionInterface,
    \Magento\Framework\Mview\ActionInterface
{
    /*
    * Used by mview, allows process indexer in the "Update on schedule" mode  */
    public function execute($ids){}
    /*
    * Will take all of the data and reindex
    * Will run when reindex via command line
    */
    public function executeFull(){}
    /*
    * Works with a set of entity changed (may be massaction)
    */
    public function executeList(array $ids){}
    /*
    * Works in runtime for a single entity using plugins
    */
    public function executeRow($id){}
}
