<?php


namespace App\Admin\Controllers;

use App\Container;
use App\Project;
use App\USCarriers;
use Carbon\Carbon;
use DB;
use App\Batch;
use App\Enums\BatchStatus;
use App\PoClient;
use App\PoFactory;
use App\PoFactoryHistory;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoFactoryController extends ResponseController
{

    public function add($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_client_id' => 'required',
            'no'           => 'required',
            'type'         => 'required',
            'factory_id'   => 'nullable|integer',
            'remarks'      => 'nullable',
            'product'      => 'nullable',
        ], [
            'type.required' => 'The Type of PO field is required.',
            'no.required'   => 'The PO factory field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }
//        $data = $validator->validated();

//        $po_client_ids = PoClient::where('project_id', $id)->pluck('id');
//        $po_factory = PoFactory::whereIn('po_client_id', $po_client_ids)->orderBy('id', 'desc')->first();
//        $po_factory_count = $po_factory ? intval($po_factory->no) : 0;
//        $data['no'] = sprintf("%02d", ++$po_factory_count);

        $po_factory = PoFactory::create($validator->validated());

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
            'no'         => 'required',
            'factory_id' => 'nullable|integer',
            'remarks'    => 'nullable',
            'product'    => 'nullable',
        ], [
            'type_id.required' => 'The Type of PO field is required.',
            'no.required'      => 'The PO factory field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }
        $po_factory = PoFactory::findOrFail($id);

//        $update_data = $validator->validated();

//        $data1 = [
//            'type'       => $po_factory->type,
//            'factory_id' => $po_factory->factory_id,
//            'remarks'    => $po_factory->remarks
//        ];
//        $data2 = [
//            'type'       => intval($update_data['type']),
//            'factory_id' => intval($update_data['factory_id']),
//            'remarks'    => $update_data['remarks']
//        ];
//
//        if ($data1 === $data2) {
//            return $this->setStatusCode(422)->responseError('Unmodified');
//        }

        PoFactory::where('id', $po_factory->id)->update($validator->validated());

//        DB::transaction(function () use ($po_factory, $validator) {
//            PoFactoryHistory::create([
//                'po_factory_id' => $po_factory->id,
//                'po_client_id'  => $po_factory->po_client_id,
//                'factory_id'    => $po_factory->factory_id,
//                'type'          => $po_factory->type,
//                'no'            => $po_factory->no,
//                'number'        => $po_factory->number,
//                'remarks'       => $po_factory->remarks,
//                'created_at'    => $po_factory->created_at,
//                'updated_at'    => $po_factory->updated_at,
//            ]);
//            PoFactory::where('id', $po_factory->id)->update(array_merge($validator->validated(), ['number' => ++$po_factory->number]));
//        });

        return $this->responseSuccess(true, 'Updated');
    }

    public function addBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_factory_id'                   => 'required',
            'name'                            => 'nullable',
            'sequence'                        => 'required|integer',
            'carrier'                         => 'nullable',
            'ocean_forwarder'                 => 'nullable|integer',
            'inland_forwarder'                => 'nullable|integer',
            'china_inland_forwarder'          => 'nullable|integer',
            'b_l'                             => 'nullable',
            'shipping_method'                 => 'nullable',
            'vessel'                          => 'nullable',
            'remarks'                         => 'nullable',
            'port_of_departure'               => 'nullable',
            'destination_port'                => 'nullable',
            'rmb'                             => 'nullable|numeric',
            'foreign_currency'                => 'nullable|numeric',
            'foreign_currency_type'           => 'required_with:foreign_currency',
            'estimated_production_completion' => 'required_with:etd_port|nullable|date',
            'etd_port'                        => 'required_with:eta_port|nullable|date',
            'eta_port'                        => 'nullable|date',
            'actual_production_completion'    => 'required_with:atd_port|nullable|date',
            'atd_port'                        => 'required_with:ata_port|nullable|date',
            'ata_port'                        => 'nullable|date',
            'apc_remarks'                     => 'nullable',
            'atd_remarks'                     => 'nullable',
            'invoice_no'                      => 'nullable',
            'delivery_date'                   => 'nullable|date',
            'invoice_date'                    => 'nullable|date',
            'shipping_ate'                    => 'nullable|date',
        ], [
            'name.required' => 'The sequence field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        if ($request->input('etd_port') && $request->input('etd_port') < $request->input('estimated_production_completion')) {
            return $this->setStatusCode(422)->responseError('ETD Port 必须大于等于 EPC');
        }

        if ($request->input('eta_port') && $request->input('eta_port') < $request->input('etd_port')) {
            return $this->setStatusCode(422)->responseError('ETA Port 必须大于等于 ETD Port');
        }

        if ($request->input('atd_port') && $request->input('atd_port') < $request->input('actual_production_completion')) {
            return $this->setStatusCode(422)->responseError('ATD Port 必须大于等于 APC');
        }

        if ($request->input('ata_port')) {
            if ($request->input('ata_port') < $request->input('atd_port')) {
                return $this->setStatusCode(422)->responseError('ATA Port 必须大于等于 ATD Port');
            }
            if ($request->input('ata_port') > Carbon::today()->toDateString()) {
                return $this->setStatusCode(422)->responseError('ATA Port 必须小于等于今天');
            }
        }

        $data = $validator->validated();

        if (isset($data['actual_production_completion']) && $data['actual_production_completion']) {
            $data['status'] = BatchStatus::Shipping;
        } else {
            $data['status'] = BatchStatus::InProduction;
        }

        $data['epc_history'] = isset($data['estimated_production_completion']) && $data['estimated_production_completion'] ? [
            [
                'estimated'  => $data['estimated_production_completion'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ] : [];

        $data['etd_port_history'] = isset($data['etd_port']) && $data['etd_port'] ? [
            [
                'estimated'  => $data['etd_port'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ] : [];

        $data['eta_port_history'] = isset($data['eta_port']) && $data['eta_port'] ? [
            [
                'estimated'  => $data['eta_port'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ] : [];

        $poFactory = PoFactory::with('poClient.project')->find($data['po_factory_id']);
        $data['project_id'] = $poFactory->poClient->project->id;


        $batch = Batch::create($data);

        $batch = Batch::orderBy('sequence', 'ASC')->find($batch->id);

        $project = Project::find($data['project_id']);
        $project->updated_at = Carbon::now();
        $project->save();

        return $this->responseSuccess($batch, 'Created');
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
        $batch = Batch::with('poFactory.poClient.project.author', 'containers.uSCarriers', 'oceanForwarder.forwarder', 'inlandForwarder.forwarder', 'chinaInlandForwarder.forwarder')->findOrFail($id);

        $url = url('/admin/projects/' . $batch->poFactory->poClient->project->id);
        $text = "<a href='{$url}' >{$batch->poFactory->poClient->project->name}</a>";

        $batch->setAttribute('epc_color', getWarning($batch->estimated_production_completion, $batch->actual_production_completion));
        $batch->setAttribute('etd_color', getWarning($batch->etd_port, $batch->atd_port));
        $batch->setAttribute('eta_color', getWarning($batch->eta_port, $batch->ata_port));
        $batch->setAttribute('eta_job_site_color', getWarning($batch->eta_job_site, $batch->ata_job_site));


        $containers = $batch->containers->map(function ($container) {
            $container['eta_job_site_color'] = getWarning($container->eta_job_site, $container->ata_job_site);
            $container['containers'] = $a = Container::with('batch.project')->where('no', $container->no)->where('id', '!=', $container->id)->get();

            return $container;
        });

        $batch->setAttribute('containers', $containers);

        $us_carriers = USCarriers::all();

        return $content
            ->title($text)
            ->description(getSequence($batch->sequence) . ' : ' . $batch->name)
            ->row(view('admin.batch.show', compact('batch', 'us_carriers')));
    }

    public function editBatch(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                            => 'nullable',
            'sequence'                        => 'required|integer',
            'carrier'                         => 'nullable',
            'ocean_forwarder'                 => 'nullable|integer',
            'inland_forwarder'                => 'nullable|integer',
            'china_inland_forwarder'          => 'nullable|integer',
            'b_l'                             => [
                'nullable',
//                Rule::unique('batches')->ignore($id),
            ],
            'shipping_method'                 => 'nullable',
            'vessel'                          => 'nullable',
            'remarks'                         => 'nullable',
            'port_of_departure'               => 'nullable',
            'destination_port'                => 'nullable',
            'rmb'                             => 'nullable|numeric',
            'foreign_currency'                => 'nullable|numeric',
            'foreign_currency_type'           => 'required_with:foreign_currency',
            'estimated_production_completion' => 'required_with:etd_port|nullable|date',
            'etd_port'                        => 'required_with:eta_port|nullable|date',
            'eta_port'                        => 'nullable|date',
            'actual_production_completion'    => 'required_with:atd_port|nullable|date',
            'atd_port'                        => 'required_with:ata_port|nullable|date',
            'ata_port'                        => 'nullable|date',
            'apc_remarks'                     => 'nullable',
            'atd_remarks'                     => 'nullable',
            'ata_remarks'                     => 'nullable',
            'invoice_no'                      => 'nullable',
            'delivery_date'                   => 'nullable|date',
            'invoice_date'                    => 'nullable|date',
            'shipping_ate'                    => 'nullable|date',
            'invoice_display'                 => 'required|boolean',
        ], [
            'name.required' => 'The sequence field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        if ($request->input('etd_port') && $request->input('etd_port') < $request->input('estimated_production_completion')) {
            return $this->setStatusCode(422)->responseError('ETD Port 必须大于等于 EPC');
        }

        if ($request->input('eta_port') && $request->input('eta_port') < $request->input('etd_port')) {
            return $this->setStatusCode(422)->responseError('ETA Port 必须大于等于 ETD Port');
        }

        if ($request->input('atd_port') && $request->input('atd_port') < $request->input('actual_production_completion')) {
            return $this->setStatusCode(422)->responseError('ATD Port 必须大于等于 APC');
        }

        if ($request->input('ata_port')) {
            if ($request->input('ata_port') < $request->input('atd_port')) {
                return $this->setStatusCode(422)->responseError('ATA Port 必须大于等于 ATD Port');
            }
            if ($request->input('ata_port') > Carbon::today()->toDateString()) {
                return $this->setStatusCode(422)->responseError('ATA Port 必须小于等于今天');
            }
        }

        $data = $validator->validated();

        $eta_job_site = Container::where('batch_id', $id)->where('eta_job_site', '!=', null)->orderBy('eta_job_site', 'DESC')->first();
        $ata_job_site = Container::where('batch_id', $id)->where('ata_job_site', '!=', null)->orderBy('ata_job_site', 'DESC')->first();

        if ($eta_job_site && $data['eta_port'] > $eta_job_site->eta_job_site) {
            return $this->setStatusCode(422)->responseError('ETA Port must be less than or equal to ' . $eta_job_site->eta_job_site);
        }

        if ($ata_job_site && $data['ata_port'] > $ata_job_site->ata_job_site) {
            return $this->setStatusCode(422)->responseError('ATA Port must be less than or equal to ' . $ata_job_site->ata_job_site);
        }

        if (is_null($data['eta_port']) && $eta_job_site) {
            return $this->setStatusCode(422)->responseError('Please set eta port');
        }

        if (is_null($data['ata_port']) && $ata_job_site) {
            return $this->setStatusCode(422)->responseError('Please set ata port');
        }

        $containerCount = Container::where('batch_id', $id)->count();
        $containerAtaJobSiteCount = Container::where('batch_id', $id)->where('ata_job_site', '!=', null)->count();

        if ($containerCount > 0 && $containerCount == $containerAtaJobSiteCount) {
            $data['status'] = BatchStatus::Finished;
        } else if (isset($data['actual_production_completion']) && $data['actual_production_completion']) {
            $data['status'] = BatchStatus::Shipping;
        } else {
            $data['status'] = BatchStatus::InProduction;
        }

        $batch = Batch::findOrFail($id);

        $data['epc_history'] = isset($data['estimated_production_completion']) && $data['estimated_production_completion'] != optional($batch->estimated_production_completion)->toDateString() ? array_merge($batch->epc_history, [
            [
                'estimated'  => $data['estimated_production_completion'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]) : $batch->epc_history;

        $data['etd_port_history'] = isset($data['etd_port']) && $data['etd_port'] != optional($batch->etd_port)->toDateString() ? array_merge($batch->etd_port_history, [
            [
                'estimated'  => $data['etd_port'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]) : $batch->etd_port_history;

        $data['eta_port_history'] = isset($data['eta_port']) && $data['eta_port'] != optional($batch->eta_port)->toDateString() ? array_merge($batch->eta_port_history, [
            [
                'estimated'  => $data['eta_port'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]) : $batch->eta_port_history;

        Batch::where('id', $id)->update($data);

        $project = Project::find($batch->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        $batch = Batch::orderBy('sequence', 'ASC')->find($batch->id);


        $batch->setAttribute('epc_color', getWarning($batch->estimated_production_completion, $batch->actual_production_completion));
        $batch->setAttribute('etd_color', getWarning($batch->etd_port, $batch->atd_port));
        $batch->setAttribute('eta_color', getWarning($batch->eta_port, $batch->ata_port));
        $batch->setAttribute('eta_job_site_color', getWarning($batch->eta_job_site, $batch->ata_job_site));

        return $this->responseSuccess($batch, 'Updated');
    }

    public function addContainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id'        => 'required|integer',
            'no'              => 'required',
            'type'            => 'nullable',
            'u_s_carriers_id' => 'nullable|integer',
            'amount'          => 'nullable',
            'remarks'         => 'nullable',
            'eta_job_site'    => 'nullable|date',
            'ata_job_site'    => 'nullable|date',
        ], [
            'no.required' => 'The No. field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();

        $batch = Batch::findOrFail($data['batch_id']);

        if ($batch->ata_port) {
            return $this->setStatusCode(422)->responseError('Can\'t add');
        }

        $save = false;
        $maxEtaJobSite = $this->getMaxEtaJobSite($data['batch_id']);

        if ($data['eta_job_site'] > $maxEtaJobSite) {
            $batch->eta_job_site = $data['eta_job_site'];
            $save = true;
        }

        $save ? $batch->save() : '';

        $data['eta_job_site_history'] = isset($data['eta_job_site']) && $data['eta_job_site'] ? [
            [
                'estimated'  => $data['eta_job_site'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ] : [];

        Container::create($data);

        $project = Project::find($batch->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        return $this->responseSuccess(true);
    }

    public function deleteContainer($id)
    {
        $container = Container::findOrFail($id);
        $batch = Batch::findOrFail($container->batch_id);

        $save = false;
        $maxEtaJobSite = $this->getMaxEtaJobSite($container->batch_id, $container->id);
        if (is_null($maxEtaJobSite)) {
            $batch->eta_job_site = null;
            $save = true;
        }

        $maxAtaJobSite = $this->getMaxAtaJobSite($container->batch_id, $container->id);
        if (is_null($maxAtaJobSite)) {
            $batch->ata_job_site = null;
            $save = true;
        }

        if (Container::where('batch_id', $container->batch_id)->count() == 1) {
            if (!is_null($batch->actual_production_completion)) {
                $batch->status = BatchStatus::Shipping;
            } else {
                $batch->status = BatchStatus::InProduction;
            }
            $batch->ata_job_site = null;
        }

        $save ? $batch->save() : '';
        Container::destroy($id);

        $project = Project::find($batch->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        return $this->responseSuccess(true, 'Deleted');
    }

    public function containerInfo($id)
    {
        $container = Container::findOrFail($id);

        return $this->responseSuccess($container);
    }

    public function editContainer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no'              => 'required',
            'type'            => 'nullable',
            'u_s_carriers_id' => 'nullable|integer',
            'amount'          => 'nullable',
            'remarks'         => 'nullable',
            'eta_job_site'    => 'nullable|date',
            'ata_job_site'    => 'nullable|date',
        ], [
            'no.required' => 'The No. field is required.',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();

        $container = Container::findOrFail($id);
        $batch = Batch::findOrFail($container->batch_id);

        $maxEtaJobSite = $this->getMaxEtaJobSite($container->batch_id, $container->id);
        $batch->eta_job_site = $maxEtaJobSite;
        if ($data['eta_job_site'] && $data['eta_job_site'] > $maxEtaJobSite) {
            $batch->eta_job_site = $data['eta_job_site'];
        }

        $maxAtaJobSite = $this->getMaxAtaJobSite($container->batch_id, $container->id);
        if ($data['ata_job_site'] && $this->needSetAtaJboSite($container->batch_id, $container->id)) {
            $batch->ata_job_site = $data['ata_job_site'] > $maxAtaJobSite ? $data['ata_job_site'] : $maxAtaJobSite;
        }


        $res = Container::where([
            'batch_id'     => $container->batch_id,
            'ata_job_site' => null,
        ])->where('id', '!=', $container->id)->count();

        if (!is_null($data['ata_job_site']) && $res == 0) {
            $batch->status = BatchStatus::Finished;
        }

        if (is_null($data['ata_job_site'])) {
            if (!is_null($batch->actual_production_completion)) {
                $batch->status = BatchStatus::Shipping;
            } else {
                $batch->status = BatchStatus::InProduction;
            }
            $batch->ata_job_site = null;
        }

        $data['eta_job_site_history'] = isset($data['eta_job_site']) && $data['eta_job_site'] != optional($container->eta_job_site)->toDateString() ? array_merge($container->eta_job_site_history, [
            [
                'estimated'  => $data['eta_job_site'],
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]) : $container->eta_job_site_history;

        $batch->save();

        Container::where('id', $id)->update($data);

        $project = Project::find($batch->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        return $this->responseSuccess(true, 'Updated');
    }

    public function getMaxEtaJobSite($batchId, $withoutContainerId = null)
    {
        $etaJobSite = Container::where('batch_id', $batchId)->orderBy('eta_job_site', 'DESC');
        $etaJobSite = $withoutContainerId ? $etaJobSite->where('id', '!=', $withoutContainerId)->first() : $etaJobSite->first();

        return $etaJobSite && $etaJobSite->eta_job_site ? $etaJobSite->eta_job_site : null;
    }

    public function getMaxAtaJobSite($batchId, $withoutContainerId = null)
    {
        $ataJobSite = Container::where('batch_id', $batchId)->orderBy('ata_job_site', 'DESC');
        $ataJobSite = $withoutContainerId ? $ataJobSite->where('id', '!=', $withoutContainerId)->first() : $ataJobSite->first();

        return $ataJobSite && $ataJobSite->ata_job_site ? $ataJobSite->ata_job_site : null;
    }

    public function needSetAtaJboSite($batchId, $withoutContainerId = null)
    {
        $container = Container::where('batch_id', $batchId);
        $container = $withoutContainerId ? $container->where('id', '!=', $withoutContainerId)->get() : $container->get();
        $count = $container->count();
        $res = $container->where('ata_job_site', '!=', null)->count();
        if ($count == $res) {
            return true;
        }

        return false;
    }
}
