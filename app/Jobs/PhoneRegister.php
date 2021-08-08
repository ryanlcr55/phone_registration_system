<?php

namespace App\Jobs;

use App\Services\PhoneRegisterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PhoneRegister implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNum;
    protected $storeCode;
    protected $registrationDatetime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $phoneNum, string $storeCode, string $registrationDatetime)
    {
        $this->phoneNum = $phoneNum;
        $this->storeCode = $storeCode;
        $this->registrationDatetime = $registrationDatetime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var PhoneRegisterService $registerService */
        $registerService = resolve(PhoneRegisterService::class);
        $registerService->register($this->phoneNum, $this->storeCode,$this->registrationDatetime);
    }
}
