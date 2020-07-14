<members-editor :game-id="{{$game->id}}" inline-template>
    <div class="form-elements w-100">
        <div v-if="loading" class="alert alert-info alert-message">
            <i class="fas fa-sync-alt fa-lg fa-spin"></i>
            @lang('admin.games.view.loading')
        </div>
        <div v-if="readOnlyGame && !game.results_published" class="alert alert-warning alert-message">
            <i class="fas fa-exclamation-triangle fa-lg"></i>
            @lang('admin.games.view.msg_results')
        </div>
        <div v-if="readOnlyGame && game.results_published" class="alert alert-warning alert-message">
            <i class="fas fa-exclamation-triangle fa-lg"></i>
            @lang('admin.games.view.msg_results_published')
        </div>
        <div v-if="showSaved" class="alert alert-success alert-message">
            <i class="fas fa-check fa-lg"></i>
            @lang('admin.games.view.msg_saved')
        </div>
        <div v-if="showPublished" class="alert alert-success alert-message">
            <i class="fas fa-bullhorn fa-lg"></i>
            @lang('admin.games.view.msg_published')
        </div>
        <table class="table-primary table table-stripeddataTable" style="width: 100%;"
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
                    @lang('admin.games.view.rows.frags1')
                </th>
                <th class="row-header" style="width: 100px;">
                    @lang('admin.games.view.rows.frags2')
                </th>
                <th class="row-header" style="width: 100px;">
                    @lang('admin.games.view.rows.frags3')
                </th>
                <th class="row-header" style="width: 100px;">
                    @lang('admin.games.view.rows.total_frags')
                </th>
                <th class="row-header" style="width: 130px;">
                    @lang('admin.games.view.rows.visit')
                </th>
            </tr>
            </thead>
            <tbody>
            <tr role="row" class="odd" v-for="member in membersOrdered" :key="member.id">
                <td>
                    <div class="row-text">@{{member.id}}
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <a target="_blank"
                           :href="'/missioncontrol/users/'+member.id+'/edit'">@{{member.name}}</a>
                        <a target="_blank"
                           v-if="member.vk_link"
                           :href="member.vk_link"><i class="fab fa-vk"></i></a>
                    </div>
                </td>
                <td>
                    <div class="row-text">@{{member.pivot.team}}
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <input :disabled="!!game.results_published" type="number" v-model="member.pivot.kills1"
                               @input="updateKills(member.pivot)"
                        />
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <input :disabled="!!game.results_published" type="number" v-model="member.pivot.kills2"
                               @input="updateKills(member.pivot)"
                        />
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        <input :disabled="!!game.results_published" type="number" v-model="member.pivot.kills3"
                               @input="updateKills(member.pivot)"
                        />
                    </div>
                </td>
                <td>
                    <div class="row-text">
                        @{{member.pivot.kills}}
                    </div>
                </td>

                <td>
                    <div class="row-text">
                        <input :disabled="readOnlyGame" type="checkbox"
                               v-model="member.pivot.visit"> @lang('admin.games.view.visited')
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="form-buttons panel-footer">
            <button type="button" class="btn btn-primary" @click="saveMembers"
                    :disabled="!!game.results_published || loading"><i
                        class="fas fa-save"></i> @lang('admin.games.view.buttons.save')
            </button>
            <button type="button" class="btn btn-success" @click="publishMembers"
                    :disabled="readOnlyGame || !!game.results_published || loading"><i
                        class="fas fa-bullhorn"></i> @lang('admin.games.view.buttons.publish')
            </button>
            <button type="button" class="btn btn-secondary" @click="fillTeams"
                    :disabled="game.status!=0 || !!game.results_published || loading"><i
                        class="fas fa-sitemap"></i> @lang('admin.games.view.buttons.fill_teams')
            </button>
            <button type="button" class="btn btn-warning" @click="reloadGame"
                    :disabled="!!game.results_published || loading"><i
                        class="fas fa-ban"></i> @lang('admin.games.view.buttons.reset')
            </button>
        </div>
    </div>
</members-editor>