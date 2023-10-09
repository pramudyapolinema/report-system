<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if request is ajax, return the datatable
        if (request()->ajax()) {
            $petani = Petani::with(['kelompokTani']);
            return datatables()->eloquent($petani)
            ->addColumn('kelompok_tani', function ($petani) {
                return $petani->kelompokTani->name;
            })
                ->addColumn('actions', function ($petani) {
                    $button = '<button type="button" id="editButton" data-id="' . $petani->id . '" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editPetaniModal"><i class="fa fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" id="deleteButton" data-id="' . $petani->id . '" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['actions'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('petani.index');
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
        // make store logic here
        DB::beginTransaction();
        try {
            $request->validate([
                'kelompok_tani_id' => 'required',
                'name' => 'required',
                'nik' => 'required',
                'address' => 'required',
                'luas_lahan' => 'required',
            ]);

            Petani::create([
                'kelompok_tani_id' => $request->kelompok_tani_id,
                'name' => $request->name,
                'nik' => $request->nik,
                'address' => $request->address,
                'luas_lahan' => $request->luas_lahan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Petani berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Petani  $petani
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Petani  $petani
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $petani = Petani::with(['kelompokTani'])->findOrFail($id);
        return response()->json($petani, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Petani  $petani
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $petani = Petani::findOrFail($id);
            $petani->update([
                'kelompok_tani_id' => $request->kelompok_tani_id ?? $petani->kelompok_tani_id,
                'name' => $request->name ?? $petani->name,
                'nik' => $request->nik ?? $petani->nik,
                'address' => $request->address ?? $petani->address,
                'luas_lahan' => $request->luas_lahan ?? $petani->luas_lahan,
            ]);
            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Petani  $petani
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $petani = Petani::findOrFail($id);
            $petani->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
