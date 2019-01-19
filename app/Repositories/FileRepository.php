<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Repositories;


use App\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileRepository
{
    public function upload(UploadedFile $uploadedFile, $ext_object)
    {
        $name = str_random(32);
        //$uploadedFile->move($_SERVER['DOCUMENT_ROOT'].'/public/upload', $name);

        if(!($path = Storage::putFileAs('upload',$uploadedFile, $name))) throw new UploadException();

        $orig_name = $uploadedFile->getClientOriginalName();

        $db_file = new File();
        $db_file->name = $name;
        $db_file->orig_name = $orig_name;
        $db_file->ext_id = $ext_object->id;
        $db_file->ext_type = get_class($ext_object);
        $db_file->save();

        return $db_file;
    }

    public function getByName($name)
    {
        /**
         * @var File $file
         */
        $file = File::where('name', $name)->first();
        if(!$file) throw new NotFoundHttpException();

        return $file;
    }

    public function delete($id)
    {
        $file = File::find($id);

        Storage::delete('upload/'.$file->name);

        File::destroy($id);
    }

    public function download($name)
    {
        $file = $this->getByName($name);

        $exp = explode('.',$file->orig_name);

        $n = reset($exp);
        $ext = end($exp);

        return Storage::download('upload/'.$file->name, Str::slug($n).'.'.$ext);

        //static::forceDownload($file->orig_name, $content);
    }

    /**
	 * Force Download
	 *
	 * Generates headers that force a download to happen
	 *
	 * @param	mixed	filename (or an array of local file path => destination filename)
	 * @param	mixed	the data to be downloaded
	 * @param	bool	whether to try and send the actual file MIME type
	 * @return	void
	 */
	public static function forceDownload($filename = '', $data = '', $set_mime = FALSE)
	{
		if ($filename === '' OR $data === '')
		{
			return;
		}
		elseif ($data === NULL)
		{
			// Is $filename an array as ['local source path' => 'destination filename']?
			if (is_array($filename))
			{
				if (count($filename) !== 1)
				{
					return;
				}
				reset($filename);
				$filepath = key($filename);
				$filename = current($filename);
				if (is_int($filepath))
				{
					return;
				}
			}
			else
			{
				$filepath = $filename;
				$filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
				$filename = end($filename);
			}
			if ( ! @is_file($filepath) OR ($filesize = @filesize($filepath)) === FALSE)
			{
				return;
			}
		}
		else
		{
			$filesize = strlen($data);
		}
		// Set the default MIME type to send
		$mime = 'application/octet-stream';
		$x = explode('.', $filename);
		$extension = end($x);
		if ($set_mime === TRUE)
		{
			if (count($x) === 1 OR $extension === '')
			{
				/* If we're going to detect the MIME type,
				 * we'll need a file extension.
				 */
				return;
			}
			// Load the mime types
			$mimes =& get_mimes();
			// Only change the default MIME if we can find one
			if (isset($mimes[$extension]))
			{
				$mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
			}
		}
		/* It was reported that browsers on Android 2.1 (and possibly older as well)
		 * need to have the filename extension upper-cased in order to be able to
		 * download it.
		 *
		 * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
		 */
		if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT']))
		{
			$x[count($x) - 1] = strtoupper($extension);
			$filename = implode('.', $x);
		}
		// Clean output buffer
		if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
		{
			@ob_clean();
		}
		// RFC 6266 allows for multibyte filenames, but only in UTF-8,
		// so we have to make it conditional ...
		$charset = strtoupper('UTF-8');
		$utf8_filename = ($charset !== 'UTF-8')
			? get_instance()->utf8->convert_to_utf8($filename, $charset)
			: $filename;
		isset($utf8_filename[0]) && $utf8_filename = " filename*=UTF-8''".rawurlencode($utf8_filename);
		// Generate the server headers
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.$filename.'";'.$utf8_filename);
		header('Expires: 0');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.$filesize);
		header('Cache-Control: private, no-transform, no-store, must-revalidate');
		// If we have raw data - just dump it
		if ($data !== NULL)
		{
			exit($data);
		}
		// Flush the file
		if (@readfile($filepath) === FALSE)
		{
			return;
		}
		exit;
	}
}