<?php

namespace App\Repositories;

use App\Contracts\AddressInterface;
use App\Contracts\PaymentMethodInterface;
use App\Models\PaymentMethod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PaymentMedthodRepository extends BaseRepository implements PaymentMethodInterface
{
    public function getModel(): string
    {
        return PaymentMethod::class;
    }

    public function create(array $data): PaymentMethod
    {
        return PaymentMethod::create($data);
    }

    public function update(array $data, int $id): PaymentMethod
    {
        $address = PaymentMethod::find($id);
        $address->update($data);

        return $address;
    }
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return PaymentMethod::paginate(10);
    }
}
