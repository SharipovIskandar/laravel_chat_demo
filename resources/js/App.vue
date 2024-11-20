<script>
import axios from 'axios';

export default {
    data() {
        return {
            messages: [],
            newMessage: "",
            users: [],
            selectedUserId: null,
        };
    },
    methods: {

        selectUser(userId){
          this.selectedUserId = userId;
          localStorage.setItem('setectedUserId', userId);
        },

        async getAllUsers(){
            try {
                const response = await axios.get('/users');
                this.users = response.data
            }catch (err){
                console.log(err.messages)
            }
        },
        async postMessage(text) {
            const receiverId = this.selectedUserId || localStorage.getItem('selectedUserId');
            try {
                const response = await axios.post('/message', {
                    text,
                    receiver_id: receiverId,
                });

                this.getMessages();
            } catch (err) {
                console.log(err.message);
            }
        },
        async getMessages() {
            try {
                const response = await axios.get('/messages');
                this.messages = response.data;
                this.scrollToBottom();
            } catch (err) {
                console.log(err.message);
            }
        },
        sendMessage() {
            if (this.newMessage.trim() !== "") {
                this.postMessage(this.newMessage.trim());
                this.newMessage = "";
            } else {
                return;
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const messageList = document.getElementById('messagelist');
                if (messageList) {
                    messageList.scrollTop = messageList.scrollHeight;
                }
            });
        }
    },
    created() {
        this.getMessages();
        this.getAllUsers()
        window.Echo.private("channel_for_everyone")
            .listen('GotMessage', (e) => {
                this.getMessages();
            });
    },
};
</script>


<template>
    <div class="container d-flex">
        <div class="user-list">
            <h5>Users</h5>
            <ul>
                <li v-for="(user, index) in users" :key="index" class="user-item">
                    <li v-for="(foydalanuvchilar, index) in user">
                        <button type="submit" @click="selectUser(foydalanuvchilar.id)" >{{ foydalanuvchilar.name }}</button>
                    </li>
                </li>
            </ul>
        </div>

        <div class="chat-box-container">
            <div class="chat-box" id="messagelist">
                <div v-for="(message, index) in messages" :key="index" class="message">
                        <strong>{{ message.user.name }}:</strong> {{ message.text }}
                        <small class="text-muted float-right">{{ message.time }}</small>
                </div>
            </div>
            <div class="input-area">
                <input v-model="newMessage" @keyup.enter="sendMessage" type="text"
                       placeholder="Type your message here..." />
                <button @click="sendMessage">Send</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.chat-box {
    border: 1px solid #ccc;
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
}

.message {
    margin-bottom: 10px;
}
.user-item {
    color: black;  /* Matnni qora rangda qilish */
}
 .user-item button {
     background-color: #007bff;
     color: white;
     border: none;
     padding: 5px 10px;
     cursor: pointer;
 }

.user-item button:hover {
    background-color: #0056b3;
}
</style>
