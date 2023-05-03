<?php

namespace App\Http\Controllers;

use App\Models\panitia;
use App\Models\Ulympic;
use App\Http\Services\GoogleSheetsServices;
use App\Models\TimMobileLegend;
use Illuminate\Http\Request;


class GoogleSheetController extends Controller
{
    public function init()
    {
        $service = new GoogleSheetsServices();
        $service->DeleteSheet();
        $panitia = Panitia::get()->where('created_at', '>=', '2023-03-02')->toarray();

        if (!$panitia) {
            return response()->json('table Panitia is empty!');
        }
		

        foreach ($panitia as $value) {
			
			if($value['vaccine_certificate'] == 'none') {
				$imgLink = "none";
			}
			else {
					$imgLink = env('APP_URL'). 'laravel/storage/app/public/vaccine_image/'.  $value['vaccine_certificate'];
			}
			
            $arr[] =
                [
                    $value["nim"],
                    $value["name"],
                    $value['email'],
                    $value['program_studi'],
                    $value['angkatan'],
                    $imgLink,
                    $value['division_1'],
                    $value['division_2'],
                    $value['phone_number'],
                    $value['reason_1'],
                    $value['reason_2'],
                    $value['portofolio'],
                    $value['id_line'],
                    $value['instagram_account'],
                    $value['city'],
                    $value["is_accepted"]
                ];
        }

        $data =  $service->writeSheet($arr);

        if (!$data) {
            return response()->json([
                'success' => false,
                'msg' => 'Something When Wrong... try again later',
            ], 403);
        }

        return response()->json([
            'success' => true,
        ], 201);
    }

    public function appendData($panitia)
    {
        $service = new GoogleSheetsServices();
		
		if($panitia->vaccine_certificate != 'none') {
				$imgLink = env('APP_URL'). 'laravel/storage/app/public/vaccine_image/'.  $panitia->vaccine_certificate;		
			}
			else {
					$imgLink = $panitia->vaccine_certificate;
			}
		
        $arr[] =
            [
                $panitia->nim,
                $panitia->name,
                $panitia->email,
                $panitia->program_studi,
				$panitia->angkatan,
                $imgLink,
                $panitia->division_1,
                $panitia->division_2,
                $panitia->phone_number,
                $panitia->reason_1,
                $panitia->reason_2,
                $panitia->portofolio,
                $panitia->id_line,
                $panitia->instagram_account,
                $panitia->city,
                $panitia->is_accepted
            ];

        $data =  $service->appendSheet($arr);

        if (!$data) {
            return response()->json([
                'success' => false,
                'msg' => 'Something When Wrong... try again later',
            ], 403);
        }
        return response()->json([
            'success' => true,
        ], 201);
    }
	
	 public function initMlTeam()
    {

        $service = new GoogleSheetsServices();
        $service->DeleteSheet();
        $ulympic = Ulympic::all()->toArray();

        if (!$ulympic) {
            return response()->json('table ulympic is empty!');
        }


        foreach ($ulympic as $uly) {
			if ($uly['ketua'] == null) {
                $uly['ketua'] = 'none';
            }
            if ($uly['buktiPembayaran'] == null) {
                $buktiPembayaran = 'none';
            }
			else {
				 $buktiPembayaran = env('APP_URL'). 'laravel/storage/app/public/bukti_pembayaran/'. $uly['buktiPembayaran'];
			}
            $tim = TimMobileLegend::where('tokenID', $uly['tokenID'])->get();
            if (count($tim) == 0) {
                $arr[] = [
                    $uly['namaTim'],
                    $uly['ketua'],
                    $buktiPembayaran,
                ];
            }
            foreach ($tim as $member) {
				
				if ($member['buktiWA'] == null) {
                	$buktiWA = 'none';
            	}
				else {
					$buktiWA = env('APP_URL'). 'laravel/storage/app/public/bukti_WA/'. $member['buktiWA'];
				}
            	if ($member['fotoKtm'] == null) {
                	$fotoKtm = 'none';
           	 	}
				else {
					$fotoKtm = env('APP_URL'). 'laravel/storage/app/public/foto_ktm/'. $member['fotoKtm'];
				}

                $arr[] = [
                    $uly['namaTim'],
                    $uly['ketua'],
				    $buktiPembayaran,
					$buktiWA,
					$fotoKtm,
                    $member['nama'],
					$member['phoneNumber'],
                    $member['jurusan'],
                    $member['angkatan'],
                    $member['userID'],
                    $member['userName'],
                    $member['diterima'],
                ];
            }
        }
		 $service->writeSheet($arr);

        return response()->json([
            'success' => true,
        ], 201);
    }
}
