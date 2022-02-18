<template>
    <div class="queue" @click="getTicket()">

        <img :src="require(`@/assets/img/queues/${queue.id}.png`)"   alt="">
        <h2>{{queue.name}}</h2>
        <div class="waiting">Total waiting: <strong>{{queue_summary.waiting}}</strong></div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    data(){
        return{
            queue_summary: {
                waiting: 0
            }
        }
    },
    computed:{
        ...mapGetters({url: 'getUrl'})
    },
    props: ['queue', 'updates'],
    watch: {
        updates: {
            handler(){
                if (this.updates?.queue == this.queue.id) {
                    this.udpateQueue();
                }
            }
        }
    },
    methods:{
        udpateQueue(){
             axios.get('queue-summary/'+this.queue.id, {
            baseURL: this.url
        }).then(res => {this.queue_summary = res.data })
            .catch( error => console.log(error));
        },

        getTicket(){
            axios.post("get-ticket/"+this.queue.id, {}, {
            baseURL:  this.url
            }).then(res => {
                console.log(res.data)
                this.$emit('updatePrinted', res.data.id);
                this.udpateQueue();
            });
        }

    },
    mounted(){
        this.udpateQueue();
    }
}
</script>

<style scoped>
 .queue{
             position: relative;

        background-color: rgb(255, 255, 255);
        padding: 20px;
        width: 150px;
        height: 150px;
        margin: 20px;
        max-width: 350px;
        border: rgb(85, 123, 248) solid 3px;
        box-shadow: rgb(23, 17, 48) 1px 1px 30px 5px;
        border-radius: 10px;
        display: flex;
         flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
        cursor: pointer;

    }
    .queue:hover{
      transition:  transform 0.2s;
      transform: scale(1.1);

    }

    .queue img{
        width: 80px;
    }
    .queue h2{
        font-size: 30px;
    }

    .waiting{
        position: absolute;
        bottom: 0;
        font-weight: 100;
        font-size: 14px;
    }
</style>
