<template>
<div class="contanainer">
    <login @authenticated='authenticated'>
    </login>
    <logout></logout>
    <div class="header">Welcome to Open-Q</div>
  <div class="queues-contaniner">
      <queues v-if="show_tickets"  :queue="queue" :updates="updates"></queues>
        <div class="slide-show">
            <h2>Slide Show</h2>
        </div>
  </div>
</div>

</template>

<script>
import axios from 'axios';
import Queues from './QueueMonitor/Queues.vue';
import { mapGetters } from 'vuex';
import Login from '../components/Login.vue'
import Logout from '../components/Logout.vue';
export default {
  components: { Queues, Login, Logout },
    data(){
        return{
            queue: {},
            updates: {},
            show_tickets: false,
        }
    },
    computed:{
        ...mapGetters({url: 'getUrl', token: 'getToken'})
    },
    async mounted(){
        window.Echo.channel('queue') // Broadcast channel name
            .listen('.queue-channel', (e) => { // Message name
                this.updates = e;
            }
        );
    },
    methods: {
        updateQueues(){
            axios.get('queues/'+this.$route.params.id, {
                    baseURL: this.url,
                    headers: {
                        Authorization: "Bearer " + this.token,
                    },
                }).then( res => {
                    this.queue = res.data})
                .catch( error => console.log(error));
        },

        authenticated(queue) {
            this.updateQueues();
            this.show_tickets = true
            console.log(queue);
        }
    }
}
</script>

<style scoped>
    body{
        display: block;
        color: white;
    }
    .header{
        /* background-color: hotpink; */
        padding: 5vh;
        font-size: 60px;
        color: white;

    }
    .contanainer{
        background-image: linear-gradient(#7e3ce7, #00EAFF);
        background-size: cover;
        width: 100%;
        height: 100vh;
        color: white;

    }
    .queues-contaniner{
        /* background-color: grey; */
        display: flex;
        justify-content: space-around;
        margin:auto;
    }
    .queues-contaniner .queue{
        width: 30vw;
    }


    .slide-show{
        border: rgb(196, 85, 248) solid 5px;
        border-radius: 10px;
        box-shadow: rgb(23, 17, 48) 1px 1px 15px 1px;        
        width: 60%;
        display: flex;
        /* justify-content: center; */
        
    }

    .queues-contaniner .slide-show h2{
        font-weight: bold;
        font-size: 200px;
        margin: auto;
    }
</style>
