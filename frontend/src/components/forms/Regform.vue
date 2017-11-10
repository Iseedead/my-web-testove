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
                        faico: 'fa fa-user-plus',
                        button: 'Sign Up'
                    }
                ],
                formables: [
                    {
                        label: 'First Name',
                        type: 'text',
                        value: '',
                        error: 'Can contain latin letters',
                        valid: false,
                        regex: '^[a-zA-Z\\s]+$'
                    },
                    {
                        label: 'Last Name',
                        type: 'text',
                        value: '',
                        error: 'Can contain latin letters',
                        valid: false,
                        regex: '^[a-zA-Z\\s]+$'
                    },
                    {
                        label: 'Nickname',
                        type: 'text',
                        value: '',
                        error: 'Can contain latin letters and numbers',
                        valid: false,
                        regex: '^[A-Za-z0-9]+$'
                    },
                    {
                        label: 'Age',
                        type: 'text',
                        value: '',
                        error: 'Must contain numbers between 1 and 200',
                        valid: false,
                        regex: '^(0?[1-9]|[1-9][0-9]|[1][1-9][1-9]|200)$'
                    },
                    {
                        label: 'Password',
                        type: 'password',
                        value: '',
                        error: 'Must contain minimum eight letters, at least one letter and one number, no special characters',
                        valid: false,
                        regex: '^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]{8,}$'
                    }
                ]
            }
        },
        methods: {
            submit() {
                let newUser = {};
                this.formables.forEach(form => {
                    if (form.value === '' || !form.value.match(form.regex)) {
                        form.valid = true;
                    } else {
                        form.valid = false;
                        newUser[form.label.split(' ').join('').toLowerCase()] = form.value;
                    }
                });
                if (Object.keys(newUser).length === 5) {
                    axios.post('/new_user', newUser).then(response => {
                        if (response.data === false) {
                            this.formables.forEach(form => {
                                if (form.label === 'Nickname') {
                                    form.error = 'Such nickname is already taken';
                                    form.valid = true;
                                }
                            })
                        } else {
                            this.$root.$emit('registration');
                        }
                    });
                }
            }
        },
    }
</script>

<style scoped>
    .holder {
        padding: 15px 20px 15px;
    }
</style>