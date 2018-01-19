<template>
    <div id="alertMain">
        <div class="alert alert-dismissible" v-for="(alert, index) in alerts" :class="alertClass(alert.type)"
             :id="alertId(index)" v-show="alert.show">
            <div class="bar">&nbsp;</div>
            <button type="button" class="close" @click="close(index)">&times;</button>
            {{alert.text}}
        </div>
    </div>
</template>

<script>
    import {TweenMax, Power0, Back} from "gsap";

    export default {
        data() {
            return {
                alerts: [],
            };
        },
        computed: {},
        methods: {
            alertClass(type) {
                return 'alert-' + type;
            },
            alertId(i) {
                return 'alert-' + i;
            },

            alert(alert) {
                var i = this.alerts.length;
                alert.show = false;
                this.alerts.push(alert);
                this.$nextTick(function () {
                    this.show(i);
                });
            },
            show(i) {
                this.alerts[i].show = true;
                TweenMax.fromTo('#alert-' + i, 1, {rotationX: 90}, {
                    rotationX: 0, ease: Back.easeOut, onComplete: this.bar,
                    onCompleteParams: [i],
                    onCompleteScope: this
                });
            },
            bar(i) {
                TweenMax.to('#alert-' + i + ' .bar', 5, {
                    width: '0%', ease: Power0.easeNone,
                    onComplete: this.close,
                    onCompleteParams: [i],
                    onCompleteScope: this
                });
            },
            close(i) {
                if (this.alerts[i].show) {
                    TweenMax.to('#alert-' + i, 1, {
                        rotationX: 90, ease: Back.easeIn,
                        onComplete: this.finish,
                        onCompleteParams: [i],
                        onCompleteScope: this
                    });
                }
            },
            finish(i) {
                this.alerts[i].show = false;
            },
        },
        mounted() {
            window.events.$on('alert', this.alert)
        }
    }
</script>

<style>
    #alertMain {
        position: fixed;
        right: 20px;
        bottom: 0px;
        z-index: 1;
        perspective: 800px;
        -webkit-perspective: 800px;
        transformStyle: preserve-3d;
        -webkit-transform-style: preserve-3d;
        /*display:none;*/
    }

    #alertMain .alert {
        position: relative;
        transform-origin: center bottom;
        -webkit-transform-origin: center bottom;
    }

    #alertMain .alert .bar {
        position: absolute;
        width: 100%;
        height: 4px;
        top: -1px;
        left: -1px;

    }

    #alertMain .alert-success .bar {
        background-color: #389438;
    }

    #alertMain .alert-danger .bar {
        background-color: #b91813;
    }
</style>