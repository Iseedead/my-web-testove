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
    import axios from 'axios'
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
                        faico: 'fa fa-sign-in',
                        button: 'Sign In'
                    }
                ],
                formables: [
                    {
                        label: 'Nickname',
                        type: 'text',
                        value: '',
                        error: 'Something went Wrong, try again',
                        valid: false,
                    },
                    {
                        label: 'Password',
                        type: 'password',
                        value: '',
                        error: 'Something went Wrong, try again',
                        valid: false,
                    }
                ]
            }
        },
        methods: {
            submit() {
                let oldUser = {};
                this.formables.forEach(form => {
                    oldUser[form.label.split(' ').join('').toLowerCase()] = form.value;
                });
                axios.post('/old_user', oldUser).then(response => {
                    if (response.data.length === 2) {
                        this.formables.forEach(form => {
                            form.valid = true;
                        })
                    } else {
                        this.$root.$emit('login', oldUser);
                    }
                });
            }
        }
    }
</script>

<style scoped>
    .holder {
        padding: 15px 20px 15px;
    }
</style>