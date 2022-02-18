<template>
<div class="contanainer">
    <login @authenticated="authenticated"></login>
    <logout></logout>

    <div class="header">Welcome to Open-Q</div>
  <div class="queues-contaniner">
        <queues v-for="queue in queues" :key="queue.id" :queue="queue" :updates="updates"></queues>

        <div class="snoozed">
            <h1>Snoozed</h1>
            <div class="items">
                    <strong>ID</strong>--<strong>Time</strong>
                    <div class="item"  v-for="ticket in snoozed_tickets" :key="ticket.id">
                        <strong>{{ticket.id}}</strong> - {{ticket.waiting_call.substring(10,16)}}
                    </div>
                    <div class="waiting">Total: <strong> {{Object.keys(snoozed_tickets).length}} </strong></div>
            </div>
        </div>
  </div>


</div>

</template>

<script>
import axios from 'axios';
import Queues from './QueueMonitor/Queues.vue';
import { mapGetters } from 'vuex';
import Login from '../components/Login.vue';
import Logout from '../components/Logout.vue';

export default {
  components: { Queues, Login, Logout },
    data(){
        return{
            queues: {},
            snoozed_tickets:{},
            updates: {}
        }
    },
    computed:{
        ...mapGetters({url: 'getUrl'})
    },
    async mounted(){
        window.Echo.channel(`queue`) // Broadcast channel name
            .listen('.queue-channel', (e) => { // Message name
                console.log(e); // The operation performed by the message, the parameter e is the data carried
                // this.queues = {};
                this.updates = e;
                this.snoozedTicket();
            }
        );
    },
    methods: {
        updateQueues(){
            axios.get('queues', {
                    baseURL: this.url
                }).then( res => {
                    // this.queues = {};
                    this.queues = res.data})
                .catch( error => console.log(error));
        },

        snoozedTicket(){
            axios.get('snoozed', {
                baseURL: this.url
            }).then(res => {
                this.snoozed_tickets = res.data;
            });
        }, 

        
        authenticated() {
            this.updateQueues();
            this.snoozedTicket();
        }
    }
}
</script>

<style scoped>
    body{
        /* background-image: url('/img/thunderbolt-1905603_1280.png'); */
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




    .snoozed{
       position: relative;
        background-color: white;
        padding: 20px;
        padding-bottom: 50px;
        min-width: 15%;
        max-width: 350px;
        border: rgb(196, 85, 248) solid 5px;
        border-radius: 10px;
        box-shadow: rgb(23, 17, 48) 1px 1px 15px 1px;
    }

    .snoozed h1{
        display: flex;
        background-image: linear-gradient(#651a83, #bb0000);
        height: 100px;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }

    .snoozed .items{
        font-size:30px;
        color: rgb(55, 60, 102);
    }


    .snoozed .waiting{
        position: absolute;
        bottom: 0;
        font-size: 20px;
        text-align: center;
        width: 90%;
        margin-bottom: 5px;
        background-image: linear-gradient(#651a83, #bb0000);
        color: white;
        border: #651a83 solid 3px;
        border-radius: 5px;
    }
</style>
