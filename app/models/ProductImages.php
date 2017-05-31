<?php

class ProductImages extends BaseModel {

	protected $table = 'product_images';

    public static function upload($file, $path, $width = 110, $makeThumb = true, $fileName = '')
    {
        if( !File::exists($path) ) {
            File::makeDirectory($path, 493, true);
        }
        if( !empty($fileName) ) {
            $fileName .= '.'.$file->getClientOriginalExtension();
        } else {
            $fileName = Str::slug(str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName())).'.'.date('d-m-y').'.'.$file->getClientOriginalExtension();
        }
        $path = str_replace('\\', DS, $path);
        if($file->move($path, $fileName)){
            BackgroundProcess::resize($width, $path, $fileName);
            if( $makeThumb ) {
                BackgroundProcess::makeThumb($path, $fileName);
            }
            $imgPath = str_replace(public_path(), '', $path);
            $imgPath = str_replace(DS, '/', $imgPath);
            $imgPath = ltrim(rtrim($imgPath, '/'), '/');
            $imgPath .= '/'.$fileName;
            return self::insertGetId([
                    'path' => $imgPath,
                ]);
        }
        return 0;
    }

}