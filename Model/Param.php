<?php

namespace Ubista\Ubista\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Param Model
 */
class Param extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'ubista_ubista_param';

    protected $_cacheTag = 'ubista_ubista_param';

    protected $_eventPrefix = 'ubista_ubista_param';
    /**
     * @var \Magento\Framework\Stdlib\Text
     */
    protected $_name;

    /**
     * @var \Magento\Framework\Stdlib\Text
     */
    protected $_value;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_status;

    protected function _construct()
    {
        $this->_init('Ubista\Ubista\Model\ResourceModel\Param');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }
}
