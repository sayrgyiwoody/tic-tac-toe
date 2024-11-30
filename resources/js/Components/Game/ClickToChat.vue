<template>
  <div class="fixed bottom-8 right-8">
    <transition name="fade">
      <div v-if="isOpen" class="mb-4">
        <div
          class="w-80 h-96 bg-white rounded-lg shadow-xl flex flex-col overflow-hidden custom-scrollbar"
        >
          <div class="flex items-center justify-between p-4 bg-gray-100">
            <h3 class="text-lg font-semibold">Chat with {{ opponentName }}</h3>

            <button
              @click="toggleChat"
              class="text-gray-500 hover:text-gray-700 focus:outline-none"
            >
              <XIcon size="20" />
            </button>
          </div>
          <div ref="chatContainer" class="flex-grow p-4 overflow-auto">
            <!-- Chat messages would go here -->
            <p v-if="messages.length===0" class="text-sm">you can send message to opponent here!</p>
            <div v-for="(msg, index) in messages" :key="index" class="mb-2 flex" :class="{'justify-end': msg.sender_id === props.auth?.user.id, 'justify-start': msg.sender_id !== props.auth?.user.id}">
                <div
                    :class="{
                        'bg-blue-500 text-white': msg.sender_id === props.auth?.user.id,
                        'bg-gray-200 text-gray-800': msg.sender_id !== props.auth?.user.id,
                    }"
                    class="p-2 rounded-lg w-fit max-w-[50%]"
                >
                    <p class="text-sm break-words whitespace-pre-wrap">{{ msg.text }}</p>
                </div>
            </div>
          </div>
          <form @submit.prevent="sendMessage" class="flex p-4 bg-gray-100">
            <input
              v-model="message"
              type="text"
              placeholder="Type your message..."
              class="flex-grow px-3 py-2 text-sm border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button
              type="submit"
              class="px-4 py-2 text-white bg-blue-500 rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <SendIcon size="20" />
            </button>
          </form>
        </div>
      </div>
    </transition>
    <button
      @click="toggleChat"
      class="w-12 h-12 text-white bg-blue-500 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center"
      :aria-label="isOpen ? 'Close chat' : 'Open chat'"
    >
      <MessageCircleIcon v-if="!isOpen" size="24" />
      <XIcon v-else size="24" />
    </button>


  </div>
</template>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.3);
}

.custom-scrollbar::-webkit-scrollbar-track {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}
</style>

<script setup>
import { computed, ref } from "vue";
import { MessageCircleIcon, XIcon, SendIcon } from "lucide-vue-next";
import { defineProps } from "vue";
import { nextTick } from "vue";

const props = defineProps(["game", "auth"]);

const messages = ref([]);

const chatContainer = ref(null);

const isOpen = ref(false);
const message = ref("");

const toggleChat = () => {
    isOpen.value = !isOpen.value;
  if(isOpen.value){
    scrollToBottom();
  }
};

const scrollToBottom = () => {
  nextTick(() => {
    if (chatContainer.value) {
      chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
  });
};

const opponentName = computed(() => {
  if (props.game.player_two_id && props.auth.user) {
    return props.game.player_one_id === props.auth.user.id
      ? props.game.player_two.name
      : props.game.player_one.name;
  }
  return "other player";
});

const channel = Echo.join(`game.${props.game.id}`)
    .listenForWhisper("Message", (data) => {
        messages.value = data.messages;
        isOpen.value = true;
        scrollToBottom();
    });

const sendMessage = () => {
    if (message.value.trim()) {
    messages.value.push({
        text: message.value,
        sender_id: props.auth.user.id,
    });
    // Send the message to the opponent
    channel.whisper("Message", {
        messages : messages.value,
    });

    scrollToBottom();
    message.value = "";
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
