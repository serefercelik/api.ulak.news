<?php

/**
 * Created by PhpStorm.
 * User: Ozan Yıldız
 * Date: 09.12.2018
 * Time: 18:36
 */

namespace UlakNews;

require_once "env.php";

require_once 'IStorage.php';

// Require the Composer autoloader.
require 'vendor/autoload.php';

use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;

/**
 * Class S3
 * @author Ozan Yıldız
 * @copyright
 * @version 0.0.1
 */
class S3 implements IStorage {

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var string
     */
    private $error;

    /**
     * S3 constructor.
     * @param $bucket
     */
    public function __construct($bucket)
    {
        // Set bucket
        $this->bucket = $bucket;

        // Use the default credential provider
        $provider = CredentialProvider::env();
            /**
             * for local
             */
            $this->client = new S3Client([
                'region'      => 'eu-west-3',
                'version'     => 'latest',
                'credentials' => [
                    'key'    => $_ENV['IAM_USER_KEY'],
                    'secret' => $_ENV['IAM_USER_SECRET'],
                ],
            ]);
    }

    /**
     *
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @param $key string
     * @param $file string
     * @return bool
     */
    public function upload($key, $file, $remote=false)
    {

        // Upload a publicly accessible file. The file size and type are determined by the SDK.
        
        $file = file_get_contents($file);
        if(!$file){
            return array("result"=>array("ObjectURL"=> "https://images.ulak.news/images/web/node-image.webp"));
        }

        try {
            if($remote){
                $result = $this->client->putObject([
                    'Bucket' => $this->bucket,
                    'Key'    => $key,
                    'Body'   => $file
                ]);
            }else{
                $result = $this->client->putObject([
                    'Bucket' => $this->bucket,
                    'Key'    => $key,
                    'Body'   => fopen($file, 'r')
                ]);
            }

        } catch (Aws\S3\Exception\S3Exception $e) {
            $this->error = $e->getMessage() . "\n";
            return false;
        }

        return array("status"=>true, "result"=>$result);
    }

    /**
     * @param $key
     * @param $destination
     * @return bool
     */
    public function download($key, $destination)
    {
        try {
            // Save object to a file.
            $result = $this->client->getObject(array(
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SaveAs' => $destination
            ));
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
            $this->error = $e->getMessage() . "\n";
            return array("status"=>false, "result"=>"");
        }
        return array("status"=>true, "result"=>$result);
    }


    /**
     * @param $key
     * @return array
     */
    public function read($key){

        try {
            //get objext
            $result = $this->client->getObject(
                array(
                    'Bucket' => $this->bucket,
                    'Key' => $key,
                )
            );
            // Cast as a string to result object
            return array("status"=>true, "result"=>$result['Body']);
        } catch(S3Exception $e) {
            echo $e->getMessage() . "\n";
            $this->error = $e->getMessage() . "\n";
            return array("status"=>false);
        }
        
    }

    /**
     * @param $key string
     */
    public function delete($key)
    {
        $this->client->deleteObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ));
    }

    /**
     * @param $key
     * @param $private
     */
    public function acl($key, $private)
    {
        $this->client->deleteObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'ACL'    => $private ? 'private' : 'public-read'
        ));
    }

}