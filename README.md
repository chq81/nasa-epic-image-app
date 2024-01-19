# nasa-epic-image-app

The *nasa-epic-image-app* provides the possibility to download NASA images from the NASA EPIC (Earth Polychromatic Imaging Camera) API.

### Setup
* Please clone the repository in a local folder
* Make sure you have an API Key from NASA. You are able to retrieve one here: https://api.nasa.gov/index.html#signUp
* Run `composer install`. 
* Copy the provided .env.dev.local.dist to .env.dev.local and set the API Key.

### Run
To download images, just run
```
nasa:epic:download-images [options] [--] <image-folder> [<date>]
```
* The image folder is the folder the images are stored in.
* The date is the date the images were taken. If empty, the images of the last available day are retrieved.
* Optionally, you can provide two more options:
  * imagery type: Possible values are natural, enhanced, aerosol or cloud. The standard is *natural*.
  * image format: Possible values are jpg or png. The standard is *png*.
