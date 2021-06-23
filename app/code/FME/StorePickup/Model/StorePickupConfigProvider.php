<?php
namespace FME\StorePickup\Model;

use FME\StorePickup\Helper\Data as StoresData;
use Magento\Catalog\Model\ProductFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Checkout\Model\Session as SessionModel;

class StorePickupConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface {

    /**
     * @var StoresData
     */
    private $_inStoreDataHelper;

    /**
     * @var CartRepositoryInterface
     */
    protected $_cartRepositoryInterface;

    /**
     * @var InStoreShopFactory
     */
    private $_inStoreShopFactory;

    /**
     * @var ProductFactory
     */
    private $_productFactory;

    /**
     * @var SessionModel
     */
    private $_sessionModel;

    /**
     * InStoreConfigProvider constructor.
     * @param StoresData $inStoreHelper
     * @param ProductFactory $productFactory
     * @param InStoreShopFactory $inStoreShopFactory
     * @param CartRepositoryInterface $cartRepositoryInterface
     * @param SessionModel $session
     */
    public function __construct(
        StoresData $inStoreHelper,
        ProductFactory $productFactory,
        CartRepositoryInterface $cartRepositoryInterface,
        SessionModel $session
    ) {
        $this->_inStoreDataHelper = $inStoreHelper;
        $this->_productFactory = $productFactory;
        $this->_cartRepositoryInterface = $cartRepositoryInterface;
        $this->_sessionModel = $session;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            "fmeStorePickUp"        =>  \FME\StorePickup\Model\Carrier\StorePickup::FME_STORE_PICKUP_CODE,
            "fmeStorePickUpPickupTime"    =>  [
                ''
            ],
            "allStorePickUpLocations"     =>  $this->getStores(),
            "storePickUpList"          =>  $this->getInStoresList(),
            "getfirstone" =>  $this->_inStoreDataHelper->getFirstStore(),
            'shipping' => [
                'pickup_date' => [
                    'format' => 'yy-mm-dd',
                    'disabled' => '-1',
                    'noday' => 0,
                    'hourMin' => 8,
                    'hourMax' => 22
                ]
            ]
         
        ];
    }



    /**
     * @return array
     */
    private function getStores() {
        return StoresData::getStoresList();
    }

    /**
     * @return array
     */
    private function getInStoresList() {
        return $this->_inStoreDataHelper->getInStoreList();
    }

}
