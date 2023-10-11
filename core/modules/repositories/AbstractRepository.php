<?php

namespace core\modules\repositories;

abstract class AbstractRepository
{
    public string $driver;
    const FILE_EXTENSION = 'json';

    public function __construct(string $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Считываем файл .json из директории data и возвращаем значения
     * @param $fileTitle
     * @param $toArray
     * @return mixed
     */
    public function readDataFile($fileTitle, $toArray = false)
    {
        $file = data_dir($fileTitle . '.' . self::FILE_EXTENSION);
        if (!file_exists($file)) {
            dd('Не удалось прочитать файл ' . $file);
        }

        return json_decode(file_get_contents($file), $toArray);
    }

    public function saveToFile(string $fileTitle, object $data)
    {
        $file = data_dir($fileTitle . '.' . self::FILE_EXTENSION);
        file_put_contents($file, json_encode($this->settings));
    }

}