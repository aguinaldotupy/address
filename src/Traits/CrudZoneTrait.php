<?php

namespace Tupy\AddressesManager\Traits;

use Tupy\AddressesManager\Models\Zone;

trait CrudZoneTrait
{
    private $data = [];

    public function storeCrud()
    {
        $validator = self::validator(request()->all());

        if ($validator->fails()) {
            return  response()->json(['errors' => $validator->errors()], 401);
        }

        $zone = Zone::create(request()->all());


        $this->data = [
            'data' => $zone
        ];

        return response()->json($this->data, 200);
    }

    public function showCrud($id)
    {
        $this->data = [
            'data' => Zone::findOrFail($id)
        ];

        return response()->json($this->data, 200);
    }

    public function updateCrud($id)
    {
        $zone = Zone::findOrFail($id);

        $validator = self::validator(request()->all(), $zone->name);

        if ($validator->fails()) {
            return  response()->json(['errors' => $validator->errors()], 401);
        }

        $zone->update(request()->all());

        $this->data = [
            'data' => $zone
        ];

        return response()->json($this->data, 200);
    }

    public function destroyCrud($id)
    {
        $zone = Zone::findOrFail($id);

        try {
            $zone->delete();
            $message = 'Apagado com sucesso!';
        } catch (\Exception $e) {
            $message = 'Erro ao apagar';
        }

        return response()->json($message, 200);
    }

    protected function validator(array $data, $name = null)
    {
        return \Validator::make($data, [
            'name' => "required|string|unique:zones,name,{$name}"
        ]);
    }
}
