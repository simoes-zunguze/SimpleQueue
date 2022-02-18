<template>
  <div class="container" id="container">

            <div class="menu-section">
                <!-- <div class="back" id="back">
                    <img src="@/assets/img/back.png" alt="">
                </div> -->
                <div class="menu-row">
                    <h2>Queue Monitor</h2>
                        <div class="menu-col">
                             <a href="/queue-monitor" target="_blank">
                                <div class="menu-group">
                                    <div>
                                        <img src="@/assets/img/monitor.png" alt="">
                                    </div>
                                    <div class="menu-group-description">
                                       General Monitor
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="menu-col" @click="showQueueMenu(true)">
                                <a  target="_blank">
                                   <div class="menu-group">
                                       <div>
                                           <img src="@/assets/img/queue.png" alt="">
                                       </div>
                                       <div class="menu-group-description">
                                           Queue Monitor
                                       </div>
                                   </div>
                               </a>
                        </div>

                </div>
                <div class="menu-row">
                    <h2>General ticket</h2>
                        <div class="menu-col">
                                <a href="/general-ticket" target="_blank">
                                <div class="menu-group">
                                    <div>
                                        <img src="@/assets/img/select.png" alt="">
                                    </div>
                                    <div class="menu-group-description">
                                        General Tickets
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- <div class="menu-col">
                                <a href="#" target="_blank">
                                    <div class="menu-group">
                                        <div>
                                            <img src="@/assets/img/officeschema.png" alt="">
                                        </div>
                                        <div class="menu-group-description">
                                        Queue Ticket
                                        </div>
                                    </div>
                                </a>
                        </div> -->
                </div>

                <div class="menu-row">
                    <h2>KeyBoard</h2>
                    <div class="menu-col">
                            <a @click="openTicketController()" target="_blank" >
                            <div class="menu-group">
                                <div>
                                    <img src="@/assets/img/battery.png" alt="">
                                </div>
                                <div class="menu-group-description">
                                    Ticket controller
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
                <div class="menu-row">
                        <h2>Admin</h2>
                            <div class="menu-col">
                                 <a href="#" target="_blank">
                                    <div class="menu-group">
                                        <div>
                                            <img src="@/assets/img/admin.png" alt="">
                                        </div>
                                        <div class="menu-group-description">
                                            Stats
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="menu-col">
                                    <a href="#" target="_blank">
                                       <div class="menu-group">
                                           <div>
                                               <img src="@/assets/img/adminuser.png" alt="">
                                           </div>
                                           <div class="menu-group-description">
                                            Config
                                           </div>
                                       </div>
                                   </a>
                            </div>

                    </div>
                <!-- <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div> -->

            </div>

            <div v-if="show_queue_menu" class="submenu">
                <div class="sub-container">
                     <div class="menu-row">
                        <div class="close" @click="showQueueMenu(false)">x</div>

                        <h2>Choose a queue:</h2>
                            <div v-for="queue in queues" :key="queue.id" class="menu-col" @click="showQueueMenu(false)">
                                 <a :href="'queue-monitor/'+queue.id" target="_blank">
                                    <div class="menu-group">
                                        <div>
                                            <img :src="require(`@/assets/img/queues/${queue.id}.png`)"   alt="">

                                        </div>
                                        <div class="menu-group-description">
                                        {{queue.name}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                    </div>
                </div>
            </div>
        </div>

</template>

<script>

import axios from 'axios';
import {mapGetters} from 'vuex';
export default {
    components: { },
    name: 'Menu',
    data(){
        return{
            queues: {},
            show_queue_menu: false,
            ticket_control_window_referemce : null
        }
    },
    props: {
    msg: String
    },

    computed: {
        ...mapGetters({ token: 'getToken', url: 'getUrl'})
    },
    methods:{
        showQueueMenu(cond){
            this.show_queue_menu = cond;
        },

        openTicketController(){
            if(this.ticket_control_window_referemce != null){
                this.ticket_control_window_referemce.close();
            }
            this.ticket_control_window_referemce = window.open(window.document.location.href+'ticket-control', "_blank", "toolbar=yes,scrollbars=no,resizable=no,top=500,left=500,width=735,height=345");
        }

    },

    mounted() {
        axios.get('queues',
            {baseURL: this.url}
        ).then( res =>{
            this.queues = res.data;
            console.log(res.data);
        })
    }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
/*
	Designed and coded by Simoes Zunguze
	Contact: simoeszunguze@gmail.com
*/
body,header ,section,input,label,textarea,footer,div,form,p,h1,h2,h3,h4,h5,h6,img,figure,figcaption,table,td,tr,thead,tbody,ul,ol,li{
	padding: 0;
	margin: 0;
	list-style: none;
	border-collapse: collapse;
	border: none;
	color: rgba(1, 63, 133);
	color: rgba(38, 41, 42, 0.856);
}

body{
	text-align: center;
	position: relative;
	font-family: sans-serif;
	background-color: rgb(255, 255, 255);
	background-size:auto;
	background-repeat: no-repeat;
	background-position: center center;
	/* color: rgb(11, 14, 31); */
	background-attachment: fixed;
}

@media (min-width:320px) {

	.container{
		position: relative;
		display: inline-block;
		width: 100%;
		/* height: 100%; */
		background-color: rgba(255, 255, 255, 0.55);
	}

	.back{
		position: absolute;
		top: -30px;
		left: -10px;
		width: 50px;
		height: 50px;
		z-index: 100;
	}
	.back img{
		width: 100%;
		z-index: 100;
		/* box-shadow: 10px 10px 10px 10px rgba(20, 20, 20, 0.432); */
	}
	.bank-section{
		position: relative;
		display: inline-block;
		width: 90%;
		height: 100%;
		/* margin-top: 40px; */
	}

	.menu-section{
		position: relative;
		display: inline-block;
		width: 90%;
		margin-top: 40px;
	}

	.menu-row{
		position: relative;
		display: inline-block;
		width: 100%;
		/* background-color: red; */
		text-align: left;
		border-bottom: 2px solid rgba(1, 63, 133, 0.411);
		border-bottom: 2px solid rgba(38, 41, 42, 0.411);

		padding: 20px 0;
	}

	.menu-row h2{
		/* background-color: aqua; */
		padding-bottom: 30px;
		text-align: left;
	}
	.menu-col{
		position: relative;
		display: inline-block;
		width: 45%;
		/* background-color: blueviolet; */
		margin-bottom: 10px;
		margin: 5px;
        margin-left: 10px;
		text-align: center;

	}

	.menu-group{
    
		transition-duration: 0.8s;
		position: relative;
		display: inline-block;
		/* background: coral; */
		border: 1px solid rgba(1, 63, 133, 0.452);
		border: 0.5px solid rgba(38, 41, 42, 0.35);

		border-radius: 15px;
		background-color: rgba(255, 255, 255, 0.945);
		box-shadow: 1px 0 30px 1px rgba(1, 63, 133, 0.452);
		box-shadow: 1px 0 30px 1px rgba(121, 130, 133, 0.774);
        width: 180px;
        height: 180px;
        margin: 10px;
        padding: 5px;
	}

	.menu-group:hover{
		transform: scale(1.1);
		transition-duration: 0.5s;
		/* background-color: rgba(4, 8, 14, 0.746); */
		/* border-radius: 2px; */
		z-index: 2;
	}

	.menu-group img{
		max-width: 70%;
		display: inline-block;
		padding: 10px 0;
	}

	.menu-group-description{
		padding: 10px 0;
		font-size: 14px;
		font-weight: 100;
		/* letter-spacing: 1px;	*/
	}

    .submenu{
        background: rgba(0, 0, 0, 0.9);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
    }
    .submenu h2{
        color: white;
    }

    .sub-container{
        height: 60vh;
    }

    .sub-container .close{
        position: absolute;
        top: 5px;
        right: 5px;
        padding: 5px;
        font-weight: bold;
        border-radius: 2px;
        font-size: 30px;
        color: white;
        background: rgb(182, 4, 4);
        cursor: pointer;
    }
    .submenu .menu-group-description{
        font-weight: bold;
        font-size: 20px;
    }
}

@media (min-width:720px) {
	.back{
		transition-duration: 0.4s;
		position: fixed;
		top: 20px;
		left: 20px;
	}

	.back:hover{
		transition-duration: 0.2s;
		transform: scale(1.4);
		cursor: pointer;
	}
	.menu-section{
		margin-top: 0;
		padding: 10px 0;
		max-width: 680px;

	}
	.menu-row{
		padding: 50px 0;
	}
	.menu-row h2{
		text-align: center;
	}
	.menu-group-description{
		font-size: 16px;
		font-weight: 100;
		letter-spacing: 1px;
	}

    .sub-container{
        height: 70vh;
    }
}

@media (min-width:1080px) {
	.menu-section{
		max-width: 900px;
		/* text-align: left; */
	}
	.menu-row h2{
		padding-left: 15px;
		text-align: left;
	}
	.menu-row{
		transition-duration: 0.4s;
		/* background-color: red; */
		text-align: left;
		width: 45%;
		margin-right: 20px;
		margin-top: 20px;
	}
	.menu-row:hover{
		transition-duration: 0.4s;
		background-color: rgba(121, 130, 133, 0.15);
		border-radius: 15px;

		/* box-shadow: 10px 10px 30px 21px rgba(121, 130, 133, 0.774); */
	}

	.menu-col{
		/* width: 33%; */
		text-align: center;

	}
	.menu-group{
		width: 195;
		text-align: center;
	}

}

@media (min-width:1280px) {
	.container{
		/* background-color: rgba(0, 0, 0, 0.3);		 */
	}

	.back{
		top: 40px;
		left: 60px;
	}
	.menu-section{
		/* background-color: darkorange; */
		max-width: 1080;
		padding-top: 60px;
		padding-bottom: 100px;
	}
	.menu-row{
		margin-right: 30px;
	}
	.menu-col{
		/* width: 23%; */
		/* padding-right: 10px; */
	}
	.menu-group{

	}
	.menu-group-description{
		font-size: 16px;
		font-weight: 100;
		letter-spacing: 1px;
	}
}
</style>
