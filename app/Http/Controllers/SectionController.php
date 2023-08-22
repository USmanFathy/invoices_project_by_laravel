<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections' ,['sections'=>$sections]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $valdiation = $request->validate([
            'section_name'=>'required|unique:sections',
            'description'=>'required'
        ],[
            'section_name.unique'=>'هذا القسم مسجل مسبقا ',
            'section_name.required'=>'يرجي كتابة اسم القسم',
            'description.required'=>'يرجي كتابة الوصف الخاص بالقسم',

        ]);




//        $section_exist= Section::where('section_name' , $input['section_name'])->exists();
//        if ($section_exist){
//            session()->flash('Error','تم إضافة سابقا');
//        session()->flash('Add','تم إضافة القسم بنجاح');

//            return redirect()->route('sections.index');
//        }else{
        Section::create([
            'section_name'=>$request->section_name ,
            'description'=>$request->description,
            'created_by'=>auth()->user()->name
        ]);
        session()->flash('Add' ,'تم إنشاء العنصر بنجاح');
            return redirect()->route('sections.index');
//        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $valdiation = $request->validate([
            'section_name'=>'required|unique:sections,section_name,'.$id,
            'description'=>'required'
        ],[
            'section_name.unique'=>'هذا القسم مسجل مسبقا ',
            'section_name.required'=>'يرجي كتابة اسم القسم',
            'description.required'=>'يرجي كتابة اسم القسم',

        ]);
        $section =Section::findOrFail($id);
        $section->update([
            'section_name'=>$request->section_name ,
            'description'=>$request->description,
        ]);
        session()->flash('Edit' ,'تم تعديل العنصر بنجاح');

        return redirect()->route('sections.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        Section::findOrFail($id)->delete();
        session()->flash('Delete' ,'تم حذف العنصر بنجاح');
        return redirect()->route('sections.index');

    }

}
