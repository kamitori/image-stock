<?php

class UserController extends BaseController {
	
	function loadGallery($user_id)
	{
        try {
            $user_obj = User::findOrFail($user_id);
        } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return App::abort(404);
        }

		$data = Category::select('categories.id as category_id',
										'categories.name as category_name',
										'categories.short_name as category_short_name',
										'categories.description as category_description'
									)->withUserImages($user_obj->id)
									->where('categories.active', 1)       
									->orderBy('categories.name', 'asc')->get()->toArray();
		//pr(DB::getQueryLog());die;
		$arrCategories = array();
		if(!empty($data))
		{		
			$arrImages = array();
			$i = 0;
			
			$arrImages[0] = $data[0];
			$category_id = $data[0]['category_id'];
			for ($j=1; $j<count($data); $j++) 
			{
				$cat = $data[$j];
				if($cat['category_id'] == $category_id)
				{
					$arrImages[] = $cat;
				}
				else
				{
					if(!empty($arrImages))
					{
						$arrCategories[$i]['id'] = $data[$j-1]['category_id'];
						$arrCategories[$i]['name'] = $data[$j-1]['category_name'];
						$arrCategories[$i]['short_name'] = $data[$j-1]['category_short_name'];
						$arrCategories[$i]['description'] = $data[$j-1]['category_description'];					
						$arrCategories[$i]['images'] = $arrImages;						
						$i++;
						$category_id = $cat['category_id'];
						$arrImages = array();
						$arrImages[0] = $cat;
					}
				}
			}
			if($i == 0)
			{
				$arrCategories[$i]['id'] = $data[$i]['category_id'];
				$arrCategories[$i]['name'] = $data[$i]['category_name'];
				$arrCategories[$i]['short_name'] = $data[$i]['category_short_name'];
				$arrCategories[$i]['description'] = $data[$i]['category_description'];
				$arrCategories[$i]['images'] = $arrImages;					
			}
			else
			{
				if(!empty($arrImages))
				{
					$arrCategories[$i]['id'] = $data[$j-1]['category_id'];
					$arrCategories[$i]['name'] = $data[$j-1]['category_name'];
					$arrCategories[$i]['short_name'] = $data[$j-1]['category_short_name'];
					$arrCategories[$i]['description'] = $data[$j-1]['category_description'];
					$arrCategories[$i]['images'] = $arrImages;						
				}
			}
		}
	
		//pr($arrCategories);exit;
		$this->layout->content = View::make('frontend.users.gallery')->with([
																		'arrCategories'=>$arrCategories,
																		'user_obj'=>$user_obj,
																		'currentObj'=>$this
																		]);
	}

	function loadLightboxes($user_id)
	{
        try {
            $user_obj = User::findOrFail($user_id);
        } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return App::abort(404);
        }

		$data = Lightbox::select('lightbox.id',
										'lightbox.name'
									)->with('images')
									->where('lightbox.user_id', $user_obj->id)       
									->orderBy('lightbox.name', 'asc')->get()->toArray();
		//pr(DB::getQueryLog());die;
		//pr($data);exit;		
	
		$this->layout->content = View::make('frontend.users.collection')->with([
																		'arrLightboxes'=>$data,
																		'user_obj'=>$user_obj,
																		'currentObj'=>$this
																		]);
	}

	function about($user_id)
	{
        try {
            $user_obj = User::findOrFail($user_id);
        } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return App::abort(404);
        }

		$this->layout->content = View::make('frontend.users.about')->with([
																		'user_obj'=>$user_obj,
																		"categories"=>$this->layout->categories,
																		]);
	}	
}