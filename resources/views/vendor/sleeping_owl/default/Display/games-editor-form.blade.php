<members-editor
        :multiple="{{$multiple}}"
        :index="{{$index}}"
        :game-id="{{$game->id}}"
        inline-template>
    <div class="form-elements w-100">
        <template v-if="index >= 0">
            <div v-if="loading" class="alert alert-info alert-message">
                <i class="fas fa-sync-alt fa-lg fa-spin"></i>
                @lang('admin.games.view.loading')
            </div>
            <div v-if="readOnlyGame && !resultsPublished" class="alert alert-warning alert-message">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                @lang('admin.games.view.msg_results')
            </div>
            <div v-if="readOnlyGame && resultsPublished" class="alert alert-warning alert-message">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                @lang('admin.games.view.msg_results_published')
            </div>
            <div v-if="showSaved" class="alert alert-success alert-message">
                <i class="fas fa-check fa-lg"></i>
                @lang('admin.games.view.msg_saved')
            </div>
            <div v-if="showError" class="alert alert-danger alert-message">
                <i class="fas fa-close fa-lg"></i>
                @{{ error }}
            </div>
            <div v-if="showPublished" class="alert alert-success alert-message">
                <i class="fas fa-bullhorn fa-lg"></i>
                @lang('admin.games.view.msg_published')
            </div>
        </template>
        <div class="card" v-if="index >= 0">
            <div class="card-header">
                <div class="card-title">Распознавание результатов</div>
            </div>
            <div class="card-body">
                <input type="file" ref="files" multiple/>
            </div>
            <div class="card-footer">
                <div class="row align-items-center mx-0">
                    <div clsas="col-md-2">
                        <button class="btn btn-primary" @click="loadScreens">Обработать</button>
                    </div>
                    <div class="col-md-5">
                        <div class="">@{{screensStatus}}</div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table-primary table table-striped dataTable" style="width: 100%;"
               :style="'opacity: ' + (loading ? .5 : 1) + ';'">
            <thead>
            <tr role="row">
                <th class="row-header" style="width: 50px;">
                    @lang('admin.games.view.rows.id')
                </th>
                <th class="row-header">
                    @lang('admin.games.view.rows.nick')
                </th>
                <th class="row-header">
                    @lang('admin.games.view.rows.team')
                </th>
                <th class="row-header" style="width: 100px;">
                    @lang('admin.games.view.rows.frags')
                    (@{{totalKills}})
                </th>
                <th class="row-header" style="width: 130px;">
                    @lang('admin.games.view.rows.visit')
                </th>
            </tr>
            </thead>
            <tbody>
            <tr role="row" class="" v-for="(member,idx) in membersOrdered" :key="member.id"
                :class="{[member.pivot.color+'-line']:member.pivot.color}"
            >
                <td>
                    <div class="row-text">@{{member.id}}
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <a target="_blank"
                           :href="'/missioncontrol/users/'+member.id+'/edit'">
                           @if ($game->game_code == App\Models\Game::GAME_PUBG)
                                @{{member.name}}
                            @elseif ($game->game_code == App\Models\Game::GAME_CALL_OF_DUTY)
                                @{{member.name_cod}}
                            @elseif ($game->game_code == App\Models\Game::GAME_FREEFIRE)
                                @{{member.name_freefire}}
                            @endif
                        </a>
                        (@{{ _.round(member.kd, 4) }})
                        <a target="_blank"
                           v-if="member.vk_link"
                           :href="member.vk_link"><i class="fab fa-vk"></i></a>
                        <template v-if="member.pivot.bonus">
                            &#36;+
                        </template>
                    </div>
                </td>
                <td>
                    <div class="row-text">@{{member.pivot.team}}
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <input :disabled="!!resultsPublished" type="number" v-model="member.pivot.kills"
                               @input="member.pivot.visit = true"
                        />
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <input :disabled="!!resultsPublished" type="checkbox"
                               v-model="member.pivot.visit"> @lang('admin.games.view.visited')
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="form-buttons panel-footer" v-if="index >= 0">
            <button type="button" class="btn btn-primary" @click="saveMembers"
                    :disabled="!!resultsPublished || loading"><i
                        class="fas fa-save"></i> @lang('admin.games.view.buttons.save')
            </button>
            <button type="button" class="btn btn-success" @click="publishMembers"
                    :disabled="readOnlyGame || !!resultsPublished || loading"><i
                        class="fas fa-bullhorn"></i> @lang('admin.games.view.buttons.publish')
            </button>
            <button
                    v-if="multiple < 2"
                    type="button" class="btn btn-secondary" @click="fillTeams"
                    :disabled="game.status!=0 || !!resultsPublished || loading"><i
                        class="fas fa-sitemap"></i> @lang('admin.games.view.buttons.fill_teams')
            </button>
            <button type="button" class="btn btn-warning" @click="reloadGame"
                    :disabled="!!resultsPublished || loading"><i
                        class="fas fa-ban"></i> @lang('admin.games.view.buttons.reset')
            </button>
        </div>
    </div>
</members-editor>
