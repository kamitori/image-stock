<?php
use Faker\Factory as Faker;
use League\ColorExtractor\Client as ColorExtractor;
use Carbon\Carbon;

class ISImagesController extends BaseController {

	public function index($image_id)
	{
		$arrReturn = array();
		$arrImage = array();
		$imageObj = VIImage::select('images.id',
								'images.name',
								'images.short_name',
								'images.description',
								'images.keywords',
								'images.artist',
								'images.author_id',
								'image_details.path',
								'image_details.width',
								'image_details.height',
								'image_details.dpi',
								'image_details.ratio')
						->with('user')
						->with('categories')
						->join('image_details', 'image_details.image_id', '=', 'images.id')
                        ->where('images.id', $image_id)
                        ->where('images.active', 1)
                        //->where('image_details.size_type', DB::raw( '(SELECT MIN(image_details.size_type) FROM image_details WHERE image_details.image_id = '.$image_id.' )'))
                        ->first();

		if( is_object($imageObj) )
		{

			// if( $imageObj->ratio > 1 ) {
			// 	$width = 450;
			// 	$height = $width / $imageObj->ratio;
			// } else {
			// 	$height = 450;
			// 	$width = $height * $imageObj->ratio;
			// }
			if( $imageObj->dpi == 0 )
			{
				$imageObj->dpi = 72;
			}
			$arrImage = [
										'image_id' => $imageObj->id,
										'name' => $imageObj->name,
										'short_name' => $imageObj->short_name,
										'description' => $imageObj->description,
										'keywords' => $imageObj->keywords,
										'artist' => $imageObj->artist,
										'author_id' => $imageObj->author_id,
										'width' => $imageObj->width,
										'height' => $imageObj->height,
										'dpi' => $imageObj->dpi,
										'path' => '/pic/with-logo/'.$imageObj->short_name.'-'.$imageObj->id.'.jpg',
										'path_thumb' => '/pic/thumb/'.$imageObj->short_name.'-'.$imageObj->id.'.jpg',
										//'path' => $imageObj->path
										'user' => $imageObj->user,
										'categories' => $imageObj->categories->toArray(),

									];

			$htmlChooseDownload = $this->loadChooseDownload($imageObj->id);

			$product = Product::with([
									'type',
									'sizeLists',
									'optionGroups' => function($query) {
										$query->select('option_groups.id', 'name', 'key');
									},
									'options' => function($query) {
										$query->select('options.id', 'name', 'key', 'option_group_id');
									},
									'images' => function($query) {
										$query->select('path');
									},
									'mainImage' => function($query) {
										$query->select('path');
									}
								]);

			$product = $product->where('active', 1)
						->get();
			$arrProduct = array();
			if( is_object($product) )
			{
				$arrProduct = $product->toArray();
			}
			// echo '<pre>';
			// print_r($arrImage['categories']);
			// echo '</pre>';

			$htmlOrder = $this->loadOrder($arrImage, $arrProduct);

			$htmlSignin = View::make('frontend.account.signin')->with([])->render();

			$arrKeywords = explode(',', $imageObj->keywords);
			$arrReturn['htmlKeywords'] = View::make('frontend.images.view-keywords')->with('arrKeywords', $arrKeywords)->render();

			//view image's categories
			$arrReturn['htmlImageCategories'] = View::make('frontend.images.view-image-categories')->with('arrImageCategories', $arrImage['categories'])->render();

			$action = Input::has('a')?Input::get('a'):'';

			$user_backgrounds = Session::has('user_backgrounds') ? Session::get('user_backgrounds') : [];

			$arrReturn['htmlMainImage'] = View::make('frontend.images.main-image')
											->with(['imageObj'=>$arrImage,
													'htmlChooseDownload'=>$htmlChooseDownload,
													'htmlOrder'=>$htmlOrder,
													'htmlSignin'=>$htmlSignin,
													'action'=>$action,
													'htmlKeywords'=>$arrReturn['htmlKeywords'],
													'htmlImageCategories'=>$arrReturn['htmlImageCategories'],
													'arrProduct'=>$arrProduct,
													'user_backgrounds'=> $user_backgrounds,
													'system_backgrounds'=> $this->getBackgrounds()
											])->render();


			$arrReturn['htmlSameArtist'] = $this->loadSameArtist($imageObj->author_id);
			$arrReturn['htmlSimilarImages'] = $this->getSimilarImage($imageObj);

			$this->layout->metaTitle = $imageObj->name;

			//save to recently_view_images table
			//App::make('AccountController')->addToRecentlyViewImages($imageObj->id);
			if(Auth::user()->check())
			{
				BackgroundProcess::actionSearch(['type'=>'recently-view',
												'image_id'=>$imageObj->id,
												'user_id'=>Auth::user()->get()->id
											]);
			}

		}
		else
		{
			return App::abort(404);
		}

		$arrReturn['imageObj'] = $arrImage;

		$categories = $this->layout->categories;
		$arrReturn['htmlCategories'] = View::make('frontend.account.view-categories')->with('categories', $categories)->render();

		$lightboxes = array();
		if(Auth::user()->check())
		{
			$lightboxes = Lightbox::where('user_id','=',Auth::user()->get()->id)->get()->toArray();
			foreach ($lightboxes as $key => $lightbox) {
				$lightboxes[$key]['total'] = LightboxImages::where('lightbox_id','=',$lightbox['id'])->count();
			}

		}
		$arrReturn['lightboxes'] = $lightboxes;
		$arrReturn['mod_download']=Configure::GetValueConfigByKey('mod_download');
		$arrReturn['mod_order']=Configure::GetValueConfigByKey('mod_order');
		$arrReturn['arrProduct'] = $arrProduct;
		$arrReturn['currentURL'] = Request::url();
		$this->layout->content = View::make('frontend.images.index')->with(
			$arrReturn
		);

	}

	public static function getBackgrounds()
	{
		$arrData = [];
		if( Cache::has('backgrounds') ) {
			$arrData = Cache::get('backgrounds');
		} else {
			$backgrounds = BannerBackground::select('image')->where('type', 'background')->where('active', 1)->where('name', 'like', '%on wall%')->orderBy('order_no', 'asc')->get();
			foreach($backgrounds as $background) {
				$arrData[] = URL.'/'.$background->image;
			}
			Cache::forever('background_on_wall', $arrData);
		}
		//$rand = rand(0, count($arrData)-1);
		//return ['main' => isset($arrData[$rand]) ? $arrData[$rand] : ''];
		return $arrData;
	}


	//load choose download
	function loadChooseDownload($image_id)
	{
		$query = VIImageDetail::select('image_details.image_id',
										'image_details.detail_id',
										'image_details.width',
										'image_details.height',
										'image_details.dpi',
										'image_details.size',
										'image_details.size_type'
									)->leftJoin('images', function($join){
											$join->on('image_details.image_id', '=', 'images.id');
										})
									->where('image_details.image_id', $image_id)
									->orderBy('size_type')
									->get();
		$arrImageDetails = array();
		if(!$query->isempty())
		{
			$i = 0;
			foreach ($query as $value)
			{
				$arrImageDetails[$i]['detail_id'] = $value->detail_id;
				$arrImageDetails[$i]['image_id'] = $value->image_id;
				$arrImageDetails[$i]['width'] = $value->width;
				$arrImageDetails[$i]['height'] = $value->height;
				$arrImageDetails[$i]['dpi'] = $value->dpi;
				$arrImageDetails[$i]['size'] = $value->size;
				$arrImageDetails[$i]['size_type'] = $value->size_type;

				switch ($value->size_type) {
					case '1':
						$size_name = 'Small';
						break;
					case '2':
						$size_name = 'Medium';
						break;
					case '3':
						$size_name = 'Large';
						break;
					case '4':
						$size_name = 'Supper';
						break;

					default:
						$size_name = 'Small';
						break;
				}

				$arrImageDetails[$i]['size_name'] = $size_name;

				$i++;
			}
		}

		$htmlChooseDownload = View::make('frontend.images.choose-download')->with('arrImageDetails', $arrImageDetails)->render();

		if( Request::ajax() ) {

			$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$htmlChooseDownload];

			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}

		return $htmlChooseDownload;
	}

	//load choose download
	function loadOrder($arrImage, $arrProduct)
	{

		$htmlOrder = View::make('frontend.images.order')->with(['arrImage'=>$arrImage,
																'arrProduct'=>$arrProduct
																])->render();

		if( Request::ajax() ) {

			$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$htmlOrder];

			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}

		return $htmlOrder;
	}

	//view all same artist images
	function viewAllSameArtistImages($user_id)
	{
		$page = Input::has('page')?Input::get('page'):1;
		$take = Input::has('take')?Input::get('take'):50;
		$skip = ($page-1)*$take;

		$sort_style=Input::has('sort_style')?Input::get('sort_style'):'mosaic';

		$action = Input::has('action')?Input::get('action'):null;
		//echo 'action: '.$action.'<br/>';
		$data = array();

		if( !Request::ajax() || !Cache::has('data_same_artist_images') || $action != null)
		{
            try {
                $user_obj = User::findOrFail($user_id);
            } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return App::abort(404);
            }

            $category_id = Input::has('catid')?Input::get('catid'):null;
            $category_obj = false;
            if($category_id != '')
            {
	            try {
	                $category_obj = Category::findOrFail($category_id);
	            } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	                return App::abort(404);
	            }
            }

            $lightbox_id = Input::has('lid')?Input::get('lid'):null;
            $lightbox_obj = false;
            if($lightbox_id != '')
            {
	            try {
	                $lightbox_obj = Lightbox::findOrFail($lightbox_id);
	            } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	                return App::abort(404);
	            }
            }

			$data = $this->loadSameArtist(trim($user_id), true, $skip, $take, $category_id, $lightbox_id);
			Cache::forget('data_same_artist_images');
			$expiresAt = Carbon::now()->addMinutes(10);
			Cache::put('data_same_artist_images', $data, $expiresAt);
		}
		else
		{
			$data = Cache::get('data_same_artist_images');
		}

		$arrSameArtistImages = $data['arrSameArtist'];
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
			$html = View::make($load_view)->with(['images'=>$arrSameArtistImages,
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
		$arrReturn = array('images'=>$arrSameArtistImages,
					'user_obj'=>$user_obj,
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
					"category_obj"=>$category_obj,
					"lightbox_obj"=>$lightbox_obj,
					);

		$this->layout->content = View::make('frontend.images.same-artist-all')->with($arrReturn);
	}
	function viewAllSameArtistImages1($user_id)
	{
		$page = Input::has('page')?Input::get('page'):1;
		$take = Input::has('take')?Input::get('take'):50;
		$skip = ($page-1)*$take;

		$data = $this->loadSameArtist(trim($user_id), true, $skip, $take);
		$arrSameArtist = $data['arrSameArtist'];
		$total_image = $data['total_image'];
		$total_page = ceil($total_image/$take);
		$from = $page - 2> 0 ? $page - 2: 1;
		$to = $page + 2<= $total_page ? $page + 2: $total_page;

		$this->layout->content = View::make('frontend.images.same-artist')->with(['arrSameArtist'=>$arrSameArtist,
																				'user_id'=>$user_id,
																				'total_image'    => $total_image,
																				'total_page'    => $total_page,
																				'current'    => $page,
																				'from'    => $from,
																				'to'    => $to
																				]);
	}

	//load same artist
	function loadSameArtist($user_id, $viewAll=false, $skip=0, $takes=12, $category_id=null, $lightbox_id=null)
	{
        try {
            $user_obj = User::findOrFail($user_id);
        } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return App::abort(404);
        }

		$query = VIImage::select('images.id',
										'images.name',
										'images.short_name',
										'image_details.ratio',
										'image_details.width',
										'image_details.height',
										'image_details.path',
										DB::raw('count(lightbox_images.id) as count_favorite')
									);
		$query->leftJoin('image_details', function($join){
											$join->on('image_details.image_id', '=', 'images.id')
													->where('image_details.type', '=', 'main');
			});

		$query->where('images.active', 1);

		if($category_id != null)
		{
			$query->join('images_categories', 'images_categories.image_id', '=', 'images.id')
				->join('categories', 'categories.id', '=', 'images_categories.category_id')
				->where('images_categories.category_id', $category_id)
				->where('categories.active', 1);
		}

		$query->leftJoin('lightbox_images', function($join){
					$join->on('lightbox_images.image_id', '=', 'images.id');

			});

		if($lightbox_id != null)
		{
			$query->where('lightbox_images.lightbox_id', $lightbox_id);
		}
		else
		{
			$query->where('images.author_id', $user_obj->id);
		}

		$query->with('downloads')
			->groupBy('images.id')
			->orderBy('images.id', 'desc');
		$total_image = $query->get()->count();
		$data = $query->skip($skip)->take($takes)->get();
		//pr(DB::getQueryLog());die;
		$arrSameArtist = array();
		if(!$data->isempty())
		{
			foreach ($data as $key=>$value)
			{
				$arrSameArtist[$key]['image_id'] = $value->id;
				$arrSameArtist[$key]['id'] = $value->id;
				$arrSameArtist[$key]['name'] = $value->name;
				$arrSameArtist[$key]['short_name'] = $value->short_name;
				$arrSameArtist[$key]['width'] = $value->width;
				$arrSameArtist[$key]['height'] = $value->height;
				//$arrSameArtist[$key]['path'] = $value->path;
				$arrSameArtist[$key]['path'] = '/pic/small-thumb/'.$value->short_name.'-'.$value->id.'.jpg';
				$arrSameArtist[$key]['count_favorite'] = $value['count_favorite'];
			}
		}

		if( Request::ajax() ) {
			if(!$viewAll)
			{
				$html = View::make('frontend.images.same-artist')->with(['arrSameArtist'=>$arrSameArtist, 'user_id'=>$user_id, 'user_obj'=>$user_obj])->render();
				$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];

				$response = Response::json($arrReturn);
				$response->header('Content-Type', 'application/json');
				return $response;
			}
		}
		if(!$viewAll)
		{
			return View::make('frontend.images.same-artist')->with(['arrSameArtist'=>$arrSameArtist, 'user_id'=>$user_id, 'user_obj'=>$user_obj])->render();
		}
		return ['arrSameArtist'=>$arrSameArtist, 'total_image'=>$total_image];
	}

	//view all similar images
	function viewAllSimilarImages($id_image)
	{
		$page = Input::has('page')?Input::get('page'):1;
		$take = Input::has('take')?Input::get('take'):50;
		$skip = ($page-1)*$take;

		$sort_style=Input::has('sort_style')?Input::get('sort_style'):'mosaic';

		$action = Input::has('action')?Input::get('action'):null;
		//echo 'action: '.$action.'<br/>';
		$data = array();
		$image_name = '';
		if( !Request::ajax() || !Cache::has('data_similar_images') || $action != null)
		{
            try {
                $image_obj = VIImage::findOrFail($id_image);
                $image_name = $image_obj->name;
            } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return App::abort(404);
            }

			$data = $this->getSimilarImage($image_obj, true, $skip, $take);
			Cache::forget('data_similar_images');
			$expiresAt = Carbon::now()->addMinutes(10);
			Cache::put('data_similar_images', $data, $expiresAt);
		}
		else
		{
			$data = Cache::get('data_similar_images');
		}

		$arrSimilarImages = $data['arrSimilarImages'];
		//pr($arrSimilarImages);exit;
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
			$html = View::make($load_view)->with(['images'=>$arrSimilarImages,
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

		$arrReturn = array('images'=>$arrSimilarImages,
					'image_obj'=>$image_obj,
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
					);

		$this->layout->content = View::make('frontend.images.similar-images-all')->with($arrReturn);
	}
	//load similar images
	function loadSimilarImages($image_name, $viewAll=false)
	{
		$query = VIImage::select('images.id',
										'images.name',
										'images.short_name',
										'image_details.ratio',
										'image_details.width',
										'image_details.height',
										'image_details.path'
									)->leftJoin('image_details', function($join){
											$join->on('image_details.image_id', '=', 'images.id')
													->where('image_details.type', '=', 'main');
										})
									->whereRaw('MATCH(images.name,images.description,images.keywords) AGAINST(? IN BOOLEAN MODE)',
			                                    [ trim($image_name) ])
									->where('images.active', 1)
									->orderBy('images.id', 'desc');

		if(!$viewAll)
		{
			$query->take(10);
		}

		$query = $query->get();
		$arrSimilarImages = array();
		if(!$query->isempty())
		{
			$i = 0;
			foreach ($query as $value)
			{
				$arrSimilarImages[$i]['image_id'] = $value->id;
				$arrSimilarImages[$i]['id'] = $value->id;
				$arrSimilarImages[$i]['name'] = $value->name;
				$arrSimilarImages[$i]['short_name'] = $value->short_name;
				$arrSimilarImages[$i]['width'] = $value->width;
				$arrSimilarImages[$i]['height'] = $value->height;
				//$arrSimilarImages[$i]['path'] = $value->path;
				$arrSimilarImages[$i]['path'] = '/pic/small-thumb/'.$value->short_name.'-'.$value->id.'.jpg';

				$i++;
			}
		}

		if( Request::ajax() ) {
			$html = View::make('frontend.images.similar-images')->with(['arrSimilarImages'=>$arrSimilarImages, 'image_name'=>$image_name])->render();
			$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];

			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
		if(!$viewAll)
		{
			return View::make('frontend.images.similar-images')->with(['arrSimilarImages'=>$arrSimilarImages, 'image_name'=>$image_name])->render();
		}

		return $arrSimilarImages;

	}

	public function getLink($imageId)
	{
		if( !Auth::user()->check() ){
			return ['status' => 'error'];
		}
		$detailId = Input::has('img') ? Input::get('img') : 0;
		$image = VIImage::select('id', 'short_name', 'image_details.detail_id')
							->join('image_details', 'image_details.image_id', '=', 'images.id')
							->where('id', $imageId)
							->where('detail_id', $detailId)
							->first();
		if(  !is_object($image) ) {
			return ['status' => 'error'];
		}
		$rand = rand(1, rand(12, 38));
		$token = Str::random(rand(30, 55));
		$token = substr($token, 0, $rand).md5(time().$image->detail_id.md5($image->short_name)).substr($token, $rand);
		Download::insert([
				'image_id' => $image->id,
				'user_id' => Auth::user()->get()->id,
				'image_detail_id' => $image->detail_id,
				'token' => $token,
			]);
		return ['status' => 'ok', 'url' => URL.'/d/'.$image->id.'/'.$token.'/'.$image->short_name.'.jpg'];
	}

	public function download($imageId, $token)
	{
		if( !Auth::user()->check() ){
			return App::abort(404);
		}
		$image = Download::select('path', 'short_name')
						->join('image_details', function($join){
							$join->on('image_details.detail_id', '=', 'downloads.image_detail_id')
									->on('image_details.image_id', '=', 'downloads.image_id');
						})
						->join('images', 'images.id', '=', 'downloads.image_id')
						->where('downloads.image_id', $imageId)
						->where('token', $token)
						->where('user_id', Auth::user()->get()->id)
						->first();
		if( !is_object($image) ) {
			return App::abort(404);
		}
		return Response::download( public_path($image->path), $image->short_name.'.jpg' );
	}

	public function getSimilarImage($image_obj, $viewAll=false, $skip=0, $takes=12){
		$arr_image_id = [$image_obj->id];
		$similar_images = array();
		$total_image = 0;
		if(count($arr_image_id)){
			$arr_keyword=array();
			$keywords = VIImage::select(DB::raw(" GROUP_CONCAT(`keywords` SEPARATOR ',') as 'keyword' "))->whereIn('id',$arr_image_id)->get()->toArray();
			$keywords = explode(',',$keywords[0]['keyword']);

			// foreach ($keywords as $key => $keyword) {
			// 	if(!isset($arr_keyword[$keyword])){
			// 		$arr_keyword[$keyword] = 1;
			// 	}else{
			// 		$arr_keyword[$keyword] += 1;
			// 	}
			// }
			// pr(DB::getQueryLog());die;
			// shuffle($arr_keyword);
			// arsort($arr_keyword);
			// $arr_keyword = array_keys($arr_keyword);
			$arr_keyword = $keywords;
			//pr($arr_keyword);die;
			$take = count($arr_keyword)<5?count($arr_keyword)-1:5;
			$query = VIImage::select('name', 'short_name','images.id', DB::raw('count(lightbox_images.id) as count_favorite'))->withType('main');
			$query->where('images.active', 1);
			$query->leftJoin('lightbox_images', 'images.id', '=', 'lightbox_images.image_id')->with('downloads');
			if(count($arr_keyword) > 0)
			{
				$query->whereRaw("MATCH(images.name,images.description,images.keywords) AGAINST('".$arr_keyword[0]."' IN BOOLEAN MODE)");
			}
			for($i=1;$i<$take;$i++){
				$query->orWhereRaw("MATCH(images.name,images.description,images.keywords) AGAINST('".$arr_keyword[$i]."' IN BOOLEAN MODE)");
			}
			$query->whereNotIn('images.id',$arr_image_id);
			$query->groupBy('images.id');
			$total_image = $query->get()->count();
			//echo $total_image;
			$similar_images = $query->skip($skip)->take($takes)->get()->toArray();
			//pr(DB::getQueryLog());die;
		}

		$arrSimilarImages = array();

		foreach ($similar_images as $key=>$value)
		{
			$arrSimilarImages[$key]['image_id'] = $value['id'];
			$arrSimilarImages[$key]['id'] = $value['id'];
			$arrSimilarImages[$key]['name'] = $value['name'];
			$arrSimilarImages[$key]['short_name'] = $value['short_name'];
			$arrSimilarImages[$key]['width'] = $value['width'];
			$arrSimilarImages[$key]['height'] = $value['height'];
			//$arrSimilarImages[$key]['path'] = $value['path'];
			$arrSimilarImages[$key]['path'] = '/pic/small-thumb/'.$value['short_name'].'-'.$value['id'].'.jpg';
			$arrSimilarImages[$key]['count_favorite'] = $value['count_favorite'];
		}

		if( Request::ajax() ) {

			if(!$viewAll)
			{
				$html = View::make('frontend.images.similar-images')->with(['arrSimilarImages'=>$arrSimilarImages, 'image_obj'=>$image_obj])->render();
				$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];

				$response = Response::json($arrReturn);
				$response->header('Content-Type', 'application/json');
				return $response;

			}
			return ['arrSimilarImages'=>$arrSimilarImages, 'total_image'=>$total_image];
		}
		if(!$viewAll)
		{
			return View::make('frontend.images.similar-images')->with(['arrSimilarImages'=>$arrSimilarImages, 'image_obj'=>$image_obj])->render();
		}

		return ['arrSimilarImages'=>$arrSimilarImages, 'total_image'=>$total_image];
	}
}