<?php
namespace Ubista\Ubista\Block;

use Magento\Cms\Block\Page;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Param extends Template
{
    protected $_paramFactory;
    protected $_page;

    public function __construct(
        Context $context,
        Page $page,
        \Ubista\Ubista\Model\ParamFactory $paramFactory
    ) {
        $this->_paramFactory = $paramFactory;
        $this->_page = $page;
        parent::__construct($context);
    }

    /**
     * Récupère la collection de la table Param
     */
    public function getParamCollection()
    {
        $param = $this->_paramFactory->create();
        return $param->getCollection();
    }

    /**
     * Récupère l'id de la catégorie courante (première) du produit affiché
     * @return int : Id de la première catégorie
     */
    public function getCategoryId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');

        //echo $product->getId();
        $categories = $product->getCategoryIds();

        //foreach ($categories as $category) {
        $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($categories[0]);
        return $cat->getId();
        //}
    }
}
