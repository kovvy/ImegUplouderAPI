<?php namespace App\Services\Api;

use Illuminate\Http\Response;

class Errors {

    const API_ERROR_USER_SENT_BAD_FILE = 1;
    const API_ERROR_USER_SENT_BAD_HEIGHT_WIDTH = 2;
    const API_ERROR_USER_SENT_ALREADY_UPLOADED_IMAGE = 3;
    const API_ERROR_USER_IS_NOT_AUTHENTICATED = 4;
    const API_ERROR_IMAGE_DOESNT_BELONG_TO_CURRENT_USER = 5;
    const API_ERROR_IMAGE_NOT_FOUND = 6;
    const API_ERROR_USER_ALREADY_EXISTS = 7;
    const API_ERROR_USER_ID_IS_REQUIRED = 8;
    const API_ERROR_INVALID_TOKEN = 9;

    static $apiErrorMessages = [
        self::API_ERROR_USER_SENT_BAD_FILE => [
            'data' => [
                'status'    => 'error',
                'message'   => 'You can upload only images. Allowed formats gif, jpeg, png.'
            ],
            'code'      => Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        ],
        self::API_ERROR_USER_SENT_BAD_HEIGHT_WIDTH => [
            'data' => [
                'status'    => 'error',
                'message'   => 'You must send height and width in numeric format'
            ],
            'code'      => Response::HTTP_BAD_REQUEST
        ],
        self::API_ERROR_USER_SENT_ALREADY_UPLOADED_IMAGE => [
            'data' => [
                'status'    => 'error',
                'message'   => 'You have already upload this image'
            ],
            'code'      => Response::HTTP_CONFLICT
        ],
        self::API_ERROR_USER_IS_NOT_AUTHENTICATED => [
            'data' => [
                'status'    => 'error',
                'message'   => 'You must authenticate with token'
            ],
            'code'      => Response::HTTP_UNAUTHORIZED
        ],
        self::API_ERROR_IMAGE_DOESNT_BELONG_TO_CURRENT_USER => [
            'data' => [
                'status'    => 'error',
                'message'   => "It's not your image"
            ],
            'code'      => Response::HTTP_FORBIDDEN
        ],
        self::API_ERROR_IMAGE_NOT_FOUND => [
            'data' => [
                'status'    => 'error',
                'message'   => "Image not found"
            ],
            'code'      => Response::HTTP_NOT_FOUND
        ],
        self::API_ERROR_USER_ALREADY_EXISTS => [
            'data' => [
                'status'    => 'error',
                'message'   => "User already exists"
            ],
            'code'      => Response::HTTP_CONFLICT
        ],
        self::API_ERROR_USER_ID_IS_REQUIRED => [
            'data' => [
                'status'    => 'error',
                'message'   => "Parameter user_id is required"
            ],
            'code'      => Response::HTTP_BAD_REQUEST
        ],
        self::API_ERROR_INVALID_TOKEN => [
            'data' => [

            ],
            'code'      => Response::HTTP_UNAUTHORIZED
        ]

    ];

    /**
     * @param $apiErrorType
     * @return mixed
     */
    static function getApiError($apiErrorType)
    {
        if(array_key_exists($apiErrorType, self::$apiErrorMessages)) {
            return self::$apiErrorMessages[$apiErrorType];
        }
    }
}