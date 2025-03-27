<?php
namespace App\Repositories;
use App\Contracts\PostInterface;
use App\Models\Post;
use App\Repositories\BaseRepository;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class PostRepository extends BaseRepository implements PostInterface
{
    public function getModel(): string
    {
        return Post::class;
    }

    public function create(array $data): Post
    {
        // Xử lý hình ảnh chính
        $image = $data['imagePrimary'];
        $imageName = 'postPrimary_' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('posts', $imageName, 'public');
        $imagePath = 'storage/posts/' . $imageName;

        // Xử lý hình ảnh phụ
        $image2 = $data['imageSecondary'];
        $imageName2 = 'postSecondary_' . time() . '.' . $image2->getClientOriginalExtension();
        $image2->storeAs('posts', $imageName2, 'public');
        $imagePath2 = 'storage/posts/' . $imageName2;

        return Post::create([
            'description' => $data['description'],
            'tittle' => $data['tittle'],
            'imagePrimary' => $imagePath,
            'imageSecondary' => $imagePath2,
        ]);
    }

    public function update(array $data, int $id): mixed
    {
        $post = $this->find($id);
        if (!$post) {
            return false;
        }

        // Cập nhật hình ảnh chính nếu có
        if (isset($data['imagePrimary'])) {
            $oldImagePath = $post->imagePrimary;

            $image = $data['imagePrimary'];
            $imageName = 'postPrimary_' . time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('posts/' . $imageName, file_get_contents($image));
            $imagePath = 'storage/posts/' . $imageName;

            $data['imagePrimary'] = $imagePath;

            if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
            }
        }

        // Cập nhật hình ảnh phụ nếu có
        if (isset($data['imageSecondary'])) {
            $oldImagePath2 = $post->imageSecondary;

            $image2 = $data['imageSecondary'];
            $imageName2 = 'postSecondary_' . time() . '.' . $image2->getClientOriginalExtension();
            Storage::disk('public')->put('posts/' . $imageName2, file_get_contents($image2));
            $imagePath2 = 'storage/posts/' . $imageName2;

            $data['imageSecondary'] = $imagePath2;

            if ($oldImagePath2 && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath2))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath2));
            }
        }

        $post->update([
            'description' => $data['description'],
            'tittle' => $data['tittle'],
            'imagePrimary' => isset($data['imagePrimary']) ? $data['imagePrimary'] : $post->imagePrimary,
            'imageSecondary' => isset($data['imageSecondary']) ? $data['imageSecondary'] : $post->imageSecondary,
        ]);

        return $post;
    }

    /**
     * Retrieve all records Categories pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return Post::all();
    }

    public function delete(int $id): mixed
    {
        $post = $this->find($id);
        if (!$post) {
            return false;
        }

        $oldImagePath = $post->imagePrimary;
        $oldImagePath2 = $post->imageSecondary;
        if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
        }
        if ($oldImagePath2 && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath2))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath2));
        }
        $post->delete();

        return true;
    }

}
