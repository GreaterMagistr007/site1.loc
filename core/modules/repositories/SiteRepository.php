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

    public function getTitle()
    {
        return $this->settings->title;
    }

    public function setTitle(string $newTitle)
    {
        $this->settings->title = $newTitle;
    }

    public function getDescription()
    {
        return $this->settings->description;
    }

    public function setDescription(string $newDescription)
    {
        $this->settings->description = $newDescription;
    }

    public function getKeywords()
    {
        return $this->settings->keywords;
    }

    public function setKeywords(string $newKeywords)
    {
        $this->settings->keywords = $newKeywords;
    }

    public function save()
    {
        $this->saveToFile('site', $this->settings);
    }




}