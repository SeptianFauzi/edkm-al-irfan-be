<?php

namespace App\Http\Controllers;

use App\Model\PesertaModel;
use App\Model\ZakatFitrahReceived;
use App\Model\Celengan;
use App\Model\QurbanSent;
use App\Model\ZakatFitrahSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PesertaModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $peserta = PesertaModel::orderBy('id', 'asc')->get();
        if ($peserta) {
            $status = 'Success';
            $data = $peserta;
            $message = 'Success Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $data = null;
            $message = 'Failed Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_person' => 'required',
            'service_money' => 'required',
            'service_zakat_sent' => 'required',
            'service_zakat_received' => 'required',
            'service_qurban_received' => 'required',
            'service_qurban_sent' => 'required',
        ]);

        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $data = array(
                'name' => $request->name,
                'is_person' => $request->is_person,
                'service_money' => $request->service_money,
                'service_zakat_sent' => $request->service_zakat_sent,
                'service_zakat_received' => $request->service_zakat_received,
                'service_qurban_sent' => $request->service_qurban_sent,
                'service_qurban_received' => $request->service_qurban_received,
                'url_qrcode' => $request->url_qrcode,
                'notes' => $request->notes,
                'phone' => $request->phone,
            );
            $pesertaSave = PesertaModel::create($data);
            if ($pesertaSave) {
                $status = 'Success';
                $message = 'Data Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $pesertaSave], 201);
            } else {
                $status = 'Failed';
                $message = 'Data Not Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PesertaModel  $pesertaModel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // echo $request->get('id');
        $peserta = PesertaModel::where('id', $id)->get();
        if ($peserta) {
            $status = 'Success';
            $message = 'Success Get Detail Peserta';
            $data = $peserta;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Failed Get Detail Peserta';
            $data = [];
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PesertaModel  $pesertaModel
     * @return \Illuminate\Http\Response
     */
    public function edit(PesertaModel $pesertaModel)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PesertaModel  $pesertaModel
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'is_person' => 'required',
            'service_money' => 'required',
            'service_zakat_sent' => 'required',
            'service_zakat_received' => 'required',
            'service_qurban_received' => 'required',
            'service_qurban_sent' => 'required',
        ]);

        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $data = array(
                'name' => $request->name,
                'is_person' => $request->is_person,
                'service_money' => $request->service_money,
                'service_zakat_sent' => $request->service_zakat_sent,
                'service_zakat_received' => $request->service_zakat_received,
                'service_qurban_sent' => $request->service_qurban_sent,
                'service_qurban_received' => $request->service_qurban_received,
                'url_qrcode' => $request->url_qrcode,
                'notes' => $request->notes,
                'phone' => $request->phone,
            );
            $pesertaUpdate = PesertaModel::where('id', $id)->update($data);
            if ($pesertaUpdate) {
                $status = 'Success';
                $message = 'Data Updated';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
            } else {
                $status = 'Failed';
                $message = 'Data Not Updated';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PesertaModel  $pesertaModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesertaDelete = PesertaModel::find($id)->delete();
        if ($pesertaDelete) {
            $status = 'Success';
            $message = 'Data Deleted';
            $data = $pesertaDelete;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Data Not Deleted';
            $data = $pesertaDelete;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getpesertazakatfitrahsent(Request $request)
    {
        $peserta = PesertaModel::whereNotIn('id', ZakatFitrahSent::where('year_hijriah', $request->year_hijriah)->get('id_peserta')->toArray())->where('service_zakat_sent', true)->orderBy('id', 'asc')->get();

        if ($peserta) {
            $status = 'Success';
            $data = $peserta;
            $message = 'Success Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $data = [];
            $message = 'Failed Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getpesertazakatfitrahreceived(Request $request)
    {
        $peserta = PesertaModel::whereNotIn('id', ZakatFitrahReceived::where('year_hijriah', $request->year_hijriah)->get('id_peserta')->toArray())->where('service_zakat_received', true)->orderBy('id', 'asc')->get();
        // $peserta = DB::select("SELECT * FROM peserta WHERE id NOT IN (SELECT id_peserta FROM service_zakat_received WHERE year_hijriah = '".$request->year_hijriah."' AND deleted_at = null) AND service_zakat_received = 'true' AND deleted_at = null") ;
        if ($peserta) {
            $status = 'Success';
            $data = $peserta;
            $message = 'Success Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $data = [];
            $message = 'Failed Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getpesertacelengan(Request $request)
    {
        $peserta = PesertaModel::whereNotIn('id', Celengan::where('year_hijriah', $request->year_hijriah)->get('id_peserta')->toArray())->where('service_money', true)->orderBy('id', 'asc')->get();
        // $peserta = DB::select("SELECT * FROM peserta WHERE id NOT IN (SELECT id_peserta FROM service_zakat_received WHERE year_hijriah = '".$request->year_hijriah."' AND deleted_at = null) AND service_zakat_received = 'true' AND deleted_at = null") ;
        if ($peserta) {
            $status = 'Success';
            $data = $peserta;
            $message = 'Success Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $data = [];
            $message = 'Failed Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getpesertaqurbansent(Request $request)
    {
        $peserta = PesertaModel::whereNotIn('id', QurbanSent::where('year_hijriah', $request->year_hijriah)->get('id_peserta')->toArray())->where('service_qurban_sent', true)->orderBy('id', 'asc')->get();

        $data = [];



        if ($peserta) {
            foreach ($peserta as $key => $peserta) {
                array_push($data, [
                    'id' => $peserta->id,
                    'name' => $peserta->name,
                    'is_person' => $peserta->is_person,
                    'service_qurban_sent' => $peserta->service_qurban_sent,
                    'amount_sent' => 1,
                    'amount_type' => 'Bungkus'
                ]);
            }
            $status = 'Success';
            $data = $data;
            $message = 'Success Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $data = [];
            $message = 'Failed Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
}
