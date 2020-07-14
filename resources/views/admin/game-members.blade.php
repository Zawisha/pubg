<table class="table-primary table table-stripeddataTable" style="width: 100%;">
    <thead>
    <tr role="row">
        <th class="row-header" style="width: 50px;">
            ID
        </th>
        <th class="row-header">
            НИК
        </th>
        <th class="row-header">
            Команда
        </th>
        <th class="row-header">
            Киллы
        </th>
    </tr>
    </thead>
    <tbody>
    @if($game)
        @foreach($game->members as $member)
            <tr role="row" class="odd">
                <td>
                    <div class="row-text">{{$member->id}}
                    </div>
                </td>
                <td>
                    <div class="row-text"><a target="_blank"
                                             href="/missioncontrol/users/{{$member->id}}/edit">{{$member->name}}</a>
                        @if($member->vk_link)
                            <a target="_blank"
                               href="{{$member->vk_link}}"><i class="fab fa-vk"></i></a>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="row-text">{{$member->pivot->team}}
                    </div>
                </td>
                <td>
                    <div class="row-text">0
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>