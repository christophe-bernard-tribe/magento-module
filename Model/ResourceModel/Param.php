<?php
namespace Ubista\Ubista\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Param Resource Model
 */
class Param extends AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        // format _init('{nom_de_la_table}', '{clé_primaire')
        $this->_init('ubista_parameters', 'id');
    }
}
