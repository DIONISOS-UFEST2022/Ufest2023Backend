<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TimMobileLegendResource;
use App\Http\Resources\UlympicResource;
use App\Models\Ulympic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UlympicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tim = Ulympic::all();
        if (!$tim) {
            return response()->json([
                'success' => false
            ], 409);
        }
        return response()->json([
            'success' => true,
            'data' => UlympicResource::collection($tim),
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
            'namaTim' => 'string|required|unique:ulympics',
            'ketua' => 'string|',
            'buktiPembayaran' => 'image|',
            'jumlahMember' => 'numeric|required|min:5|max:7',
        ]);

        $ulympic = Ulympic::create($request->all());

        if ($request->buktiPembayaran) {
            $filenamePembayaran = Str::random(25);
            $extensionPembayaran = $request->buktiPembayaran->extension();
            Storage::putFileAs('public/bukti_pembayaran',  $request->buktiPembayaran, $filenamePembayaran . '.' . $extensionPembayaran);
            $ulympic->buktiPembayaran = $filenamePembayaran . '.' . $extensionPembayaran;
        }

        $ulympic->tokenID = Str::random(15);
        $ulympic->save();

        if (!$ulympic) {
            return response()->json([
                'success' => false,
            ], 409);
        }

        return response()->json([
            'success' => true,
            'data' => new UlympicResource($ulympic),
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
        $ulympic = Ulympic::findOrFail($id);

        if (!$ulympic) {
            return response()->json([
                'success' => false,
                'msg' => 'team not found',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'data' => new UlympicResource($ulympic),
            'tim' => TimMobileLegendResource::collection($ulympic->timMobileLegend),
        ], 201);
    }

    public function showTimByToken($token)
    {
        $ulympic = Ulympic::where('tokenID', '=', $token)->first();

        if (!$ulympic) {
            return response()->json([
               'success' => false,
                'msg' => 'Team not found'
], 409);
        }

        return response()->json([
            'success' => true,
            'data' => new UlympicResource($ulympic),
            'tim' => TimMobileLegendResource::collection($ulympic->timMobileLegend),
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
        $ulympic = Ulympic::findOrFail($id);

        $tim = $ulympic->TimMobileLegend;

        $request->validate([
            'namaTim' => 'string|unique:ulympics,namaTim,' . $id . ',id',
            'ketua' => 'string',
            'buktiPembayaran' => 'image',
            'jumlahMember' => 'numeric|min:5|max:7',
        ]);
        $input =  collect(request()->all())->filter()->all();

        $ulympic->update($input);

        if ($request->buktiPembayaran) {
			
            $imageFolder = Storage::disk('bukti_pembayaran');
            if ($ulympic->buktiPembayaran != null) {
                if ($imageFolder->exists($ulympic->buktiPembayaran)) {
                    $imageFolder->delete($ulympic->buktiPembayaran);
                }
            }
            $filename = Str::random(25);
            $extension = $request->buktiPembayaran->extension();
            Storage::putFileAs('public/bukti_pembayaran', $request->buktiPembayaran, $filename . '.' . $extension);
            $ulympic->buktiPembayaran = $filename . '.' . $extension;
            $ulympic->save();
        }

        if ($tim) {
            foreach ($tim as $t) {
                $t->ketua = $ulympic->ketua;
                $t->save();
            }
        }

        if ($ulympic) {
			//$service = new GoogleSheetController();
            //$service->initMlTeam();
			
            return response()->json([
                'success' => true,
                'data' => new UlympicResource($ulympic, 201),
                'tim' => TimMobileLegendResource::collection($ulympic->timMobileLegend),
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 404);
        }
    }
	
	public function updateByToken(Request $request, $token)
    {
        $ulympic = Ulympic::where('tokenID', $token)->first();

        if (!$ulympic) {
            return response()->json([
                'success' => false,
                'msg' => 'member not found'
            ], 201);
        }

        $tim = $ulympic->TimMobileLegend;

        $request->validate([
            'namaTim' => 'string|unique:Ulympics,namaTim,' . $ulympic->id . ',id',
            'ketua' => 'string',
            'buktiPembayaran' => 'image',
            'jumlahMember' => 'numeric|min:5|max:7',
        ]);
        $input =  collect(request()->all())->filter()->all();

        $ulympic->update($input);

        if ($request->buktiPembayaran) {
            $imageFolder = Storage::disk('bukti_pembayaran');
            if (!$ulympic->buktiPembayaran != null) {
                if ($imageFolder->exists($ulympic->buktiPembayaran)) {
                    $imageFolder->delete($ulympic->buktiPembayaran);
                }
            }
            $filename = Str::random(25);
            $extension = $request->buktiPembayaran->extension();
            Storage::putFileAs('public/bukti_pembayaran', $request->buktiPembayaran, $filename . '.' . $extension);
            $ulympic->buktiPembayaran = $filename . '.' . $extension;
            $ulympic->save();
        }

        if ($tim) {
            foreach ($tim as $t) {
                $t->ketua = $ulympic->ketua;
                $t->save();
            }
        }

        if ($ulympic) {
            //$service = new GoogleSheetController();
            //$service->initMlTeam();

            return response()->json([
                'success' => true,
                'data' => new UlympicResource($ulympic, 201),
                'tim' => TimMobileLegendResource::collection($ulympic->timMobileLegend),
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
        $ulympic = Ulympic::findOrFail($id);

        $imageFolder = Storage::disk('bukti_pembayaran');
        if ($ulympic->buktiPembayaran != null) {
            if ($imageFolder->exists($ulympic->buktiPembayaran)) {
                $imageFolder->delete($ulympic->buktiPembayaran);
            }
        }

        $tim = $ulympic->timMobileLegend;

        if ($tim) {
            foreach ($tim as $t) {
                $t->forceDelete();
            }
        }

        $isDeleted = $ulympic->forceDelete();

        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'msg'    => "tim" . $ulympic->namaTim . " has been deleted!",
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 404);
        }
    }
}
