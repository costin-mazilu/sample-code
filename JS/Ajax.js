/**
 * Created by Costin Mazilu on 15.10.2017.
 * Ajax wrapper for Axios with vueJs event handler component
 */

/**
 * @class
 */
export default class Ajax {
    constructor() {
        this.path = 'http://demo.dev/api/'
    }
    
    request(url, event, method = 'get', data = {}) {
        window.events.$emit('showAjaxLoading', event);
        url = this.path.concat(url);
        window.axios({
            method: method,
            url: url,
            data: data
        }).then(response => {
            if(response.data.hasOwnProperty('msg')) {
                window.events.$emit('alert', {type: 'success', text: response.data.msg});
            }
            window.events.$emit('hideAjaxLoading', event);
            window.events.$emit(event, response.data);
        }).catch(error => {
            window.events.$emit('hideAjaxLoading', event);
            window.events.$emit('alert', {type: 'danger', text: error.response.data.message});
        });
    }
}
