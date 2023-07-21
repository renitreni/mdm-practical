<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VoucherExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $userIds;

    public function __construct($userIds = null)
    {
        $this->userIds = $userIds;
    }

    public function query()
    {
        return User::query()->isUser()->when($this->userIds, fn($q) => $q->whereIn('id', $this->userIds));
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->vouchers->pluck('code'),
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'E-mail',
            'Voucher Codes',
        ];
    }
}
