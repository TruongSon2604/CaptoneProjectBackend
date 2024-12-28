<?php

namespace App\Contracts;

interface BaseInterface
{
    /**
     * Get all records .
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getAll();

    /**
     * Find a record by its ID.
     *
     * @param int|string $id ID của bản ghi cần tìm.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    /**
     * Delete a record by its ID.
     *
     * @param int|string $id ID của bản ghi cần xóa.
     *
     * @return bool Trả về true nếu xóa thành công, false nếu thất bại.
     */
    public function delete(int $id);

}
