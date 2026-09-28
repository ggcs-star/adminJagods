<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Models\Banner;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Requests\BannerRequest;
use App\Http\Controllers\BackendController;
use App\Models\Category;

class BannerController extends BackendController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Banners';

        $this->middleware(['permission:banner'])->only('index');
        $this->middleware(['permission:banner_create'])->only('create', 'store');
        $this->middleware(['permission:banner_edit'])->only('edit', 'update');
        $this->middleware(['permission:banner_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queryArray = [];
        if (auth()->user()->myrole != 1 && auth()->user()->restaurant) {
            $queryArray['restaurant_id'] = auth()->user()->restaurant->id;
        }

        if (!blank($queryArray)) {
            $this->data['banners'] = Banner::where($queryArray)->orderBy('sort', 'asc')->get();
        } else {
            $this->data['banners'] = Banner::orderBy('sort', 'asc')->get();
        }

        return view('admin.banner.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->data['categories'] = Category::orderBy('name')->get();
        $this->data['restaurants'] = Restaurant::where(['status' => Status::ACTIVE])->get();
        return view('admin.banner.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        $banner = new Banner;

        $banner->target_type       = $request->target_type;
        $banner->target_id         = $request->target_id;
        $banner->title             = $request->name;
        $banner->short_description = $request->description;
        $banner->link              = $request->url;
        $banner->status            = $request->status;
        $banner->show_on_landing   = $request->show_on_landing ?? 0;
        $banner->save();

        // Store Image Media Library Spatie
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $banner
                ->addMediaFromRequest('image')
                ->toMediaCollection('banner');
        }

        $banner->sort = $banner->id;
        $banner->save();

        return redirect(route('admin.banner.index'))
            ->withSuccess('The data inserted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['banner'] = Banner::findOrFail($id);
        $this->data['restaurants'] = Restaurant::where(['status' => Status::ACTIVE])->get();
        $this->data['categories'] = Category::orderBy('name')->get();
        return view('admin.banner.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, $id)
{
    $banner = Banner::findOrFail($id);

    $banner->target_type       = $request->target_type;
    $banner->target_id         = $request->target_id;
    $banner->show_on_landing   = $request->show_on_landing ?? 0;
    $banner->title             = $request->name;
    $banner->short_description = $request->description;
    $banner->link              = $request->url;
    $banner->status            = $request->status;

    $banner->save();

    // Update image only if new image uploaded
    if ($request->hasFile('image') && $request->file('image')->isValid()) {

        $banner->clearMediaCollection('banner');

        $banner
            ->addMediaFromRequest('image')
            ->toMediaCollection('banner');
    }

    return redirect(route('admin.banner.index'))
        ->withSuccess('The data updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Banner::findOrFail($id)->delete();
        return redirect(route('admin.banner.index'))->withSuccess('The data deleted successfully.');
    }

    public function sortBanner(Request $request)
    {
        if ($request->has('ids')) {
            $arr = explode(',', $request->input('ids'));
            foreach ($arr as $sortOrder => $id) {
                $banner       = Banner::find($id);
                $banner->sort = ++$sortOrder;
                $banner->save();
            }
            return ['success' => true, 'message' => 'Updated'];
        }
    }
}
