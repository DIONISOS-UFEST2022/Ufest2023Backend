<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimMobileLegendResource;
use App\Models\TimMobileLegend;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TimMobileLegendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tim = TimMobileLegend::all();

        if (!$tim) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'data' => TimMobileLegendResource::collection($tim),
        ], 201);
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
        $request->validate([
            'tokenID' => 'string|required|exists:ulympics,tokenID',
            'ketua' => 'string|',
            'nama' => 'string|required',
            'angkatan' => 'string|numeric:4|required',
            'jurusan' =>  'string|required',
            'userID' => 'string|required|unique:tim_mobile_legends,userID',
            'userName' => 'string|required',
            'buktiWA' => 'image|',
            'fotoKtm' => 'image|',
            'phoneNumber' => 'numeric|digits_between:11,15|required|unique:tim_mobile_legends,phoneNumber'
        ], [
            'tokenID.exists' => 'this team does not exist'
        ]);

        $tim = TimMobileLegend::create($request->all());

        if ($request->buktiWA) {
            $filenameWA = Str::random(25);
            $extensionWA = $request->buktiWA->extension();
            Storage::putFileAs('public/bukti_WA',  $request->buktiWA, $filenameWA . '.' . $extensionWA);
            $tim->buktiWA = $filenameWA . '.' . $extensionWA;
        }
        if ($request->fotoKtm) {
            $filenameKTM = Str::random(25);
            $extensionKTM = $request->fotoKtm->extension();
            Storage::putFileAs('public/foto_ktm',  $request->fotoKtm, $filenameKTM . '.' . $extensionKTM);
            $tim->fotoKtm = $filenameKTM . '.' . $extensionKTM;
        }

        $ulympic = $tim->ulympic;
        $tim->ketua = $ulympic->ketua;
        $tim->save();
        $sisaMember =  $tim->ulympic->jumlahMember - $ulympic->timMobileLegend->count();


        // if ($sisaMember == 0) {
       //    $service = new GoogleSheetController();
      //     $service->initMlTeam();
     //  }

        if ($sisaMember < 0) {
            $tim->forceDelete();
            return response()->json([
                'success' => false,
                'msg' => 'jumlah Anggota sudah melebihi kapasitas tim'
            ]);
        }

        if (!$tim) {
            return response()->json([
                'success' => false
            ], 409);
        }

        return response()->json([
            'success' => true,
            'data' => new TimMobileLegendResource($tim),
            'namaTim' => $tim->ulympic->namaTim,
            'sisaMember' => $sisaMember,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tim = TimMobileLegend::findOrFail($id);
        $ulympic = $tim->ulympic;
        $sisaMember =  $tim->ulympic->jumlahMember - $ulympic->timMobileLegend->count();

        if (!$tim) {
            return response()->json([
                'success' => false
            ], 409);
        }

        return response()->json([
            'success' => true,
            'data' => new TimMobileLegendResource($tim),
            'namaTim' => $tim->ulympic->namaTim,
            'sisaMember' => $sisaMember,

        ], 201);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tim = TimMobileLegend::findorfail($id);

        $request->validate([
            'tokenID' => 'string|exists:ulympics,tokenID',
            'ketua' => 'string',
            'nama' => 'string',
            'angkatan' => 'string|numeric:4',
            'jurusan' =>  'string',
            'userID' => 'string|unique:tim_mobile_legends,userID,' . $id . 'id',
            'userName' => 'string',
            'buktiWA' => 'image',
            'fotoKtm' => 'image',
            'phoneNumber' => 'digits_between:11,15|min:11',
        ], [
            'tokenID.exists' => 'this team does not exist'
        ]);

        $input = collect(request()->all())->filter()->all();

        if ($request->buktiWA) {
            $imageFolder = Storage::disk('bukti_WA');
            if (!$tim->buktiWA != null) {
                if ($imageFolder->exists($tim->buktiWA)) {
                    $imageFolder->delete($tim->buktiWA);
                }
            }
            $filename = Str::random(25);
            $extension = $request->buktiWA->extension();
            Storage::putFileAs('public/bukti_WA', $request->buktiWA, $filename . '.' . $extension);
            $tim->buktiWA = $filename . '.' . $extension;
            $tim->save();
        }
        if ($request->fotoKtm) {
            $imageFolder = Storage::disk('foto_ktm');
            if ($tim->fotoKtm != null) {
                if ($imageFolder->exists($tim->fotoKtm)) {
                    $imageFolder->delete($tim->fotoKtm);
                }
            }
            $filename = Str::random(25);
            $extension = $request->fotoKtm->extension();
            Storage::putFileAs('public/foto_ktm', $request->fotoKtm, $filename . '.' . $extension);
            $tim->fotoKtm = $filename . '.' . $extension;
            $tim->save();
        }

        $tim->update($input);

        $ulympic = $tim->ulympic;
        $tim->ketua = $ulympic->ketua;
        $tim->save();

        if ($tim) {
            return response()->json([
                'success' => true,
                'data' => new TimMobileLegendResource($tim, 201),
                'namaTim' => $tim->ulympic->namaTim,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tim = TimMobileLegend::findOrFail($id);
        $isDeleted = $tim->forceDelete();

        $imageFolder = Storage::disk('bukti_WA');
        if ($tim->buktiWA != null) {
            if ($imageFolder->exists($tim->buktiWA)) {
                $imageFolder->delete($tim->buktiWA);
            }
        }

        $imageFolder = Storage::disk('foto_ktm');
        if ($tim->fotoKtm != null) {
            if ($imageFolder->exists($tim->fotoKtm)) {
                $imageFolder->delete($tim->fotoKtm);
            }
        }


        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'msg'    => "tim" . $tim->nama . " has been deleted!",
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 404);
        }
    }
}
