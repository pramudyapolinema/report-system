<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomoditasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // make index like other controller but this with Komoditas Model
        if (request()->ajax()) {
            $komoditas = Komoditas::all();
            return datatables()->of($komoditas)
                ->addColumn('actions', function ($komoditas) {
                    $button = '<button type="button" id="editButton" data-id="' . $komoditas->id . '" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKomoditasModal"><i class="fa fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" id="deleteButton" data-id="' . $komoditas->id . '" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['actions'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('komoditas.index');
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

            Komoditas::create([
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
     * @param  \App\Models\Komoditas  $komoditas
     * @return \Illuminate\Http\Response
     */
    public function show(Komoditas $komoditas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Komoditas  $komoditas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $komoditas = Komoditas::findOrfail($id);
        return response()->json($komoditas, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Komoditas  $komoditas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $komoditas = Komoditas::findOrfail($id);
        $komoditas->update([
            'name' => $request->name ?? $komoditas->name,
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
     * @param  \App\Models\Komoditas  $komoditas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $komoditas = Komoditas::findOrfail($id);
            $komoditas->delete();
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
