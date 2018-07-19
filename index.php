<?php
function getValueByKey($fileName, $key)
{
    $fileSize = filesize($fileName);
    $file     = new SplFileObject($fileName);
    $first    = 0;
    $last     = $fileSize - 1;

    while ($first < $last) {
        $firstSimbolPosition = round(($first + $last) / 2);
        // перемещаем указатель в середину текущего сегмента
        $file->fseek($firstSimbolPosition);
        $firstSimbol = $file->fgetc();
        // находим текущую строку
        while ($firstSimbol !== "\x0A") {
            $firstSimbolPosition--;
            $file->fseek($firstSimbolPosition);
            $currentRow = $file->fgets();
            $firstSimbol = mb_substr($currentRow, 0, 1);
        }
        $firstSimbolPosition++;
        $file->fseek($firstSimbolPosition);
        // берем строку
        $currentRow = $file->fgets();
        $keyVal = explode("\t", $currentRow);
        // берем ключ и значение
        $currentKey = $keyVal[0];
        $currentValue = rtrim($keyVal[1]);

        if ($currentKey === $key) {
            return $currentValue;
        } elseif ($currentKey < $key) {
            $first = $firstSimbolPosition + strlen($currentRow) + 1;
        } else {
            $last = $firstSimbolPosition - 1;
        }
    }

    return null;
}
