<?php


namespace App\Admin\Controllers;


use App\PoFactoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoFactoryFactoryController extends ResponseController
{

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_factory_id' => 'required|integer',
            'factory_id'   => 'required|integer',
            'remarks'      => 'nullable',
            'product'      => 'nullable',
        ], [
            'factory_id.required' => 'Please choose factory',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        PoFactoryFactory::create($validator->validated());

        return $this->responseSuccess(true);
    }

    public function delete($id)
    {
        PoFactoryFactory::destroy($id);

        return $this->responseSuccess(true);
    }
}
