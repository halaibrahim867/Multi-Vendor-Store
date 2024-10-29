<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::all();
        return view('dashboard.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents=Category::all();
        $category=new Category();
        return view('dashboard.categories.create',compact('category','parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|min:3|max:255',
            'parent_id'=>[
                'nullable','int','exists:categories,id'
            ],
            'image'=>[
                'image','max:1048576','dimensions:width=100,height=100'
            ],
            'status'=>'in:active,archived'
        ]);
        $request->merge([
            'slug'=>Str::slug($request->post('name'))
        ]);

        $data=$request->except('image');


        $data['image']=$this->uploadImage($request);


        $category=Category::create($data);

        return Redirect::route('dashboard.categories.index')->with('success','Category Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category=Category::findOrFail($id);
        }catch (Exception $e){
            return redirect()->route('dashboard.categories.index')
                ->with('info','Record not Found');
        }

        $parents=Category::where('id','<>',$id)
            ->where(function ($query) use ($id){
                $query->whereNull('parent_id')
                    ->orWhere('parent_id','<>',$id);
            })->get();

        return view('dashboard.categories.edit',
            compact('category','parents'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category=Category::findOrFail($id);

        $old_image=$category->image;

        $data=$request->except('image');

        $data['image']=$this->uploadImage($request);


        $category->update($data);

        if ($old_image && $data['image']){
            Storage::disk('public')->delete($old_image);
        }
        return Redirect::route('dashboard.categories.index')
            ->with('success','Category Updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category=Category::findOrFail($id);

        $category->delete();

        if ($category->image){
            Storage::disk('public')->delete($category->image);
        }
        return Redirect::route('dashboard.categories.index')
            ->with('success','Category Deleted!');
    }

    protected function uploadImage(Request $request){


        if (!$request->hasFile('image')) {
            return ;
        }
            $file=$request->file('image'); //uploaded file object
            $path=$file->store( 'uploads','public');


            return $path;

    }
}
