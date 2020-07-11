<?php


namespace Tupy\AddressesManager\Traits;


use App\Models\Address;

trait CrudAddress
{
    protected function storeCrud()
    {
        $validator = self::validator(request()->all());

        if ($validator->fails()) {
            return  response()->json(['errors' => $validator->errors()], 422);
        }

        return new JsonResource(Address::create(request()->all()));
    }

    public function showCrud($id)
    {
        return new JsonResource(Address::findOrFail($id));
    }

    public function updateCrud($id)
    {

        $validator = self::validator(request()->all());

        if ($validator->fails()) {
            return  response()->json(['errors' => $validator->errors()], 422);
        }

        $address = Address::findOrFail($id);

        $address->update(request()->all());

        return new JsonResource($address);
    }

    public function destroyCrud($id)
    {
        $address = Address::findOrFail($id);

        try {
            $address->delete();
            return new JsonResource('Apagado com sucesso');
        } catch (\Exception $e) {
            return new JsonResource('Erro ao apagar', 500);
        }
    }

    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'zip_code' => "required"
        ]);
    }
}
