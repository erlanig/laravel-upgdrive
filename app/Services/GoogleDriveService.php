<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    protected $drive;
    protected $folderId;

    public function __construct()
    {
        $client = new Client();

        $client->setClientId(
            env('GOOGLE_CLIENT_ID')
        );

        $client->setClientSecret(
            env('GOOGLE_CLIENT_SECRET')
        );

        $client->refreshToken(
            env('GOOGLE_REFRESH_TOKEN')
        );

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken(
                env('GOOGLE_REFRESH_TOKEN')
            );
        }

        $this->drive = new Drive($client);

        $this->folderId = env('GOOGLE_DRIVE_FOLDER_ID');
    }

    public function upload($file)
    {
        $fileName = uniqid().'_'.$file->getClientOriginalName();

        $driveFile = new DriveFile([
            'name' => $fileName,
            'parents' => [$this->folderId]
        ]);

        $createdFile = $this->drive->files->create(
            $driveFile,
            [
                'data' => file_get_contents($file->getRealPath()),
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]
        );

        $fileId = $createdFile->id;

        $permission = new Permission([
            'type' => 'anyone',
            'role' => 'reader'
        ]);

        $this->drive->permissions->create(
            $fileId,
            $permission,
            [
                'sendNotificationEmail' => false
            ]
        );

        return [
            'id' => $fileId,
            'name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'download_url' => "https://drive.google.com/uc?id={$fileId}",
            'view_url' => "https://drive.google.com/file/d/{$fileId}/view"
        ];
    }
}