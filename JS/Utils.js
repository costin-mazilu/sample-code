/**
 * Created by Costin Mazilu on 30.10.2017.
 * Utility class with helper functions

 */
/**
 *  @class Utils
 */
export default class Utils {
    constructor() {
        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        this.taskHeight = 60;
    }

    sortByTaskFields(fields) {
        return (a, b) => fields.map(o => {
            let dir = 1;
            if (o[0] === '-') { dir = -1; o=o.substring(1); }
            if(typeof a.task[o] == 'string')
                a.task[o] = a.task[o].toLowerCase();
            if(typeof b.task[o] == 'string')
                b.task[o] = b.task[o].toLowerCase();

            return a.task[o] > b.task[o] ? dir : a.task[o] < b.task[o] ? -(dir) : 0;
        }).reduce((p,n) => p ? p : n, 0);
    }

    formatTimeForHumans(time) {
        let text = '';
        let days = Math.floor(time / (60 * 60 * 24));
        let hours = Math.floor((time % (60 * 60 * 24)) / (60 * 60));
        let minutes = Math.floor((time % (60 * 60)) / 60);
        let seconds = Math.floor(time % 60);


        if(days > 0)
            text += days + 'd ';
        if(hours > 0)
            text += hours + 'h '
        if(minutes > 0)
            text += minutes + 'm '

        text += seconds + 's '

        return text;
    }
}