<?php

include_once dirname(__FILE__) . '/../../../services/restaurants/domain/options/CountrySubdivisionsLocalFavoritesRequestOptions.php';
include_once dirname(__FILE__) . '/../../../services/restaurants/domain/country_subdivisions/CountrySubdivisions.php';
include_once dirname(__FILE__) . '/../../../services/restaurants/domain/country_subdivisions/CountrySubdivision.php';
include_once dirname(__FILE__) . '/../../../common/Connector.php';
include_once dirname(__FILE__) . '/../../../common/Environment.php';
include_once dirname(__FILE__) . '/../../../common/Serializer.php';
include_once dirname(__FILE__) . '/../../../common/URLUtil.php';

class CountrySubdivisionsLocalFavoritesService extends Connector {

    private $environment;

    private $SANDBOX_URL = 'https://sandbox.api.mastercard.com/restaurants/v1/countrysubdivision?Format=XML';
    private $PRODUCTION_URL = 'https://api.mastercard.com/restaurants/v1/countrysubdivision?Format=XML';

    public function __construct($consumerKey, $privateKey, $environment)
    {
        parent::__construct($consumerKey, $privateKey);
        $this->environment = $environment;
    }

    public function getCountrySubdivisions(CountrySubdivisionsLocalFavoritesRequestOptions $options)
    {
        $response = parent::doSimpleRequest($this->getURL($options),Connector::GET);
        $xml = simplexml_load_string($response);
        return $this->buildCountrySubdivisions($xml);
    }

    private function getURL(CountrySubdivisionsLocalFavoritesRequestOptions $options)
    {
        $url = "";

        if ($this->environment == Environment::PRODUCTION)
        {
            $url = $this->PRODUCTION_URL;
        }
        else
        {
            $url = $this->SANDBOX_URL;
        }

        $url = URLUtil::addQueryParameter($url, "Country", $options->getCountry());
        return $url;
    }

    private function buildCountrySubdivisions($xml)
    {
        $countrySubdivisions = new CountrySubdivisions();
        $countrySubdivisionArray = array();
        foreach($xml->CountrySubdivision as $country)
        {
            $tmpCountrySubdivision = new CountrySubdivision();
            $tmpCountrySubdivision->setCode((string)$country->Code);
            $tmpCountrySubdivision->setName((string)$country->Name);
            array_push($countrySubdivisionArray, $tmpCountrySubdivision);
        }
        $countrySubdivisions->setCountrySubdivision($countrySubdivisionArray);
        return $countrySubdivisions;
    }
} 