<div class="row">
    <div class="col-md-12">
        <script type="text/javascript">
            function sendStat() {
                var email = $('#email').val();

                if (email.trim() == '') {
                    Admin.Messages.error('Ошибка', 'Введите e-mail')
                } else {

                    axios.post('/missioncontrol/stat_export', {email: email})
                        .then(function () {
                            Admin.Messages.success('Успешно', 'Отчет будет подготовлен и отправлен на e-mail ' + email
                                + '. Обаботка займет от 10 до 30 минут, в зависимости от нагрузки на сервер.');
                        })
                        .catch(function (response) {
                            Admin.Messages.error('Ошибка', response.response.data.message)
                        });
                }
            }
        </script>
        <div class="card">
            <div class="card-header">
                <strong>Отправка статистики на e-mail</strong>
            </div>
            <div class="card-body">
                <div class="">
                    <label>E-mail</label>
                    <input class="form-control" type="email" id="email" placeholder="введите e-mail"/>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" onclick="sendStat()">Отправить</button>
            </div>
        </div>
    </div>


    @if(\Auth::user()->isSuperAdmin() || \Auth::user()->isAdmin())
        <div class="col-md-4">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$balance}}</h3>
                    <p>@lang('admin.dashboard.balance')</p>
                </div>
                <div class="icon">
                    <i class="fa fas fa-university"></i>
                </div>
            </div>
        </div>
        @if(!app()->environment('local'))
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{round(\App\Models\PayeerPayment::getBalance()['balance']['RUB']['DOSTUPNO'])}}
                            <sup style="font-size: 20px"></sup></h3>

                        <p>Payeer</p>
                    </div>
                    <div class="icon">
                        <i class="fa fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-4">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{$income}}<sup style="font-size: 20px"></sup></h3>

                    <p>@lang('admin.dashboard.income')</p>
                </div>
                <div class="icon">
                    <i class="fa fas fa-cash-register"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$refPayments}}<sup style="font-size: 20px"></sup></h3>

                    <p>@lang('admin.dashboard.ref_payments')</p>
                </div>
                <div class="icon">
                    <i class="fa fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        @if(!app()->environment('local'))
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$echoStat->subscription_count}}<sup style="font-size: 20px"></sup></h3>

                        <p>@lang('admin.dashboard.online')</p>
                    </div>
                    <div class="icon">
                        <i class="fa fas fab fa-globe-europe"></i>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-4">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>{{$telegram}}
                    </h3>

                    <p>@lang('admin.dashboard.bots')</p>
                </div>
                <div class="icon">
                    <i class="fab fa-telegram-plane"></i>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-4">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{$echoStat->subscription_count}}<sup style="font-size: 20px"></sup></h3>

                    <p>@lang('admin.dashboard.online')</p>
                </div>
                <div class="icon">
                    <i class="fa fas fab fa-globe-europe"></i>
                </div>
            </div>
        </div>
    @endif
</div>
