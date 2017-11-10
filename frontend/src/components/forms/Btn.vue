<template>
    <div class="btn" @click="$emit('submit')">
        <i :class="btn.faico" aria-hidden="true"></i> {{ btn.button }}
    </div>
</template>

<script>
    export default {
        props: {
            btn: {
                type: Object,
                required: true
            }
        },
        created: function () {
            window.addEventListener('keypress', this.enter);
        },
        methods: {
            enter: function (e) {
                let key = e.which || e.keyCode;
                if (key === 13) {
                    this.$emit('submit');
                }
            }
        }
    }
</script>

<style>
    .btn {
        background-color: #5d9f11;
        color: #FFF;
        font-size: 15px;
        font-weight: 400;
        cursor: pointer;
        border: 1px solid transparent;
        padding: 10px 20px;
        margin-top: 4px;
        border-radius: 4px;
        text-align: center;
    }

    .btn:hover {
        background-color: #63a811;
    }
</style>