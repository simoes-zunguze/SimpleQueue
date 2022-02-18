<template>
    <div class="queue">
        <h1>{{queue.name}}</h1>
    <div class="items">
            <div class="item">{{queue_summary.current_ticket}}</div>
            <div class="item"  v-for="ticket in tickets" :key="ticket.id">
                {{ticket.id}}
            </div>
            <div class="waiting">Total waiting: <strong>{{queue_summary.waiting}}</strong></div>
    </div>

    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    data() {
        return{
            tickets: {},
            queue_summary : {
                waiting: 0
            }
        }
    },
    computed:{
        ...mapGetters({url: 'getUrl'})
    },
    props: {
        queue: Object,
        updates: Object
    },
    watch:{
        updates: {
            handler(){
                if(this.queue.id == this.updates?.update?.queue){
                    console.log("updated "+this.updates?.update?.queue)
                    this.udpateQueue();
                }
            }
        }
    },
    methods:{
        udpateQueue(){
             axios.get('tickets-list/'+this.queue.id, {
            baseURL: this.url
        }).then(res => {
                this.tickets = res.data.tickets;
                this.queue_summary = res.data.queue_summary })
            .catch( error => console.log(error));
        }
    },
    mounted(){
        setTimeout( ()=>this.udpateQueue(), 1000)
    },
 /*    created(){
        this.udpateQueue();
        // setInterval( ()=>this.udpateQueue(), 4000)
    } */

}
</script>

<style scoped>
    .queue{
        position: relative;
        background-color: white;
        padding: 20px;
        padding-bottom: 50px;
        min-width: 15%;
        max-width: 350px;
        border: rgb(85, 123, 248) solid 5px;
        border-radius: 10px;
        box-shadow: rgb(23, 17, 48) 1px 1px 15px 1px;
    }

    h1{
        display: flex;
        background-image: linear-gradient(#2f85e7, #00abbb);
        height: 100px;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }

    .items{
        font-size: 40px;
        color: rgb(55, 60, 102);
    }

    .item:first-child{
        font-size: 170px;
        font-weight: bold;
        color:#2161aa;
    }
    .item:nth-child(2){
        font-size: 70px;
    }

    .waiting{
        position: absolute;
        bottom: 0;
        font-size: 20px;
        text-align: center;
        width: 90%;
        margin-bottom: 5px;
        background-image: linear-gradient(#184d8a, #048d99);
        color: white;
        border: #2f85e7 solid 3px;
        border-radius: 5px;
    }

</style>
