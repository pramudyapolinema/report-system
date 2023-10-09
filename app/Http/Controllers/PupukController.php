<?php

namespace App\Http\Controllers;

use App\Models\Pupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PupukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // make index like other controller but this with Pupuk Model
        if (request()->ajax()) {
            $pupuk = Pupuk::all();
            return datatables()->of($pupuk)
                ->addColumn('actions', function ($pupuk) {
                    $button = '<button type="button" id="editButton" data-id="' . $pupuk->id . '" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editPupukModal"><i class="fa fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" id="deleteButton" data-id="' . $pupuk->id . '" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['actions'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('pupuk.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
            ]);

            Pupuk::create([
                'name' => $request->name,
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Data berhasil disimpan'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pupuk  $pupuk
     * @return \Illuminate\Http\Response
     */
    public function show(Pupuk $pupuk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pupuk  $pupuk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pupuk = Pupuk::findOrfail($id);
        return response()->json($pupuk, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pupuk  $pupuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $pupuk = Pupuk::findOrfail($id);
        $pupuk->update([
            'name' => $request->name ?? $pupuk->name,
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Data berhasil diupdate'
        ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pupuk  $pupuk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pupuk = Pupuk::findOrfail($id);
            $pupuk->delete();
            DB::commit();
            return response()->json([
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
