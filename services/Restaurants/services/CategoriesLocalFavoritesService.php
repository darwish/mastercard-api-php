<?php

include_once dirname(__FILE__) . '/../../../services/restaurants/domain/categories/Categories.php';
include_once dirname(__FILE__) . '/../../../common/Connector.php';
include_once dirname(__FILE__) . '/../../../common/Environment.php';
include_once dirname(__FILE__) . '/../../../common/Serializer.php';

class CategoriesLocalFavoritesService extends Connector {
    private $environment;

    private $SANDBOX_URL = 'https://sandbox.api.mastercard.com/restaurants/v1/category?Format=XML';
    private $PRODUCTION_URL = 'https://api.mastercard.com/restaurants/v1/category?Format=XML';

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