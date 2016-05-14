<?php
/**
 * Project: telegram
 *
 * File: Photo.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 13/05/2016
 * Time: 12:51
 */

namespace Gr77\Telegram\Message\Content;


use Gr77\Telegram\BaseObject;

class InputFile
{
    private $file_name;
    private $resource;

    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    public function getResource()
    {
        if (!isset($this->resource)) {
            $this->resource = fopen($this->file_name, 'r');
        }
        return $this->resource;
    }

    public function getContent()
    {
        return file_get_contents($this->file_name);
    }
}