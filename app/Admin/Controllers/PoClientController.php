<?php


namespace App\Admin\Controllers;

use App\PoClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoClientController extends ResponseController
{

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id'           => 'required',
            'no'                   => 'required',
            'voltage_no'           => 'required',
            'client_delivery_time' => 'nullable|date',
            'po_date'              => 'nullable|date',
        ], [
            'no.required' => 'The PO# Client field is required.'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $po_client = PoClient::create($validator->validated());

        $data = PoClient::with(['poFactories' => function ($query) {
            $query->with(['batches' => function ($query) {
                $query->orderBy('sequence', 'ASC');
            }]);
        }])->find($po_client->id);

        return $this->responseSuccess($data, 'Created');
    }

    public function getPoClient($id)
    {
        $po_client = PoClient::findOrFail($id);

        return $this->responseSuccess($po_client);
    }

    public function save($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no'                   => 'required',
            'voltage_no'           => 'required',
            'client_delivery_time' => 'nullable|date',
            'po_date'              => 'nullable|date',
        ], [
            'no.required' => 'The PO# Client field is required.'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        PoClient::where('id', $id)->update($validator->validated());

        return $this->responseSuccess(true, 'Updated');
    }

    public function delete($id)
    {
        $po_client = PoClient::with('poFactories')->findOrFail($id);

        if (count($po_client->poFactories)) {
            return $this->setStatusCode(422)->responseError('Failed to delete');
        }

        $po_client->delete();

        return $this->responseSuccess(true, 'Deleted');
    }

}
