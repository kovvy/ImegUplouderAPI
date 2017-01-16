## ImageUploader API

ImageUploader is a simple API that can be used for upload and resize images. 
Application based on Laravel 5.0 with mongoDB database. 

## Get started using composer

```

composer install
```


After you must start migration


```

php artisan migrate
```


## API Documentation

Base rout of application: /api/v{version_of_api}/{method}

## POST /api/v1/signup -- method for register user by user_id and recieve token 

You need to send user_id in body of request. 
In response you will recieve json with status message and token like this:

```

{
  "status": "success",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"
}
```


This token you have to save and send it with another requests

## POST /api/v1/resize-image -- route for upload and resize new image

You need to send token in header "Authorization". For example: "Authorization" : "Bearer {your_token}"

Also you have to attach image(gif, jpeg or png) and send size parameters in pixels: (int)width and (int)height.

In response you will see link for resized image and new parameters of width and height.

Example of response:

```

{
  "status": "success",
  "data": {
    "link": "http://localhost/storage/img/resized/100_100_3d8f4f290d5f7e49822f2a38d7aa9e76_56ec0b59ddfccda40400003c.png",
    "width": 100,
    "height": 100
  }
}
```


## GET /api/v1/images -- route for getting list of user's earlier resized images

You need to send token in header "Authorization". For example: "Authorization" : "Bearer {your_token}"

In response you will see list of your original and resized images with sizes, links and id's

Example of respone:

```

{
  "status": "success",
  "images": {
    "75169b2ddd91f8eff85906b3cf9afabc.jpg": {
      "link": "http://localhost/storage/img/original/75169b2ddd91f8eff85906b3cf9afabc.jpg",
      "width": 2592,
      "height": 1944,
      "resized": {
        "100_100_75169b2ddd91f8eff85906b3cf9afabc.jpg": {
          "link": "http://localhost/storage/img/resized/100_100_75169b2ddd91f8eff85906b3cf9afabc.jpg",
          "width": 100,
          "height": 100
        }
      }
    }
 }
}
```


## PATCH /api/v1/resize-image/{image_id} -- route for resize earlier uploaded image

You need to send token in header "Authorization". For example: "Authorization" : "Bearer {your_token}"

You need to put in query string image_id of image that you want to resize.

Also you have to send new size parameters in pixels: (int)width and (int)height;

In response you will see link for resized image and new parameters of width and height.


```

{
  "status": "success",
  "data": {
    "link": "http://localhost/storage/img/resized/100_100_3d8f4f290d5f7e49822f2a38d7aa9e76_56ec0b59ddfccda40400003c.png",
    "width": 100,
    "height": 100
  }
}
```
