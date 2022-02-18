<template>
  <div class="container">
      <div class="popup">
          <h1>Redirect To:</h1>
          <div class="queues">
              <div class="queue" v-for="queue in queuesToRedirect" :key="queue.id" @click="redirect(queue.id)"> {{queue.name}}</div>
              <button class="cancel" @click="cancel">Cancel</button>
          </div>
      </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from 'vuex'

export default {
    props: ['queues', 'current_queue'],
    computed: {
        queuesToRedirect(){
            if(this.queues == null){
                return [];
            }


            return this.queues.filter((queue) => {
                return queue.id != this.current_queue
            });
        },
        ...mapGetters({ token: 'getToken', url: 'getUrl'})
    },
    methods:{
          redirect(queue_id){
            this.$emit('showLoading', true);
            axios.patch("redirect-ticket/"+this.current_queue,
                {'redirect_to_queue': queue_id},
                {
                    baseURL: this.url,
                    headers: {
                            Authorization: 'Bearer '+ this.token
                    }
                })
            .then(res => {
                this.$emit('showLoading', false);
                this.printed_ticket = res.data.id;
                this.$emit('hideRedirect');
                this.$emit('showAlert', 'success', 'Ticket '+res.data.id+' redirected to '+res.data.queue.name);

            });
        },
        cancel(){
                this.$emit('hideRedirect');
        }
    }
}
</script>

<style scoped>
    .container{
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      display: flex;
      background: rgba(0, 0, 0, 0.8);
      color: aliceblue;
    }

    .popup{
      background-image: linear-gradient(#184d8a, #048d99);
      margin: auto;
      width: 80%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      align-items: center;
      border-radius: 7px;
    }

    .popup h1{
      font-size: 30px;
    }
    .queues{
      display: block;
      width: 90%;

      /* background: coral; */
    }
    .queue{
      margin: 5px 0;
      padding: 5px 0;
      font-size: 24px;
      font-weight: bold;
      border-radius: 7px;
       background-color:white;
      color:rgb(71, 71, 71);
    }

    .queue:hover{
      background: rgb(176, 213, 255);
      cursor: pointer;
    }

    .cancel{
        padding: 10px 30px;
        font-size: 20px;
        margin-bottom: 5px;
        font-weight: bold;
        background: tomato;
        color: white;
        border: none;
        border-radius: 5px;
    }

    .cancel:hover{
        background: rgb(243, 52, 52);
    }
</style>
