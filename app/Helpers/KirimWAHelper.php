<?php 

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class KirimWAHelper
{
	public function kirim($nohp, $subject, $teks, $tekstengah)
	{
		try{
			$client = new \GuzzleHttp\Client();
			$endpoint = env('APP_WHATSAPP_API');

			$tujuan = str_replace('-','',$nohp);
			$tujuan = str_replace(' ','',$tujuan);
			$tujuan = str_replace('+','',$tujuan);
			$temp = substr($tujuan,0,1);
			if($temp=="0"){
				$tujuan="62".substr($tujuan,1);
			}
			if(env("APP_ENV")=="local"){
				$tujuan = "6282132922001";
			}
			
			$response = $client->request('GET', $endpoint, ['query' => [
				'number' => $tujuan, 
				'subject' => $subject,
				'text' => $teks,
				'text_tengah' => $tekstengah,
				'session_id' => env('APP_WHATSAPP_API_NAME'),
				'session_key' => env('APP_WHATSAPP_API_KEY'),
			]]);
			// return $response->getStatusCode();
			return true;
		}catch(RequestException $e){
			return false;
		}
	}

	public function kirimWithFooter($nohp, $subject, $teks, $tekstengah, $teksfooter)
	{
		$client = new \GuzzleHttp\Client();
		$endpoint = env('APP_WHATSAPP_API');

		$tujuan = str_replace('-','',$nohp);
		$tujuan = str_replace(' ','',$tujuan);
		$tujuan = str_replace('+','',$tujuan);
		$temp = substr($tujuan,0,1);
		if($temp=="0"){
			$tujuan="62".substr($tujuan,1);
		}
		if(env("APP_ENV")=="local"){
			$tujuan = "6282132922001";
		}
		
		$response = $client->request('GET', $endpoint, ['query' => [
			'number' => $tujuan, 
			'subject' => $subject,
			'text' => $teks,
			'text_tengah' => $tekstengah,
		 	'text_footer' => $teksfooter,
			'session_id' => env('APP_WHATSAPP_API_NAME'),
			'session_key' => env('APP_WHATSAPP_API_KEY'),
		]]);
		return $response->getStatusCode();
	}

	public function kirimAttachment($nohp, $subject, $teks, $tekstengah, $fileurl)
	{
		try{
			$client = new \GuzzleHttp\Client();
			$endpoint = env('APP_WHATSAPP_API');

			$tujuan = str_replace('-','',$nohp);
			$tujuan = str_replace(' ','',$tujuan);
			$tujuan = str_replace('+','',$tujuan);
			$temp = substr($tujuan,0,1);
			if($temp=="0"){
				$tujuan="62".substr($tujuan,1);
			}
			$response = $client->request('GET', $endpoint, ['query' => [
				'number' => $tujuan, 
				'subject' => $subject,
				'text' => $teks,
				'text_tengah' => $tekstengah,
				'session_id' => env('APP_WHATSAPP_API_NAME'),
				'session_key' => env('APP_WHATSAPP_API_KEY'),
				'file_url' => $fileurl,
				'media' => "isTrue"
			]]);
			// return $response->getStatusCode();
			return true;
		}catch(RequestException $e){
			return false;
		}
	}
}