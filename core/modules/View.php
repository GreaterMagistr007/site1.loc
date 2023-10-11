<?php

namespace core\modules;

class View
{
    const FILE_EXTENSION = '.php';

    public static array $variablesArray = [] ;

    public function __construct(string $template, $variables = [])
    {
        $file = $this->findTemplateFile($template);
        // Массив переменных, дублируемых по ключу:
        $oldVariables = [];
        // Загоним список переменных в массив, чтоб не потерять - так добьемся наследования переменных
        foreach ($variables as $key => $variable) {
            // Если название дублируется, то сохраним значение для выходного массива
            if (isset(self::$variablesArray[$key])) {
                $oldVariables[$key] = $variable;
            }
            self::$variablesArray[$key] = $variable;
        }
        $this->renderFile($file);

        // Вернем старые значения переменных:
        foreach ($oldVariables as $key => $variable) {
            self::$variablesArray[$key] = $variable;
        }
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