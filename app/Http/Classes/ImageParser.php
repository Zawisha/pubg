<?php


namespace App\Http\Classes;


use App\Models\Game;
use Illuminate\Support\Facades\App;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ImageParser
{
    public const presets = [
//        ['c' => 0, 's' => 0],
//        ['c' => 10, 's' => 30],
//        ['c' => 20, 's' => 30],
//        ['c' => 30, 's' => 30],
//        ['c' => 40, 's' => 30],
//        ['c' => 50, 's' => 30],
//        ['c' => 60, 's' => 30],
//        ['c' => 70, 's' => 30],
//        ['c' => 90, 's' => 30],
//        ['c' => 0, 's' => 20],
//        ['c' => 10, 's' => 20],
//        ['c' => 20, 's' => 20],
//        ['c' => 30, 's' => 20],
//        ['c' => 40, 's' => 20],
//        ['c' => 50, 's' => 20],
//        ['c' => 60, 's' => 20],
//        ['c' => 70, 's' => 20],
//        ['c' => 90, 's' => 20],
        ['c' => 0, 's' => 50, 'lang' => ['eng', 'rus']],
        ['c' => 0, 's' => 50, 'lang' => ['rus']],
//        ['c' => 10, 's' => 20],
//        ['c' => 20, 's' => 20],
//        ['c' => 30, 's' => 20],
//        ['c' => 40, 's' => 20],
        ['c' => 50, 's' => 50, 'lang' => ['eng', 'rus']],
        ['c' => 50, 's' => 50, 'lang' => ['rus']],
//        ['c' => 60, 's' => 20],
//        ['c' => 70, 's' => 20],
        ['c' => 90, 's' => 90, 'lang' => ['eng', 'rus']],
        ['c' => 90, 's' => 90, 'lang' => ['eng', 'rus'], 'x2' => true],
        ['c' => 90, 's' => 90, 'lang' => ['rus']],
//        ['c' => 90, 's' => 90, 'lang' => ['eng']],
    ];

    public static function parseAllPresets($imageName, $showSteps = false)
    {
        $result = collect();
        $counts = self::getCounts($imageName);

        if ($showSteps) {
            $cnts = $counts->map(function ($cnt) {
                return $cnt['cnt'];
            });
            echo $counts->count() . ': [' . $cnts->join('], [') . "]\n";
        }


        foreach (self::presets as $preset) {
            if ($showSteps) {
                echo "Step c={$preset['c']} s={$preset['s']}\n";
            }

            try {

                $x2 = false;
                if (!empty($preset['x2'])) {
                    $x2 = true;
                }

                $mResult = self::getNames($imageName, $preset['c'], $preset['s'], $preset['lang'], $x2);

                if ($showSteps) {
                    echo $mResult->count() . ': [' . $mResult->join('], [') . "]\n";
                }

                $mResult = $mResult->combine($counts);

                //print_r($mResult->toArray());

                $result = $result->merge($mResult);
//                echo $result->keys()->join(', ') . "\n";

            } catch (\Throwable $ex) {
                if (App::runningInConsole()) {
                    echo 'Exception occured: ' . $ex->getMessage() . "\n";
                }
            }

        }

        return $result;
    }

    public static function getCounts($imageName)
    {
        $img = \Intervention\Image\Facades\Image::make($imageName);
        $img->greyscale();
        $img->contrast(90);
//        $img->sharpen(50);
        $img->limitColors(10);
        $img->invert();

//        $img->contrast(90);
//        $img->sharpen(50);
//        $img->invert();
//        $img->greyscale();
//        $img->limitColors(4);
        $img->crop(intval($img->width() * 0.25),
            $img->height(),
            intval($img->width() * 0.75), 0);
        $img->save($imageName . '-step2', 100, 'png');


        $nicks = collect(explode("\n",
            (new TesseractOCR($imageName . '-step2'))
                ->executable(self::getTessPath())
//                ->whitelist()
                ->lang('eng', 'rus')
                ->run()));

        $nicks = $nicks->filter(function ($nik) {
            return trim($nik) != '';
        });

//        echo '[' . $nicks->join('], [') . ']';

        $nicks = $nicks->map(function ($nik) {

            if (mb_strtoupper($nik) == 'E'
                || mb_strtoupper($nik) == 'Е') {
                return '';
            }

            $replaceMap = [
                'A' => '4',
                'А' => '4',
                'Б' => '6',
                'З' => '3',
                'О' => '0',
                'В' => '8',
                'Ю' => '0',
                'Е' => '8',
                'B' => '8',
                'Z' => '2',
                'G' => '6',
                'I' => '1',
                '|' => '1',

            ];

            $ch = mb_strtoupper(mb_substr($nik, 0, 1));
            foreach ($replaceMap as $letter => $digit) {
                if ($ch == $letter) {
                    $nik = $digit . mb_substr($nik, 1);
                    break;
                }
            }

            $nik = self::normalizeNik($nik);

            if (!in_array($nik[0], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                'K', 'I', 'L', 'S', 'O', '|', 'B', 'Z', 'U'
            ])) {
                return '';
            }

            if (mb_strlen($nik) == 1 && $nik == 'U') {
                return '';
            }

            if ($nik[0] == 'I') {
                $nik[0] = '1';
            }

            if ($nik[0] == '|') {
                $nik[0] = '1';
            }

            if ($nik[0] == '0') {
                $nik = '0';
            }
            return intval($nik);
        });

        $nicks = $nicks->filter(function ($nik) {
            return trim($nik) != '';
        });

        $nicks = $nicks->values();

        $nicks = $nicks->map(function ($value, $key) use ($imageName) {
            return [
                'cnt' => $value,
                'key' => md5($imageName) . '-' . $key
            ];
        });

        return $nicks;
    }

    public static function normalizeNik($nik, $clear = false)
    {
        if ($clear) {
            $nik = preg_replace('/[^ a-zа-яё\d]/ui', '', $nik);
        }
        return strip_tags(
            str_replace(array(
                '`',
                ' ', '%', '?', '-', '°', '!', '|', '&', '*',
                '', '】', '【', '・', '¹', '¤', '』', '『', '_',
                '〥', '~', '´', "\u20ac", '$',
                '[', ']', '(', ')', '{', '}', '<', '>',
                '=', '+', '/', '\\',
                '\'', '"', ',', ';', '.', ':',), '',
                mb_strtoupper(
                    trim(
                        transliterator_transliterate('Any-Latin; Latin-ASCII;',
                            $nik)
                    )
                )
            )
        );
    }

    public static function getTessPath()
    {
        return config('pubg.tesseract_path');
    }

    public static function getNames($imageName, $c = 0, $s = 30, $args = ['eng', 'rus'], $x2 = false)
    {
        $img = \Intervention\Image\Facades\Image::make($imageName);
        $img->greyscale();
        $img->contrast($c);
        $img->sharpen($s);
        $img->limitColors(10);
        $img->invert();
        if ($x2) {
            $img->resize($img->width() * 2, $img->getHeight() * 2);
        }

        $img->crop(intval($img->width() * 0.75),
            $img->height(),
            0, 0);

        $img->save($imageName . '-step1', 100, 'png');

        $nicks = collect(explode("\n", (new TesseractOCR($imageName . '-step1'))
            ->executable(self::getTessPath())
            ->lang(...$args)
            ->run()));

        $nicks = $nicks->filter(function ($nik) {
            return mb_strlen(trim($nik)) > 1;
        });

        $names = $nicks->map(function ($nik) {
            return '_' . self::normalizeNik($nik);
        });

        return $names;
    }

    public static function parse($imageName, $c = 0, $s = 30)
    {
        $names = self::getNames($imageName, $c, $s);
        $nicks = self::getCounts($imageName);

        return $names->combine($nicks);
    }

    public static function filterArray(&$found, &$notFound, &$result, $perCent = 100)
    {
        $nf = collect();

        foreach ($notFound as $user) {
            $max = self::findMaxSimilar($user['cname'], $result);
            $clearMax = self::findMaxSimilar($user['clear_cname'], $result);

            $max = ($max['p'] > $clearMax['p']) ? $max : $clearMax;

            if ($max['p'] > $perCent) {
                $found->push([
                    'id' => $user['id'],
                    'p' => $max['p'],
                    'cnt' => $result[$max['name']]['cnt'],
                    'name' => $user['name'],
                    'cname' => $user['cname'],
                    'sname' => $max['name']
                ]);

                $result = $result->reject(function ($value, $key) use ($result, $max) {
                    return $value['key'] == $result[$max['name']]['key'];
                });
            } else {
                $nf[$user['id']] = [
                    'id' => $user['id'],
                    'cname' => $user['cname'],
                    'clear_cname' => $user['clear_cname'],
                    'name' => $user['name'],
                ];
            }
        }

        $notFound = $nf;
    }

    public static function findMaxSimilar($name, $results)
    {
        $maxSimilarity = 0;
        $nick = '';

        foreach ($results->toArray() as $key => $count) {
            $p = 0;
            similar_text($name, mb_substr($key, 1), $p);
            if ($p > $maxSimilarity) {
                $nick = $key;
                $maxSimilarity = $p;
            }
        }

        return ['name' => $nick, 'p' => $maxSimilarity];
    }

    public static function processParseResults($result, $gameId)
    {
        /** @var Game $game */
        $game = Game::find($gameId);
        $users = $game->members;

        $found = collect();
        $notFound = collect();

        foreach ($users as $user) {
            $notFound[$user->id] = [
                'id' => $user->id,
                'cname' => ImageParser::normalizeNik($user->name),
                'clear_cname' => ImageParser::normalizeNik($user->name, true),
                'name' => $user->name,
            ];
        }

        ImageParser::filterArray($found, $notFound, $result, 99);
        ImageParser::filterArray($found, $notFound, $result, 89);
        ImageParser::filterArray($found, $notFound, $result, 79);
        ImageParser::filterArray($found, $notFound, $result, 69);
        ImageParser::filterArray($found, $notFound, $result, 60);

        return [
            'found' => $found,
            'notFound' => $notFound
        ];
    }

}
