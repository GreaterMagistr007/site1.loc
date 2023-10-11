<?php

namespace core\modules;

class View
{
    const FILE_EXTENSION = '.php';

    public static array $variablesArray = [] ;

    public function __construct(string $template, $variables = [])
    {
        $file = $this->findTemplateFile($template);
        // Загоним список переменных в массив, чтоб не потерять - так добьемся наследования переменных
        foreach ($variables as $key => $variable) {
            self::$variablesArray[$key] = $variable;
        }
        $this->renderFile($file, $variables);
    }

    public function renderFile(string $file)
    {
        ob_start();
        extract(self::$variablesArray);
        require $file;
        echo ob_get_clean();
    }

    public function findTemplateFile(string $title)
    {
        $title = str_replace('.', DS, $title);

        $file = template_dir($title . self::FILE_EXTENSION);
        if (!file_exists($file)) {
            dd('Не найден файл шаблона ' . $file);
        }

        return $file;
    }
}