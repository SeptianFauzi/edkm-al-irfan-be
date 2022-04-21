<?php

namespace App\Http\Controllers;

use App\Model\QurbanSent as ModelQurbanSent;
use App\QurbanSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class QurbanSentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $qurbansent = ModelQurbanSent::where('year_hijriah', $request->year_hijriah)->with('id_user_service_qurban:id', 'id_user_qurban_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name,location')->orderBy('id_peserta', 'asc')->get();
        if ($qurbansent) {
            $status = 'Success';
            $data = $qurbansent;
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
            'peserta_qurban' => 'required|array',
            'year_hijriah' => 'required|numeric',
            'peserta_qurban.*.amount_sent' => 'required|numeric',
            'peserta_qurban.*.id_peserta' => 'required|numeric',
            'peserta_qurban.*.amount_type' => 'required',
        ]);
        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $peserta_qurban = $request->peserta_qurban;
            $dataAllqurban = [];
            foreach ($peserta_qurban as $key => $peserta_qurban) {
                $data = array(
                    'id_user' => $request->id_user,
                    'id_peserta' => $peserta_qurban['id_peserta'],
                    'amount_sent' => $peserta_qurban['amount_sent'],
                    'amount_type' => $peserta_qurban['amount_type'],
                    'year_hijriah' => $request->year_hijriah,
                    'notes' => $request->notes
                );
                $qurbanSentSave = ModelQurbanSent::create($data);
                $dataQurbanSent = ModelQurbanSent::where('id', $qurbanSentSave->id)->where('year_hijriah', $qurbanSentSave->year_hijriah)->first();
                array_push($dataAllqurban, $dataQurbanSent);
            }
            if ($qurbanSentSave) {
                $status = 'Success';
                $message = 'Data Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $dataAllqurban], 201);
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
     * @param  \App\QurbanSent  $qurbanSent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $qurbanSent = ModelQurbanSent::where('id', $id)->with('id_user_service_qurban:id', 'id_user_qurban_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name,location')->get();
        if ($qurbanSent) {
            $status = 'Success';
            $message = 'Success Get Detail Peserta';
            $data = $qurbanSent;
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
     * @param  \App\QurbanSent  $qurbanSent
     * @return \Illuminate\Http\Response
     */
    public function edit(QurbanSent $qurbanSent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QurbanSent  $qurbanSent
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'year_hijriah' => 'required|max:255',
            'amount_sent' => 'required|numeric',
            'amount_type' => 'required',
            // 'amount_sent' => 'required|numeric',
            // 'amount_received' => 'required|numeric',
            // 'is_qurban_sent' => 'required|boolean',
            // 'is_qurban_received' => 'required|boolean'
        ]);

        if ($validated->fails()) {
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        } else {
            $qurbanSentGet = ModelQurbanSent::where('id', $id)->first();
            if ($qurbanSentGet->is_qurban_sent == false && $request->is_qurban_sent == true || $qurbanSentGet->amount_sent != $request->amount_sent) {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount_sent' => $request->amount_sent,
                    'amount_type' => $request->amount_type,
                    'year_hijriah' => $request->year_hijriah,
                    'notes' => $request->notes,
                    'date_qurban_sent' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'id_user_qurban_sent' => $request->id_user,
                    'is_qurban_sent' => $request->is_qurban_sent,
                    'id_user_amount_sent_updated' => $request->id_user,
                );
                $qurbanSentUpdate = ModelQurbanSent::where('id', $id)->update($data);
            } else {
                $data = array(
                    'year_hijriah' => $request->year_hijriah,
                    'amount_sent' => $request->amount_sent,
                    'amount_type' => $request->amount_type,
                    'year_hijriah' => $request->year_hijriah,
                    'notes' => $request->notes,
                    'is_qurban_sent' => $request->is_qurban_sent
                );
                $qurbanSentUpdate = ModelQurbanSent::where('id', $id)->update($data);
            }
            if ($qurbanSentUpdate) {
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
     * @param  \App\QurbanSent  $qurbanSent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesertaDelete = ModelQurbanSent::find($id)->delete();
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
    public function getPesertaQurbanSent(Request $request)
    {
        $qurbanSent = ModelQurbanSent::where('id_peserta', $request->id_peserta)->where('year_hijriah', $request->year_hijriah)->with('id_user_service_qurban:id', 'id_user_qurban_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name,location')->orderBy('id_peserta', 'asc')->get();
        if ($qurbanSent) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $qurbanSent;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $qurbanSent;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function getPesertaStatusQurbanSent(Request $request)
    {
        $qurbanSent = ModelQurbanSent::where('year_hijriah', $request->year_hijriah)->where('is_qurban_sent', $request->is_qurban_sent)->whereHas('id_peserta_peserta', function (Builder $query) use ($request) {
            if ($request->location) {
                $query->where('location', $request->location);
            }
        })->with('id_user_service_qurban:id', 'id_user_qurban_sent_users:id,name', 'id_user_amount_sent_updated_users:id,name', 'id_peserta_peserta:id,name,location')->orderBy('id_peserta', 'asc')->get();
        if ($qurbanSent) {
            $status = 'Success';
            $message = 'Success Get Data';
            $data = $qurbanSent;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        } else {
            $status = 'Failed';
            $message = 'Success Get Data';
            $data = $qurbanSent;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
}
