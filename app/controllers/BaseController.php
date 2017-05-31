<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
    protected $layout = 'frontend.layout.default';

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
            $this->layout = View::make($this->layout);
            $this->layout->metaInfo = Home::getMetaInfo();
            $this->layout->types = Home::getTypes();
            $this->layout->categories = Home::getCategories();
            $this->layout->headerMenu = Menu::getCache(['header' => true]);
            $this->layout->footerMenu = Menu::getCache(['footer' => true]);
            $this->layout->cart_count = Cart::count(false);
            $this->layout->mod_upload = Configure::GetValueConfigByKey('mod_upload');
            $this->layout->arr_search_names = Home::getImageNames();
		}
	}

	public static function errors($code = 404, $title = '', $message = '')
    {
        $ajax = Request::ajax();
        if( !$code ) {
            $code = 500;
            $title = 'Internal Server Error';
            $message = 'We got problems over here. Please try again later!';
        } else if( $code == 404 ) {
            $title = 'Oops! You\'re lost.';
            $message = 'We can not find the page you\'re looking for.';
        }
        if( Request::ajax() ) {
            return Response::json([
                'error' => [
                    'message' => $message
                    ]
            ],$code);
        }
        $arrData = [];
        $arrData['content'] = View::make('frontend.errors.error')->with(['title' => $title, 'code' => $code, 'message' => $message]);
        $arrData['metaInfo'] = Home::getMetaInfo();
        $arrData['metaInfo']['meta_title'] = $title;
        $arrData['types'] = Home::getTypes();
        $arrData['categories'] = Home::getCategories();
        $arrData['headerMenu'] = Menu::getCache(['header' => true]);
        return View::make('frontend.layout.default')->with($arrData);
    }

    public function searchColorFromArray($color, $images) {

        $arr_images = $images;
        if($color != '')
        {
            $arr_images = array();
            foreach($images as $value)
            {
                if($value['main_color'] != null && $value['main_color'] != '')
                {
                    $cols = explode(",", $value['main_color']);
                    $flag = false;
                    foreach($cols as $col)
                    {
                        $compare_result = $this->compareColors($color, $col);
                        if($compare_result)
                        {
                            $flag = true;
                            break;
                        }                       
                    }
                    if($flag)
                    {
                        $arr_images[] = $value;
                    }
                }   
            }
        }

        return $arr_images;
    }
    public function compareColors($col1, $col2, $tolerance=65) {

        $col1 = substr($col1, 1);
        $col2 = substr($col2, 1);
        $col1Rgb = array(
            "r" => hexdec(substr($col1, 0, 2)),
            "g" => hexdec(substr($col1, 2, 2)),
            "b" => hexdec(substr($col1, 4, 2))
        );
        $col2Rgb = array(
            "r" => hexdec(substr($col2, 0, 2)),
            "g" => hexdec(substr($col2, 2, 2)),
            "b" => hexdec(substr($col2, 4, 2))
        );
      
        return ($col1Rgb['r'] >= $col2Rgb['r'] - $tolerance && $col1Rgb['r'] <= $col2Rgb['r'] + $tolerance) && ($col1Rgb['g'] >= $col2Rgb['g'] - $tolerance && $col1Rgb['g'] <= $col2Rgb['g'] + $tolerance) && ($col1Rgb['b'] >= $col2Rgb['b'] - $tolerance && $col1Rgb['b'] <= $col2Rgb['b'] + $tolerance);
    }

    function arrangeImages($type=0)
    {
        $arrRange = array();
        switch ($type) {
            case 1:     
                $arrRange[0]['width'] = '31.5%';
                $arrRange[0]['height'] = '32%';
                $arrRange[0]['top'] = '0';
                $arrRange[0]['left'] = '0';
            
                $arrRange[1]['width'] = '31.5%';
                $arrRange[1]['height'] = '32%';
                $arrRange[1]['top'] = '34%';
                $arrRange[1]['left'] = '0';

                $arrRange[2]['width'] = '66.5%';
                $arrRange[2]['height'] = '66%';
                $arrRange[2]['top'] = '0';
                $arrRange[2]['left'] = '33.5%';
            
                $arrRange[3]['width'] = '31.5%';
                $arrRange[3]['height'] = '32%';
                $arrRange[3]['top'] = '68%';
                $arrRange[3]['left'] = '0';
            
                $arrRange[4]['width'] = '32%';
                $arrRange[4]['height'] = '32%';
                $arrRange[4]['top'] = '68%';
                $arrRange[4]['left'] = '33.5%';
            
                $arrRange[5]['width'] = '33%';
                $arrRange[5]['height'] = '32%';
                $arrRange[5]['top'] = '68%';
                $arrRange[5]['left'] = '67%';           
                break;
            case 2:
                $arrRange[0]['width'] = '31.5%';
                $arrRange[0]['height'] = '32%';
                $arrRange[0]['top'] = '0';
                $arrRange[0]['left'] = '0';
            
                $arrRange[1]['width'] = '31.5%';
                $arrRange[1]['height'] = '32%';
                $arrRange[1]['top'] = '0';
                $arrRange[1]['left'] = '33.5%';
            
                $arrRange[2]['width'] = '33%';
                $arrRange[2]['height'] = '32%';
                $arrRange[2]['top'] = '0';
                $arrRange[2]['left'] = '67%';           
                
                $arrRange[3]['width'] = '65%';
                $arrRange[3]['height'] = '65%';
                $arrRange[3]['top'] = '34%';
                $arrRange[3]['left'] = '0';
            
                $arrRange[4]['width'] = '33%';
                $arrRange[4]['height'] = '31%';
                $arrRange[4]['top'] = '34%';
                $arrRange[4]['left'] = '67%';
            
                $arrRange[5]['width'] = '33%';
                $arrRange[5]['height'] = '32%';
                $arrRange[5]['top'] = '67%';
                $arrRange[5]['left'] = '67%';
            
                break;
            case 3:
                $arrRange[0]['width'] = '31.5%';
                $arrRange[0]['height'] = '32%';
                $arrRange[0]['top'] = '0';
                $arrRange[0]['left'] = '0';
            
                $arrRange[1]['width'] = '32%';
                $arrRange[1]['height'] = '32%';
                $arrRange[1]['top'] = '0';
                $arrRange[1]['left'] = '33.5%';
            
                $arrRange[2]['width'] = '33%';
                $arrRange[2]['height'] = '32%';
                $arrRange[2]['top'] = '0';
                $arrRange[2]['left'] = '67%';   
            
                $arrRange[3]['width'] = '31.5%';
                $arrRange[3]['height'] = '32%';
                $arrRange[3]['top'] = '34%';
                $arrRange[3]['left'] = '0';
            
                $arrRange[4]['width'] = '31.5%';
                $arrRange[4]['height'] = '32%';
                $arrRange[4]['top'] = '68%';
                $arrRange[4]['left'] = '0';

                $arrRange[5]['width'] = '66.5%';
                $arrRange[5]['height'] = '66%';
                $arrRange[5]['top'] = '34%';
                $arrRange[5]['left'] = '33.5%';     
                break;
            case 4:
                $arrRange[0]['width'] = '49%';
                $arrRange[0]['height'] = '49%';
                $arrRange[0]['top'] = '0';
                $arrRange[0]['left'] = '0';
            
                $arrRange[1]['width'] = '49%';
                $arrRange[1]['height'] = '49%';
                $arrRange[1]['top'] = '0';
                $arrRange[1]['left'] = '51%';
            
                $arrRange[2]['width'] = '49%';
                $arrRange[2]['height'] = '49%';
                $arrRange[2]['top'] = '51%';
                $arrRange[2]['left'] = '0';
            
                $arrRange[3]['width'] = '49%';
                $arrRange[3]['height'] = '49%';
                $arrRange[3]['top'] = '51%';
                $arrRange[3]['left'] = '51%';            
                break;            
            default:
                $arrRange[0]['width'] = '65%';
                $arrRange[0]['height'] = '66%';
                $arrRange[0]['top'] = '0';
                $arrRange[0]['left'] = '0';
            
                $arrRange[1]['width'] = '33%';
                $arrRange[1]['height'] = '32%';
                $arrRange[1]['top'] = '0';
                $arrRange[1]['left'] = '67%';
            
                $arrRange[2]['width'] = '33%';
                $arrRange[2]['height'] = '32%';
                $arrRange[2]['top'] = '34%';
                $arrRange[2]['left'] = '67%';
            
                $arrRange[3]['width'] = '31.5%';
                $arrRange[3]['height'] = '32%';
                $arrRange[3]['top'] = '68%';
                $arrRange[3]['left'] = '0';
            
                $arrRange[4]['width'] = '31.5%';
                $arrRange[4]['height'] = '32%';
                $arrRange[4]['top'] = '68%';
                $arrRange[4]['left'] = '33.5%';
            
                $arrRange[5]['width'] = '33%';
                $arrRange[5]['height'] = '32%';
                $arrRange[5]['top'] = '68%';
                $arrRange[5]['left'] = '67%';           
                break;
        }       

        return $arrRange;   
    }        

}
