<?php

namespace App\Services;


use App\Entities\PhoneRegistrationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuspectedTracingService
{
    protected $phoneRegistrationRecordModel;

    public function __construct(PhoneRegistrationRecord $phoneRegistrationRecord)
    {
        $this->phoneRegistrationRecordModel = $phoneRegistrationRecord;
    }

    public function getSuspectedRegistrations(string $phoneNum, Carbon $startTime, int $rageDays, int $numberPerPage, int $page): array
    {
        $query = DB::table('phone_registration_records as infected')
            ->leftJoin('phone_registration_records as suspected', 'suspected.store_code', '=', 'infected.store_code')
            ->where('infected.phone_num', '=', $phoneNum)
            ->where('suspected.phone_num', '!=', $phoneNum)
            // 取得卻診者進入同一間商的足跡
            ->where('infected.registration_datetime', '>=', $startTime->format('Y-m-d H:i:s'))
            ->where('infected.registration_datetime', '<=', $startTime->addDays($rageDays)->format('Y-m-d H:i:s'))
            // 取得與卻診者進入同一間商店 並以卻診者進入該商店往後推七天的名單
            ->whereRaw("suspected.registration_datetime >= infected.registration_datetime")
            ->whereRaw("suspected.registration_datetime <= DATE_ADD(infected.registration_datetime, INTERVAL ? DAY)", [$rageDays]);

        $total = $query->count();
        $data = $query->orderBy('suspected.store_code', 'ASC')
            ->orderBy('suspected.registration_datetime', 'ASC')
            ->limit($numberPerPage)
            ->offset(($page - 1) * $numberPerPage)
            ->get(['suspected.phone_num', 'suspected.store_code', 'suspected.registration_datetime'])
            ->map(function ($registration) {
                return [
                    'phone_num' => $registration->phone_num,
                    'store_code' => $registration->store_code,
                    'registration_datetime' => Carbon::parse($registration->registration_datetime)->format('Y-m-d/TH:i:s')
                ];
            });

        return [
            'current_page' => $page,
            'number_per_page' => $numberPerPage,
            'total' => $total,
            'data' => $data,
        ];
    }
}
