<?php

namespace App\Services;


use App\Entities\Store;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;

class StoreCreateService
{
    protected $storeModel;

    public function __construct(
        Store $storeModel
    ) {
        $this->storeModel = $storeModel;
    }

    public function createStore(string $storeName,float $lat,float $lon) {
        try {
            DB::beginTransaction();
            $store = $this->storeModel::query()
                ->create([
                    'store_name' => $storeName,
                    'lat' => $lat,
                    'lon' => $lon,
                ]);
            $store->update([
                'store_code' => sprintf("%015d", $store->id),
            ]);
            $store = $this->storeModel::query()->find($store->id);
            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomException($e->getMessage(), CustomException::ERROR_CODE_STORE_CODE_FAIL_TO_GENERATE);
        }
    }
}
