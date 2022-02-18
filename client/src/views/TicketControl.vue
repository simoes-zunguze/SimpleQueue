<template>
  <div class="container">
    <login @authenticated="authenticated" :show_queues=true></login>
    <!-- <h1>Queue: <strong>Teste</strong></h1> -->
    <div class="items">
        <div class="queue-name">{{ queue_name }}</div>
      <div class="item call" @click="call">
        <img :src="require(`@/assets/img/phone-call.png`)"   alt="">
        <h3>Call</h3>
      </div>
      <div class="item details" style="width: 48%">
        <div class="current-ticket">
          <h2>Current ticket:</h2>
          <div>{{ queue_summary.current_ticket }}</div>
        </div>
        <div class="extra-details">
          <table>
            <tr>
              <td>Waiting:</td>
              <td>{{ queue_summary.waiting }}</td>
            </tr>
            <tr>
              <td>Snoozed:</td>
              <td>{{ queue_summary.snoozed }}</td>
            </tr>
            <tr>
              <td>Absent:</td>
              <td>{{ queue_summary.absent }}</td>
            </tr>
            <tr>
              <td>Closed:</td>
              <td>{{ queue_summary.closed }}</td>
            </tr>


          </table>
        </div>
      </div>
      <div class="item call" @click="showPostpone">
           <img :src="require(`@/assets/img/timer.png`)"   alt="">
           <h3>Snooze</h3>
      </div>

      <div class="item redirect" @click="showRedirect">
           <img :src="require(`@/assets/img/forward.png`)"   alt="">
           <h3>Redirect</h3>
      </div>

      <div class="item absent" @click="absent">
            <img :src="require(`@/assets/img/missed-call.png`)"   alt="">
            <h3>Absent</h3>
      </div>
      <div class="item close" @click="close">
             <img :src="require(`@/assets/img/cancel.png`)"   alt="">
            <h3>Close</h3>
      </div>
      <div class="item logout" @click="logout">
            <img :src="require(`@/assets/img/exit.png`)"   alt="">
            <h3>Logout</h3>
      </div>
      <redirect
        :class="{ 'hide-redirect': hide_redirect }"
        :current_queue="queue_id"
        :queues="queues"
        @hideRedirect="hideRedirect"
        @showAlert="showAlert"
        @showLoading="showLoading"
      >
      </redirect>

      <postpone
        v-if="show_postpone"
        :current_queue="queue_id"
        @hide-postpone="hidePostpone"
        @showAlert="showAlert"
        @showLoading="showLoading"
      >
      </postpone>
      <alert :details="alert"></alert>
      <loading v-if="show_loading" />
    </div>
  </div>
</template>

<script>
import Redirect from "./TicketControl/Redirect.vue";
import axios from "axios";
import Alert from "../components/Alert.vue";
import Login from "../components/Login.vue";
import { mapGetters, mapMutations } from "vuex";
import Postpone from "./TicketControl/Postpone.vue";

import Loading from "@/components/Loading.vue"
export default {
  data() {
    return {
      queue_name: "--",
      queues: null,
      hide_redirect: true,
      show_postpone: false,
      queue_id: 0,
      ticket_list: {},
      queue_summary: {
        current_ticket: "--",
      },
     show_loading: false,

      alert: {
        message: "",
        type: "",
        show: false,
      },
    };
  },
  computed: {
    current_queue:{
        get(){
            return localStorage.getItem('queue')
        },
        set(new_queue){
            localStorage.setItem(new_queue)
        }
    },

    ...mapGetters({ url: "getUrl", token: "getToken" }),
  },
  components: {
    Redirect,
    Alert,
    Login,
    Postpone,
    Loading,
  },

  mounted() {

       window.Echo.channel(`queue`) // Broadcast channel name
      .listen(".queue-channel", (e) => {
        // Message name
        if (e.update?.queue == this.queue_id) {
          this.queueSummary();
        }
      });
  },
  methods: {
    showRedirect() {
      if (this.queue_summary.current_ticket == "--") {
        this.showAlert("warning", "Call a ticket to redirect");
      } else {
        this.hide_redirect = false;
      }
    },
    hideRedirect() {
      this.hide_redirect = true;
    },

    showPostpone() {
      if (this.queue_summary.current_ticket == "--") {
        this.showAlert("warning", "Call a ticket to snooze");
      } else {
        this.show_postpone = true;
      }
    },

    hidePostpone() {
      this.show_postpone = false;
    },

    queueList() {
      axios
        .get("queues", {
          baseURL: this.url,
          headers: {
            Authorization: "Bearer " + this.token,
          },
        })
        .then((res) => {
          this.queues = res.data;
        })
        .catch((error) => console.log(error));
    },
    getCurrentQueue(){

      axios.get("queues/" + this.queue_id, {
          baseURL: this.url,
        })
        .then((res) => {
            this.queue_name = res.data.name


        })
        .catch((error) => console.log(error));
    },

    queueSummary() {
      axios
        .get("queue-summary/" + this.queue_id, {
          baseURL: this.url,
          headers: {
            Authorization: "Bearer " + this.token,
          },
        })
        .then((res) => {
            this.queue_summary = res.data
            })
        .catch((error) => console.log(error));
    },
    ticketList() {
      axios
        .get("tickets-list/" + this.queue_id, {
          baseURL: this.url,
          headers: {
            Authorization: "Bearer " + this.token,
          },
        })
        .then((res) => (this.ticket_list = res.data))
        .catch((error) => console.log(error));
    },

    absent() {
            this.showLoading(true);
      axios
        .patch(
          "absent-ticket/" + this.queue_id,
          {},
          {
            baseURL: this.url,
            headers: {
              Authorization: "Bearer " + this.token,
            },
          }
        )
        .then(() => {
            this.showLoading(false);
            this.showAlert("success", "Ticket absent");
        })
        .catch((error) => {
            this.showLoading(false);
            this.showAlert("warning", error.response.data.errors);
        });
    },
    call() {
      this.showLoading(true);
      axios
        .patch(
          "call-ticket/" + this.queue_id,
          {},
          {
            baseURL: this.url,
            headers: {
              Authorization: "Bearer " + this.token,
            },
          }
        )
        .then(() => {
                this.showLoading(false);
        })
        .catch((error) => {
            this.showLoading(false);
          this.showAlert("warning", error.response.data.errors);
        });

    },
    close() {
            this.showLoading(true);
      axios
        .patch(
          "close-ticket/" + this.queue_id,
          {},
          {
            baseURL: this.url,
            headers: {
              Authorization: "Bearer " + this.token,
            },
          }
        )
        .then(() => {
            this.showLoading(false);
          this.showAlert("success", "Ticket closed");
        })
        .catch((error) => {
            this.showLoading(false);
          this.showAlert("warning", error.response.data.errors);
        });
    },

    showAlert(type, message) {
      this.alert.show = true;
      this.alert.message = message;
      this.alert.type = type;
    },

    showLoading(cond) {
        console.log(cond)
        this.show_loading = cond
    },


    logout() {
        localStorage.removeItem('queue');
        localStorage.removeItem('token')
      axios
        .get("logout", {
          baseURL: this.url,
          headers: {
            Authorization: "Bearer " + this.token,
          },
        })
        .then((res) => {
          if (res.data) {
            this.setToken(false);
            location.reload();
          }
        })
        .catch((error) => console.log(error));
    },

    authenticated(queue_id) {
      this.queue_id = queue_id;
      this.getCurrentQueue();
      this.queueSummary();
      this.queueList();
    },
    ...mapMutations(["setToken"]),
  },
};
</script>

<style scoped>
.queue-name{
    position: absolute;
    width: 95%;
    text-align: center;
    top: 5px;
    font-style: oblique;
    color: white;
    font-weight: bold;
}
.container {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-self: center;
}
.items {
  padding: 15px;
padding-top: 25px;
  position: relative;
  display: flex;
  align-self: center;
  width: 700px;
  justify-content: space-between;
  /* background-color: #1c6088; */
        background-image: linear-gradient(#184d8a, #048d99);

  height: 300px;
  flex-wrap: wrap;
    border: 2px solid rgb(76, 154, 226);

}
.item {
  position: relative;
  align-self: center;
  background-color:     white;
  justify-content: space-around;
  padding: 5px 0;
  font-size: 24px;
  color: rgb(255, 255, 255);
  width: 22%;
  height: 120px;
  font-weight: bold;
  cursor: pointer;
  border-radius: 7px;
  box-shadow: rgba(255, 255, 255, 0.226) 1px 1px 10px 1px;
  border: rgb(236, 176, 227) solid 1px;
  display: flex;
    flex-wrap: wrap;

}
.item h2{
    font-weight: 100;
    font-size: 20px;
    margin: 0;
}
.item:hover {
  border: 1px solid white;
}

.item img{
    width: 70px;
    height: 70px;
    padding: 3px 30px;
}
.item h3{
    margin: 0;
    font-weight: bold;
    font-size: 20px;
}
.call {
  background:  linear-gradient(#38f9d7, #43e97b );
}
.redirect {
    background-image: linear-gradient(#3aefff, #50a2ff);
}
.details {
  background:  linear-gradient(#3885f9, #4386e9 );
  display: flex;
}
.logout {
    background-image: linear-gradient(#a3a3a3, #292929);
}
.absent {
    background-image: linear-gradient(#ffc0d0, #d06efd);
}
.close {
    background-image: linear-gradient(#ecd23d, #f38063);
}
.hide-redirect {
  display: none;
}

.current-ticket {
  /* background: aquamarine; */
  width: 40%;
  display: inline-block;
}
.current-ticket h3 {
  font-size: 17px;
  font-weight: 100;
  padding: 0;
  margin: 0;
}
.current-ticket div {
  font-size: 50px;
  font-weight: bold;
}
.extra-details {
  width: 40%;
  display: inline-block;
}

.extra-details table tr td {
  font-weight: 100;
  font-size: 15px;
}
.extra-details table tr td:first-child {
  text-align: left;
}
.extra-details table tr td:nth-child(2) {
  text-align: right;
  width: 60px;
}
</style>
