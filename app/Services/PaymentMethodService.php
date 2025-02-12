<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Repositories\PaymentMedthodRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodService
{
    public function __construct(protected PaymentMedthodRepository $paymentMedthodRepository)
    {
        //
    }
    public function create(array $data): PaymentMethod
    {
        return $this->paymentMedthodRepository->create($data);
    }
    public function update(array $data, int $id): PaymentMethod
    {
        return $this->paymentMedthodRepository->update($data, $id);
    }
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->paymentMedthodRepository->getAllWithPagination();
    }

    /**
     * Delete PaymentMethod by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->paymentMedthodRepository->delete($id);
    }

    public function find(int $id): mixed
    {
        return $this->paymentMedthodRepository->find($id);
    }
}
