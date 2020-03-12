<?php


namespace App\Admin\Controllers;

use App\Batch;
use App\Enums\BatchStatus;
use App\PoFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PoFactoryController extends ResponseController
{

    public function add(Request $request)
    {
        $data = $request->validate([
            'po_client_id' => 'required',
            'no'           => 'required'
        ], [
            'no.required' => 'The PO# Factory field is required.'
        ]);

        PoFactory::create($data);

        return $this->responseSuccess('true', 'Created');
    }

    public function addBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_factory_id'                   => 'required',
            'name'                            => 'required',
            'sequence'                        => 'nullable|integer',
            'carrier'                         => 'nullable',
//            'b_l'                             => 'required|unique:batches',
            'b_l'                             => 'nullable',
            'shipping_method'                 => 'nullable',
            'vessel'                          => 'nullable',
            'container_no'                    => 'nullable',
            'remarks'                         => 'nullable',
            'estimated_production_completion' => 'nullable|date',
            'etd_port'                        => 'nullable|date',
            'eta_port'                        => 'nullable|date',
            'eta_job_site'                    => 'nullable|date',
            'actual_production_completion'    => 'nullable|date',
            'atd_port'                        => 'nullable|date',
            'ata_port'                        => 'nullable|date',
            'ata_job_site'                    => 'nullable|date',
        ], [
            'name.required' => 'The project name field is required.',
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

    public function editBatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                            => 'required',
            'sequence'                        => 'nullable|integer',
            'carrier'                         => 'nullable',
            'b_l'                             => [
                'nullable',
//                Rule::unique('batches')->ignore($id),
            ],
            'shipping_method'                 => 'nullable',
            'vessel'                          => 'nullable',
            'container_no'                    => 'nullable',
            'remarks'                         => 'nullable',
            'estimated_production_completion' => 'nullable|date',
            'etd_port'                        => 'nullable|date',
            'eta_port'                        => 'nullable|date',
            'eta_job_site'                    => 'nullable|date',
            'actual_production_completion'    => 'nullable|date',
            'atd_port'                        => 'nullable|date',
            'ata_port'                        => 'nullable|date',
            'ata_job_site'                    => 'nullable|date',
        ], [
            'name.required' => 'The project name field is required.',
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
