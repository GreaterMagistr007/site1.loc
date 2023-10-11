<?php

namespace core\modules\repositories;

final class SiteRepository extends AbstractRepository
{
    protected $settings;

    public function __construct(string $driver)
    {
        parent::__construct($driver);
        $this->loadSettings();
    }

    private function loadSettings()
    {
        $this->settings = $this->readDataFile('site');
    }

    public function __get(string $name): string
    {
        return isset($this->settings->$name) ? $this->settings->$name : '';
    }

    public function __set(string $name, $value): void
    {
        $this->settings->$name = $value;
        $this->save();
    }

    public function save()
    {
        $this->saveToFile('site', $this->settings);
    }




}