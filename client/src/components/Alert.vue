<template>
  <div :class="{'container': true, 'hide-container': !this.details.show}">
      <div :class="{popup: true, 'warning': details.type =='warning', 'success': details.type =='success'} ">
          <h3>{{details.message}}</h3>
          <div class="message-box">

          </div>
          <button @click="dismiss">OK</button>
      </div>
  </div>
</template>

<script>
export default {
    props: ['details'],

    data(){
        return{
            hide : this.details.show
        }
    },


    methods:{
        dismiss(){
            this.details.show = false;
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
    .hide-container{
        display: none  !important;
    }
    .popup{
      /* background: white; */
      margin: auto;
      width: 80%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      align-items: center;
      border-radius: 7px;
    }
    .warning{
        background-color: #d47f39;
        background:  linear-gradient(#caa70d, #e66d0b );

    }

    .success{
        background:  linear-gradient(#009114, #38f9d4 );
    }

    .popup h1{
      font-size: 30px;
    }
    .popup h3{
        font-size: 25px;
        font-weight: 200;
    }
    .message-box{
      display: block;
      width: 90%;

      /* background: coral; */
    }

    button{
        padding: 10px 30px;
        margin-bottom: 10px;
        background-color: white;
        border: none;
        border-radius: 5px;
        font-weight: bold;
    }
    button:hover{
        background-color: lightblue;
        color: white;
        cursor: pointer;
    }
</style>
