<?php

use League\ColorExtractor\Client as ColorExtractor;

class ImagesController extends AdminController {

	public static $table = 'images';
	protected $take = 20;

	public function index()
	{
		if( !Input::has('page') ) {
			$pageNum = 1;
		} else {
			$pageNum = (int)Input::get('page');
		}
		$admin_id = Auth::admin()->get()->id;
		$arrCategories = [];
		$name = '';
		$take = $this->take;
		$skip = floor( ($pageNum -1) * $take );
		$images = VIImage::select('id', 'name', 'short_name', 'description', 'keywords',
									'artist', 'model', 'gender', 'age_from', 'age_to', 'number_people', 'active',
																					DB::raw('(SELECT COUNT(*)
																							FROM notifications
																				         	WHERE notifications.item_id = images.id
																				         		AND notifications.item_type = "Image"
																								AND notifications.admin_id = '.$admin_id.'
																								AND notifications.read = 0 ) as new'))							
							->withType('main')
							->with('categories')
							->with('collections');
		if( Input::has('categories') ) {
			$arrCategories = (array)Input::get('categories');
			$images->whereHas('categories', function($query) use($arrCategories){
				$query->whereIn('id', $arrCategories);
			});
		}
		if( Input::has('name') ) {
			$name = Input::get('name');
			$nameStr = '*'.$name.'*';
			$images->search($nameStr);
		}
		$images = $images->take($take)
							->skip($skip)
							->orderBy('id', 'desc')
							->get();
		$arrImages = [];
		if( !$images->isempty() ) {
			$arrImages = $arrRemoveNew = [];
			foreach($images as $image) {
				$image->path = URL.'/pic/large-thumb/'.$image->short_name.'-'.$image->id.'.jpg';
				$image->dimension = $image->width.'x'.$image['height'];
				if( $image->new ) {
					$arrRemoveNew[] = $image->id;
				}
				$arrImages[$image->id] = $image;
				foreach(['arrCategories' => [
											'name' => 'categories',
											'id' 	=> 'id'
										],
						'arrCollections' => [
											'name' => 'collections',
											'id' => 'id'
										]
						] as $key => $value) {
					$arr = [];
					foreach($image->$value['name'] as $v) {
						$arr[] = $v[$value['id']];
					}
					$arrImages[$image->id][$key] = $arr;
				}
				unset($arr);
			}
			if( !empty($arrRemoveNew) ) {
				Notification::whereIn('item_id', $arrRemoveNew)
							->where('item_type', 'Image')
							->where('admin_id', $admin_id)
							->update(['read' => 1]);
			}
		}
		if( Request::ajax() ) {
			return $arrImages;
		}
		$this->layout->title = 'Images';
		$this->layout->content = View::make('admin.images-all')->with([
															'images' 		=> $arrImages,
															'pageNum'		=> $pageNum,
															'categories' 	=> Category::getSource(),
															'name' 			=> $name,
															'arrCategories' => $arrCategories,
															'collections' 	=> Collection::getSource(),
															'apiKey'		=> Configure::getApiKeys()
														]);
	}

	public function updateImage()
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}
		$arrReturn = ['status' => 'error'];
		$id = Input::has('id') ? Input::get('id') : 0;
		if( $id ) {
			$create = false;
			try {
				$image = VIImage::findorFail( (int)Input::get('id') );
			} catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
				return App::abort(404);
			}
			$message = 'has been updated successful';
		} else {
			$create = true;
			$image = new VIImage;
			$message = 'has been created successful';
		}

		if( $create && !Input::hasFile('image') ) {
			return ['status' => 'error', 'message' => 'You need to upload at least 1 image.'];
		}

		$image->name = Input::get('name');
		$image->short_name = Str::slug($image->name );
		$image->description = Input::get('description');
		$image->keywords = Input::get('keywords');
		$image->keywords = rtrim(trim($image->keywords), ',');

		$image->active = Input::get('active');
		//echo 'active: '.$image->active;exit;

		$image->model = Input::get('model');
		$image->model = rtrim(trim($image->model), ',');

		$image->artist = Input::get('artist');
		$image->age_from = Input::get('age_from');
		$image->age_to = Input::get('age_to');
		$image->gender = Input::get('gender');
		$image->number_people = Input::get('number_people');

		$pass = $image->valid();

		if( $pass->passes() )
		{
            if( Input::hasFile('image') )
            {
            	$color_extractor = new ColorExtractor;
	            $myfile = Input::file('image');

	            $mime_type = $myfile->getClientMimeType();

	            switch ($mime_type) {
	                case 'image/jpeg':
	                    $palette_obj = $color_extractor->loadJpeg($myfile);
	                    break;
	                case 'image/png':
	                    $palette_obj = $color_extractor->loadPng($myfile);
	                    break;
	                case 'image/gif':
	                    $palette_obj = $color_extractor->loadGif($myfile);
	                    break;
	            }
	            $main_color = '';
	            if(is_object($palette_obj))
	            {
	                $arr_palette = $palette_obj->extract(5);
	                if(!empty($arr_palette))
	                {
	                    $main_color = strtolower($arr_palette[0]);
	                    for($i=1; $i<count($arr_palette); $i++)
	                    {
	                        $main_color .= ','.strtolower($arr_palette[$i]);
	                    }
	                }
	            }
	            $image->main_color = $main_color;
            }

			$image->save();

	        //insert into statistic_images table
	        if($create)
	        {
        		StatisticImage::create([
	                'image_id'  =>$image->id,
	                'view'      => '0',
	                'download'  => '0',
	            ]);
	        }


			foreach(['category_id' => 'categories', 'collection_id' => 'collections'] as $key => $value) {
				$arrOld = $remove = $add = [];
				$old = $image->$value;
				$data =  Input::has($key) ? Input::get($key) : [];
				$data = (array)json_decode($data[0]);
				if( !empty($old) ) {
					foreach($old as $val) {
						if( !in_array($val->id, $data) ) {
							$remove[] = $val->id;
						} else {
							$arrOld[] = $val->id;
						}
					}
				}

				foreach($data as $id) {
					if( !$id ) continue;
					if( !in_array($id, $arrOld) ) {
						$add[] = $id;
					}
				}

				if( !empty($remove) ) {
					$image->$value()->detach( $remove );
				}
				if( !empty($add) ) {
					$image->$value()->attach( $add );
				}
				foreach($add as $v) {
					$arrOld[] = $v;
				}
				$image->{'arr'.ucfirst($value)} = $arrOld;
			}

			$path = public_path('assets'.DS.'upload'.DS.'images'.DS.$image->id);
			if(  $create ) {
				$main = new VIImageDetail;
				$main->image_id = $image->id;
				$main->type = 'main';
				File::makeDirectory( $path, 0755, true );
			} else {
				$main = VIImageDetail::where('type', 'main')
										->where('image_id', $image->id)
										->first();				
			}
			if(!$create && Input::hasFile('image') || (Input::has('choose_image') && Input::has('choose_name')))
			{
				File::delete( public_path( $main->path ) );
			}
			if( Input::hasFile('image') ) {
				$file = Input::file('image');
				$name = $image->short_name.'.'.$file->getClientOriginalExtension();
				$file->move($path, $name);
				$imageChange = true;
			} else if( Input::has('choose_image') && Input::has('choose_name') ) {
				$chooseImage = Input::get('choose_image');
				$name = Input::get('choose_name');
				file_put_contents($path.DS.$name, file_get_contents($chooseImage));
				$imageChange = true;
			}

			if( isset($imageChange) ) {
				$main->path = 'assets/upload/images/'.$image->id.'/'.$name;
				$img = Image::make($path.DS.$name);
				$main->width = $img->width();
				$main->height = $img->height();
				$main->ratio = $main->width / $main->height;
				$img = new Imagick($path.DS.$name);
				$dpi = $img->getImageResolution();
				$main->dpi = $dpi['x'] > $dpi['y'] ? $dpi['x'] : $dpi['y'];
				if($main->dpi == 0)
				{
					$main->dpi = 72;
				}
				$main->size = $img->getImageLength();
				$main->extension = strtolower($img->getImageFormat());
				$main->save();
				BackgroundProcess::makeSize($main->detail_id);
				$image->changeImg = true;
				if( $create ) {
					$image->dimension = $main->width.'x'.$main->height;
					$image->newImg = true;
					$image->path = URL.'/pic/large-thumb/'.$image->short_name.'-'.$image->id.'.jpg';
				}
			}

			$arrReturn['status'] = 'ok';
			$arrReturn['message'] = "{$image->name} $message.";
			$arrReturn['data'] = $image;
			return $arrReturn;
		}
		$arrReturn['message'] = '';
		$arrErr = $pass->messages()->all();
		foreach($arrErr as $value)
		    $arrReturn['message'] .= "$value\n";

		return $arrReturn;
	}

	public function deleteImage($id)
	{
		if( Request::ajax() ) {
   			$arrReturn = ['status' => 'error', 'message' => 'Please refresh and try again.'];
   			try {
	   			$image = VIImage::findorFail($id);
		    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
		        return App::abort(404);
		    }
		    $name = $image->name;
   		    if( $image->delete() )
   		        $arrReturn = ['status' => 'ok', 'message' => "<b>{$name}</b> has been deleted."];
   		    $response = Response::json($arrReturn);
   		    $response->header('Content-Type', 'application/json');
   		    return $response;
   		}
   		return App::abort(404);
	}

	public function inactive()
	{
		$this->layout->title = 'Inactive Images';
		$this->layout->content = View::make('admin.images-inactive');
	}

	public function listInactiveImages()
	{
		if( !Request::ajax() ) {
            return App::abort(404);
        }
		$admin_id = Auth::admin()->get()->id;

		$start = Input::has('start') ? (int)Input::get('start') : 0;
		$length = Input::has('length') ? Input::get('length') : 10;
		$search = Input::has('search') ? Input::get('search') : [];

		$images = VIImage::with('user')
							->with('details')
							->select('images.id', 'images.name', 'images.short_name', 'images.active', 'images.author_id', 'images.store',
																					DB::raw('(SELECT COUNT(*)
																							FROM notifications
																				         	WHERE notifications.item_id = images.id
																				         		AND notifications.item_type = "Image"
																								AND notifications.admin_id = '.$admin_id.'
																								AND notifications.read = 0 ) as new'))
							->where('images.active', '=', 0);


		if(!empty($search)){
			foreach($search as $key => $value){
				if(empty($value)) continue;
				if ($key == 'full_name') {
					$images->whereHas('user',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('first_name','like', '%'.$value2.'%');
								$q->orWhere('last_name','like', '%'.$value2.'%');
							}
						});
					});
				} else {
	                $value = ltrim(rtrim($value));
	        		$images->where($key,'like', '%'.$value.'%');
				}
			}
		}

		$images->groupBy('images.id');

		$order = Input::has('order') ? Input::get('order') : [];
		if(!empty($order)){
			$columns = Input::has('columns') ? Input::get('columns') : [];
			foreach($order as $value){
				$column = $value['column'];
				if( !isset($columns[$column]['name']) || empty($columns[$column]['name']) )continue;
				$images->orderBy($columns[$column]['name'], ($value['dir'] == 'asc' ? 'asc' : 'desc'));
			}
		}
		else
		{
			$images->orderBy('images.id', 'desc');
		}
        $count = $images->count();
        if($length > 0) {
			$images = $images->skip($start)->take($length);
		}
		$arrImages = $images->get()->toArray();

		//pr(DB::getQueryLog());die;
		//pr($arrImages);exit;

		$arrReturn = ['draw' => Input::has('draw') ? Input::get('draw') : 1, 'recordsTotal' => Order::count(),'recordsFiltered' => $count, 'data' => []];
		$arrRemoveNew = [];
		if(!empty($arrImages)){
			foreach($arrImages as $key => $image){
				$image['full_name'] = $image['user']['first_name'].' '.$image['user']['last_name'];
				if ( $image['new'] ) {
					$image['full_name'] .= '| <span class="badge badge-danger">new</span>';
					$arrRemoveNew[] = $image['id'];
				}
				$details='';
				foreach($image['details'] as $key1=>$value1)
				{
					if($image['store'] == 'dropbox')
					{

						$details .= '<div><a href="https://www.dropbox.com/home/images?preview='.$value1['path'].'" target="_blank">'.$value1['path'].'</a></div>';
					}
					else
					{
						$details .= '<div><a href="'.asset($value1['path']).'" target="_blank">'.$value1['path'].'</a></div>';	
					}
					
				}

				$arrReturn['data'][] = array(
	                              ++$start,
	                              $image['id'],
	                              $image['full_name'],
	                              $image['name'],
	                              $details,
	                              $image['active'],	                              
	                              );
			}
		}
		if( !empty($arrRemoveNew) ) {
			Notification::whereIn('item_id', $arrRemoveNew)
						->where('item_type', 'Image')
						->where('admin_id', $admin_id)
						->update(['read' => 1]);
		}
		$response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function updateStatus()
	{
		if( Input::has('pk') ) {
   			if( !Request::ajax() ) {
	   			return App::abort(404);
	   		}

	   		return self::updateQuickEdit();
		}
	}
	public function updateQuickEdit()
	{
   		$arrReturn = ['status' => 'error'];
   		$id = (int)Input::get('pk');
   		$name = (string)Input::get('name');
   		$value = Input::get('value');
   		try {
			$layout = VIImage::findorFail($id);
			$layout->$name = $value;
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
	    $pass = $layout->valid();
        if($pass->passes()) {
        	$layout->save();
   			$arrReturn = ['status' => 'ok'];
        	$arrReturn['message'] = $layout->name.'Update has been saved';

        	//Add to session
        	if($name == 'active')
        	{
        		$list_sendmail = array();
        		//pr(Session::get('list_sendmail'));
				if ( Session::has('list_sendmail') )
		        {
		            $list_sendmail = Session::get('list_sendmail');
		        }
		        $obj_image = $layout->toArray();
		        $obj_user = User::find($obj_image['author_id'])->toArray();
		        $f=0;
		        foreach ($list_sendmail as $key => $user) {
		        	
		        	if($user['id'] == $obj_user['id'])
		        	{
		        		$arr_images = $user['images'];
			        	$f1=0;
			        	foreach ($arr_images as $key1 => $image) {
			        		if($image['id'] == $obj_image['id'])
			        		{
			        			if($value == 1)
			        			{
			        				$arr_images[$key1] = $obj_image;
			        			}
			        			else
			        			{
			        				unset($arr_images[$key1]);			        				
			        			}			        			
			        			$f1 = 1;
			        			//break;
			        		}
			        	}
			        	
			        	if($f1 != 1)
			        	{
			        		if($value == 1)
			        		{
			        			$arr_images[] = $obj_image;	
			        		}			        		
			        	}
			        	else
			        	{
			        		$arr_images = array_values($arr_images);	
			        	}
		        		$obj_user['images'] = $arr_images;
		        		$list_sendmail[$key] = $obj_user;
		        		$f=1;
		        		break;	
		        	}
			    }
		        if($f != 1)
		        {
		        	if($value == 1)
		        	{
		        		$obj_user['images'] = [$obj_image];
			        	$list_sendmail[] = $obj_user;		        		
		        	}	
		        }		        
	        	Session::put('list_sendmail', $list_sendmail);
        	}
        	
			//pr(Session::get('list_sendmail'));exit;


        } else {
        	$arrReturn['message'] = '';
        	$arrErr = $pass->messages()->all();
        	foreach($arrErr as $value)
        	    $arrReturn['message'] .= "$value\n";
        }
        $response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function listSendmail()
	{
		$html = View::make('admin.images-list-sendmail')
					->with([])->render();
		
		if( Request::ajax() ) {
			$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];
			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
        return $html;            		
	}

	public function sendmailActivated()
	{
		$list_sendmail = array();
		//pr(Session::get('list_sendmail'));
		if ( Session::has('list_sendmail') )
        {
            $list_sendmail = Session::get('list_sendmail');
        }
        foreach ($list_sendmail as $key => $user)
        {
	        Mail::send('admin.emails.images.activated',
	         array('userdata' => $user), function($message) use ($user) {

	            $message->to($user['email'], $user['first_name']." ".$user['last_name'])->subject('Your images have been activated.');
	        });
        }
        //Clear session
        Session::forget('list_sendmail');
		if( Request::ajax() ) {
			$arrReturn = ['status' => 'ok', 'message' => ''];
			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
        return;
	}
}