<?php

namespace App\Services;


use App\Entities\StoreCode;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;

class StoreCodeCreateService
{
    protected $storeCodeModel;

    public function __construct(
        StoreCode $storeCodeModel
    ) {
        $this->storeCodeModel = $storeCodeModel;
    }

    public function createStoreCode(string $storeName,float $lat,float $lon) {
        try {
            DB::beginTransaction();
            $storeCode = $this->storeCodeModel::query()
                ->create([
                    'store_name' => $storeName,
                    'lat' => $lat,
                    'lon' => $lon,
                ]);
            $storeCode->update([
                'store_code' => sprintf("%015d", $storeCode->id),
            ]);
            $storeCode = $this->storeCodeModel::query()->find($storeCode->id);
            DB::commit();
            $storeCodeExistService = new StoreCodeExistedService($this->storeCodeModel);
            $storeCodeExistService->setStoreCodeToRedis($storeCode);
            return $storeCode;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomException($e->getMessage(), CustomException::ERROR_CODE_STORE_CODE_FAIL_TO_GENERATE);
        }
    }
}
