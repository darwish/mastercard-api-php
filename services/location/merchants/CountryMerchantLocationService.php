<?php

require_once('../../../services/location/domain/options/merchants/CountryMerchantLocationRequestOptions.php');
require_once('../../../services/location/domain/common/countries/Countries.class.php');
require_once('../../../services/location/domain/common/countries/Country.class.php');
require_once('../../../common/Connector.php');
require_once('../../../common/Environment.php');
require_once('../../../common/Serializer.php');
require_once('../../../common/URLUtil.php');

class CountryMerchantLocationService extends Connector {

    private $environment;

    private $PRODUCTION_URL = "https://api.mastercard.com/merchants/v1/country?Format=XML";
    private $SANDBOX_URL = "https://sandbox.api.mastercard.com/merchants/v1/country?Format=XML";

    public function __construct($consumerKey, $privateKey, $environment)
    {
        parent::__construct($consumerKey, $privateKey);
        $this->environment = $environment;
    }

    public function getCountries(CountryMerchantLocationRequestOptions $options)
    {
        $response = parent::doSimpleRequest($this->getURL($options),Connector::GET);
        $xml = simplexml_load_string($response);
        return $this->buildCountries($xml);
    }

    private function getURL(CountryMerchantLocationRequestOptions $options)
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

        $url = URLUtil::addQueryParameter($url, "Details", $options->getDetails());
        return $url;
    }

    private function buildCountries($xml)
    {
        $countries = new Countries();
        $countryArray = array();
        foreach($xml->Country as $country)
        {
            $tmpCountry = new Country();
            $tmpCountry->setCode((string)$country->Code);
            $tmpCountry->setGeoCoding((string)$country->Geocoded);
            $tmpCountry->setName((string)$country->Name);
            array_push($countryArray, $tmpCountry);
        }
        $countries->setCountry($countryArray);
        return $countries;
    }
} 