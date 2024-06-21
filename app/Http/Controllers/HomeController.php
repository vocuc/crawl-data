<?php

namespace App\Http\Controllers;

use App\Models\Geo;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = Hotel::where("crawl_status", 1);

        if ($request->has("geo_id")) {
            $query = $query->where("geo_id", $request->get("geo_id"));
        }

        $aryHotels = [];

        foreach ($query->get() as $hotel) {
            $data = json_decode($hotel->data, true);
            $en = json_decode($hotel->data_detail_en, true);
            $vi = json_decode($hotel->data_detail, true);
            $aryHotels[] = [
                "vi" => [
                    "name" => $vi["data"]["name"],
                    "address" => $vi["data"]["address"],
                    "price" => $data["hotelInventorySummary"]["cheapestRateDisplay"]["baseFare"]["amount"],
                    "longitude" => $vi["data"]["hotelGEO"]["longitude"],
                    "latitude" => $vi["data"]["hotelGEO"]["latitude"],
                    "starRating" => $vi["data"]["starRating"],
                    "userRating" => $data["userRating"],
                    "userRatingInfo" => $vi["data"]["userRatingInfo"],
                    "numReviews" => $data["numReviews"],
                    "hotelFacilitiesTagDisplay" => $vi["data"]["hotelFacilitiesTagDisplay"],
                    "overview" => $vi["data"]["attribute"]["overview"],
                    "assets" => $vi["data"]["assets"]
                ],
                "en" => [
                    "name" => $en["data"]["name"],
                    "address" => $en["data"]["address"],
                    "price" => $data["hotelInventorySummary"]["cheapestRateDisplay"]["baseFare"]["amount"],
                    "longitude" => $en["data"]["hotelGEO"]["longitude"],
                    "latitude" => $en["data"]["hotelGEO"]["latitude"],
                    "starRating" => $en["data"]["starRating"],
                    "userRating" => $data["userRating"],
                    "userRatingInfo" => $en["data"]["userRatingInfo"],
                    "numReviews" => $data["numReviews"],
                    "hotelFacilitiesTagDisplay" => $en["data"]["hotelFacilitiesTagDisplay"],
                    "overview" => $en["data"]["attribute"]["overview"],
                    "assets" => $en["data"]["assets"]
                ]
            ];
        }

        return Response::json($aryHotels, 200);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function config()
    {
        $geos = Geo::all();

        return view("geos")->with("geos", $geos);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postConfig(Request $request)
    {
        Geo::create([
            "code" => $request->get("code"),
            "name" => $request->get("name")
        ]);

        return redirect()->back()->with("success", "Thêm mới thành công");
    }

    /**
     * @param $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteGeo($code)
    {
        Geo::where("code", $code)->delete();

        Hotel::where("geo_id", $code)->delete();

        return redirect()->back();
    }
}
