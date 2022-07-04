<?php
    define('PAGINATION_COUNT', 15);
    function getFolder()
    {
        return app()->getLocale() === 'ar' ? 'css-rtl' : 'css';
    }

    function uploadImage($folder, $image)
    {
        $image->store('/', $folder);
        $filename = $image->hashName();
        return $filename;
    }
   function deleteOldImage($folder,$oldimg)
   {
       \Illuminate\Support\Facades\Storage::disk($folder)->delete($oldimg);
   }
