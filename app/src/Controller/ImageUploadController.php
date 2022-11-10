<?php

namespace Doggo\Controller;

use Doggo\Model\Park;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Upload;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;

class ImageUploadController extends Controller
{

    private static $allowed_actions = [
        'index',
    ];

    public function index(HTTPRequest $request)
    {
        $upload = new Upload();
        $upload->loadIntoFile($_FILES['file']);
        $id=$request->postVar('pid');

        $parkObject = Park::get_by_id($id);
        // print_r($parkObject);
        // exit;
        $parkObject->update([
            'ApprovePhoto'=>false,
            'dogPhotoID'=>$upload->getFile()->getField("ID")
        ]);
        $parkObject->write();
        
       // print_r();
    }
}
