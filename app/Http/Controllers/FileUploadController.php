<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(
    Request $request,
    GoogleDriveService $drive
    )
    {
        try {

            if (
                $request->header('X-API-KEY')
                !== env('UPLOAD_API_KEY')
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $request->validate([
                'file' => [
                    'required',
                    'file',
                    'max:20480'
                ]
            ]);

            $result = $drive->upload(
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Upload berhasil',
                'data' => [
                    'file_id' => $result['id'],
                    'filename' => $request->file('file')->getClientOriginalName(),
                    'download_url' => $result['download_url'],
                    'view_url' => $result['view_url'],
                    'uploaded_at' => now()
                                    ->setTimezone('Asia/Jakarta')
                                    ->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Throwable $e) {

            Log::error('Google Drive Upload Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}