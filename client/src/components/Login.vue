<template>
    <div>
        <div :class="{'body': true, 'hide-body': token}"></div>
            <div :class="{'grad': true, 'hide-body': token}"></div>
            <div  :class="{'header': true, 'hide-body': token}">
                <div>Open - <span>Q</span></div>
            </div>
            <br>
            <div  :class="{'login': true, 'hide-body': token}">
                    <select @change="setQueueOnLocalStorage" name="queue" v-model="queue">
                        <option value="" selected> --Choose the Queue--</option>
                        <option v-for="queue in queues" :key="queue.id" :value="queue.id"> {{queue.name}} </option>
                    </select>
                    <input type="email" placeholder="email" name="email" v-model="email"  v-on:keyup.enter="login"><br>
                    <input type="password" placeholder="password" name="password" v-model="password" v-on:keyup.enter="login"><br>
                    <div class="login-fail" v-if="login_fail">Login faild !<br> Remember to select a queue</div>

                    <input type="button" value="Login" @click="login">
            </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";
import axios from 'axios';
export default {
    data(){
        return{
            queues: 0,
            queue: '',
            password: '',
            email: '',
            login_fail: false
        }
    },
	props:{
		show_queues: Boolean
	},
    computed: {
        ...mapGetters({ token: 'getToken', url: 'getUrl'})
    },
    methods:{
        login(){
        axios.post('/login',
                    {email: this.email, password: this.password, queue: this.queue},
                    {baseURL:this.url})
                    .then(res => {
                        if (res.data.token) {
							this.setInLocalStorage('token', res.data.token);
							this.setInLocalStorage('queue', this.queue);
							this.resetLoginForm();
							axios.defaults.headers.common['Authorization'] =  "Bearer " +  res.data.token;
                            this.setToken(res.data.token)
                            this.$emit('authenticated', this.queue);

                        }
                    })
                    .catch(() =>{
                        this.login_fail = true;
                    });

        },
		interceptUnauthorizedResponce(){
			axios.interceptors.response.use(function (response) {
				return response;
			}, function (error) {
					console.log(error.response.status);

				if (error.response.status == 401) {
					localStorage.clear();
					document.location.reload()
				}
				return Promise.reject(error);
			});
		},

        setQueueOnLocalStorage(){
            this.setInLocalStorage('queue', this.queue);
        },

		setInLocalStorage(key, value){
			localStorage.setItem(key,value);
		},

		getFromLocalStorage(key){
			return localStorage.getItem(key);
		},

		removeInLocalStorage(key){
			localStorage.removeItem(key);
		},

		resetLoginForm(){
			this.login_fail = false;
			this.password = '';
			this.email = '';
		},

        ...mapMutations(['setToken'])
		},

		async mounted(){
			this.interceptUnauthorizedResponce();
			try{
				let res = await axios.get('queues',{
						baseURL: this.url
				});
				this.queues = res.data;
			}catch(e){
				console.log(e)
			}

			let token = this.getFromLocalStorage('token')
			if (token) {

				axios.defaults.headers.common['Authorization'] =  "Bearer " + token;
				this.setToken(token)
				this.$emit('authenticated', localStorage.getItem('queue'));
			}
	
		}

}
</script>

<style scoped>
@import url(https://fonts.googleapis.com/css?family=Exo:100,200,400);
@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);


.body{
	position: fixed;
	top: 0;
	left: 0;
	right: -40px;
	bottom: -40px;
	width: 100%;
	height: 100%;
	/* background-image: url(http://ginva.com/wp-content/uploads/2012/07/city-skyline-wallpapers-008.jpg); */
	background: rgb(245, 90, 110);
	-webkit-filter: blur(5px);
	z-index: 1;
}
.hide-body{
    display: none;
}

.login-fail{
    font-weight: bold;
    color: rgb(255, 28, 28);
    text-shadow: 1px 1px 1px rgb(2, 2, 2);
}
.grad{
	position: absolute;
	top: -20px;
	left: -20px;
	right: -40px;
	bottom: -40px;
	width: auto;
	height: auto;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0.65))); /* Chrome,Safari4+ */
	z-index: 1;
	opacity: 0.7;
}

.header{
	position: absolute;
	top: calc(50% - 35px);
	left: calc(50% - 255px);
	z-index: 1;
}

.header div{
	float: left;
	color: #fff;
	font-family: 'Exo', sans-serif;
	font-size: 45px;
	font-weight: 400;
}

.header div span{
	color: #fc4242 !important;
}

.login{
	position: absolute;
	top: calc(50% - 75px);
	left: calc(50% - 50px);
	height: 150px;
	width: 350px;
	padding: 10px;
	z-index: 1;
}

.login input[type=email]{
	width: 250px;
	height: 30px;
	background: transparent;
	border: 1px solid rgba(255,255,255,0.6);
	border-radius: 2px;
	color: #fff;
	font-family: 'Exo', sans-serif;
	font-size: 16px;
	font-weight: 400;
	padding: 4px;
}

.login input[type=password]{
	width: 250px;
	height: 30px;
	background: transparent;
	border: 1px solid rgba(255,255,255,0.6);
	border-radius: 2px;
	color: #fff;
	font-family: 'Exo', sans-serif;
	font-size: 16px;
	font-weight: 400;
	padding: 4px;
	margin-top: 10px;
}
select{
    width: 260px;
	height: 40px;
	background: transparent;
	border: 1px solid rgba(255,255,255,0.6);
	border-radius: 2px;
	font-family: 'Exo', sans-serif;
	font-size: 16px;
    color: white;
	font-weight: 400;
	padding: 4px;
    margin-bottom: 15px;
}

option{
    color: black;
}

.login input[type=button]{
	width: 260px;
	height: 35px;
	background: #fff;
	border: 1px solid #fff;
	cursor: pointer;
	border-radius: 2px;
	color: #a18d6c;
	font-family: 'Exo', sans-serif;
	font-size: 16px;
	font-weight: 400;
	padding: 6px;
	margin-top: 10px;
}

.login input[type=button]:hover{
	opacity: 0.8;
}

.login input[type=button]:active{
	opacity: 0.6;
}

.login input[type=text]:focus{
	outline: none;
	border: 1px solid rgba(255,255,255,0.9);
}

.login input[type=password]:focus{
	outline: none;
	border: 1px solid rgba(255,255,255,0.9);
}

.login input[type=button]:focus{
	outline: none;
}

::-webkit-input-placeholder{
   color: rgba(255,255,255,0.6);
}

::-moz-input-placeholder{
   color: rgba(255,255,255,0.6);
}
</style>
