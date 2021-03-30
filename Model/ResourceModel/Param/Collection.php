<?php
namespace Ubista\Ubista\Model\ResourceModel\Param;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Param Resource Model Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'ubista_ubista_param_collection';
    protected $_eventObject = 'param_collection';

    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Ubista\Ubista\Model\Param', 'Ubista\Ubista\Model\ResourceModel\Param');
    }

    /**
     * Update Data for given condition for collection
     *
     * @param int|string $limit
     * @param int|string $offset
     * @return array
     */
    public function setTableRecords($condition, $columnData)
    {
        return $this->getConnection()->update(
            $this->getTable('ubista_parameters'),
            $columnData,
            $where = $condition
        );
    }
}
