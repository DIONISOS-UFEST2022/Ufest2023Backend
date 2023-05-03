<?php

namespace App\Http\Controllers;

use App\Http\Resources\PanitiaResource;
use App\Models\panitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\GoogleSheetController;
use App\Http\Services\GoogleSheetsServices;
use Carbon\Carbon;

class PanitiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $panitia = panitia::all();
        if (!$panitia) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'data' => PanitiaResource::collection($panitia),
        ], 201);
    }
	
	public function indexFilterByDiv($division)
    {
        $panitia = panitia::where('division_2', $division)
            ->orWhere('division_1', $division)
            ->orderBy('is_accepted', 'DESC')
			->orderBy('is_created', 'DESC')
            ->get();

        if (!$panitia) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'data' => PanitiaResource::collection($panitia),
        ], 201);
    }
	
	public function accept(Request $request)
    {
        $id_list = $request->input('id');
        $is_accepted = $request->input('category', 0);
        $i = 0;
        // check if all panitia id does exsist
        foreach (explode(',', $id_list) as $id) {
            $panitia = panitia::findOrFail($id);
            if (!$panitia) {
                return response()->json("something when wrong pls try again!");
            }
            $panitiaList[$i] = array(
                'id' => $panitia->id,
                'name' => $panitia->name,
                'division_1' => $panitia->division_1,
                'division_2' => $panitia->division_2,
                'is_accepted' => $panitia->is_accepted,
            );

            $i++;
        }

        $i = 0;
        foreach ($panitiaList as $panitia) {
            $panitia = panitia::findOrFail($panitia['id']);
            $panitia->is_accepted = (int)($is_accepted);
            $panitia->save();
            $panitiaList[$i]['is_accepted'] =  $panitia->is_accepted;
            $i++;
        }

        $service = new GoogleSheetController();
        $service->init();

        if ($is_accepted == '0') {
            return response()->json([
                'msg' => 'success! panitia : ' . $id_list . ' have been rejected',
                'panitia' => $panitiaList,
            ]);
        }

        return response()->json([
            'msg' => 'success! panitia : ' . $id_list . ' have been accepted',
            'panitia' => $panitiaList,
        ]);
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
            'nim' => 'required|digits:11|numeric|unique:panitia|regex:/^0+\d\d\d\d\d$/',
            'name' => 'required',
            'email' => 'required|email|unique:panitia',
            'program_studi' => 'required',
            'vaccine_certificate' => 'image',
            'angkatan' => 'required|string:4|regex:/^[0-9]*$/',
            'division_1' => 'required|string',
            'division_2' => 'required|string',
            'phone_number' => 'required|numeric:11|unique:panitia',
            'reason_1' => 'required|string',
            'reason_2' => 'required|string',
            'id_line' => 'required',
            'instagram_account' => 'required',
            'city' => 'required',
        ]);

        $panitia = panitia::create($request->all());

        if ($request->vaccine_certificate != "") {
            $filename = Str::random(25);
            $extension = $request->vaccine_certificate->extension();
            Storage::putFileAs('public/vaccine_image', $request->vaccine_certificate, $filename . '.' . $extension);
            $panitia->vaccine_certificate = $filename . '.' . $extension;
        } else {
            $panitia->vaccine_certificate = "none";
        }
		if(!$request->portofolio) {
			$panitia->portofolio = "none";
		}

        $panitia->is_accepted = 0;
		$panitia->save();

        $service = new GoogleSheetController();
        $service->appendData($panitia);

        if ($panitia) {
            return response()->json([
                'success' => true,
                'data' => new PanitiaResource($panitia, 201),
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $panitia = Panitia::findOrFail($id);

        if (!$panitia) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'data' => new PanitiaResource($panitia),
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
        $panitia = Panitia::findOrFail($id);

        $request->validate([
            'nim' => 'digits:11|numeric|regex:/^0+\d\d\d\d\d$/|unique:panitia,nim,' . $id . ',id',
            'name' => 'string',
            'email' => 'email|unique:panitia,email,' . $id . ',id',
            'program_studi' => 'string',
            'angkatan' => 'required|string:4|regex:/^[0-9]*$/',
            'vaccine_certificate' => 'image|mimes:jpeg,jpg,png,bmp',
            'division_1' => 'string',
            'division_2' => 'string',
            'phone_number' => 'numeric:11|unique:panitia,phone_number,' . $id . ',id',
            'reason_1' => 'string',
            'reason_2' => 'string',
            'portofolio' => 'url',
            'id_line' => 'string',
            'instagram_account' => '',
            'city' => 'string',
            'is_accepted' => 'numeric:1'
        ]);

        $input = collect(request()->all())->filter()->all();

        if ($request->vaccine_certificate) {
            $imageFolder = Storage::disk('vaccine_image');
            if ($imageFolder->exists($panitia->vaccine_certificate)) {
                $imageFolder->delete($panitia->vaccine_certificate);
            }
            $filename = Str::random(25);
            $extension = $request->vaccine_certificate->extension();

            Storage::putFileAs('public/vaccine_image', $request->vaccine_certificate, $filename . '.' . $extension);
            $panitia->update($input);
            $panitia->vaccine_certificate = $filename . '.' . $extension;
            $panitia->save();
        } else {
            $panitia->update($input);
        }

        $panitia->save();

        $service = new GoogleSheetController();
        $service->init();

        if ($panitia) {
            return response()->json([
                'success' => true,
                'data' => new PanitiaResource($panitia, 201),
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
        $panitia = panitia::findOrFail($id);
        $imageFolder = Storage::disk('vaccine_image');
        if ($imageFolder->exists($panitia->vaccine_certificate)) {
            $imageFolder->delete($panitia->vaccine_certificate);
        }
        $panitia->forceDelete();

        $service = new GoogleSheetController();
        $service->init();

        if ($panitia) {
            return response()->json([
                'success' => true,
                'msg'    => "Panitia " . $panitia->name . " has been deleted!",
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 409);
        }
    }

    public function delete_all()
    {
        $panitia = panitia::All();

        foreach ($panitia as $p) {
            $imageFolder = Storage::disk('vaccine_image');
            if ($imageFolder->exists($p->vaccine_certificate)) {
                $imageFolder->delete($p->vaccine_certificate);
            }
            $p->forceDelete();
        }

        $service = new GoogleSheetController();
        $service->init();

        if ($panitia) {
            return response()->json([
                'success' => true,
                'msg'    => " All Panitia " . "has been deleted!",
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 404);
        }
    }
}
