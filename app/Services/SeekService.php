<?php

namespace App\Services;

class SeekService
{
    const BASE_URL = 'https://www.seek.co.nz';

    

    protected $searchUrl = '/api/chalice-search/v4/search?siteKey=NZ-Main&sourcesystem=houston&userqueryid=607c82cfe7708f5e983b0c96d6ea769b-5226978&userid=4f4b256a-3d6c-4b0b-9ecc-d57022105346&usersessionid=4f4b256a-3d6c-4b0b-9ecc-d57022105346&eventCaptureSessionId=4f4b256a-3d6c-4b0b-9ecc-d57022105346&seekSelectAllPages=true&keywords=react&include=seodata&solId=c6dffcae-9542-48fe-a3be-4d7f97bb9d32&page=1';
    protected $client;
    protected $listings = [];
    protected $hasNextPage = true;
    protected $totalRecords = null;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function collectListings($location = null)
    {
        if ($location) {
            $this->searchUrl .= "&where={$location}";
        } else {
            $this->searchUrl .= "&where=All%20Waikato";
        }


        $this->collectPage();

        $this->saveListings();
    }

    protected function collectPage()
    {
        $response = $this->client->get(self::BASE_URL . $this->searchUrl);
        $data = json_decode($response->getBody()->getContents(), true);

        $this->totalRecords = $data['totalCount'];
        $this->listings = array_merge($this->listings, $data['data']);
    }

    protected function saveListings()
    {
        foreach ($this->listings as $listing) {
            $this->saveListing($listing);
        }
    }

    protected function saveListing($listing)
    {
        $newOrExistingListing = \App\Models\Listing::where('vendor', 'seek')
            ->where('vendor_id', $listing['id'])
            ->first();

        if (!$newOrExistingListing) {
            $newListing = new \App\Models\Listing();

            $newListing->vendor = 'seek';
            $newListing->vendor_id = $listing['id'];
            $newListing->title = $listing['title'];
            $newListing->short_description = $listing['teaser'];
            $newListing->location = $listing['location'];
            $newListing->salary = $listing['salary'];
            $newListing->hours = null;
            $newListing->company_name = $listing['advertiser']['description'];
            $newListing->listing_date = $listing['listingDate'];

            $newListing->save();

            return $newListing;
        } 

        $existingListing = $newOrExistingListing;

        if ($existingListing->is_comparable || $existingListing->is_adjusted) {
            return $existingListing;
        }

        $existingListing->vendor = 'seek';
        $existingListing->vendor_id = $listing['id'];
        $existingListing->title = $listing['title'];
        $existingListing->short_description = $listing['teaser'];
        $existingListing->location = $listing['location'];
        $existingListing->salary = $listing['salary'];
        $existingListing->hours = null;
        $existingListing->company_name = $listing['advertiser']['description'];
        $existingListing->listing_date = $listing['listingDate'];

        $existingListing->save();


    }

}