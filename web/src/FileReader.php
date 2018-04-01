<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

namespace App;


use App\Exceptions\DieException;
use Exception;

class FileReader
{
    const WEBROOT_PATH = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;

    /**
     * @param   string $filename
     * @return  array
     * @throws  DieException
     */
    static function read_file( string $filename ) : array
    {
        self::check_filename( $filename );

        # Set file absolute webroot path
        $file_path = self::WEBROOT_PATH . $filename;

        $file = self::get_file_body( $file_path, $filename );

        self::check_file_body( $file );

        return $file;
    }

    /**
     * @param   string $filename
     * @throws  DieException
     */
    private static function check_filename( string $filename )
    {
        if ( empty( $filename ) ) {
            throw new DieException( "Enter file name" );
        }
    }

    /**
     * @param   string $file_path
     * @param   string $filename
     * @return  array
     * @throws  DieException
     */
    private static function get_file_body( string $file_path)
    {
        try {
            $file = file( $file_path, FILE_IGNORE_NEW_LINES );

            if ( $file === false ) {
                throw new DieException( "Can't read file $file_path" );
            }

            # Clean file from empty strings
            return array_filter( $file );
        } catch (Exception $e) {
            throw new DieException("Can't read file $file_path");
        }
    }

    /**
     * @param   array $file
     * @throws  DieException
     */
    private static function check_file_body( array $file )
    {
        if ( count( $file ) == 0 ) {
            throw new DieException( "File is empty" );
        }
    }
}