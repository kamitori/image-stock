<?php

class OrdersController extends AdminController {

	public static $table = 'orders';

	public function index()
	{
		$this->layout->title = 'Orders';
		$this->layout->content = View::make('admin.orders-all');
	}

	public function listOrder()
	{
		if( !Request::ajax() ) {
            return App::abort(404);
        }
		$admin_id = Auth::admin()->get()->id;

		$start = Input::has('start') ? (int)Input::get('start') : 0;
		$length = Input::has('length') ? Input::get('length') : 10;
		$search = Input::has('search') ? Input::get('search') : [];
		$orders = Order::with('billingAddress')
						->with('shippingAddress')
						->with('user')
						->select(DB::raw('id, user_id, billing_address_id, shipping_address_id, status, sum_amount, discount, tax, sum_tax, note,
											(SELECT COUNT(*)
												FROM notifications
									         	WHERE notifications.item_id = orders.id
									         		AND notifications.item_type = "Order"
													AND notifications.admin_id = '.$admin_id.'
													AND notifications.read = 0 ) as new'));
		if(!empty($search)){
			foreach($search as $key => $value){
				if(empty($value)) continue;
				if( $key == 'status' ) {
	        		$orders->where($key, $value);
				} else if ($key == 'full_name') {
					$orders->whereHas('user',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('first_name','like', '%'.$value2.'%');
								$q->orWhere('last_name','like', '%'.$value2.'%');
							}
						});
					});
				} else if ($key == 'billing_address_id') {
					$orders->whereHas('billing_address',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('street','like', '%'.$value2.'%');
								$q->orWhere('street_extra','like', '%'.$value2.'%');
							}
						});
					});
				} else if ($key == 'shipping_address_id') {
					$orders->whereHas('shipping_address',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('street','like', '%'.$value2.'%');
								$q->orWhere('street_extra','like', '%'.$value2.'%');
							}
						});
					});
				} else {
	                $value = ltrim(rtrim($value));
	        		$orders->where($key,'like', '%'.$value.'%');
				}
			}
		}
		$order = Input::has('order') ? Input::get('order') : [];
		if(!empty($order)){
			$columns = Input::has('columns') ? Input::get('columns') : [];
			foreach($order as $value){
				$column = $value['column'];
				if( !isset($columns[$column]['name']) || empty($columns[$column]['name']) )continue;
				$orders->orderBy($columns[$column]['name'], ($value['dir'] == 'asc' ? 'asc' : 'desc'));
			}
		}
        $count = $orders->count();
        if($length > 0) {
			$orders = $orders->skip($start)->take($length);
		}
		$arrOrders = $orders->get()->toArray();
		$arrReturn = ['draw' => Input::has('draw') ? Input::get('draw') : 1, 'recordsTotal' => Order::count(),'recordsFiltered' => $count, 'data' => []];
		$arrRemoveNew = [];
		if(!empty($arrOrders)){
			foreach($arrOrders as $key => $order){
				$order['full_name'] = $order['user']['first_name'].' '.$order['user']['last_name'];
				if ( $order['new'] ) {
					$order['full_name'] .= '| <span class="badge badge-danger">new</span>';
					$arrRemoveNew[] = $order['id'];
				}
				$order['billing_address'] = $order['billing_address']['street'].' '.$order['billing_address']['street_extra'];
				$order['shipping_address'] = $order['shipping_address']['street'].' '.$order['shipping_address']['street_extra'];

				//setlocale(LC_MONETARY, 'en_US');
				//$sum_amount = money_format('%i', $order['sum_amount']);
				$sum_amount = VIImage::viFormat($order['sum_amount']);

				$arrReturn['data'][] = array(
	                              ++$start,
	                              $order['id'],
	                              $order['full_name'],
	                              $order['billing_address'],
	                              $order['shipping_address'],
	                              $order['status'],
	                              $sum_amount,
	                              $order['note'],
	                              htmlentities(nl2br($order['billing_address'])),
	                              htmlentities(nl2br($order['shipping_address'])),
	                              );
			}
		}
		if( !empty($arrRemoveNew) ) {
			Notification::whereIn('item_id', $arrRemoveNew)
						->where('item_type', 'Order')
						->where('admin_id', $admin_id)
						->update(['read' => 1]);
		}
		$response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function updateOrder()
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
			$layout = Order::findorFail($id);
			$layout->$name = $value;
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
	    $pass = $layout->valid();
        if($pass->passes()) {
        	$layout->save();
   			$arrReturn = ['status' => 'ok'];
        	$arrReturn['message'] = $layout->name.'Update has been saved';
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
	public function addOrder()
	{
   		$this->layout->title = 'Add Order';
		$this->layout->content = View::make('admin.orders-one');
	}

	public function editOrder($orderId, $export_pdf=false)
	{
   		try {
   			$order = Order::with('images')
   							->with('orderDetails')
   							->with('user')
   							->with('billingAddress')
   							->with('shippingAddress')
   							->findorFail($orderId);
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
   		$order = $order->toArray();
   		$order['images'] = reset($order['images']);


		//Get order detail information
		$order_details = $order['order_details'];										
		foreach($order_details as $key=>$value)
		{						
			$viimage = VIImage::findOrFail($value['image_id']);
			$value['name'] = $viimage->name;			
			$value['short_name'] = $viimage->short_name;			
			$value['path_thumb'] = '/pic/thumb/'.$viimage->short_name.'-'.$viimage->id.'.jpg';
			
			$option = $value['option'];
			$arr_option = [];
			if($option != null && $option != '')
			{
				$arr_option_key = explode(",", $option);	
				foreach ($arr_option_key as $option_key) {
					$data_option = ProductOption::select('options.name', 
													'options.key', 
													DB::raw('option_groups.name as type'), 
													DB::raw('option_groups.key as type_key')
												)
					->join('option_groups', 'options.option_group_id', '=', 'option_groups.id')
                    ->where('options.key', $option_key)
                    ->first();
                    $arr_option[] = ['type'=>$data_option->type, 'type_key'=>$data_option->type_key, 'key'=>$data_option->key, 'value'=>$data_option->name];
				}
			}						
			$value['options'] = $arr_option;
			
			$order_details[$key] = $value;
		}
		$order['order_details'] = $order_details;

		//Get shipping address information
		$shipping_address = $order['shipping_address'];
		$address_obj = Address::findorFail($shipping_address['id']);
		$order['shipping_address_html'] = $address_obj->toHtml();


		//Get billing address information
		$billing_address = $order['billing_address'];
		$address_obj = Address::findorFail($billing_address['id']);
		$order['billing_address_html'] = $address_obj->toHtml();

   		if($export_pdf)
   		{
   			return $order;
   		}
   		//pr($order);die;
   		$this->layout->title = 'Edit Order';
		$this->layout->content = View::make('admin.orders-one')->with([
															'order' 		=> $order,
															]);
	}

	public function deleteOrder($id)
	{
		if( Request::ajax() ) {
			$arrReturn = ['status' => 'error', 'message' => 'Please refresh and try again.'];
			try {
				$order = Order::findorFail($id);
			} catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
				return App::abort(404);
			}
			$id = $order->id;
			if( $order->delete() )
				$arrReturn = ['status' => 'ok', 'message' => "Order #<b>{$id}</b> has been deleted."];
			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
		return App::abort(404);
	}

	public function exportToPdf($order_id, $sendmail=false)
	{
   		$order = $this->editOrder($order_id, true);   		
   		$order['billing_address_html'] = str_replace('<br />', '&nbsp;-&nbsp;', $order['billing_address_html']);
   		$order['shipping_address_html'] = str_replace('<br />', '&nbsp;-&nbsp;', $order['shipping_address_html']);
		$html = View::make('admin.orders-pdf-export')->with([
															'order' 		=> $order,
															])->render();
		
		// $pdf = PDF::loadView('admin.orders-pdf-export', [
		// 													'order' 		=> $order,
		// 													])->setPaper('a4');
		// return $pdf->download('order.pdf');		
		//echo $html;exit;
		// $exportDir = public_path().DS.'assets'.DS.'export';
		// if( !File::exists($exportDir) ) {
		// 	File::makeDirectory($exportDir, 0755);
		// }
		
		$pdf = PDF::loadHTML($html)->setPaper('a4');
				//->save($exportDir.'/order_'.$order_id.'.pdf');
		if($sendmail)
		{
			//$pdf = PDF::loadHTML('<h2>hello</h2>');
			return $pdf->stream();
		}
		$response = Response::make($pdf->stream(), 200);
	    // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
	    $response->header('Content-Type', 'application/pdf');
	    return $response;

	}

	public function getAddress($address_id) 
	{
		$address_obj = Address::findorFail($address_id);
		$html = $address_obj->toHtml();
		if( Request::ajax() ) {
			$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];
			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
        return $html;
	}

	public function editAddress($type, $orderId)
	{
   		try {
   			$order = Order::with($type.'Address')->findorFail($orderId);
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
   		$order = $order->toArray();

		//Get address information
		$address = $order[$type.'_address'];
		$countries = Address::getCountries();
        $states = Address::getStates($address['country_a2']);    
		$html = View::make('admin.orders-address-edit')
										->with(['address'=>$address,
												'countries'=>$countries,
												'states'=>$states,
										])->render();

		if( Request::ajax() ) {
			$arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];
			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
        return $html;            
	}	

    public function createAddress($order_id) {

        $data = \Input::all();

        if((Auth::admin()->check()))
        {
            $address = Address::where($data)->first();
            if(!$address)
            {
                $address = Address::create($data);
                $order_obj = Order::findorFail($order_id);
                if($data['is_billing'])
                {
                	$order_obj->billing_address_id = $address->id;	
                }
                else
                {
                	$order_obj->shipping_address_id = $address->id;		
                }
                
                $order_obj->save();
            }	            
			if( Request::ajax() ) {
				$arrReturn = ['status' => 'ok', 'message' => '', 'result'=>$address];
				$response = Response::json($arrReturn);
				$response->header('Content-Type', 'application/json');
				return $response;
			}
	        return $address;            
        }
		return App::abort(404);
    }    

}