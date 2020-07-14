<?php

namespace App\Jobs;

use App\Mail\ExcelFile;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class ExportStatAsExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $email;
    var $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName = '', $email = false)
    {
        $this->email = $email;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->fileName) {
            $this->fileName = public_path('export.xlsx');
        }


//        public const TYPE_PAYMENT = 1;
//        public const TYPE_FEE = 2;
//        public const TYPE_WITHDRAW = 3;
//        public const TYPE_GAME_PAYMENT = 4;
//        public const TYPE_GAME_RETURN = 5;
//        public const TYPE_KILL = 6;
//        public const TYPE_REFUND = 7;
//        public const TYPE_ADMIN = 8;
//        public const TYPE_EPS_CANCEL = 9;
//        public const TYPE_TOP1_PAYMENT = 10;
//        public const TYPE_TOP2_PAYMENT = 11;
//        public const TYPE_TOP3_PAYMENT = 12;
//        public const TYPE_GAME_FEE = 14;

        $fn = 'report-' . str_replace([' ', ':'], '-', now()->toDateTimeString());

        Excel::create($fn, function ($excel) {
            $excel->sheet('Transactions', function ($sheet) {
                $index = 2;

                $transactionNames = [];

                foreach (Transaction::getTypeNames() as $id => $name) {
                    $transactionNames[] = "$name ($id)";
                }

                $sheet->row(1, array_merge([
                    'Дата создания',
                    'PUBG ID',
                    'COD ID',
                    'FREE FIRE ID',
                    'Тип транзакции',
                ], $transactionNames));

                Transaction::with('user')->chunk(1000, function ($transactions) use ($sheet, &$index) {
                    /** @var Transaction $transaction */
                    foreach ($transactions as $transaction) {
                        $types = [
                            '', '', '', '',
                            '', '', '', '',
                            '', '', '', '',
                            '', '',
                        ];

                        $types[0] = $transaction->type;
                        $types[$transaction->type] = $transaction->amount;

                        unset($types[13]);

                        $sheet->row($index++, array_merge([
                            $transaction->created_at->toDateString(),
                            $transaction->user->pubg_id,
                            $transaction->user->cod_id,
                            $transaction->user->freefire_id,
                        ], $types));
                    }
                });
            });

            $excel->sheet('Games', function ($sheet) {
                $index = 2;

                $sheet->row(1, array_merge([
                    'Дата создания',
                    'PUBG ID',
                    'COD ID',
                    'FREE FIRE ID',
                    'Сумма'
                ], []));

                Game::with('members')->chunk(1000, function ($games) use ($sheet, &$index) {
                    /** @var Game $game */
                    foreach ($games as $game) {
                        /** @var User $member */
                        foreach ($game->members as $member) {
                            $sheet->row($index++, [
                                $game->created_at->toDateString(),
                                $member->pubg_id,
                                $member->cod_id,
                                $member->freefire_id,
                                $game->price
                            ]);
                        }
                    }
                });
            });
        })->store('xlsx', $this->fileName);

        if ($this->email) {
            Mail::to($this->email)
                ->send(new ExcelFile($this->fileName . '/' . $fn . '.xlsx'));
        }
    }
}
