<?php

namespace core\modules;

class View
{
    const TEMPLATE_DIRECTORY = 'template';
    const FILE_EXTENSION = '.php';
    public function __construct(string $template, $variables = [])
    {
        $file = $this->findTemplateFile($template);
//        $this->render($file, $variables);
        $this->renderFile($file, $variables);
    }

    public function renderFile(string $file, $variables = [])
    {
//        $result = '';
        ob_start();
        extract($variables);
        require $file;
        echo ob_get_clean();
    }

    public function findTemplateFile(string $title)
    {
        $file = template_dir($title . self::FILE_EXTENSION);
        if (!file_exists($file)) {
            dd('Не найден файл шаблона ' . $file);
        }

        return $file;
    }

    public function render(string $file, array $variables = [])
    {
        ob_start();

        foreach ($variables as $key => $variable) {
            $this->$key = $variable;
        }

        extract($variables);


        require $file;

        ob_get_clean();
    }
}