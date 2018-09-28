<?php

namespace Versatile\Core\Http\Controllers\Operations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Versatile\Core\Events\FileDeleted;
use Versatile\Core\Events\BreadDataDeleted;
use Versatile\Core\Events\BreadImagesDeleted;
use Versatile\Core\Models\DataType;

trait Delete
{
    /**
     * Delete an item BREA(D)
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, $id)
    {
        $model = $this->bread->getModel();

        // Check permission
        $this->authorize('delete', $model);

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$model, 'findOrFail'], $id);
            $this->cleanup($this->bread, $data);
        }

        $displayName = count($ids) > 1 ? $this->bread->display_name_plural : $this->bread->display_name_singular;

        $res = $data->destroy($ids);

        $data = [
            'message' => __('versatile::generic.error_deleting') . " ({$displayName})",
            'alert-type' => 'error',
        ];

        if ($res) {
            $data = [
                'message' => __('versatile::generic.successfully_deleted') . " ({$displayName})",
                'alert-type' => 'success',
            ];

            event(new BreadDataDeleted($this->bread, $data));
        }

        return redirect()->route("versatile.{$this->bread->slug}.index")->with($data);
    }

    /**
     * Remove translations, images and files related to a BREAD item.
     *
     * @param DataType $dataType
     * @param \Illuminate\Database\Eloquent\Model $data
     *
     * @return void
     */
    protected function cleanup($dataType, $data)
    {
        // Delete Translations, if present
        if (is_bread_translatable($data)) {
            $data->deleteAttributeTranslations($data->getTranslatableAttributes());
        }

        // Delete Images
        $this->deleteBreadImages($data, $dataType->deleteRows->where('type', 'image'));

        // Delete Files
        foreach ($dataType->deleteRows->where('type', 'file') as $row) {
            if (isset($data->{$row->field})) {
                foreach (json_decode($data->{$row->field}) as $file) {
                    $this->deleteFileIfExists($file->download_link);
                }
            }
        }
    }

    /**
     * Delete all images related to a BREAD item.
     *
     * @param \Illuminate\Database\Eloquent\Model $data
     * @param \Illuminate\Database\Eloquent\Model $rows
     *
     * @return void
     */
    public function deleteBreadImages($data, $rows)
    {
        foreach ($rows as $row) {
            if ($data->{$row->field} != config('versatile.user.default_avatar')) {
                $this->deleteFileIfExists($data->{$row->field});
            }

            $options = $row->details;

            if (isset($options->thumbnails)) {
                foreach ($options->thumbnails as $thumbnail) {
                    $ext = explode('.', $data->{$row->field});
                    $extension = '.' . $ext[count($ext) - 1];

                    $path = str_replace($extension, '', $data->{$row->field});

                    $thumb_name = $thumbnail->name;

                    $this->deleteFileIfExists($path . '-' . $thumb_name . $extension);
                }
            }
        }

        if ($rows->count() > 0) {
            event(new BreadImagesDeleted($data, $rows));
        }
    }

    /**
     * Delete file if exists
     *
     * @param $path
     */
    public function deleteFileIfExists($path)
    {
        if (Storage::disk(config('versatile.storage.disk'))->exists($path)) {
            Storage::disk(config('versatile.storage.disk'))->delete($path);
            event(new FileDeleted($path));
        }
    }
}
