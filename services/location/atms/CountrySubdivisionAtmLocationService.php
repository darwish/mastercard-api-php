<?php

require_once('../../../services/location/domain/options/atms/CountrySubdivisionAtmLocationRequestOptions.php');
require_once('../../../services/location/domain/common/countriesSubdivisions/CountrySubdivisions.class.php');
require_once('../../../services/location/domain/common/countriesSubdivisions/CountrySubdivision.class.php');
require_once('../../../common/Connector.php');
require_once('../../../common/Environment.php');
require_once('../../../common/Serializer.php');
require_once('../../../common/URLUtil.php');

class CountrySubdivisionAtmLocationService extends Connector {

    private $environment;

    private $PRODUCTION_URL = "https://api.mastercard.com/atms/v1/countrysubdivision?Format=XML";
    private $SANDBOX_URL = "https://sandbox.api.mastercard.com/atms/v1/countrysubdivision?Format=XML";

    public function __construct($consumerKey, $privateKey, $environment)
    {
        parent::__construct($consumerKey, $privateKey);
        $this->environment = $environment;
    }

    public function getCountrySubdivisions(CountrySubdivisionAtmLocationRequestOptions $options)
    {
        $response = parent::doSimpleRequest($this->getURL($options),Connector::GET);
        $xml = simplexml_load_string($response);
        return $this->buildCountrySubdivisions($xml);
    }

    private function getURL(CountrySubdivisionAtmLocationRequestOptions $options)
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