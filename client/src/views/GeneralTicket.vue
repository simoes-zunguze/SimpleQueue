<template>
  <div class="contanainer">
    <login @authenticated="authenticated"/>
    <div class="header">Welcome to Open - Q</div>
            <logout/>

    <div class="queues-contaniner">

        <queue class="queue" v-for="queue in queues" :key="queue.id" :queue="queue" :updates="updates" @click="getTicket(queue.id)" @updatePrinted="updatePrinted">
        </queue>
    </div>

    <div class="ticket">
        <h1>{{printed_ticket}}</h1>
    </div>

  </div>

</template>

<script>
import axios from 'axios';
import queue from './GeneralTicket/Queue';
import { mapGetters } from 'vuex';
import Login from '../components/Login.vue'
import Logout from '../components/Logout.vue'

export default {

    components:{
        queue, Logout, Login
    },
    data(){
        return {
            printed_ticket: 0,
            queues: {},
            updates: {}
        }
    },
    computed: {
        ...mapGetters({ token: 'getToken', url: 'getUrl'})
    },
    mounted(){

            window.Echo.channel(`queue`) // Broadcast channel name
                .listen('.queue-channel', (e) => { // Message name
                    this.updates = e.update;
                }
            );
    },

    methods:{
        updatePrinted(ticket_id){
            this.printed_ticket = ticket_id
            setTimeout(() => {
                this.printed_ticket = 0;
            }, 10000);
        },
         authenticated() {
            axios.get('queues', {
                baseURL: this.url
            }).then( res => this.queues = res.data)
                .catch( error => console.log(error));
        },
    }

}
</script>

<style scoped>



    .contanainer{
        /* position: relative; */
        margin: auto;
        color: rgb(42, 44, 75);
        max-width: 100%;
        height: 100vh;
        background-image: linear-gradient(#3C8CE7, #00EAFF);
        padding: 0 10%;
 
    }

    .header{
        padding: 5vh;
        font-size: 40px;
        color: white;

    }
    .queues-contaniner{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-content: center;
        height: 60vh;
        max-width: 600px;
        margin: 0 auto;
    }

    .ticket{
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ticket h1{
        font-size: 90px;
          padding: 30px;
          min-width: 250px;
        background: white;
        color: black;
        margin: 0;
    }
    .queue h1{
        display: flex;
        /* background: brown; */
        height: 100px;
        justify-content: center;
        align-items: center;
        box-shadow: rgb(40, 40, 40) 1px 1px 30px 5px inset;
    }
    .items{
        font-size: 4    0px;
        color: rgb(70, 68, 68);
    }

    .item:first-child{
        font-size: 170px;
        font-weight: bold;
        color:brown;
    }
    .item:nth-child(2){
        font-size: 70px;
    }


</style>
