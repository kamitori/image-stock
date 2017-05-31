<?php
class DesignsController extends BaseController {

	public function index($id)
	{
		try {
			$image = VIImage::select('images.id',
									'images.name',
									'images.short_name',
									'image_details.path'
									)
							->join('image_details', 'image_details.image_id', '=', 'images.id')
	                        ->where('images.id', $id)
	                        ->first();
	        if(!$image)
	        {
				return App::abort(404);	        	
	        }
	        $image = $image->toArray();
	        
	        $image['path'] = 'pic/with-logo/'.$image['short_name'].'-'.$image['id'].'.jpg';
	        $image['path_thumb'] = '/pic/thumb/'.$image['short_name'].'-'.$image['id'].'.jpg';

	        $products = Product::select('products.id', 'products.name', 'products.short_name', 'products.sku')
	        				->with([
	        						'optionGroups' => function($query) {
	        							$query->select('option_groups.id', 'name', 'key');
	        						},
	        						'options' => function($query) {
	        							$query->select('options.id', 'name', 'key', 'option_group_id');
	        						},
	        						'mainImage' => function($query) {
	        							$query->select('path');
	        						}
	        					])
        					->where('active', 1)
	        				->get()
	        				->toArray();
	       	foreach($products as $key => $detail) {
	       		foreach($detail['option_groups'] as $group_key => $option_group) {
	       			$options = [];
	       			foreach($detail['options'] as $option_key => $option) {
	       				if( $option['option_group_id'] != $option_group['id'] ) continue;
	       				unset($detail['options'][$option_key], $option['pivot']);
	       				$options[] = $option;
	       			}
	       			$products[$key]['option_groups'][$group_key]['options'] = $options;
	       			unset($products[$key]['option_groups'][$group_key]['pivot']);
	       		}
	       		unset($options[$key]['options']);
	       		if( isset($detail['main_image'][0]) ) {
	       			$products[$key]['main_image'] = $detail['main_image'][0]['path'];
	       		}
	       		else
	       		{
	       			$products[$key]['main_image'] = '';	
	       		}
	       	}
		} catch(Exception $e) {
			return App::abort(404);
		}
		//pr($products);exit;

		//pr(Session::get('userImages'));				

		$userImages = Session::has('userImages') ? Session::get('userImages') : [];
		if(!in_array($image, $userImages, true)) 
		{
			$userImages[] = $image;
		}
		Session::put('userImages', $userImages);

		//pr(Session::get('user_backgrounds'));exit;
		$user_backgrounds = Session::has('user_backgrounds') ? Session::get('user_backgrounds') : [];

		$this->layout->content = View::make('frontend.designs.index')->with([
																			'image_path' 			=> URL.'/'.$image['path'],
																			'image_obj' 			=> $image,
																			'products' 			=> $products,
																			'systemBackgrounds' => $this->getBackgrounds(),
																			'userBackgrounds' 	=> $user_backgrounds,
																			'userImages' 		=> Session::get('userImages')
																		]);
	}

	public function putImageSession()
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}
		$arrReturn = ['status' => 'error'];
		if( Input::has('images') ) {
			$images = Input::get('images');

			$userImages = Session::has('userImages') ? Session::get('userImages') : [];

			foreach($images as $id) {

				//$image = VIImage::findOrFail($id)->toArray();
				$image = VIImage::select('images.id',
									'images.name',
									'images.short_name',
									'image_details.path'
									)
							->join('image_details', 'image_details.image_id', '=', 'images.id')
	                        ->where('images.id', $id)
	                        ->first()
	                        ->toArray();
	            $image['path'] = 'pic/with-logo/'.$image['short_name'].'-'.$image['id'].'.jpg';
	            $image['path_thumb'] = '/pic/thumb/'.$image['short_name'].'-'.$image['id'].'.jpg';
				
				if(!in_array($image, $userImages, true)) 
				{
					$userImages[] = $image;
				}				
			}
			Session::put('userImages', $userImages);
			$arrReturn = ['status' => 'ok'];
		}
		//pr(Session::get('userImages'));
		return $arrReturn;
	}

	public function clearImageSession($image_id, $short_name)
	{
		Session::forget('userImages');
		return Redirect::route('design-image', array($image_id, $short_name));
	}

	public function getImages()
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}

		$keyword = Input::has('keyword')?Input::get('keyword'):'';

		//for recently searched images
		$keyword_searched = $keyword;
		$short_name = Input::has('short_name')?Input::get('short_name'):'';

		if(trim($keyword) != '')
		{
			$keyword = '*'.trim($keyword).'*';	
		}
		
		$page = Input::has('page')?Input::get('page'):1;
		$take = Input::has('take')?Input::get('take'):20;
		$skip = ($page-1)*$take;
		$category = Input::has('category')?intval(Input::get('category')):0;
		$search_type =  Input::has('search_type')?(Input::get('search_type')):'search';

		$images = VIImage::select('images.id', 'images.name', 'images.short_name', 'image_details.path')
						->join('image_details', 'image_details.image_id', '=', 'images.id')
						->where('images.active', 1)
						->groupBy('images.id')
						->orderBy('images.name');

		if($search_type=='search' && $keyword != ''){
			$images =$images->search($keyword);			
		}

		if($category!=0){
			$images = $images->join('images_categories','images_categories.image_id','=','images.id')
					     ->where('images_categories.category_id','=',$category);
			
		}

		$color = Input::has('color')?(Input::get('color')):'';

		$arr_images = $images->get()->toArray();

        $arr_images = $this->searchColorFromArray($color, $arr_images);
        $total_image = count($arr_images);
        $arr_images = array_slice($arr_images, $skip, $take);

		//pr(DB::getQueryLog());die;
		$total_page = ceil($total_image/$take);
		
		//for recently searched images
		if((trim($keyword_searched) != '' || $short_name != '') && count($arr_images) > 0)
		{
			if($keyword_searched == '')
			{
				$keyword_searched = str_replace('-',' ',$short_name);
			}
			if(Auth::user()->check())
			{
				BackgroundProcess::actionSearch(['type'=>'recently-search',
												'keyword'=>$keyword_searched,
												'image_id'=>$arr_images[0]['id'],
												'user_id'=>Auth::user()->get()->id,
												'query'=>Request::server('REQUEST_URI')
											]);

			}
			BackgroundProcess::actionSearch(['type'=>'popular-search',
											'keyword'=>$keyword_searched,
											'image_id'=>$arr_images[0]['id'],
											'query'=>Request::server('REQUEST_URI')
										]);
		}

		$arrReturn = [];
		if( !empty($arr_images) ) {
			foreach($arr_images as $image) {
				$thumb = URL.'/pic/thumb/'.$image['short_name'].'-'.$image['id'].'.jpg';
				$path_thumb = '/pic/thumb/'.$image['short_name'].'-'.$image['id'].'.jpg';
				$link = URL.'/pic/with-logo/'.$image['short_name'].'-'.$image['id'].'.jpg';
				$ext = 'image/'.substr($image['path'], strrpos($image['path'], '.') + 1);
				$arrReturn[] = [
								'id' => $image['id'],
								'name' => $image['name'],
								'short_name' => $image['short_name'],
								'thumb' => $thumb,
								'path_thumb' => $path_thumb,
								'link'	=> $link,
								'ext'	=> $ext,
								'store' => 'local',
							];
			}
		}
		return ['data'=>$arrReturn, 'total_page'=>$total_page, 'total_image'=>$total_image];
	}

	public static function getBackgrounds()
	{
		$arrData = [];
		if( Cache::has('backgrounds') ) {
			$arrData = Cache::get('backgrounds');
		} else {
			$backgrounds = BannerBackground::select('image')->where('type', 'background')->where('active', 1)->where('name', 'like', '%design%')->orderBy('order_no', 'asc')->get();
			foreach($backgrounds as $background) {
				$arrData[] = URL.'/'.$background->image;
			}
			Cache::forever('background_design', $arrData);
		}
		//$rand = rand(0, count($arrData)-1);
		//return ['main' => isset($arrData[$rand]) ? $arrData[$rand] : ''];
		return $arrData;
	}

    public function saveBackground()
    {
        //$user_ip = Request::getClientIp();
        
		$user_ip = (Request::getClientIp()=='::1') ? '127.0.0.1' : Request::getClientIp();
        Session::set('user_ip', $user_ip);
        $arr_background = Session::has('user_backgrounds')?Session::get('user_backgrounds'):array();
        $arrReturn = [];

        if( Input::hasFile('background') ) {
            $uploaddir = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS;
            
            if(!File::exists($uploaddir)) {
                // path does not exist
                File::makeDirectory($uploaddir, 0777, true);
            }
            
            $file = Input::file('background');
            $fileName = 'bg_'.md5($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
            $file->move($uploaddir, $fileName);
            $url = URL.'/assets/upload/themes/'.$user_ip.'/'.$fileName;
            $arr_background[] = $url;
            Session::set('user_backgrounds', $arr_background);
            $arrReturn = ['url' => $url];
        }
        return $arrReturn;
    }

    public function removeBackground()
    {
    	$bg = Input::get('bg');
    	$arr_background = Session::has('user_backgrounds')?Session::get('user_backgrounds'):array();
    	foreach ($arr_background as $key => $value) {
    		if($value == $bg)
    		{
    			unset($arr_background[$key]);
    		}
    	}
    	Session::set('user_backgrounds', array_values($arr_background));
    	return ['status'=>'ok'];
    }

    public function analyzeImage(){
        $img = Input::get('img');

        $img_name = explode('/',$img);
        $img_name = end($img_name);

        $user_ip = (Request::getClientIp()=='::1') ? '127.0.0.1' : Request::getClientIp();
        $user_ip = Session::has('user_ip') ? Session::get('user_ip') : $user_ip;

        $dir = URL::to('/')."/assets/upload/themes/".$user_ip.'/';

        $file_content = file_get_contents($img);
        // $image = new Imagick();
        // $image->pingImageBlob($file_content);
        // $image->resizeImage(1024, 768, imagick::FILTER_LANCZOS, 1);
        // $image->writeImage('image.png');
        // $image->clear();
        // $image->destroy();

        file_put_contents('./assets/upload/themes/'.$user_ip.'/'.$img_name, $file_content);

        $uploaddir = './assets/upload/themes/'.$user_ip.'/';

        list($width, $height) = getimagesize($uploaddir.$img_name);
        $size = filesize($uploaddir.$img_name) / (1024 * 1024);
        $size = round($size,2);
        $f = $width/$height;
        $mp = round(($width*$height)/1000000,1);
        if ($f<1) {
            $dimensions = array(
                                array(12,16),
                                array(16,21),
                                array(24,32),
                                array(30,40),
                                array(36,48),
                                array(48,64),
                                array(72,96),
                                );
        } elseif ($f>1) {
            $dimensions = array(
                                array(16,12),
                                array(21,16),
                                array(32,24),
                                array(40,30),
                                array(48,36),
                                array(64,48),
                                array(96,72),
                                );
        } elseif ($f==1) {
            $dimensions = array(
                                array(12,12),
                                array(16,16),
                                array(24,24),
                                array(30,30),
                                array(36,36),
                                array(48,48),
                                array(72,72),
                                );
        }
        foreach ($dimensions as $key => $arr_inch) {
            $diagonal = sqrt($arr_inch[0]*$arr_inch[1]);
            $viewdis = 1.5*$diagonal;
            $ppineed = 3438/$viewdis;
            $ppi = ($width*$height)/($arr_inch[0]*$arr_inch[1]);
            $quantity = $ppi/$ppineed;
            $dimensions[$key][2] = $quantity;
            if ($quantity>95) $dimensions[$key][3] = '<b style="color:#197600">AMAZING</b>';
            elseif ($quantity>45) $dimensions[$key][3] = '<b style="color:#206026">GOOD</b>';
            elseif ($quantity>30) $dimensions[$key][3] = '<b style="color:#244327">ACCEPTABLE</b>';
            elseif ($quantity>22) $dimensions[$key][3] = '<b style="color:#594a30">OK but...</b>';
            elseif ($quantity>1.5) $dimensions[$key][3] = '<b style="color:#8d5b04">FAIR, WILL NEED OPTIMIZATION</b>';
            else $dimensions[$key][3] = '<b style="color:#9f0000">DON\'T EVEN THINK ABOUT IT.</b>';
        }
        if(Request::ajax()){

            $arr_data = array('image' => $img,
                                'width' => $width,
                                'height' => $height,
                                'size' => $size,
                                'f' => $f,
                                'mp' => $mp,
                                'dimensions' => $dimensions,
                                'upload_dir' => $dir
                            );
            return json_encode($arr_data);
        }
        return App::abort(404);
    }    	

}