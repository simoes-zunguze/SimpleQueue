<template>
    <div class="loading">
            <img :src="require(`@/assets/img/Hourglass.gif`)"   alt="">
    </div>
</template>

<script>
export default {


}
</script>
<style scoped>
    .loading{
        background-color: rgb(255, 255, 255);
        width: 100%;
        height: 100%;
        position: absolute;
        top:  0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loading img{
        width: 70px;
        height: 70px;
    }

</style>
