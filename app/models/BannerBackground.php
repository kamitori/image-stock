<?php

class BannerBackground extends BaseModel {

	protected $table = 'banners_backgrounds';

	public function afterSave($banner)
	{
		Cache::forget('banners');
		Cache::forget('background_on_wall');
		Cache::forget('background_design');
	}

	public function beforeDelete($banner)
    {
		Cache::forget('banners');
		Cache::forget('background_on_wall');
		Cache::forget('background_design');

    }
}