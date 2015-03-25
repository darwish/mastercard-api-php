<?php

require_once('../../../services/location/domain/categories/Categories.class.php');
require_once('../../../common/Connector.php');
require_once('../../../common/Environment.php');
require_once('../../../common/Serializer.php');

class MerchantCategoriesService extends Connector {
    private $environment;

    private $PRODUCTION_URL = "https://api.mastercard.com/merchants/v1/category?Format=XML";
    private $SANDBOX_URL = "https://sandbox.api.mastercard.com/merchants/v1/category?Format=XML";

    public function __construct($consumerKey, $privateKey, $environment)
    {
        parent::__construct($consumerKey, $privateKey);
        $this->environment = $environment;
    }

    public function getCategories()
    {
        $response = parent::doSimpleRequest($this->getURL(),Connector::GET);
        $xml = simplexml_load_string($response);
        return $this->buildCategories($xml);
    }

    private function getURL()
    {
        if ($this->environment == Environment::PRODUCTION)
        {
            return $this->PRODUCTION_URL;
        }
        else
        {
            return $this->SANDBOX_URL;
        }
    }

    private function buildCategories($xml)
    {
        $categories = new Categories();
        $categoryArray = array();
        foreach($xml->Category as $category)
        {
            array_push($categoryArray, (string) $category);
        }
        $categories->setCategory($categoryArray);
        return $categories;
    }

} 