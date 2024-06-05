<?php

namespace App\Console\Commands;

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
        $year = date("Y");

        $moth = date("m");

        $day = date("d");

        $nexMoth = date("m", (time() + 86400));

        $nexDay = date("d", (time() + 86400));

        $aryHotel = [
            10010083 => "Đà Nẵng",
            10010169 => "Đà Lạt"
        ];

        foreach ($aryHotel as $geoId => $hotelName) {
            $response = $this->makeSearchRequest($year, $moth, $day, $nexMoth, $nexDay, $geoId, $hotelName);

            $data = json_decode($response, true);

            foreach ($data["data"]["entries"] as $hotel) {
                Hotel::create([
                    "code" => $hotel["data"]["id"],
                    "data" => json_encode($hotel["data"])
                ]);
            }

            sleep(10);
        }
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
    public function makeSearchRequest($year, $moth, $day, $nexMoth, $nexDay, $geoId, $hotelName)
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
                    "numOfNights": 30,
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
                            true,
                            true,
                            true,
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
                        "skip": 0,
                        "top": 1
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

    public function crawlDetailHotel()
    {

    }
}
