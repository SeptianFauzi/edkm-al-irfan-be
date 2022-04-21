<?php

namespace App\Http\Controllers;

use App\Model\ZakatFitrahSent as ModelZakatFitrahSent;
use App\ZakatFitrah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZakatFitrahSentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $zakatfitrah = ModelZakatFitrahSent::where('year_hijriah', $request->year_hijriah)->with('id_user_service_zakat:id', 'id_user_zakat_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($zakatfitrah) {
            $status = 'Success';
            $data = $zakatfitrah;
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
            'amount_sent' => 'required|numeric',
            'year_hijriah' => 'required|numeric',
        ]);

        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $id_peserta = $request->id_peserta;
            $dataAllzakat = [];
            foreach ($id_peserta as $key => $id_peserta) {

                $data = array(
                    'id_user' => $request->id_user,
                    'id_peserta' => $id_peserta,
                    'amount_sent' => $request->amount_sent,
                    'year_hijriah' => $request->year_hijriah,
                    'is_zakat_sent' => false,
                    'notes' => $request->notes
                );
                $zakatFitrahSave = ModelZakatFitrahSent::create($data);
                $dataZakatFitrah = ModelZakatFitrahSent::where('id', $zakatFitrahSave->id)->where('year_hijriah', $zakatFitrahSave->year_hijriah)->first();
                array_push($dataAllzakat, $dataZakatFitrah);
            }
            if ($zakatFitrahSave) {
                $status = 'Success';
                $message = 'Data Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $dataAllzakat], 201);
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
     * @param  \App\ZakatFitrah  $zakatFitrah
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zakatFitrah = ModelZakatFitrahSent::where('id', $id)->with('id_user_service_zakat:id', 'id_user_zakat_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name')->get();
        if ($zakatFitrah) {
            $status = 'Success';
            $message = 'Success Get Detail Peserta';
            $data = $zakatFitrah;
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
     * @param  \App\ZakatFitrah  $zakatFitrah
     * @return \Illuminate\Http\Response
     */
    public function edit(ZakatFitrah $zakatFitrah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ZakatFitrah  $zakatFitrah
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'year_hijriah' => 'required|max:255',
            // 'amount_sent' => 'required|numeric',
            // 'amount_received' => 'required|numeric',
            // 'is_zakat_sent' => 'required|boolean',
            // 'is_zakat_received' => 'required|boolean'
        ]);

        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $zakatFitrahGet = ModelZakatFitrahSent::where('id', $id)->first();
            if ($zakatFitrahGet->is_zakat_sent == false && $request->is_zakat_sent == true || $zakatFitrahGet->amount_sent != $request->amount_sent) {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount_sent' => $request->amount_sent,
                    'notes' => $request->notes,
                    'date_zakat_sent' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'id_user_zakat_sent' => $request->id_user,
                    'is_zakat_sent' => $request->is_zakat_sent,
                    'id_user_amount_sent_updated' => $request->id_user,
                );
                $zakatFitrahUpdate = ModelZakatFitrahSent::where('id', $id)->update($data);
            } else {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount_sent' => $request->amount_sent,
                    'notes' => $request->notes,
                    'is_zakat_sent' => $request->is_zakat_sent
                );
                $zakatFitrahUpdate = ModelZakatFitrahSent::where('id', $id)->update($data);
            }
            if ($zakatFitrahUpdate) {
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
     * @param  \App\ZakatFitrah  $zakatFitrah
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesertaDelete = ModelZakatFitrahSent::find($id)->delete();
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
    public function getStatusPesertaZakatFitrahSent(Request $request)
    {
        $zakatFitrah = ModelZakatFitrahSent::where('is_zakat_sent', $request->is_zakat_sent)->where('year_hijriah', $request->year_hijriah)->with('id_user_service_zakat:id', 'id_user_zakat_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($zakatFitrah) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $zakatFitrah;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $zakatFitrah;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getPesertaZakatFitrah(Request $request)
    {
        $zakatFitrah = ModelZakatFitrahSent::where('id_peserta', $request->id_peserta)->where('year_hijriah', $request->year_hijriah)->with('id_user_service_zakat:id,name', 'id_user_zakat_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name')->orderBy('id_peserta', 'asc')->get();
        if ($zakatFitrah) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $zakatFitrah;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $zakatFitrah;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
}
