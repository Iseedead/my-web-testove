<template>
    <div class="holder">
        <Formable
                v-for="formable in formables"
                :key="formable.id"
                :formable="formable"
        ></Formable>
        <Btn
                v-for="btn in btns"
                :key="btn.id"
                :btn="btn"
                @submit="submit"
        ></Btn>
    </div>
</template>

<script>
    import Formable from './Formable.vue'
    import Btn from './Btn.vue'

    export default {
        components: {
            Formable, Btn
        },
        data() {
            return {
                btns: [
                    {
                        faico: 'fa fa-search',
                        button: 'Search'
                    }
                ],
                formables: [
                    {
                        type: 'text',
                        value: '',
                    }
                ]
            }
        },
        methods: {
            submit() {
                let search = {};
                this.formables.forEach(form => {
                    search['search'] = form.value;
                });
                this.$root.$emit('search', search);
            }
        }
    }
</script>

<style scoped>
    .holder {
        padding: 15px 20px 15px;
    }
</style>