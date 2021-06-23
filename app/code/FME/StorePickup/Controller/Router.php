<?php
namespace FME\StorePickup\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    
    protected $actionFactory;
    protected $_response;
    protected $_request;
    protected $pageRepository;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\RequestInterface $request,
        \FME\StorePickup\Helper\Data $helper,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \FME\StorePickup\Model\StorePickupFactory $storeLocatorFactory,
        \FME\StorePickup\Model\TagsFactory $tagsFactory,
        \Magento\Framework\App\ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->_request = $request;
        $this->storeLocatorFactory = $storeLocatorFactory;
        $this->pageRepository = $pageRepository;
        $this->tagsFactory = $tagsFactory;
        $this->_response = $response;
        $this->_storeManager = $storeManager;
        $this->storePickupHelper = $helper;
    }
    
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
            $route = $this->storePickupHelper->getGMapSeoIdentifier();
            $tagRoute = $this->storePickupHelper->getGMapTagUrl();
            $suffix = $this->storePickupHelper->getGMapSeoSuffix();
            $identifier = trim($request->getPathInfo(), '/');
   
        if($this->storePickupHelper->isEnabledInFrontend()):
            $oldIdentifier=$identifier;
            $identifier = str_replace($suffix, '', $identifier);
            $storeLocator = $this->storeLocatorFactory->create();

            if (strcmp($route, $identifier)==0) {
                $request->setModuleName('storepickup')->setControllerName('index')->setActionName('index');
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $oldIdentifier);
                return $this->actionFactory->create(
                'Magento\Framework\App\Action\Forward',
                ['request' => $request]
            );

            } 
           
            $storeLocatorId = $storeLocator->checkUrlKey($identifier, $this->_storeManager->getStore()->getId()); 
            if ($storeLocatorId) {
                $request->setModuleName('storepickup')->setControllerName('index')->setActionName('detail')->setParam('id', $storeLocatorId);
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $oldIdentifier);
                return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
            }
                
            $tags = $this->tagsFactory->create();
            $routePath = explode('/', $identifier);
            if($routePath[0]==$tagRoute):
                $tagName = str_replace('-', ' ', $routePath[1]);
            $tagId = $tags->checkUrlKey($tagName); 
            if ($tagId) {
                $request->setModuleName('storepickup')->setControllerName('index')->setActionName('tags')->setParam('id', $tagId);
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $oldIdentifier);
                return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
            }
            endif;

        endif;
        


    }
}
