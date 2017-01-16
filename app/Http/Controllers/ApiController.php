<?php namespace App\Http\Controllers;

use App\Services\Api\Errors;
use Auth;
use Illuminate\Support\Facades\Request;
use \App\Services\Api\Errors as ApiErrors;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller {

    protected $_defaultApiVersion = 1;

    /**
     * @var $_apiService \App\Services\Api\AbstractApi | \App\Services\Api\Version1 | \App\Services\Api\Version2
     */
    protected $_apiService;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $apiClass = '\App\Services\Api\Version' . $this->_defaultApiVersion;

        if(array_key_exists('api_version', \Route::current()->parameters()))
        {
            $apiVersion = \Route::current()->parameters()['api_version'];
            if(class_exists('\App\Services\Api\Version' . $apiVersion))
            {
                $apiClass = '\App\Services\Api\Version' . $apiVersion;
            }
        }

        $this->_apiService = new $apiClass;
    }

    /**
     * Get token by user_id
     *
     * @param user_id
     *
     * @return mixed
     */
    public function signUp()
    {
        if(!Request::input('user_id')|| empty(Request::input('user_id'))) {
            $apiError = Errors::getApiError(Errors::API_ERROR_USER_ID_IS_REQUIRED);
            return response()->json($apiError['data'], $apiError['code']);
        }

        $checkUser = User::find(Request::input('user_id'));
        if($checkUser instanceof User) {
            $apiError = ApiErrors::getApiError(ApiErrors::API_ERROR_USER_ALREADY_EXISTS);
            return response()->json($apiError['data'], $apiError['code']);
        }

        $credentials['user_id'] = Request::input('user_id');
        $user = User::create($credentials);

        $token = JWTAuth::fromUser($user);
        $data = ['status' => 'success', 'token' => $token];
        return response()->json($data);
    }

    /**
     * Get list of uploaded and resized images from current user
     *
     * @return json
     */
    public function getListOfImages()
    {
        /** @var User $user */
        $user = Auth::user();
        $images = $this->_apiService->getListOfImagesByUser($user);
        $data = ['status' => 'success', 'images' => $images];

        return response()->json($data);
    }

    /**
     * Upload and resize new image
     * @file image
     * @param width
     * @param height
     * @return mixed
     */
    public function resizeNewImage()
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = Request::file('image');

        $newWidth = (int)Request::input('width');
        $newHeight = (int)Request::input('height');

        if(!$this->_apiService->checkMimeType($file))
        {
            $apiError = ApiErrors::getApiError(ApiErrors::API_ERROR_USER_SENT_BAD_FILE);
            return response()->json($apiError['data'], $apiError['code']);
        }

        $data = $this->_apiService->resizeNewImageWithData($file, $user, $newWidth, $newHeight);

        return response()->json($data);
    }

    /**
     * Resize already uploaded image by image_id
     *
     * @param image_id
     * @param width
     * @param height
     * @return mixed
     */
    public function resizeOldImage()
    {
        /** @var User $user */
        $user = Auth::user();

        $newWidth = (int)Request::input('width');
        $newHeight = (int)Request::input('height');
        $imageId = \Route::current()->parameters()['image_id'];
        $imageModel = \App\Models\Image::find($imageId);

        if(!$imageModel instanceof \App\Models\Image) {
            $apiError = ApiErrors::getApiError(ApiErrors::API_ERROR_IMAGE_NOT_FOUND);
            return response()->json($apiError['data'], $apiError['code']);
        }
        
        if($imageModel->user_id != $user->user_id) {
            $apiError = ApiErrors::getApiError(ApiErrors::API_ERROR_IMAGE_DOESNT_BELONG_TO_CURRENT_USER);
            return response()->json($apiError['data'], $apiError['code']);
        }

        $data = $this->_apiService->resizeOldImageWithData($imageModel, $user, $newWidth, $newHeight);

        return response()->json($data);
    }
}