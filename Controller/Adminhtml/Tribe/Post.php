<?php
namespace Ubista\Ubista\Controller\Adminhtml\Tribe;

use Magento\Backend\App\Action\Context;
use Ubista\Ubista\Controller\Adminhtml\Tribe\ApiController;
use Ubista\Ubista\Model;

class Post extends ApiController
{
    public $_paramFactory;
    protected $_resultJsonFactory;
    protected $_context;

    public function __construct(
        Context $context,
        \Ubista\Ubista\Model\ParamFactory $paramFactory
    ) {
        $this->_paramFactory = $paramFactory;
        $this->_context = $context;
        return parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        if (!$post) {
            $this->_redirect('*/*/');
            return;
        }
        try {
            if (isset($post['submitFormSettings'])) {
                if (isset($post['checkboxValue'])) {
                    $this->updateParamValue('1');
                } else {
                    $this->updateParamValue('0');
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('We can\'t process your request right now.')
            );
            $this->_redirect('ubista/tribe/index');
            return;
        }
        $this->_redirect('ubista/tribe/index');
        return;
    }

    /**
     * @description : Fonction de mise à jour en bdd du param
     */
    public function updateParamValue($value)
    {
        $param = $this->_paramFactory->create();
        $condition = "`id`= 1";
        $param = $this->_paramFactory->create();
        $param->getCollection()
            ->setTableRecords(
                $condition, ['value' => $value]
            );
    }

    public function paramObject()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager->create('Ubista\Ubista\Model\Param');
    }

    protected function _isAllowed()
    {
        return true;
    }
}
