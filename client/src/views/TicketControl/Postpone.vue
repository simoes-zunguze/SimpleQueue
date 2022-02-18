<template>
  <div class="container">
    <div class="popup">
      <h1>Snooze for:</h1>
      <div class="queues">
        <div v-for="time in snooze_time" @click="postpone(time)" class="queue" :key="time">{{time}} Min</div>


        <button class="cancel" @click="cancel">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  props: ["queues", "current_queue"],
  data(){
    return {
        snooze_time: [2, 5, 10, 30]
    }
  },
  computed: {
    ...mapGetters({ token: "getToken", url: "getUrl" }),
  },
  methods: {
    postpone(minutes) {
      this.$emit("showLoading", true);
      console.log("Snooze for " + minutes);
      axios
        .patch(
          "postpone-ticket/" + this.current_queue,
          { minutes: minutes },
          {
            baseURL: this.url,
            headers: {
              Authorization: "Bearer " + this.token,
            },
          }
        )
        .then((res) => {
          this.$emit("showLoading", false);
          this.printed_ticket = res.data.id;
          this.$emit("hide-postpone");
          this.$emit(
            "showAlert",
            "success",
            "Ticket " + res.data.id + " snoozed for: " + minutes + " Minutes"
          );
        });
    },

    cancel() {
      console.log("hide");
      this.$emit("hide-postpone");
    },
  },
};
</script>

<style scoped>
.container {
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  display: flex;
  background: rgba(0, 0, 0, 0.8);
  color: aliceblue;
}

.popup {
  background-image: linear-gradient(#184d8a, #048d99);
  margin: auto;
  width: 80%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  align-items: center;
  border-radius: 7px;
}

.popup h1 {
  font-size: 30px;
}
.queues {
  display: block;
  width: 90%;

  /* background: coral; */
}
.queue {
  margin: 5px 0;
  padding: 5px 0;
  font-size: 24px;
  font-weight: bold;
  border-radius: 7px;
  background-color: white;
  color: rgb(71, 71, 71);
}

.queue:hover {
  background: rgb(176, 213, 255);
  cursor: pointer;
}

.cancel {
  padding: 10px 30px;
  font-size: 20px;
  margin-bottom: 5px;
  font-weight: bold;
  background: tomato;
  color: white;
  border: none;
  border-radius: 5px;
}

.cancel:hover {
  background: rgb(243, 52, 52);
}
</style>
