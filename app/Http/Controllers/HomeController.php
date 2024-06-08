<?php

namespace App\Http\Controllers;

use App\Models\Geo;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        $hotels = Hotel::where("crawl_status", 1)->paginate(1);

        return view("welcome")->with("hotels", $hotels);
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

        return redirect()->back()->with("success","Thêm mới thành công");
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
