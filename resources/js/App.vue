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
        selectUser(userId) {
            localStorage.setItem('selectedUserId', userId);
        },
        async getAllUsers() {
            try {
                const response = await axios.get('/users');
                this.users = response.data;
            } catch (err) {
                console.error(err.message);
            }
        },
        async postMessage(text) {
            const userId = localStorage.getItem('selectedUserId');

            if (!userId) {
                alert("Iltimos, foydalanuvchini tanlang!");
                return;
            }

            try {
                await axios.post(`/user/${userId}/message`, {
                    text: text,
                    receiver_id: userId
                });
                this.getMessages();
            } catch (err) {
                console.error(err.message);
            }
        },
        async getMessages() {
            try {
                const userId = localStorage.getItem('selectedUserId');

                const response = await axios.get(`/user/${userId}/messages`);
                this.messages = response.data;
                this.scrollToBottom();
            } catch (err) {
                console.error(err.message);
            }
        },
        sendMessage() {
            if (this.newMessage.trim() !== "") {
                this.postMessage(this.newMessage.trim());
                this.newMessage = "";
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
        this.getAllUsers();
        const selectedUserId = localStorage.getItem('selectedUserId');
        window.Echo.private("channel_for_everyone")
            .listen('GotMessage', () => {
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
                    <li v-for="(foydalanuvchilar, index) in user" :key="index">
                        <a :href="`/user/${foydalanuvchilar.id}`" @click="selectUser(foydalanuvchilar.id)">
                            {{ foydalanuvchilar.name }}
                        </a>
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
