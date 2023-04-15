<?php

namespace App\Http\Controllers;

use App\Model\Celengan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CelenganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $celengan = Celengan::where('year_hijriah', $request->year_hijriah)->with('id_user_service_money:id,name', 'id_user_money_received_users:id,name', 'id_user_money_box_sent_users:id,name', 'id_user_amount_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($celengan) {
            $status = 'Success';
            $data = $celengan;
            $message = 'Success Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $data = null;
            $message = 'Failed Get All Peserta';
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        }
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
        $validated = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'id_peserta' => 'required',
            'year_hijriah' => 'required|numeric',
        ]);

        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $id_peserta = $request->id_peserta;
            $dataAllmoney = [];
            foreach ($id_peserta as $key => $id_peserta) {
                $data = array(
                    'id_user' => $request->id_user,
                    'id_peserta' => $id_peserta,
                    'year_hijriah' => $request->year_hijriah,
                    'amount' => 0,
                    'is_money_received' => false,
                    'is_money_box_sent' => false,
                    'notes' => $request->notes
                );
                $celenganSave = Celengan::create($data);
                $datacelengan = Celengan::where('id', $celenganSave->id)->where('year_hijriah', $celenganSave->year_hijriah)->first();
                array_push($dataAllmoney, $datacelengan);
            }
            if ($celenganSave) {
                $status = 'Success';
                $message = 'Data Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $dataAllmoney], 201);
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
     * @param  \App\celengan  $celengan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $celengan = Celengan::where('id', $id)->with('id_user_service_money:id,name', 'id_user_money_received_users:id,name', 'id_user_amount_updated_users:id,name', 'id_peserta_peserta:id,name')->get();
        if ($celengan) {
            $status = 'Success';
            $message = 'Success Get Detail Peserta';
            $data = $celengan;
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
     * @param  \App\celengan  $celengan
     * @return \Illuminate\Http\Response
     */
    public function edit(celengan $celengan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\celengan  $celengan
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'year_hijriah' => 'required|max:255',
        ]);

        Log::info($request);
        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $celenganGet = Celengan::where('id', $id)->where('year_hijriah', $request->year_hijriah)->first();
            if (($celenganGet->is_money_received != $request->is_money_received || $celenganGet->amount != $request->amount) && ($celenganGet->is_money_box_sent != $request->is_money_box_sent)) {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'date_money_received' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'id_user_money_received' => $request->id_user,
                    'is_money_received' => $request->is_money_received,
                    'id_user_amount_updated' => $request->id_user,
                    'date_money_box_sent' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'id_user_money_box_sent' => $request->id_user,
                    'is_money_box_sent' => $request->is_money_received
                );
            } else if ($celenganGet->is_money_received != $request->is_money_received || $celenganGet->amount != $request->amount) {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'date_money_received' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'id_user_money_received' => $request->id_user,
                    'is_money_received' => $request->is_money_received,
                    'id_user_amount_updated' => $request->id_user
                );
            } else if ($celenganGet->is_money_box_sent != $request->is_money_box_sent) {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'date_money_box_sent' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'id_user_money_box_sent' => $request->id_user,
                    'is_money_box_sent' => $request->is_money_box_sent
                );
            } else {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'is_money_received' => $request->is_money_received,
                    'is_money_box_sent' => $request->is_money_box_sent
                );
            }
            Log::info("Ini Data",$data);
            $celenganUpdate = Celengan::where('id', $celenganGet->id)->update($data);
            if ($celenganUpdate) {
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
     * @param  \App\celengan  $celengan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesertaDelete = Celengan::find($id)->delete();
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
    public function getPesertaCelengan(Request $request)
    {
        $celengan = Celengan::where('id_peserta', $request->id_peserta)->where('year_hijriah', $request->year_hijriah)->with('id_user_service_money:id,name', 'id_user_money_received_users:id,name', 'id_user_money_box_sent_users:id,name', 'id_user_amount_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($celengan) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $celengan;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $celengan;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getPesertaStatusMoneyBoxSent(Request $request)
    {
        $celengan = Celengan::where('year_hijriah', $request->year_hijriah)->where('is_money_box_sent', $request->is_money_box_sent)->with('id_user_service_money:id,name', 'id_user_money_received_users:id,name', 'id_user_money_box_sent_users:id,name', 'id_user_amount_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($celengan) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $celengan;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $celengan;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getPesertaStatusMoneyreceived(Request $request)
    {
        $celengan = Celengan::where('year_hijriah', $request->year_hijriah)->where('is_money_received', $request->is_money_received)->with('id_user_service_money:id,name', 'id_user_money_received_users:id,name', 'id_user_money_box_sent_users:id,name', 'id_user_amount_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($celengan) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $celengan;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $celengan;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
}
