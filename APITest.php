<?php
require 'vendor/autoload.php'; // include Composer's autoloader

$api = $argv[1];

$appUrl = "https://flipprime.video365.com";
$appId = "5fa4f1ef-7e52-42c5-bd3b-af0386003229";
$appKey = "X0hJOzaqRL1S5BZ/oUOIIEM3XMkpkAhBS+qpgeFybHc=";

$header = array("AppId:$appId", "ApiKey:$appKey", "content-type:application/json");
$data = array("pageSize"=>20, "PageNumber"=>1);

switch ($api) {

case "MediaList":
case "MediaDetails":	
	$response = getResponse("/api/v2/Medialist", $header, $data);
	print_r($response);

	$mediaId = $response['ApiData'][0]['MediaId'];
	$response = getResponse("/api/v1/MediaDetails/$mediaId", $header);
	print_r($response);
	break;

case "PlayerList":
case "PlayerDetails":
	$response = getResponse("/api/v1/PlayerList", $header, $data);
	print_r($response);

	$playerId = $response['ApiData']['ApiData'][1]['Id'];
	$response = getResponse("/api/v1/PlayerDetails/$playerId", $header, $data);
	break;

case "Upload":
	$data = array('BulkVideoList'=>array(0=>
			array(
				//'VideoUrl'=>'https://b2ccontents.s3-ap-south-1.amazonaws.com/EOL/Contents/2014050700180710/output.mp4',
				//'Name'=>'output.mp4',
				'VideoUrl'=>'https://b2ccontents.s3-ap-south-1.amazonaws.com/EOL/Contents/2014050700180710/output.mp4',
				'Name'=>'vishal.mp4',
				'FileHierarchy'=>'tmpfileuploads-dc5bc651-66cd-4f63-969a-6ac360673d2b//',
				'FileSize'=>''
				)
			));
	$response = getResponse("/api/v2/BulkUpload", $header, $data, "POST");
	print_r($response); //*! generate ["ApiData"][0]["RequestId"] => 50
	break;

case "UploadStatus":
	$data = array("RequestId"=>50);
	$response = getResponse("/api/v2/Status", $header, $data);	
	print_r($response); //*! generate ["ApiData"]["mediaData"]["Id"] => 18
	break;

case "AesProtection":
	$data = array('MediaDetails'=>array(0=>
			array('MediaID'=>18,
			      'EnableAes'=> true)
		      ));
	$response = getResponse("/api/v2/AesProtection", $header, $data, "POST");
	print_r($response);
	break;

case "AesPublish":
	$data = array('AesPublishDetails'=>array(0=>
			array('MediaID'=>18,
			      'EnableAes'=> true)
		      ));
	$response = getResponse("/api/v2/AesPublish", $header, $data, "POST");
	print_r($response);
	break;

case "OnlineContentProtection":
	$data = array('DRMDetails'=>array(0=>
			array('MediaID'=>18,
			      'EnableDrm'=>true)
		      ));	
	$response = getResponse("/api/v2/OnlineContentProtection", $header, $data, "POST");
	print_r($response);
	break;

case "Unpublish":	
	$data = array('UnpublishDetails'=>array(0=>
		array('MediaID'=>18
		)
	));
	$response = getResponse("api/v2/Unpublish", $header, $data, "POST");
	print_r($response);

}

function getResponse($url, $header = array(), $data = array(), $method = "GET", $appUrl = "https://flipprime.video365.com") {
	$pest = new PestJSON($appUrl);
	if ($method == "GET") {
		$method = "get";
	} else {
		$method = "post";
	}
	$response = $pest->$method($url, $data, $header);
	return $response;
}
