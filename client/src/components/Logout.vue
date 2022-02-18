<template>
    <div class="logout">
        <img @click="logout" src="@/assets/img/logout.png" alt="Logout">
    </div>
</template>

<script>
import axios from 'axios'
import {mapGetters} from 'vuex'
export default {

    computed:{
            ...mapGetters({url: 'getUrl'})
    },
    methods: {
        logout(){
            localStorage.removeItem('token');
            localStorage.removeItem('queue');

            axios.get('logout', {baseURL: this.url}).then((res)=>{
                console.log(res);
            }).then(()=>{
                location.reload();
            })
        }

    }
}
</script>


<style scoped>
    img{
        position: absolute;
        top: 15px;
        right: 15px;
        width: 40px;
        /* height: 50px; */
        cursor: pointer;
    }
</style>
