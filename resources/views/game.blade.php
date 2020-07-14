@if($game)
    <div class="title">@lang('game.names.nearest')</div>
    <div class="mode">@lang('game.mode'): <strong>{{$game->getTypeName()}}</strong> Карта:
        <strong>{{$game->map_name}}</strong></div>
{{--    <div class="date">{{$game->planned_at->format('d.m.Y в H:i')}} / осталось 5ч. 35мин.</div>--}}
    <div class="kill-price">{{round($game->price * $killValue)}} &#x20bd; за килл</div>
    <div class="competitors">@lang('game.members')
        {{$game->members_count . '/'}}{{$game->max_players}}</div>
    <div class="button">
        <button class="{{$button ?? 'button-2'}} btn"
                @click="becomeMember('{{$type}}')">@lang('game.become_member')</button>
    </div>
    <div class="rules"><a href="#" @click.prevent="showRules">@lang('game.how_it_works')</a></div>
@else
    <div class="title">@lang('game.no_games')</div>
@endif
