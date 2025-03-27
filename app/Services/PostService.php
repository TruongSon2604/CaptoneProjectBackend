<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    public function __construct(protected PostRepository $postRepository)
    {

    }

    public function create(array $data)
    {
        return $this->postRepository->create($data);
    }

    public function update(array $data, int $id)
    {
        return $this->postRepository->update($data, $id);
    }

    public function delete(int $id)
    {
        return $this->postRepository->delete($id);
    }

    public function getAllWithPagination()
    {
        return $this->postRepository->getAllWithPagination();
    }

    public function find(int $id)
    {
        return $this->postRepository->find($id);
    }
}
