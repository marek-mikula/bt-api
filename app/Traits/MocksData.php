<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait MocksData
{
    /**
     * Retrieves JSON data from mock file/s as array
     * using pagination
     *
     * @param  string  $domain the domain name of the package
     * @param  int  $page current page
     * @param  int  $perPage number of items on one page
     * @param  int  $fileStep step number which is used to store data
     * across multiple files
     * @param  int  $fileMax the max number of file
     * @param  string  $fileData the name patter for file path, which will be used
     * with vsprintf, for instance example/%s_%s.json
     * @param  string  $fileEmpty the name of the empty file with empty data array
     * @param  string  $pathToData the path to obtain data from the JSON array
     */
    private function mockPagination(
        string $domain,
        int $page,
        int $perPage,
        int $fileStep,
        int $fileMax,
        string $fileData,
        string $fileEmpty,
        string $pathToData = 'data'
    ): array {
        // firstly, count the indexes of starting
        // and ending item regardless of files
        //
        // page    = 3
        // perPage = 4500
        //
        // $from = 9001
        // $to   = 13500

        $from = (($page - 1) * $perPage) + 1;
        $to = $page * $perPage;

        // get the start index of the first file and
        // ending index of the last file

        $fromFile = round_down_to_nearest_multiple(($page - 1) * $perPage, multiple: $fileStep) + 1;
        $toFile = round_up_to_nearest_multiple($page * $perPage, multiple: $fileStep);

        // if the page number is too high, limit the
        // ending file index

        if ($toFile > $fileMax) {
            $toFile = $fileMax;
        }

        $files = [];

        // now, this crazy ass while loop counts
        // from which index we have to take how many
        // items in each file

        while ($fromFile < $toFile) {
            $currentToFile = $fromFile + $fileStep - 1;

            $filePath = vsprintf($fileData, [
                $fromFile,
                $currentToFile,
            ]);

            // normalizer normalizes the index, so it matches
            // the index in the file
            //
            // we do this because we work with the files as
            // it was one big array, but it is chunked into
            // files, where each file has data array with
            // indexes starting from 0

            $normalizer = ($fromFile - ($fromFile % $fileStep));

            if ($from >= $fromFile && $to <= $currentToFile) {
                $files[$filePath] = [
                    $from - $normalizer,
                    $to - $from + 1,
                ];
            } elseif ($from >= $fromFile) {
                $files[$filePath] = [
                    $from - $normalizer,
                    $currentToFile - $from + 1,
                ];
            } elseif ($to <= $currentToFile) {
                $files[$filePath] = [
                    $fromFile - $normalizer,
                    $to - $fromFile + 1,
                ];
            } else {
                $files[$filePath] = [
                    $fromFile - $normalizer,
                    $fileStep,
                ];
            }

            $fromFile += $fileStep;
        }

        // now get empty data json

        $data = $this->mockData($domain, $fileEmpty);

        // for each file, take the N number of items starting
        // from ($offset - 1) index (-1 because splice is 0 based)
        // and merge it with the default empty array

        foreach ($files as $file => [$offset, $count]) {
            $dataFromFile = collect(Arr::get($this->mockData($domain, $file), $pathToData))
                ->splice(($offset - 1), $count)
                ->all();

            $mergedData = array_merge(
                Arr::get($data, $pathToData),
                $dataFromFile,
            );

            Arr::set($data, $pathToData, $mergedData);
        }

        return $data;
    }

    /**
     * Retrieves JSON data from a mock file as array
     */
    private function mockData(string $domain, string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: api_path($domain, "Resources/mocks/{$path}")
        );

        return json_decode(json: $json, associative: true);
    }
}
