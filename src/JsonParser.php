<?php

declare(strict_types=1);

namespace RecipeCalculator;

use IteratorAggregate;

class JsonParser implements IteratorAggregate
{
    private const JSON_REMOVABLE_CHARS = [PHP_EOL, ']', '['];
    private const JSON_SEPARATOR = '},';
    private string $chunkRest = '';

    public static function readFromFile(string $fileName): JsonParser
    {
        return new static(FileReader::readFromFile($fileName));
    }

    public function __construct(protected FileReader $fileReader)
    {
    }

    public function getIterator(): \Generator
    {
        foreach ($this->fileReader->read() as $chunk) {
            foreach ($this->getJsonFromChuck($chunk) as $json) {
                yield json_decode($json, true);
            }
        }
    }

    protected function cleanJsonChunk(string $chunk): string
    {
        return str_replace(self::JSON_REMOVABLE_CHARS, '', $chunk);
    }

    protected function getJsonFromChuck(string $chunk): array
    {
        $this->chunkRest .= $this->cleanJsonChunk($chunk);
        $jsonPieces = explode(self::JSON_SEPARATOR, $this->chunkRest);
        $this->chunkRest = $jsonPieces[array_key_last($jsonPieces)];

        return array_map(fn($item) => $item . '}', array_slice($jsonPieces, 0, -1));
    }
}
