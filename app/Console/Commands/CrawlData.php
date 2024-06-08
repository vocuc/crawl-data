<?php

namespace App\Console\Commands;

use App\Models\Geo;
use App\Models\Hotel;
use Illuminate\Console\Command;

class CrawlData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawl-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = time() + (86400 * 100);

        $year = date("Y", $time);

        $moth = date("m", $time);

        $day = date("d", $time);

        $nexMoth = date("m", ($time + 86400));

        $nexDay = date("d", ($time + 86400));

        $geo = Geo::where("crawl_status", 0)->first();

        if (empty($geo)) return true;

        Hotel::where("geo_id", $geo->code)->delete();

        for ($i = 0; $i < 4; $i++) {
            $response = $this->makeSearchRequest(
                $year,
                $moth,
                $day,
                $nexMoth,
                $nexDay,
                $geo->code,
                $geo->name,
                ($i * 25)
            );

            $data = json_decode($response, true);

            if (empty($data["data"]["entries"])) break;

            foreach ($data["data"]["entries"] as $hotel) {
                Hotel::create([
                    "code" => $hotel["data"]["id"],
                    "geo_id" => $geo->code,
                    "data" => json_encode($hotel["data"])
                ]);
            }

            sleep(10);
        }

        $geo->crawl_status = 1;

        $geo->save();

        return true;
    }

    /**
     * @param $year
     * @param $moth
     * @param $day
     * @param $nexMoth
     * @param $nexDay
     * @param $geoId
     * @param $hotelName
     * @return bool|string
     */
    public function makeSearchRequest($year, $moth, $day, $nexMoth, $nexDay, $geoId, $hotelName, $skip)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.traveloka.com/api/v2/hotel/searchList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "fields": [],
                "data": {
                    "checkInDate": {
                        "year": "' . $year . '",
                        "month": "' . $moth . '",
                        "day": "' . $day . '"
                    },
                    "checkOutDate": {
                        "year": "' . $year . '",
                        "month": "' . $nexMoth . '",
                        "day": "' . $nexDay . '"
                    },
                    "numOfNights": 1,
                    "currency": "VND",
                    "numAdults": 2,
                    "numChildren": 0,
                    "childAges": [],
                    "numInfants": 0,
                    "numRooms": 1,
                    "ccGuaranteeOptions": {
                        "ccInfoPreferences": [
                            "CC_TOKEN",
                            "CC_FULL_INFO"
                        ],
                        "ccGuaranteeRequirementOptions": [
                            "CC_GUARANTEE"
                        ]
                    },
                    "rateTypes": [
                        "PAY_NOW",
                        "PAY_AT_PROPERTY"
                    ],
                    "isJustLogin": false,
                    "backdate": false,
                    "geoId": "' . $geoId . '",
                    "showHidden": false,
                    "locationName": "' . $hotelName . '",
                    "sourceType": "HOTEL_GEO",
                    "isExtraBedIncluded": true,
                    "supportedDisplayTypes": [
                        "INVENTORY",
                        "INVENTORY_LIST",
                        "HEADER",
                        "INVENTORY_WITH_HEADER"
                    ],
                    "userSearchPreferences": [],
                    "uniqueSearchId": null,
                    "highlightedHotelId": null,
                    "boundaries": null,
                    "contexts": {
                        "isFamilyCheckbox": false,
                        "abTestPageSpeedVariant": "variant1"
                    },
                    "basicFilterSortSpec": {
                        "accommodationTypeFilter": [],
                        "starRatingFilter": [
                            false,
                            false,
                            false,
                            true,
                            true
                        ],
                        "facilityFilter": [],
                        "hasFreeCancellationRooms": false,
                        "minPriceFilter": null,
                        "maxPriceFilter": null,
                        "quickFilterId": null,
                        "ascending": false,
                        "basicSortType": "POPULARITY",
                        "chainIds": null,
                        "skip": ' . $skip . ',
                        "top": 25
                    },
                    "criteriaFilterSortSpec": null,
                    "filterSortRequestSpec": null
                },
                "clientInterface": "desktop"
            }',
            CURLOPT_HTTPHEADER => array(
                'accept: */*',
                'accept-language: en-US,en;q=0.9,vi;q=0.8',
                'cache-control: no-cache',
                'content-type: application/json',
                'cookie: _fbp=fb.1.1716176240798.731866919; _gcl_au=1.1.237779573.1716176241; _cs_c=1; _tt_enable_cookie=1; _ttp=CxpgQYefzmkhUb17PHz9oLGKeLp; countryCode=VN; _gid=GA1.2.1705411153.1716784634; tv-repeat-visit=true; aws-waf-token=b080992e-2f7d-4e3e-9262-8622b06ae932:AAoAhTpeGeEJAAAA:1X2TyZBsZat9EU/SyaO52IS9oFTtU4CmMgQLvxD07MlwHet+jmGUI5y3TddsQgihyZvpXdy+6RJJGPAV+h4iJi3Ev7H+58YbCA2O9/DsYAqvpOiRS0jTh4xj1K/+kjDxXBe8X/2ksDa1/2J12D45ymagOybL4iDxcVX6YRbYwz8AlbLYic0V362L7HCU+EeM7lI=; hotelSearchLoginModalLastShown=1716816390377; accomSuccessLoginConfirmation=0; tvTopAppBanner=1; amp_f4354c=juwwAYR55hWxFAEFvlFiJK...1hut3fhiu.1hut3njrm.0.6.6; _gat_UA-29776811-12=1; tvl=LgSF8+1aocCk1BUuGuHqVPVzcqBgUJV42HtolUGhtkXyMyWJtCkp7eSjSjiPqwfPKFKcJja2Hxxt5l76qWQjRyQ+uwxspVkxyvti5D85bHGSMXt0vpSV0x8lbJjSeTbdcNz53dCyqQDqhBGyMxsihYz2ZEgM3FPlhdm7wOsd7To8lyZyUhDHcYDomJMDDGIBLqF/qBBfSA+xT3P91FKK/DSYaHuE8WUawhiqJ4Mmy2OCVjISQB9Q0jDzpsr+qy/GWjN7JCJwNO0=~djAy; tvs=dkr+BucdZCGEJP8AqJiMh3TyeCl8CDXfGHazKRia7gxOOKzeryUWGq469llDVcAYmlZ2zE4nOFM6eh0aLRCHi/WwBqfejV8ndXCkwi7+PoOoeDmc6JKNdzL/kkp46o37YNTdtO1hhzr4oCPBpRSbygNnXjwijcS8zmG7/oLQ+Juwm7tRAxkj+Avf6wrVhIsMlaSl7nkX5VOI7DEQiiqsocuEkK5zo26r7K0glKQs4UN9HjNzQO+ojbXOCNSjcZuzw7ZgmkXgxj5aEvwpMqYgE0lXEQ+PwU1VKcM=~djAy; _ga=GA1.2.50148888.1716176241; _ga_RSRSMMBH0X=GS1.1.1716816169.7.1.1716816434.57.0.0; _cs_id=27ee1142-7c01-a982-c074-d530bd2076ed.1716176241.7.1716816435.1716816169.1.1750340241063.1; _cs_s=10.5.0.1716818235444; cto_bundle=q_4Ei19ZWE53VUR2cXZGVTE5U1NxQmNvSlNHNHVLd1VCZFMwNlJEQThZaE4xeFhOSmYwWGlyM2ElMkJ0dEY4b3BiZ2ZoZWJmRWlPenlRVVV1Y1I4eUpGbjgzJTJCN0x1RGhvd01jV2VXRDU4NFJYenBFc2FLbHolMkZrZlpPMVYxOE54QWhRRXdSMGtHeWppM2hQYXBjQ3RvWWpseko3S3lEUkhSbUVmJTJCYjNkbzVDNmxQeENQY3RzdUZsRmJwTjdISnlhQyUyQjhEWkxJUXRXNlRIYTZERnIlMkJuelh2SjFlY2w0b285WkkwcDc2d3g2MFZHVUQ1amRLSTh5REJNMkpPVmxEeVhpSmg1MDlUa0xWWlQ2dGM4SVpRaDlWRkJqUTNQQSUzRCUzRA; amp_1a5adb=DKEN4fe6hRih61Ds37weAT...1hut3fhir.1hut3np1m.4u.6.54; _dd_s=rum=0&expire=1716817358797&logs=1&id=4cb22ee6-0ea5-4797-b925-c977cc8a7e3e&created=1716816168401; tvl=eZpIPM47seW5VVy0rZRAex6Gf+owXJmwPDjDBvmteERNvJh97NEbnKgHWT8gSVVIjFenCl+/RIi15desM/LXm8i343GC5G90izCRu+44nTtyjQaBWj4IPsvVoKgL+ntOXmLubxW5gUa7AVgTaSOca4sbUb4PeXNFRMEmVeCUw+WCHfYYFcw1XrT/VIsBaBdf7TgJltz2tpR0FZ6nKBvo5com0Jgl9f6PwdZDFlxUe5dWfUCXFb8Ny9UiKlWrkEboVrLU2SG7hGo=~djAy; tvs=dOk5YaI/CzyvJhMIn7qAW18yneRGapGMXFygnnz4one7JYVWY+qdakNXRp7L1dfiUNxh52Hxa49/VGE0UdoDwtspPYCmeFVSz+50MD1jFnKAHBHfrELx6GZKN+y3pvAc0NKgEc73h2y80feDo41VfnVklaFn96qq5swduLnzycS+0I4hnXTOMWQw8ULRdSnmPuMT1ncy3WB7+horKZuCMz+/KmEDoeao87Od0M3Z+LtTDNXbMSuNtpDxx/dfgVdaAMe6eDm84hnKTaaltRtOCMYVQDJ+ckYwGL8=~djAy',
                'origin: https://www.traveloka.com',
                'pragma: no-cache',
                'priority: u=1, i',
                'referer: https://www.traveloka.com/vi-vn/hotel/search?spec=28-05-2024.27-06-2024.30.1.HOTEL_GEO.10010083.%C4%90%C3%A0%20N%E1%BA%B5ng.2',
                'sec-ch-ua: "Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-origin',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'x-domain: accomSearch',
                'x-route-prefix: vi-vn'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * @param $hotelId
     * @return bool|string
     */
    public function crawlDetailHotel($hotelId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.traveloka.com/api/v2/hotel/search/detail',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "fields": [],
                "data": {
                    "hotelId": "' . $hotelId . '",
                    "userSearchPreferences": []
                },
                "clientInterface": "desktop"
            }',
            CURLOPT_HTTPHEADER => array(
                'accept: */*',
                'accept-language: en-US,en;q=0.9,vi;q=0.8',
                'cache-control: no-cache',
                'content-type: application/json',
                'cookie: _fbp=fb.1.1716176240798.731866919; _gcl_au=1.1.237779573.1716176241; _cs_c=1; _tt_enable_cookie=1; _ttp=CxpgQYefzmkhUb17PHz9oLGKeLp; countryCode=VN; _gid=GA1.2.1483678610.1717427651; tv-repeat-visit=true; hotelSearchLoginModalLastShown=1717468615502; aws-waf-token=8041d6a4-5f3b-481b-8b52-af8eb11d1523:AAoAkrs9L1MDAAAA:wCgz4AJbEE/ajToMexIb/4wA37nxGDOzPjhlP8HPPe2qCYVEpThZuXTymMxz6pe3bewCoYoZRhFWis6WlCi8hVaDw0/giiKBIN0RoNTo0BYGKFbsuwJ4/GsCPR4esyp7l+1ORg8iucQ+7oAJ1/x2IImTR19O4b3b0axhUnK76UmlLcJm3UoK+K8cTNW+znJCprs=; amp_f4354c=juwwAYR55hWxFAEFvlFiJK...1hvh48de1.1hvh6nevt.0.a.a; _gat_UA-29776811-12=1; tvl=CYqWMtBpQbiH+sdJAvLc+wLseJ91PATNmE2UvBM9D8xtZQsSxwRy33dkMXM/tE3HaemusHmy5f23NMphxSQVu6EWzw6tbDeFkBrV4ldhmiSMypQmkrrxSfRjFulcwdxcT7zkEjaFG8Q/81o2ctIVEBIaL0yvLJ7EsFvT4FuRpnuNNAwzjwsJRqmYZkIrzmmepvVDMv31r4Rmt1RAzdauNI0uevfoSUrChp8OrtDMUWSfq4UELVi/Rlp/4ZWhBMyHqkxtKee8O9I=~djAy; tvs=Xx3WbuAxc9TBPnW09Poai7AA9aREC9JqwUoAZi0FQdx7zp4vP2EvYkM1OR4xLnzlw5MD9cFkh+RzJMBgthiU1weXcyt4zbf0xk/dQuW7YzxjzHMftSlHP0aV/H7y9T64FwM5rnqNnvIGODTN8tXx0bFwdZ2uRG9crz0Lh1BIDd11o89zmVxg2M40OP7sh1rskSxZO3mP4TqBD9jVhwgLIW+1E28LSxBGkD/0Tmum4Ke71yF5EElKtc/Q08rmJKexRAN1PVFLWVOcWM6owSCyhlIAoEPwdmKFKL4=~djAy; _ga=GA1.2.50148888.1716176241; _ga_RSRSMMBH0X=GS1.1.1717488072.13.1.1717490664.57.0.0; amp_1a5adb=DKEN4fe6hRih61Ds37weAT...1hvh48ddv.1hvh6nh7k.b2.a.bc; _cs_id=27ee1142-7c01-a982-c074-d530bd2076ed.1716176241.13.1717490665.1717488067.1.1750340241063.1; _cs_s=6.0.0.1717492465220; cto_bundle=pU4DEF9ZWE53VUR2cXZGVTE5U1NxQmNvSlNFTWxDbUxEbU90S2FRdXdpeE1ISFI5Sm1ncXU0Q3kwdFZZTFp4YyUyRkZDSEFDbms5Z0M5WVd0UTF3bE1oNkNldU02Mnp5dE43S3c2JTJCZE10cGo1OFFOSkZDUlVsdVAwdldtTGVuM0R1eEM4RUY5ZyUyQnp4Z2JGbmJwb0FkNG1vMFUlMkJ3dXFQUFFMZkN6eUdpTWt3SUNEZmlGenklMkZkaUhKSm84Q0lMdUlBQjdFYzNVa29ZY1J2WUR0N3h1Q3k0aGZPc1kwZlVCZWxDTlBFN1dnb2pyU2MlMkJqaDlya1IlMkJET3Qxa0pZU3BoZCUyRkxMdnIzQXpFUTI2aWhzbWQ3dDFnViUyRkxHVkNzZyUzRCUzRA; _dd_s=rum=0&expire=1717491576850&logs=1&id=69f97f56-1559-4688-bb40-46d13baec94d&created=1717489819744; tvl=+N0+9pOBwQ8nSdItsWm6OVr8kHX7tLFjbwfWU8d5k9oqS04hkV1K6RwpW91pSfWIIKBNFim7gQpvGR1cvo8WTUUhpwIc8pHCytS7wVZI2kEY2JVNrfZoq7GrMc5GPAr9DlmWPGc4kyR8V/fLAiLcqXAioQ47bRRmtdbmm4+HJINTqoBSG9lWAQdKrTn7QXsC2CACb0TwEcrcdA2wtGsA5SeHggZQ2bsoo7lxT/13hWmFR3kN79zovZhcwJVNnqFt2T31ghj6QRI=~djAy',
                'origin: https://www.traveloka.com',
                'pragma: no-cache',
                'priority: u=1, i',
                'referer: https://www.traveloka.com/vi-vn/hotel/search?spec=04-06-2024.15-06-2024.11.1.HOTEL_GEO.10010169.%C4%90%C3%A0%20L%E1%BA%A1t.2',
                'sec-ch-ua: "Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-origin',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'x-domain: accomSearch',
                'x-route-prefix: vi-vn'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
