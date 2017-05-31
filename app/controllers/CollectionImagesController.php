<?php
use Carbon\Carbon;

class CollectionImagesController extends BaseController {

	public function index($collectionId)
    {
        $page = Input::has('page')?Input::get('page'):1;
        $take = Input::has('take')?Input::get('take'):50;
        $skip = ($page-1)*$take;

        $sort_style=Input::has('sort_style')?Input::get('sort_style'):'mosaic';     

        $action = Input::has('action')?Input::get('action'):null;       
    
        $data = array();
        if( !Request::ajax() || !Cache::has('data_collection_images') || $action != null)
        {                       
            //echo 'action: '.$action.'<br/>';
            try {
                $collection = Collection::findOrFail($collectionId);
            } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return App::abort(404);
            }

            $query = CollectionImage::select('images.id', 
                                            'images.short_name', 
                                            'images.name', 
                                            'image_details.width', 
                                            'image_details.height', 
                                            'image_details.ratio',
                                            DB::raw('count(lightbox_images.id) as count_favorite')
                                            )
                            ->join('images', 'images.id', '=', 'collections_images.image_id')
                            ->join('image_details', 'image_details.image_id', '=', 'images.id')
                            ->leftJoin('lightbox_images', 'collections_images.image_id', '=', 'lightbox_images.image_id')
                            ->where('collections_images.collection_id', $collection->id)->groupBy('images.id');

            $data['total_image'] = $query->get()->count();
            $data['collection_images'] = $query->skip($skip)->take($take)->get()->toArray();

            Cache::forget('data_collection_images');
            $expiresAt = Carbon::now()->addMinutes(10);
            Cache::put('data_collection_images', $data, $expiresAt);
        }
        else
        {
            $data = Cache::get('data_collection_images');
        }

        $collection_images = $data['collection_images'];
        $total_image = $data['total_image'];
        $total_page = ceil($total_image/$take);
        $from = $page - 2> 0 ? $page - 2: 1;
        $to = $page + 2<= $total_page ? $page + 2: $total_page;

        $arr_sort_method = array('new'=>'New','popular'=>'Popular','relevant'=>'Relevant','undiscovered'=>'Undiscovered');
        $lightboxes = array();
        if(Auth::user()->check())
        {
            $lightboxes = Lightbox::where('user_id','=',Auth::user()->get()->id)->get()->toArray();
            foreach ($lightboxes as $key => $lightbox) {
                $lightboxes[$key]['total'] = LightboxImages::where('lightbox_id','=',$lightbox['id'])->count();
            }
        }

        $image_action_title = 'Like this item';
        if(Auth::user()->check())
        {
            $image_action_title = 'Save to lightbox';
        }       
            
        if( Request::ajax() ) {
            //return $arrReturn;
            switch ($sort_style) {
                case 'small_grid':
                    $load_view = 'frontend.images.grid-images1';
                    break;
                case 'grid':
                    $load_view = 'frontend.images.grid-images2';
                    break;
                case 'mosaic':
                    $load_view = 'frontend.images.grid-images3';
                    break;              
                default:
                    $load_view = 'frontend.images.grid-images3';
                    break;
            }
            $html = View::make($load_view)->with(['images'=>$collection_images,
                                                "image_action_title"=>$image_action_title,
                                            ])->render();
            $arrReturn = ['html'=>$html,
                            'total_page'=>$total_page,
                            'total_image'=>$total_image,
                            'sort_style'=>$sort_style,
                            'take'=>$take
                        ];
            $response = Response::json($arrReturn);
            $response->header('Content-Type', 'application/json');
            return $response;           
        }
        else
        {
            $arrReturn = array('images'=>$collection_images, 
                        'total_image'    => $total_image,
                        'total_page'    => $total_page,
                        'current'    => $page,
                        'from'    => $from,
                        'to'    => $to,
                        "arr_sort_method"=>$arr_sort_method,
                        "categories"=>$this->layout->categories,
                        "lightboxes"=>$lightboxes,
                        "image_action_title"=>$image_action_title,
                        'mod_download'=>Configure::GetValueConfigByKey('mod_download'),
                        "sort_style"=>$sort_style,
                        "collection_id"=>$collection->id,
                        "collection_name"=>$collection->name,
                        "collection_short_name"=>$collection->short_name,
                        );

        }
        $this->layout->content = View::make('frontend.collections.index')->with($arrReturn);
    }

    public function index1($collectionId)
	{
		try {
			$collection = Collection::with('images')
				    					->findOrFail($collectionId);
		} catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return App::abort(404);
		}

    	$collection =  $collection->toArray();
    	if( !empty($collection['images']) ) {
    		foreach($collection['images'] as $key => $image) {
    			$height = 175;
    			$width = $height * $image['ratio'];
    			$collection['images'][$key] = [
    											'id' 	=> $image['id'],
    											'name' 	=> $image['name'],
    											'short_name' 	=> $image['short_name'],
    											'description' 	=> $image['description'],
    											'path' 	=> URL.'/pic/with-logo/'.$image['short_name'].'-'.$image['id'].'.jpg',
    											'width' => $width,
    											'height' => $height,
    										];
    		}
    	}

		$this->layout->content = View::make('frontend.collections.index')->with([
																				'collection' => $collection
																			]);
	}
}
