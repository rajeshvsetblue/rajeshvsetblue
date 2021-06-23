<?php 

namespace FME\StorePickup\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
class Ajax extends Action {
protected $request;

	public function execute() {
	    $data = array();
	    $query = $this->getRequest()->getParam('store_id'); 
	    $this->_view->loadLayout();
	    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
	     $data[] = $this->_view->getLayout()
                     ->createBlock("FME\StorePickup\Block\Ajax")
                     ->setTemplate("FME_StorePickup::ajax.phtml")
                     ->setData('store_id', $query)
                     ->toHtml();
	    $resultJson->setData($data);
	    return $resultJson;

	}
}