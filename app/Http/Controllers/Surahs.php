<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surah;
use App\Models\ReadingProgress;

class Surahs extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = request('q');

        $surahs = Surah::when($query, function ($q) use ($query) {

            // If query is numeric, search by surah number
            if (is_numeric($query)) {

                $q->where(
                    'number',
                    intval($query)
                );

            } else {

                // Otherwise search by English or Arabic name
                $q->where(function ($sub) use ($query) {

                    $sub->where(
                        'name_en',
                        'like',
                        "%{$query}%"
                    )->orWhere(
                        'name_ar',
                        'like',
                        "%{$query}%"
                    );

                });

            }

        })
        ->orderBy('id', 'asc')
        ->paginate(15);

        return view(
            'admin.surahs.index',
            compact('surahs')
        );
    }


    /**
     * Display interpreted surahs for the user.
     */
 


    /*
    |--------------------------------------------------------------------------
    | Return view
    |--------------------------------------------------------------------------
    */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(
            'admin.surahs.create'
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-\']+$/'
            ],

            'number' => [
                'required',
                'integer',
                'min:1',
                'max:114',
                'unique:surhes'
            ],

            'verses_count' => [
                'required',
                'integer',
                'min:1',
                'max:286'
            ],

            'name_ar' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}\s]+$/u'
            ],

        ], [

            'number.min' =>
                'Surah number must be 1 or more.',

            'number.max' =>
                'Surah number cannot exceed 114.',

            'verses_count.max' =>
                'Verse count cannot exceed 286.',

            'name_ar.regex' =>
                'Arabic name must contain Arabic letters only.',

            'name_en.regex' =>
                'English name must contain English letters only.',
        ]);


        $surah =
            Surah::create($validated);


        return redirect()
            ->route('admin.surahs.index')
            ->with(
                'success',
                'Surah "'.$surah->name_ar.'" has been added to the list.'
            );
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surah = Surah::with([
            'verses' => function ($q) {
                $q->orderBy(
                    'verse_number',
                    'asc'
                );
            }
        ])
        ->findOrFail($id);


        return view(
            'admin.surahs.show',
            compact('surah')
        );
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surah =
            Surah::findOrFail($id);


        return view(
            'admin.surahs.edit',
            compact('surah')
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        string $id
    ) {

        $validated = $request->validate([

            'name_en' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-\']+$/'
            ],

            'number' => [
                'required',
                'integer',
                'min:1',
                'max:114',
                'unique:surhes,number,'.$id
            ],

            'verses_count' => [
                'required',
                'integer',
                'min:1',
                'max:286'
            ],

            'name_ar' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}\s]+$/u'
            ],

        ], [

            'number.min' =>
                'Surah number must be 1 or more.',

            'number.max' =>
                'Surah number cannot exceed 114.',

            'verses_count.max' =>
                'Verse count cannot exceed 286.',

            'name_ar.regex' =>
                'Arabic name must contain Arabic letters only.',

            'name_en.regex' =>
                'English name must contain English letters only.',
        ]);


        $surah =
            Surah::findOrFail($id);


        $surah->update(
            $validated
        );


        return redirect()
            ->route('admin.surahs.index')
            ->with(
                'success',
                'Surah "'.$surah->name_ar.'" has been updated successfully.'
            );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surah =
            Surah::findOrFail($id);


        $surahName =
            $surah->name_ar;


        $surah->delete();


        return redirect()
            ->route('admin.surahs.index')
            ->with(
                'success',
                'Surah "'.$surahName.'" has been deleted successfully.'
            );
    }
}