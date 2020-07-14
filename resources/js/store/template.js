import {ERROR} from "./action-names";

export function makeAction(context, url, params, mutation, headers = {}) {
    return new Promise((resolve, reject) => {
        (params ? axios.post(url, params, headers) : axios.get(url))
            .then(response => response.data)
            .then(data => {
                console.log(data);
                context.commit(mutation, data);
                resolve(data);
            })
            .catch(error => {
                context.dispatch(ERROR, error);
                reject(error);
            })
    })
}
