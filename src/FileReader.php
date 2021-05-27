<?php

namespace RecipeCalculator;

use SplFileObject;

class FileReader
{
    protected const BUFFER = 8192;

    public static function readFromFile(string $fileName): FileReader
    {
        return new static(new SplFileObject($fileName));
    }

    public function __construct(protected SplFileObject $file)
    {
    }

    public function read($buffer = self::BUFFER): \Generator
    {
        while (!$this->file->eof()) {
            yield $this->file->fread($buffer);
        }
    }
}
