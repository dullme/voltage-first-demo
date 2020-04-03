<?php


namespace App\Admin\Controllers;

use DB;
use App\Batch;
use App\Enums\BatchStatus;
use App\PoClient;
use App\PoFactory;
use App\PoFactoryHistory;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PoFactoryController extends ResponseController
{

    public function add($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_client_id' => 'required',
            'type'         => 'required',
            'factory_id'   => 'required|integer',
            'remarks'      => 'nullable'
        ], [
            'type.required'       => 'The Type of PO field is required.',
            'factory_id.required' => 'The Factory field is required.'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }
        $data = $validator->validated();

        $po_client_ids = PoClient::where('project_id', $id)->pluck('id');
        $po_factory = PoFactory::whereIn('po_client_id', $po_client_ids)->orderBy('id', 'desc')->first();
        $po_factory_count = $po_factory ? intval($po_factory->no) : 0;
        $data['no'] = sprintf("%02d", ++$po_factory_count);

        $po_factory = PoFactory::create($data);

        $data = PoFactory::with(['batches' => function ($query) {
            $query->orderBy('sequence', 'ASC');
        }])->find($po_factory->id);

        return $this->responseSuccess($data, 'Created');
    }

    public function getPoFactory($id)
    {
        $po_factory = PoFactory::with('poFactoryHistories')->findOrFail($id);

        return $this->responseSuccess($po_factory);
    }

    public function save($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'       => 'required|integer',
            'factory_id' => 'required|integer',
            'remarks'    => 'nullable'
        ], [
            'type_id.required'    => 'The Type of PO field is required.',
            'factory_id.required' => 'The Factory field is required.'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }
        $po_factory = PoFactory::findOrFail($id);

        $update_data = $validator->validated();

        $data1 = [
            'type'       => $po_factory->type,
            'factory_id' => $po_factory->factory_id,
            'remarks'    => $po_factory->remarks
        ];
        $data2 = [
            'type'       => intval($update_data['type']),
            'factory_id' => intval($update_data['factory_id']),
            'remarks'    => $update_data['remarks']
        ];

        if($data1 === $data2){
            return $this->setStatusCode(422)->responseError('Unmodified');
        }

        DB::transaction(function () use ($po_factory, $validator) {
            PoFactoryHistory::create([
                'po_factory_id' => $po_factory->id,
                'po_client_id' => $po_factory->po_client_id,
                'factory_id' => $po_factory->factory_id,
                'type' => $po_factory->type,
                'no' => $po_factory->no,
                'number' => $po_factory->number,
                'remarks' => $po_factory->remarks,
                'created_at' => $po_factory->created_at,
                'updated_at' => $po_factory->updated_at,
            ]);
            PoFactory::where('id', $po_factory->id)->update(array_merge($validator->validated(),['number' => ++$po_factory->number]));
        });

        return $this->responseSuccess(true, 'Updated');
    }

    public function addBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_factory_id'                   => 'required',
            'name'                            => 'nullable',
            'sequence'                        => 'required|integer',
            'carrier'                         => 'nullable',
//            'b_l'                             => 'required|unique:batches',
            'b_l'                             => 'nullable',
            'shipping_method'                 => 'nullable',
            'vessel'                          => 'nullable',
            'container_no'                    => 'nullable',
            'remarks'                         => 'nullable',
            'port_of_departure'               => 'nullable',
            'destination_port'                => 'nullable',
            'rmb'                             => 'nullable|numeric',
            'foreign_currency'                => 'nullable|numeric',
            'foreign_currency_type'           => 'required_with:foreign_currency',
            'estimated_production_completion' => 'nullable|date',
            'etd_port'                        => 'nullable|date',
            'eta_port'                        => 'nullable|date',
            'eta_job_site'                    => 'nullable|date',
            'actual_production_completion'    => 'nullable|date',
            'atd_port'                        => 'nullable|date',
            'ata_port'                        => 'nullable|date',
            'ata_job_site'                    => 'nullable|date',
        ], [
            'name.required' => 'The sequence field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();

        if (isset($data['ata_job_site']) && $data['ata_job_site']) {
            $data['status'] = BatchStatus::Finished;
        } else if (isset($data['actual_production_completion']) && $data['actual_production_completion']) {
            $data['status'] = BatchStatus::Shipping;
        } else {
            $data['status'] = BatchStatus::InProduction;
        }

        Batch::create($data);

        return $this->responseSuccess(true, 'Created');
    }

    public function deleteFactory($id)
    {
        $po_factory = PoFactory::with(['batches' => function ($query) {
            $query->withTrashed();
        }])->findOrFail($id);

        if (count($po_factory->batches)) {
            return $this->setStatusCode(422)->responseError('Failed to delete');
        }

        $po_factory->delete();

        return $this->responseSuccess(true, 'Deleted');
    }

    //删除 Batch
    public function deleteBatch(Request $request, $id)
    {
        if ($request->input('force_delete') == 'true') {
            $batch = Batch::withTrashed()->findOrFail($id);
            $batch->forceDelete();
        } else {
            Batch::destroy($id);
        }

        return $this->responseSuccess(true, 'Deleted');
    }

    public function restoreBatch($id)
    {
        $batch = Batch::withTrashed()->findOrFail($id);
        $batch->restore();

        return $this->responseSuccess(true, 'restored');
    }

    //已删除的 Batch
    public function deletedBatch($id)
    {
        $batch = Batch::onlyTrashed()->where('po_factory_id', $id)->orderByDesc('deleted_at')->get();

        return $this->responseSuccess($batch);
    }

    public function getBatch($id)
    {
        $batch = Batch::with('poFactory')->findOrFail($id);

        return $this->responseSuccess($batch);
    }

    public function showBatch($id, Content $content)
    {
        $batch = Batch::with('poFactory.poClient.project')->findOrFail($id);

        return $content
            ->title(getSequence($batch->sequence))
            ->description($batch->name)
            ->row(view('admin.batch.show', compact('batch')));
    }

    public function editBatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                            => 'nullable',
            'sequence'                        => 'required|integer',
            'carrier'                         => 'nullable',
            'b_l'                             => [
                'nullable',
//                Rule::unique('batches')->ignore($id),
            ],
            'shipping_method'                 => 'nullable',
            'vessel'                          => 'nullable',
            'container_no'                    => 'nullable',
            'remarks'                         => 'nullable',
            'port_of_departure'               => 'nullable',
            'destination_port'                => 'nullable',
            'rmb'                             => 'nullable|numeric',
            'foreign_currency'                => 'nullable|numeric',
            'foreign_currency_type'           => 'required_with:foreign_currency',
            'estimated_production_completion' => 'nullable|date',
            'etd_port'                        => 'nullable|date',
            'eta_port'                        => 'nullable|date',
            'eta_job_site'                    => 'nullable|date',
            'actual_production_completion'    => 'nullable|date',
            'atd_port'                        => 'nullable|date',
            'ata_port'                        => 'nullable|date',
            'ata_job_site'                    => 'nullable|date',
        ], [
            'name.required' => 'The sequence field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();

        if (isset($data['ata_job_site']) && $data['ata_job_site']) {
            $data['status'] = BatchStatus::Finished;
        } else if (isset($data['actual_production_completion']) && $data['actual_production_completion']) {
            $data['status'] = BatchStatus::Shipping;
        } else {
            $data['status'] = BatchStatus::InProduction;
        }

        Batch::where('id', $id)->update($data);

        return $this->responseSuccess(true, 'Updated');
    }
}
