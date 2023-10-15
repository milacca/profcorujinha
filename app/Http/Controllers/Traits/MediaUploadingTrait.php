<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait MediaUploadingTrait
{
        public function storeMedia(Request $request)
        {
        // Validates file size
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:' . request()->input('size') * 1024,
            ]);
        }

        // Validates file type for both images and PDF
        $this->validate(request(), [
            'file' => 'file|mimes:jpeg,jpg,png,gif,pdf',
        ]);

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
            // Handle exception if necessary
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
