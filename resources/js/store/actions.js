import * as actions from "./action-names";
import {makeAction} from './template';
import * as backRoutes from '../back-routes';
import * as mutations from "./mutation-names";

export default {
    [actions.GET_PAYMENT_URL](context, gameId) {
        return makeAction(
            context,
            backRoutes.GET_PAYMENT_URL + gameId,
            null,
            mutations.DUMMY
        )
            .then(data => data.url);
    },
    [actions.GET_FREE_PAYMENT_URL](context, amount) {
        return makeAction(
            context,
            backRoutes.GET_FREE_PAYMENT_URL + amount,
            null,
            mutations.DUMMY
        )
            .then(data => data.url);
    },
    [actions.GET_UN_URL](context, amount) {
        return makeAction(
            context,
            backRoutes.GET_UN_URL + amount,
            null,
            mutations.DUMMY
        )
            .then(data => data.url);
    },
    [actions.CHECK_PAYPAL_ORDER](context, payload) {
        return makeAction(
            context,
            backRoutes.CHECK_PAYPAL_ORDER + payload.orderId,
            null,
            mutations.DUMMY
        );
    },
    [actions.ENTER_GAME](context, gameId) {
        return makeAction(
            context,
            backRoutes.BECOME_MEMBER + gameId,
            null,
            mutations.DUMMY
        )
    },
    [actions.SET_GAME_MODE](context, params) {
        return makeAction(
            context,
            backRoutes.SET_GAME_MODE
            + params.gameId + "/" + params.isSingle + "/" + params.teamNum + "/" + params.groupIndex,
            null,
            mutations.DUMMY
        )
    },
    [actions.GET_GAME_MODE](context, gameId) {
        return makeAction(
            context,
            backRoutes.GET_GAME_MODE + gameId,
            null,
            mutations.DUMMY
        )
    },
    [actions.LEAVE_GAME](context, gameId) {
        return makeAction(
            context,
            backRoutes.LEAVE_GAME + gameId,
            null,
            mutations.DUMMY
        )
    },
    [actions.GET_TEAMS](context, gameId) {
        return makeAction(
            context,
            backRoutes.GET_TEAMS + gameId,
            null,
            mutations.SET_TEAMS
        )
    },
    [actions.SET_USER_NAME_ID](context, data) {
        return makeAction(
            context,
            backRoutes.SET_USER_NAME_ID,
            {...context.state.user, ...data},
            mutations.SET_USER
        )
    },
    [actions.SEND_BLOGGERS_REQUEST](context, data) {
        return makeAction(
            context,
            backRoutes.BLOGGERS_REQUEST,
            data,
            mutations.DUMMY
        )
    },
    [actions.GET_GAMES](context) {
        return makeAction(
            context,
            backRoutes.GET_GAMES,
            null,
            mutations.SET_GAMES
        )
    },

}
