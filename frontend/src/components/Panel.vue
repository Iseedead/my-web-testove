<template>
    <div class="panel">
        <Regform
                v-if="active === 'reg'"
        >
        </Regform>
        <Logform
                v-else-if="active === 'log'"
        >
        </Logform>
        <Searchform
                v-else-if="active === 'search'"
        >
        </Searchform>
    </div>
</template>

<script>
    import Regform from './forms/Regform.vue'
    import Logform from './forms/Logform.vue'
    import Searchform from './forms/Searchform.vue'

    export default {
        components: {
            Regform, Logform, Searchform
        },
        data() {
            return {
                active: 'reg'
            }
        },
        mounted: function () {
            this.$root.$on('registration', () => {
                this.active = 'log';
            });
            this.$root.$on('login', () => {
                this.active = 'search';
            })
        }
    }
</script>

<style scoped>
    .panel {
        top: 5%;
        margin: 0 auto;
        position: relative;
        width: 37%;
        background-color: #fcfcfc;
        border: 1px solid rgb(197, 197, 197);
        border-radius: 6px;
        box-shadow: 0 0 150px rgba(0, 0, 0, 0.35);
    }
</style>