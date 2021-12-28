<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\File;

class FileController extends Controller
{
	public function image(Request $request)
	{
		$filename = $request->input('filename');
		$directory = $request->input('directory') ?? 'temporary';
		$filepath = \Storage::path("{$directory}/{$filename}");
		$file = new File($filepath);
		$image = \Image::make($filepath);

		if ($file->getMimeType() == 'image/tiff')
		{
			return $image->response('jpeg', 80);
		}

		return $image->response();
	}

	public function upload(Request $request)
	{
		$file = $request->file('file');
		$filename = $file->getClientOriginalName();
		$path = \Storage::path("temporary/{$filename}");

		\File::append($path, $file->get());

		if ($request->has('is_last') && $request->boolean('is_last'))
		{
			return \Response::json([
				'done'	=> true,
				// 'url'	=> asset("storage/temporary/{$filename}")
				'url'	=> route('file::image', [ 'filename' => $filename ])
			]);
		}

		return \Response::json([
			'done' => false
		]);
	}

	public function delete(Request $request)
	{
		$file = $request->input('file');

		\Storage::delete($file);

		return \Response::json([
			'deleted' => true
		]);
	}

	public function download(Request $request, $file, $directory = 'uploads', $name = '')
	{
		$filepath = "{$directory}/{$file}";

		if (! \Storage::exists($filepath))
		{
			return \Response::noContent();
		}

		$extension = (new File(\Storage::path($filepath)))->getExtension();

		if (strlen($name) == 0)
		{
			$name = $file;
		}
		else
		{
			$name = "{$name}.{$extension}";
		}

		return \Storage::download($filepath, $name);
	}
}
