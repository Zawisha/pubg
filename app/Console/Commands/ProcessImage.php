<?php

namespace App\Console\Commands;

use App\Http\Classes\ImageParser;
use App\Models\Game;
use Illuminate\Console\Command;
use Intervention\Image\Image;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ProcessImage extends Command
{
    var $testSet = Array
    (
        'RBMAKGRANICK' => 0,
        'RBYWESTKILLER' => 1,
        'EEXSANTA' => 7,
        'EEXXONDAR' => 0,
        'VAGANIPI' => 3,
        'EXTRAREFLEX' => 6,
        'KLYAGIN' => 0,
        'ONTHETOPSUCCES' => 1,
        'ACODEX' => 2,
        'VARYZLODEL' => 0,
        'VARYBUDEON' => 5,
        'DUUUK' => 1,
        'UMABOBRO' => 1,
        'RBYMAKGRANICK' => 0,
        'RBSWESTKILLER' => 1,
        'EXTRAFULLTILT' => 3,
        'UMAOOBRO' => 1,
        'EBXONDAR' => 0,
        'VARBUDEON' => 5,
        'UMADOBRO' => 1,
        'NGLXEXAMPTE' => 2,
        'NGLXBISQUIT' => 1,
        'GEROAMUR' => 1,
        'PYAEPGG' => 0,
        'UGINCREW' => 0,
        'UGINDFFENDER' => 0,
        'VETERINAR47' => 1,
        'BOULBHBLU' => 2,
        'BROFIVEL' => 1,
        'DMWARFARIN' => 3,
        'RAAAADENDY' => 0,
        'RADALTREF' => 1,
        'PUSEPGIB' => 2,
        'UGINOFFENDER' => 0,
        'AAASDENDY' => 0,
        'REPOIMVP' => 1,
        'PUSEPGG' => 2,
        'UQINDFFENDER' => 0,
        'BOJLBHBLU' => 2,
        'RAAALTREF' => 1,
        'NGLEXAMPTE' => 2,
        'PUAEPGG' => 2,
        'UQINOFFENDER' => 0,
        'AKADENDY' => 0,
        'RAAALTRET' => 1,
        'RPMHALOL000' => 1,
        'RPMMRSTEPIUS' => 1,
        'NREAVR' => 0,
        'GRESHNIK' => 2,
        'PKSADAMOSKI' => 0,
        'KSHAMZAT' => 1,
        'CECPFERTIL' => 1,
        'QUANTFLETCH' => 0,
        'QUANTVETERAN' => 0,
        'ASYALIKKG' => 0,
        'KGXXCHIKO' => 0,
        'RTYIMPERATOR' => 1,
        'GRTNAO1000' => 1,
        'NREAER' => 0,
        'RKOADAMOSKI' => 0,
        'PKSHAMZAT' => 1,
        'CECPFERTIC' => 1,
        'KGXCHIKO' => 0,
        'NRENRER' => 0,
        'KS4ADAMOSKI' => 0,
        '7KBAHAMZAT' => 1,
        'CCCPFERTIC' => 1,
        'ASALIKKG' => 0,
        'KBHHHSNIKO' => 0,
        'FPEREEPR' => 0,
        'KSAHAMZAT' => 1,
        'CCCPFERLIC' => 1,
        'KBBHHHSNIKO' => 0,
        'RTLMPERATOR' => 1,
        'YXYLUBANYRT' => 2,
        'ANDRYSHKAGG' => 0,
        'EDFXX' => 0,
        'HEXEPACEAMIGA' => 0,
        'WPJUMPER' => 0,
        'STALINTIM' => 0,
        'DISKOTEKA' => 0,
        'JLUMOH4IK' => 0,
        'ZATEYA2X2' => 0,
        'RTIMPERATOR' => 1,
        'YXYLUGANYRT' => 2,
        'PASBATYA' => 0,
        'HANA' => 0,
        'RAUPA' => 0,
        'FLEXPAVLIN' => 1,
        'RANGOE744' => 0,
        'ASTRA' => 0,
        'BRATSHOYGU' => 0,
        'BRDRAFAL' => 0,
        'BRDNEGOBM' => 0,
        'FLEXMRPYPS' => 0,
        'VOCHAL7' => 0,
        'HANAI' => 0,
        'PILLATYNO' => 0,
        'RANGOB744' => 0,
        'BROHEROGM' => 0,
        'FASBATYA' => 0,
        'PILLATYNS' => 0,
        'RANGOG744' => 0,
        '€BRDRAFAL' => 0,
        'BRDHEROGM' => 0,
        'VACHAL7' => 0,
        'PILLATYND' => 0,
        '€BRDNEGOBM' => 0,
        'VACHAL' => 0,
        'HIDISON' => 1,
        'PROFRAG' => 4,
        'MSKURDI' => 1,
        'SPAS90' => 1,
        'VYBGEANTHONY' => 0,
        'STAS40' => 1,
        'HLEV' => 3,
        'OZZYWOZY' => 0,
        'DESANTNLK' => 3,
        'DSSUNSET' => 8,
        'STASL40' => 1,
        'XULEB' => 3,
        'HAEV' => 3,
        'MSKURO' => 0,
        'UBEANTHONY' => 0,
        'MERICH' => 1,
        'RAGAZNAKIZOK' => 7,
        'APAMA' => 1,
        'VELICHKOVITALY' => 0,
        'VABI' => 0,
        'EBANUSKAL' => 1,
        'MRANDERSON' => 0,
        'PENEP' => 1,
        'KAMASAMASASA' => 3,
        'PARAZLTAKUSOK' => 7,
        'FINSATLVA' => 1,
        'MGAPDEGZOPU' => 0,
        'REPER' => 1,
        'KATAZATAZAZA' => 3,
        'MRRICH' => 1,
        'PARAZITAKUSOK' => 7,
        'GENERALSOBAKA' => 1,
        'REBOOL' => 0,
        'NURLEGENDARY' => 8,
        'PROADAM' => 0,
        'GAPNIK' => 2,
        'POCHTALONI999' => 0,
        'SICRONG007' => 0,
        'BONQUENTIN' => 0,
        'SIYAVUSH' => 3,
        'SICRONG07' => 0,
        'REBAOL' => 0,
        'GOPNIK' => 2,
        'BLESSEDAB' => 3,
        'BINGOL' => 2,
        'AVOT8' => 0,
        'PHANTOM' => 0,
        'FLEXMRPUPS' => 1,
        'BATASI48' => 0,
        'WYPRX0601' => 0,
        'CHAKUR' => 0,
        'PHTOWHITE' => 1,
        'BLESSEDDAGI' => 3,
        'ANAT9' => 0,
        'PHTSWHITE' => 1,
        'BLESSEDSAB' => 3,
        'ARVAT9' => 0,
        'WYPNXQB01' => 0,
        'PHTWHITE' => 1,
        'BINGOO' => 2,
        'ARWAT09' => 0,
        'SURIKBOI' => 0,
        'NTWHITE' => 1,
        'LONEDAICHEG' => 0,
        'MONARCH' => 0,
        '90MILKYWAY' => 1,
        'AZVOV' => 1,
        'PROL00MOTYA43' => 2,
        'CRAZYDAWN' => 1,
        'PASBOYNIK' => 0,
        'EWKAZAR' => 0,
        'KGBDARKNES' => 0,
        'GQMILKYWAY' => 1,
        'FISBOBI' => 1,
        'RAZVOUMK' => 0,
        'CKGBDARKNES' => 0,
        'MAXONCHIK' => 1,
        'LEV2ILLQ' => 0,
        'KEFIKD7' => 0,
        'RUSA4ELO' => 0,
        'YYEC' => 0,
        'MSRYLOVE' => 0,
        'STRONG368457' => 0,
        'KEFIK97' => 0,
        '4YBE' => 0,
        'CES' => 0,
        'STRONG368497' => 0,
        'LEV2IILQ' => 0,
        'M5URYLOVE' => 0,
        'AHSISMILEEQ' => 0,
        'QPUWKA' => 0,
        'BYEDZUC' => 0,
        'ISRAPILOVO0L' => 0,
        'HRONAS' => 0,
        'KIRILLT494' => 0,
        'COFRUNKY' => 0,
        'FLASHURAGAN' => 0,
        'DETSHOT4495' => 0,
        'BIEOZUC' => 0,
        'KG494' => 0,
        'AH4ISMILEEQ' => 0,
        'KG1494' => 0,
        'CO5FRUNKY' => 0,
        'AHCISMILEEQ' => 0,
        'GPUWKA' => 0,
        'ISRAPILOVOO' => 0,
        'MAFIASOX' => 0,
        'GRAFDEAD' => 0,
        'QARA' => 1,
        'ATKMAKSBOO' => 4,
        'OXXXXRNY' => 0,
        'JURASICCCCE' => 0,
        'MISTERKES' => 0,
        'DAGUN' => 0,
        'EVILLVANESKA' => 2,
        'OMWARTARIN' => 2,
        'FIXSATLVA' => 0,
        'OXXRNY' => 0,
        'JURASICCCCC' => 0,
        'OMWARFARIN' => 2,
        'FNSATLVA' => 0,
        'OXXXRNIV' => 0,
        'JURASICCECCE' => 0,
        'DNWARFARIN' => 2,
        'NOZANUJ' => 0,
        'MIZANTREP' => 1,
        'EVKATSUKE' => 1,
        'UBIYCAMAMOK' => 0,
        'SHERFL03' => 0,
        'AGOANDER' => 7,
        'AGOBRAIN' => 3,
        'TUNEYDEC' => 1,
        'ELRAY' => 3,
        'HELLBOYS' => 0,
        'NXTORNADO' => 5,
        'DRAGANGEL' => 0,
        'PROEDVERLORD' => 1,
        'SHERF103' => 0,
        'ABOANDER' => 7,
        'ABOBRAIN' => 3,
        'TUNEYOEC' => 1,
        'PROEOVERLORD' => 1,
        'LESLAER' => 0,
        'MAXIMUSBIL6' => 0,
        'ALLBB1984' => 0,
        'OGYSMOKE' => 0,
        '88EFOTON' => 1,
        'CRAZYPILOT23' => 0,
        'GAMMWPAK86' => 1,
        'GAXELYDPWHAT' => 1,
        'ODINO4KA' => 0,
        'BBCXMEIOUW' => 1,
        'RESHIWK' => 0,
        '4FSIZANAG' => 0,
        'MAXIMUS6IL6' => 0,
        'ALLBBI984' => 0,
        '0456`UZMOKE' => 0,
        'RVVAEFOTON' => 1,
        'GAXELDRWHAT' => 1,
        'RPESHITWK' => 0,
        '4UPANA' => 0,
        'MAMTIZ6IB' => 0,
        '0GSMOKE' => 0,
        'AWMSWPAK86' => 1,
        'GAXELSORWHAT' => 1,
        'BBCMEIOUW' => 1,
        'RPESHUNX' => 0,
        'UPANA' => 0,
        'MAXIMUS6LL6' => 0,
        'RVVAORIP' => 1,
        'AWMWPAK86' => 1,
        'AXELSORWHAT' => 1,
        'RPESHUWM' => 0,
        'DUSILANAGI' => 0,
        'ARNGOLDI3' => 0,
        'HUIMANJEK' => 0,
        'RPMFOMINGO' => 0,
        'PROLO0I' => 0,
        'ANTON4SE' => 0,
        'BORIS7X' => 0,
        'RKSTASYAN' => 0,
        'MIK5BA' => 0,
        'DUSTDIEY' => 0,
        'ADEVALEJSS' => 0,
        'PUAEPBG' => 0,
        'BORISO7X' => 0,
        'RKASTASYAN' => 0,
        'MVA' => 0,
        'PROLD0I' => 0,
        'BORIS07X' => 0,
        'MV5' => 0,
        'RYERBB' => 0,
        'PROL0I' => 0,
        'ANTON4S6' => 0,
        'KLSTASYAN' => 0,
    );

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:image {image}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $imageName = $this->argument('image');

        $this->info($imageName);

        $stime = microtime(true);
        $result = collect();

        foreach (glob($imageName . '*.png') as $filename) {
            $this->info('processing ' . $filename);

            $step = ImageParser::parseAllPresets($filename, true);
            $result = $result->merge($step);
//            $this->info($result->keys()->join(', '));
        }

        $stime = microtime(true) - $stime;

        $this->info('Complete in ' . $stime);
//        $result = collect($this->testSet);
//        print_r($result->toArray());


        $pr = ImageParser::processParseResults($result, 286); //73


        $this->info(count($pr['notFound']));
        $this->info(count($pr['found']));
        foreach ($pr['found'] as $item) {
            $this->info("{$item['name']} - {$item['cname']} - {$item['sname']} ({$item['p']}) - {$item['cnt']}");
        }
    }
}
