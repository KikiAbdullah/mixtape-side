<?php

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

function st_aktif($var)
{
    if (empty($var)) {
        return "Enabled";
    } else {
        return "Disabled";
    }
}

function st_aktif_badge($var)
{
    if (empty($var)) {
        return '<center><span class="badge bg-primary">Enabled</span></center>';
    } else {
        return '<center><span class="badge bg-light text-body">Disabled</span></center>';
    }
}

function is_closed($var)
{
    if ($var == 1) {
        return "Closed";
    } else {
        return "Enable";
    }
}

function is_closed_badge($var)
{
    if ($var == 1) {
        return '<center><span class="badge bg-light text-body">Closed</span></center>';
    } else {
        return '<center><span class="badge bg-primary">Enable</span></center>';
    }
}


function cleanNumber($val)
{
    return  str_replace('.00', '', number_format($val, 2, '.', ','));
}

if (!function_exists('responseSuccess')) {

    function responseSuccess($data = [], $message = 'Data saved.')
    {
        return [
            'status'            => true,
            'msg'               => $message,
            'data'              => $data,
        ];
    }
}

if (!function_exists('responseFailed')) {

    function responseFailed($message = 'Gagal')
    {
        return [
            'status'            => false,
            'msg'               => $message,
            'data'              => [],
        ];
    }
}

if (!function_exists('formatDate')) {

    function formatDate($from, $to, $date)
    {
        if (!empty($date)) {
            return Carbon::createFromFormat($from, $date)->format($to);
        }
    }
}

if (!function_exists('roleName')) {

    function roleName()
    {
        return auth()->user()->roles->first()->name ?? null;
    }
}

if (!function_exists('kirimWA')) {

    function kirimWA($nohp, $subject, $teks, $tekstengah = "")
    {
        return (new \App\Helpers\KirimWAHelper)->kirim($nohp, $subject, $teks, $tekstengah);
    }
}

if (!function_exists('storeLog')) {

    function storeLog($type, $nomor, $menu)
    {
        return (new \App\Helpers\LogHelper)->storeLog($type, $nomor, $menu);
    }
}

if (!function_exists('uploadImage')) {

    /**
     * Helper Upload & Resize Gambar
     * 
     * @param $file (file dari request)
     * @param $path (relative path dari storage/app/public/)
     * @param $width (lebar resize, default 800)
     */
    function uploadImage($file, $path, $width = 800)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = storage_path('app/public/' . $path);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save($destinationPath . '/' . $filename, 80); // Quality 80%

        return $filename;
    }
}
