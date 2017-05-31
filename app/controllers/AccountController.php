<?php
use Illuminate\Support\Contracts\MessageProviderInterface;
use Faker\Factory as Faker;

class AccountController extends BaseController {


    public function show()
    {
        if( Auth::user()->check())
        {
            $user = User::select('users.*',
                                'addresses.city',
                                'addresses.state_name',
                                'addresses.country_name'
                            )->where('email', '=', Auth::user()->get()->email)
                            ->leftJoin('addresses', function($join){
                                    $join->on('addresses.user_id', '=', 'users.id')
                                            ->where('addresses.is_billing', '=', '1');
                            })->first();

            if(is_object($user)) {

                $this->layout->content = View::make('frontend.profile.show')
                ->with('user', $user);
                return;

            }
        }
        return Redirect::route('account-sign-in');
    }

    public function home()
    {
        if( Auth::user()->check())
        {
            $user = User::where('email', '=', Auth::user()->get()->email);

            if($user->count()) {
                $user = $user->first();
                $this->layout->content = View::make('frontend.account.home')
                ->with('user', $user);
                return;

            }
        }
        return Redirect::route('account-sign-in');
    }

    public function changeAvatar()
    {
        $allowed_ext = array('jpg','jpeg','png','gif');

        if( Request::ajax() && Auth::user()->check() )
        {
            if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
                return $this->exit_status('Error! Wrong HTTP method!');
            }

            if(Input::hasFile('myfile') && $_FILES['myfile']['error'] == 0 ){

                $faker = Faker::create();

                $pic = Input::file('myfile');

                $extension = strtolower($pic->getClientOriginalExtension());
                if(!in_array($extension,$allowed_ext)){
                    return $this->exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
                }

                $file_name = Auth::user()->get()->id.'_'.$faker->lexify($string = '???????????????????');
                $url = $file_name.".".$extension;

                $upload_folder = 'assets'.DS.'upload'.DS.'users';
                $imgDir = public_path().DS.$upload_folder;
                if( !File::exists($imgDir) ) {
                    File::makeDirectory($imgDir, 0755);
                }
                $url = $upload_folder.DS.$url;
                $url = str_replace('\\', '/', $url);

                if(VIImage::upload($pic, $imgDir, 110, false, $file_name))
                {
                    $user_obj = User::findOrFail(Auth::user()->get()->id);

                    //remove old avatar
                    File::delete( public_path( $user_obj->image ) );

                    $user_obj->image = $url;
                    $user_obj->updated_at = date("Y-m-d H:i:s");
                    $user_obj->save();

                    return $this->exit_status('File was uploaded successfuly!');
                }
            }
        }

        return $this->exit_status('Something went wrong with your upload!');
    }

    function exit_status($str){
        $response = Response::json(array('status'=>$str));
        $response->header('Content-Type', 'application/json');
        return $response;
    }


    //load recently viewed images
    public function loadRecentlyViewImages()
    {
        if(Auth::user()->check())
        {

            $query = RecentlyViewImages::select('recently_view_images.image_id',
                                        'images.short_name',
                                        'images.name',
                                        'image_details.path',
                                        'image_details.width',
                                        'image_details.height'
                );

            $query->leftJoin('images', 'images.id', '=', 'recently_view_images.image_id');
            $query->leftJoin('image_details', 'recently_view_images.image_id', '=', 'image_details.image_id');
            $query->where('recently_view_images.user_id', '=', Auth::user()->get()->id);
            $query->where('image_details.type', '=', 'main');
            $query->orderBy('recently_view_images.id', 'desc');
            $data = $query->take(10)->get();

            $arrRecentlyViewImages = array();
            if($data->count() > 0)
            {
                $i = 0;
                foreach ($data as $value)
                {
                    $arrRecentlyViewImages[$i]['short_name'] = $value->short_name;
                    $arrRecentlyViewImages[$i]['name'] = $value->name;
                    $arrRecentlyViewImages[$i]['image_id'] = $value->image_id;
                    $arrRecentlyViewImages[$i]['path'] = '/pic/thumb/'.$value->short_name.'-'.$value->image_id.'.jpg';
                    //$arrRecentlyViewImages[$i]['path'] = $value->path;
                    $arrRecentlyViewImages[$i]['width'] = $value->width;
                    $arrRecentlyViewImages[$i]['height'] =$value->height;
                    $i++;
                }
            }
            if( Request::ajax() ) {

                $html = View::make('frontend.account.recently-view-images')->with('arrRecentlyViewImages', $arrRecentlyViewImages)->render();

                $arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];

                $response = Response::json($arrReturn);
                $response->header('Content-Type', 'application/json');
                return $response;

            }

        }
        return Redirect::route('account-sign-in');
        //return App::abort(404);
    }

    //Add to recently_view_images table
    public function addToRecentlyViewImages($arrData) {


        $user_id = isset($arrData['user_id']) ? $arrData['user_id'] : 0;
        $current_user_id = Auth::user()->check() ? Auth::user()->get()->id : $user_id;
        if($current_user_id != 0)
        {
            $query = RecentlyViewImages::where('user_id', '=', $current_user_id);
            $query = $query->where('image_id', '=', $arrData['image_id']);
            $query = $query->first();
            if(is_object($query))
            {
                return;
            }

            $query = RecentlyViewImages::where('user_id', $current_user_id)
                    ->orderBy('id', 'asc')
                    ->get();
            if(!$query->isempty())
            {
                $data = $query->toArray();
                if(count($data) >= 10)
                {
                    $recentlyObj = RecentlyViewImages::findorFail($data[0]['id']);
                    $recentlyObj->delete();
                }
            }

            return RecentlyViewImages::create(
                array(
                    'user_id'   => $current_user_id,
                    'image_id'  => $arrData['image_id']
            ));

        }
        return Redirect::route('account-sign-in');
    }

    //load recently search images
    public function loadRecentlySearchImages()
    {
        if(Auth::user()->check())
        {
            $query = RecentlySearchImages::select('recently_search_images.image_id',
                                        'recently_search_images.keyword',
                                        'recently_search_images.query',
                                        'images.short_name',
                                        'image_details.path',
                                        'image_details.width',
                                        'image_details.height'

                );

            $query->leftJoin('images', 'images.id', '=', 'recently_search_images.image_id');
            $query->leftJoin('image_details', 'recently_search_images.image_id', '=', 'image_details.image_id');
            $query->where('recently_search_images.user_id', '=', Auth::user()->get()->id);
            $query->where('image_details.type', '=', 'main');
            $query->orderBy('recently_search_images.id', 'desc');
            $data = $query->take(10)->get();

            $arrRecentlySearchImages = array();
            if($data->count() > 0)
            {
                $i = 0;
                foreach ($data as $value)
                {
                    $arrRecentlySearchImages[$i]['query'] = $value->query;
                    $arrRecentlySearchImages[$i]['keyword'] = $value->keyword;
                    $arrRecentlySearchImages[$i]['path'] = '/pic/thumb/'.$value->short_name.'-'.$value->image_id.'.jpg';
                    //$arrRecentlySearchImages[$i]['path'] = $value->path;
                    $arrRecentlySearchImages[$i]['width'] = $value->width;
                    $arrRecentlySearchImages[$i]['height'] =$value->height;


                    $i++;
                }
            }

            if( Request::ajax() ) {

                $html = View::make('frontend.account.recently-search-images')->with('arrRecentlySearchImages', $arrRecentlySearchImages)->render();

                $arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];

                $response = Response::json($arrReturn);
                $response->header('Content-Type', 'application/json');
                return $response;

            }

        }
        return Redirect::route('account-sign-in');
        //return App::abort(404);
    }

    //Add to recently_search_images table
    public function addToRecentlySearchImages($arrData) {

        $query = isset($arrData['query']) ? $arrData['query'] : "";
        $request_url = (Request::server('REQUEST_URI') != "" && Request::server('REQUEST_URI') != "/") ? Request::server('REQUEST_URI') : $query;

        $user_id = isset($arrData['user_id']) ? $arrData['user_id'] : 0;
        $current_user_id = Auth::user()->check() ? Auth::user()->get()->id : $user_id;
        if($current_user_id) {
            $currentTime = date('Y-m-d h:i:s');
            DB::statement('INSERT INTO recently_search_images (id, keyword, user_id, image_id, query, created_at, updated_at)
                             VALUES(
                                    (SELECT id
                                        FROM (
                                            SELECT id
                                            FROM recently_search_images
                                            WHERE user_id = '.$current_user_id.'
                                            AND query = "'.$request_url.'"
                                        ) as b
                                    ), "'.$arrData['keyword'].'", '.$current_user_id.', '.$arrData['image_id'].', "'.$request_url.'", "'.$currentTime.'", "'.$currentTime.'"
                                )
                             ON DUPLICATE KEY UPDATE id = (SELECT Auto_increment FROM information_schema.tables WHERE table_name = "recently_search_images")');
            RecentlySearchImages::whereRaw('(SELECT COUNT(id) FROM (SELECT id FROM recently_search_images WHERE user_id = '. $current_user_id.') as B) > 10')
                                ->whereRaw('id = (SELECT MIN(id) FROM (SELECT id FROM recently_search_images WHERE user_id = '. $current_user_id.') as C)')
                                ->delete();
            return true;
        }

        return Redirect::route('account-sign-in');
    }

    public function loadCategories()
    {
        $categories = $this->layout->categories;

        if( Request::ajax() )
        {
            $html = View::make('frontend.account.view-categories')->with('categories', $categories)->render();

            $arrReturn = ['status' => 'ok', 'message' => '', 'html'=>$html];

            $response = Response::json($arrReturn);
            $response->header('Content-Type', 'application/json');
            return $response;

        }

        return View::make('frontend.account.view-categories')->with('categories', $categories)->render();

    }

    /* Viewing the Create Sign-in form */
    public function getCreateSignin() {

        if( Auth::user()->check())
        {
            if(Auth::user()->get()->active == '1')
            {
                return Redirect::route('profile-user');
            }
        }

        $formSignin = View::make('frontend.account.signin')->render();
        $formCreate = View::make('frontend.account.create')->render();

        $this->layout->content = View::make('frontend.account.create-signin')
                                    ->with(['formSignin'=>$formSignin,
                                    'formCreate'=>$formCreate]);
    }

    /* Viewing the sign-in form */
    public function getSignIn() {

        if( Auth::user()->check())
        {
            if(Auth::user()->get()->active == '1')
            {
                return Redirect::route('profile-user');
            }
            else
            {
                Session::flash('message', 'There is a problem. Have you activated your account?');
            }
        }

        $this->layout->content = View::make('frontend.account.signin');
    }

    /* user sign-out */
    public function getSignOut() {

        if( Auth::user()->check())
        {
            Auth::user()->logout();
            return Redirect::route('home');
        }
    }

    /* After submitting the sign-in form */
    public function postSignIn() {
        $validator = Validator::make(Input::all(),
            array(
                'emailLogin'    => 'required|email',
                'passwordLogin' => 'required'
            )
        );
        if($validator->fails()) {
            // Redirect to the sign in page
            return Redirect::route('account-sign-in')
                ->withErrors($validator)
                ->withInput();   // redirect the input
        } else {
            $remember = Input::has('remember');
            $auth = Auth::user()->attempt(array(
                'email' => Input::get('emailLogin'),
                'password' => Input::get('passwordLogin')
            ), true);
        }

        if($auth) {
            // Redirect to the intented page
            // For example, a user will be redirected to '/
            // when the user tried to change password without login'
            if( Auth::user()->get()->active == '1')
            {
                //return Redirect::route('account-home');
                // If user attempted to access specific URL before logging in
                if ( Session::has('pre_login_url') )
                {
                    $url = Session::get('pre_login_url');
                    Session::forget('pre_login_url');
                    return Redirect::to($url);
                }
                else
                {
                    return Redirect::intended('/account/home');
                }
            }

        } else {
            Session::flash('message', 'Wrong Email or Password.');
            return Redirect::route('account-sign-in');
        }
        Session::flash('message', 'There is a problem. Have you activated your account?');
        return Redirect::route('account-sign-in');
    }

    /* Viewing the form */
    public function getCreate() {

        if((Auth::user()->check()))
        {
            return Redirect::route('profile-user');
        }

        $this->layout->content = View::make('frontend.account.create');

    }

    /* Submitting the form */
    public function postCreate() {
        $validator = Validator::make(Input::all(),
            array(
                'first_name'        => 'required|max:30',
                'last_name'         => 'required|max:30',
                'email'         => 'required|max:40|email|unique:users',
                'password'      => 'required|min:6',
                'password_confirm'=> 'required|same:password'
            )
        );

        if($validator->fails()) {

            return Redirect::route('account-create')
                ->withErrors($validator)
                ->withInput();   // fills the field with the old inputs what were correct

        } else {
            // create an account
            $first_name         = Input::get('first_name');
            $last_name      = Input::get('last_name');
            $email      = Input::get('email');
            $password   = Input::get('password');

            // Activation remember_token
            $remember_token = str_random(60);

            /* This does the same as User::create()
            $user = new User;
            $user->fill(array(
                'email'     => $email,
                'username'  => $username,
                'password'  => Hash::make($password),
                'code'      => $code,
                'active'    => 0,
            ));
            $userdata = $user->save();
            */

            // record
            $userdata = User::create(array(
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'email'     => $email,
                'password'  => Hash::make($password),
                'remember_token'        => $remember_token,
                'active'    => 1
            ));


            if($userdata) {

                // Mail::send('frontend.emails.auth.activate',
                //  array('link' => URL::route('account-activate', $remember_token), 'email' => $email), function($message) use ($userdata) {
                //  $message->to($userdata->email, $userdata->first_name." ".$userdata->last_name)->subject('Activate your account');
                // });
                // Session::flash('message', 'Your account has been created. We have sent you an email to activate your account');
                Session::flash('message', 'Your account has been created.');
                return Redirect::route('home');
            }
        }
    }

    public function getActivate($remember_token) {

        $user = User::where('remember_token', '=', $remember_token)->where('active', '=', 0);

        /* if user is available */
        if($user->count()) {
            $user = $user->first();

            // Update the user status to active
            $user->active = 1;
            $user->remember_token = '';
            if($user->save()) {

                Session::flash('message', 'Activated! You can now sign in!');

                return Redirect::route('home');
                        //->with('global', 'Activated! You can now sign in!');
            }
        }

        /* fall back */
        Session::flash('message', 'We could not activate your account. Try again later.');
        return Redirect::route('home');
    }

    public function getChangePassword() {

        if( Request::ajax() ) {

            if((Auth::user()->check()))
            {
                $html = View::make('frontend.profile.password-change')->render();
                return Response::json(['html'=>$html]);
            }

        }

        return Redirect::route('home');

    }


    public function postChangePassword() {

        if( Request::ajax() )
        {
            $validator = Validator::make(Input::all(),
                    array(
                            'password'      => 'required|min:6',
                            'old_password'  => 'required',
                            'password_confirm'=> 'required|same:password'
                    )
            );

            if($validator->fails())
            {
                return Response::json(['result'=>'failed', 'message'=>'Please fill correct.']);
            }
            else
            {
                // passed validation

                // Grab the current user
                $user           = User::findOrFail(Auth::user()->get()->id);

                // Get passwords from the user's input
                $old_password   = Input::get('old_password');
                $password       = Input::get('password');

                // test input password against the existing one
                if(Hash::check($old_password, $user->getAuthPassword()))
                {

                    $user->password = Hash::make($password);

                    // save the new password
                    if($user->save())
                    {
                        return Response::json(['result'=>'success', 'message'=>'Your password has been changed.']);
                    }
                    else
                    {
                        return Response::json(['result'=>'failed', 'message'=>'Your password could not be changed.']);
                    }
                }
                /* fall back */
                return Response::json(['result'=>'failed', 'message'=>'Your old password is incorrect.']);
            }
        }
        return Redirect::route('home');
    }

    public function loadInform()
    {
        if( Request::ajax() )
        {
            if((Auth::user()->check()))
            {
                $user_obj = User::findOrFail(Auth::user()->get()->id);
                $html = View::make('frontend.profile.inform')->with(['user'=>$user_obj])->render();
                return Response::json(['html'=>$html]);
            }
        }
        return App::abort(404);
    }

    public function edit()
    {
        if( Request::ajax() )
        {
            if((Auth::user()->check()))
            {
                $user_obj = User::findOrFail(Auth::user()->get()->id);
                $html = View::make('frontend.profile.edit')->with(['user'=>$user_obj])->render();
                return Response::json(['html'=>$html]);
            }
        }
        return App::abort(404);
    }

    public function update() {

        if( Request::ajax() && Auth::user()->check() && Request::isMethod('post'))
        {
            $validator = Validator::make(Input::all(),
                    array(
                            'first_name'        => 'required',
                            'last_name' => 'required'
                    )
            );

            if($validator->fails())
            {
                return Response::json(['result'=>'failed', 'message'=>'Please fill all required fields.']);
            }
            else
            {
                // passed validation
                $user           = User::findOrFail(Auth::user()->get()->id);
                $user->first_name = Input::get('first_name');
                $user->last_name = Input::get('last_name');
                $user->description = Input::get('description');
                // save the new password
                if($user->save())
                {
                    $html = View::make('frontend.profile.inform')->with(['user'=>$user])->render();
                    $full_name = $user->first_name.' '.$user->last_name;
                    return Response::json(['result'=>'success',
                                            'message'=>'Your information has been changed.',
                                            'html'=>$html,
                                            'user'=>$user
                                        ]);
                }
                else
                {
                    return Response::json(['result'=>'failed', 'message'=>'Your information could not be changed. Please try at another time.']);
                }
            }
        }
        return Redirect::route('home');
    }

    public function forgot()
    {
        if (Request::isMethod('get')) {
            $this->layout->content = View::make('frontend.account.forgot');
        } elseif (Request::isMethod('post')) {
            $email = Input::get('email');
            $validation = Validator::make(['email' => $email], [
                            'email' => 'required|email'
                        ]);
            if ($validation->fails()) {
                $arrData = ['emailNotFound' => true];
            } else {
                $user = User::select('first_name', 'last_name')->where('email', $email)->first();
                if ($user) {
                    $token = Hash::make(md5($email.time()));
                    DB::table('password_reminders')->insert([
                            'email' => $email,
                            'token' => $token,
                            'type'  => 'User.Reset Password',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    Mail::send('emails.auth.reminder', [
                            'token' => $token,
                            'user' => $user
                        ], function ($message) use ($email, $user) {
                            $message->to($email, $user->first_name.' '.$user->last_name)
                                    ->subject('Resetting your password at ImageStock');
                        });
                    $arrData = ['resetPassword' => true];

                }
            }
            return Redirect::to(URL.'/account/forgot')->with($arrData);
        } else {
            return App::abort(404);
        }
    }

    public function reset()
    {
        if (!Input::has('token')) {
            return App::abort(404);
        }
        $token = Input::get('token');
        $reminder = DB::table('password_reminders')
                ->where('token', $token)
                ->first();
        if (!$reminder) {
            return App::abort(404);
        }
        if (Request::isMethod('get')) {
            $this->layout->content = View::make('frontend.account.reset')->with(['token' => $token]);
        } else {
            $password = Input::get('password');
            $password_confirm = Input::get('password_confirm');
            $validation = Validator::make([
                                            'password' => $password,
                                            'password_confirmation' => $password_confirm,
                                        ], [
                                            'password' => 'required|min:6|confirmed',
                                            'password_confirmation' => 'required|min:6',
                                        ]);
            if ($validation->fails()) {
                return Redirect::to(URL.'/account/reset?token='.$token)
                                ->with([
                                        'resetErrors' => $validation->messages()->all(),
                                        'token'  => $token
                                    ]);
            }
            $password = Hash::make($password);
            $user = User::where('email', $reminder->email)
                    ->update([
                        'password' => $password
                        ]);
            DB::table('password_reminders')
                ->where('token', $token)
                ->where('email', $reminder->email)
                ->delete();
            return Redirect::to(URL);
        }
    }
}

