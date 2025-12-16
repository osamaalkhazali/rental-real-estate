<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PrivateFileController extends Controller
{
  /**
   * Serve a private file to authenticated users.
   */
  public function show(Request $request, string $path): Response
  {
    $disk = Storage::disk('private');

    if (!$disk->exists($path)) {
      abort(404, 'File not found.');
    }

    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    return $disk->download($path);
  }

  /**
   * Stream a private file inline (for images).
   */
  public function stream(Request $request, string $path): Response
  {
    $disk = Storage::disk('private');

    if (!$disk->exists($path)) {
      abort(404, 'File not found.');
    }

    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $mimeType = $disk->mimeType($path);

    return response()->stream(
      fn() => fpassthru($disk->readStream($path)),
      200,
      [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
      ]
    );
  }
}
