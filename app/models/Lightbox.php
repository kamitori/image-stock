<?php


class Lightbox extends BaseModel{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'lightbox';
	
	/* Alowing Eloquent to insert data into our database */
	protected $fillable = array('name', 'user_id', 'created_by', 'updated_by');
	
    public function images()
    {
        return $this->belongsToMany('VIImage', 'lightbox_images', 'lightbox_id', 'image_id');
    }

}
