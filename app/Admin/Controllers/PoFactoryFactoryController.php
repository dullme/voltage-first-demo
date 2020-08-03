<?php


namespace App\Admin\Controllers;


use App\PoClient;
use App\PoFactory;
use App\PoFactoryFactory;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoFactoryFactoryController extends ResponseController
{

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_factory_id' => 'required|integer',
            'factory_id'    => 'required|integer',
            'no'            => 'nullable',
            'remarks'       => 'nullable',
            'product'       => 'nullable',
        ], [
            'factory_id.required' => 'Please choose factory',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $created = PoFactoryFactory::create($validator->validated());

        $res = PoFactoryFactory::with('factory')->find($created->id);

        $pof=PoFactory::find($request->input('po_factory_id'));
        $p = PoClient::find($pof->po_client_id);
        $project = Project::find($p->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        return $this->responseSuccess($res);
    }

    public function delete($id)
    {
        $poff = PoFactoryFactory::find($id);
        $pof=PoFactory::find($poff->po_factory_id);
        $p = PoClient::find($pof->po_client_id);
        $project = Project::find($p->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        PoFactoryFactory::destroy($id);

        return $this->responseSuccess(true);
    }

    public function showFactoryFactory($id)
    {
        $po_factory_factory = PoFactoryFactory::findOrFail($id);

        return $this->responseSuccess($po_factory_factory);
    }

    public function editFactoryFactory($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_factory_id' => 'required|integer',
            'factory_id'    => 'required|integer',
            'no'            => 'nullable',
            'remarks'       => 'nullable',
            'product'       => 'nullable',
        ], [
            'factory_id.required' => 'Please choose factory',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        PoFactoryFactory::where('id', $id)->update($validator->validated());

        $res = PoFactoryFactory::with('factory')->find($id);

        $pof=PoFactory::find($request->input('po_factory_id'));
        $p = PoClient::find($pof->po_client_id);
        $project = Project::find($p->project_id);
        $project->updated_at = Carbon::now();
        $project->save();

        return $this->responseSuccess($res);
    }
}
