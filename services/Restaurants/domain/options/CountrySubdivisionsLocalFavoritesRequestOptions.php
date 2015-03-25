<?php

class CountrySubdivisionsLocalFavoritesRequestOptions {

    private $country;

    public function __construct($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }
} 