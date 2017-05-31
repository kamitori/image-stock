<?php

class BannersBackgroundsController extends AdminController {

	public static $table = 'banners_backgrounds';

	public function index()
	{
		$this->layout->title = 'Banners-Backgrounds';
		$this->layout->content = View::make('admin.banners-backgrounds-all');
	}

	public function listBannerBackground()
	{
   		if( !Request::ajax() ) {
            return App::abort(404);
        }
		$start = Input::has('start') ? (int)Input::get('start') : 0;
		$length = Input::has('length') ? Input::get('length') : 10;
		$search = Input::has('search') ? Input::get('search') : [];
		$banners_backgrounds = BannerBackground::select('id', 'name', 'order_no', 'image', 'type', 'active');
		if(!empty($search)){
			foreach($search as $key => $value){
				if(empty($value)) continue;
				if( $key == 'active' ) {
					if( $value == 'yes' ) {
						$value = 1;
					} else {
						$value = 0;
					}
	        		$banners_backgrounds->where($key, $value);
	        	} else {
	                $value = ltrim(rtrim($value));
	        		$banners_backgrounds->where($key,'like', '%'.$value.'%');
				}
			}
		}
		$order = Input::has('order') ? Input::get('order') : [];
		if(!empty($order)){
			$columns = Input::has('columns') ? Input::get('columns') : [];
			foreach($order as $value){
				$column = $value['column'];
				if( !isset($columns[$column]['name']) || empty($columns[$column]['name']) )continue;
				$banners_backgrounds->orderBy($columns[$column]['name'], ($value['dir'] == 'asc' ? 'asc' : 'desc'));
			}
		}
        $count = $banners_backgrounds->count();
        if($length > 0) {
			$banners_backgrounds = $banners_backgrounds->skip($start)->take($length);
		}
		$arrBannerBackgrounds = $banners_backgrounds->get()->toArray();
		$arrReturn = ['draw' => Input::has('draw') ? Input::get('draw') : 1, 'recordsTotal' => BannerBackground::count(),'recordsFiltered' => $count, 'data' => []];
		if(!empty($arrBannerBackgrounds)){
			foreach($arrBannerBackgrounds as $banner_background){
				$arrReturn['data'][] = array(
	                              ++$start,
	                              $banner_background['id'],
	                              $banner_background['name'],
	                              $banner_background['image'],
                                $banner_background['type'],
	                              $banner_background['order_no'],
	                              $banner_background['active'],
	                              );
			}
		}
		$response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function updateBannerBackground()
	{
		if( Input::has('pk') ) {
   			if( !Request::ajax() ) {
	   			return App::abort(404);
	   		}
	   		return self::updateQuickEdit();
		} else if( !Request::ajax() ) {
   			return App::abort(404);
   		}

   		$arrReturn = ['status' => 'error'];

   		$banner_background = new BannerBackground;
   		$banner_background->name = Input::get('name');
      $banner_background->type = Input::get('type');
   		$banner_background->order_no = (int)Input::get('order_no');
   		$banner_background->active = Input::has('active') ? 1 : 0;

   		if (Input::hasFile('image')) {
   			$oldPath = $banner_background->image;
        $public_path = 'assets'.DS.'images'.DS.'banners';
        if($banner_background->type == 'background')
        {
          $public_path = 'assets'.DS.'images'.DS.'background';
        }
   			$path = VIImage::upload(Input::file('image'), public_path($public_path), 1440, false);
   			$path = str_replace(public_path().DS, '', $path);
   			$banner_background->image = str_replace(DS, '/', $path);
   			if( $oldPath == $banner_background->image ) {
   				unset($oldPath);
   			}
   		}

   		$pass = $banner_background->valid();

   		if( $pass ) {

   			$banner_background->save();

   			if( isset($oldPath) && File::exists(public_path($oldPath)) ) {
   				File::delete(  public_path($oldPath) );
			}

   			$arrReturn = ['status' => 'ok'];
        	$arrReturn['message'] = $banner_background->name.' has been saved';
        	$arrReturn['data'] = $banner_background;
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

	public function updateQuickEdit()
	{
   		$arrReturn = ['status' => 'error'];
   		$id = (int)Input::get('pk');
   		$name = (string)Input::get('name');
      $type = (string)Input::get('type');
   		try {
   			$banner_background = BannerBackground::findorFail($id);
   			if( $name == 'image' ) {
   				if (Input::hasFile('image')) {

            $public_path = 'assets'.DS.'images'.DS.'banners';
            $img_width = 1440;           
            if($banner_background->type == 'background')
            {
              $public_path = 'assets'.DS.'images'.DS.'background';
              $img_width = 1366;
            }

            if($type != null && $type != '')
            {
              $public_path = 'assets'.DS.'images'.DS.'banners';            
              if($type == 'background')
              {
                $public_path = 'assets'.DS.'images'.DS.'background';
              }              
            }
   					
            $path = VIImage::upload(Input::file('image'), public_path().DS.$public_path, $img_width, false);
   				}
   				if( $path ) {
   					$path = str_replace(public_path().DS, '', $path);
   					$oldPath = $banner_background->image;
   					$banner_background->image = str_replace(DS, '/', $path);
   					if( $oldPath == $banner_background->image ) {
   						unset($oldPath);
   					}
        			$arrReturn['path'] = URL.'/'.$banner_background->image;
		   		}
   			} else {
   				$value = Input::get('value');
   				if( $name == 'active' ) {
   					$banner_background->active = (int)$value;
   				} else {
   					$banner_background->$name = $value;
   				}
   			}
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
	    $pass = $banner_background->valid();
        if($pass->passes()) {
        	$banner_background->save();
   			if( isset($oldPath) && File::exists(public_path($oldPath)) ) {
   				File::delete(  public_path($oldPath) );
			}
   			$arrReturn['status'] = 'ok';
        	$arrReturn['message'] = $banner_background->name.' has been saved';
        } else {
        	$arrReturn['message'] = '';
        	$arrErr = $pass->messages()->all();
        	foreach($arrErr as $value)
        	    $arrReturn['message'] .= "$value\n";
        }
		return $arrReturn;
	}

    public function deleteBannerBackground($id)
   	{
   		if( Request::ajax() ) {
   			$arrReturn = ['status' => 'error', 'message' => 'Please refresh and try again.'];
   			try {
	   			$banner_background = BannerBackground::findorFail($id);
		    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
		        return App::abort(404);
		    }
		    $name = $banner_background->name;
   		    if( $banner_background->delete() )
   		        $arrReturn = ['status' => 'ok', 'message' => "<b>{$name}</b> has been deleted."];
   		    $response = Response::json($arrReturn);
   		    $response->header('Content-Type', 'application/json');
   		    return $response;
   		}
   		return App::abort(404);
   	}
}