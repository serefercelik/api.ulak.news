<?php
/**
 * Created by PhpStorm.
 * User: yildizozan
 * Date: 11.12.2018
 * Time: 17:18
 */

namespace UlakNews;

interface IStorage
{
    public function getError();

    public function upload($key, $file);

    public function download($key, $destination);
}