<template>
    <div class="container">
        <Panel></Panel>
        <div class="listContainer">
            <List
                    v-if="enable"
                    v-for="listable in lists"
                    :key="listable.id"
                    :listable="listable"
            >
            </List>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Panel from './Panel.vue'
    import List from './List.vue'

    export default {
        components: {
            Panel, List
        },
        data() {
            return {
                enable: false,
                lists: []
            }
        },
        methods: {
            firstBig: function (string) {
                let b = string.split('');
                b[0] = b[0].toUpperCase();
                return b.join('');
            }
        },
        mounted: function () {
            this.$root.$on('search', (data) => {
                this.enable = 'true';
                axios.post('/search_user', data).then(response => {
                    if (response.data.length === 0) {
                        this.lists.splice(0, this.lists.length, {user: 'No one found :C'});
                    } else {
                        this.lists.length = 0;
                        for (let i = 0; i < response.data.length; i++) {
                            this.lists.push({
                                user: this.firstBig(response.data[i].firstname) + ' '
                                + this.firstBig(response.data[i].lastname) + ' A.K.A ' + '\''
                                + response.data[i].nickname + '\' is '
                                + response.data[i].age + ' years old'
                            });
                        }
                    }
                });
            });
        }
    }
</script>


<style scoped>
    .container {
        height: 98%;
        width: 99%;
        position: absolute;
        animation-name: fadeIn;
        animation-duration: 1s;
    }

    .listContainer {
        position: absolute;
        width: 80%;
        margin: 7em 0 43px 10em;
    }
</style>