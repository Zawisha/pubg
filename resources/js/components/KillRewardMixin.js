export default {
    computed: {
        gameCodeName() {
            if (this.game.game_code == 0) {
                return 'PUBG';
            }

            if (this.game.game_code == 1) {
                return 'FREE FIRE';
            }

            if (this.game.game_code == 2) {
                return 'COD';
            }
        },
        killReward() {
            let val = this.game.price * this.killValue;
            if (this.isKing) {
                val /= 3;
            }

            if (this.game.mul == 2) {
                return Number(_.round(Number(val), 2)).toFixed(2);
            }

            return Number(_.ceil(_.round(Number(val) / 0.05, 5)) * 0.05).toFixed(2);
            // let fixed = Number(val).toFixed(2);
            // if (fixed - Math.round(val) === 0) {
            //     return this.$roundO(val);
            // }
            //
            // return fixed;
        },
        isKing() {
            return this.game.is_king;
        },
        isDoubleGame() {
            return this.game.mul == 2;
        },
        killValue() {
            if (this.$moment(this.user.created_at + ".000Z").diff(this.currentTime, 'minutes') >= -60) {
                return this.isDoubleGame
                    ? this.$store.state.maxKillReward2
                    : this.$store.state.maxKillReward;
            }

            if (this.game.members &&
                this.game.members[0] &&
                this.game.members[0].pivot &&
                this.game.members[0].pivot.bonus) {
                return this.isDoubleGame
                    ? this.$store.state.maxKillReward2
                    : this.$store.state.maxKillReward;
            }

            if (this.user && this.user.id && (this.user.bonus_games > 0)) {
                return this.isDoubleGame
                    ? this.$store.state.maxKillReward2
                    : this.$store.state.maxKillReward;
            }

            if (this.game.use_max_kill) {
                return this.isDoubleGame
                    ? this.$store.state.maxKillReward2
                    : this.$store.state.maxKillReward;
            }

            return (this.user && this.user.id)
                ? (this.isDoubleGame
                ? this.user.rank.kill_reward2
                : this.user.rank.kill_reward) / 100
                : (this.isDoubleGame
                    ? this.$store.state.minKillReward2
                    : this.$store.state.minKillReward);
        }
    }
}