<template>
    <div class="list">
        <div class="user">
            <i class="fa fa-bars fa-4x" aria-hidden="true"></i>
            <span>{{ listable.user }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            listable: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>
    .list {
        background-color: #fcfcfc;
        border: 1px solid rgb(197, 197, 197);
        border-radius: 6px;
        box-shadow: 0 0 150px rgba(0, 0, 0, 0.35);
        height: 5em;
        margin: 6px 0 0 0;
        position: relative;
        padding: 12px 0 0 20px;
        animation-name: fadeIn;
        animation-duration: 1s;
    }

    .user span {
        font-size: xx-large;
        margin: -43px 0 0 57px;
        display: block;
    }
</style>